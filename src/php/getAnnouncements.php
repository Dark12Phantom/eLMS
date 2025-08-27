<?php
require 'DatabaseConnection.php';
header('Content-Type: application/json');

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode([
        'success' => false,
        'message' => 'Only GET requests are allowed.'
    ]);
    exit;
}

try {
    // Get optional parameters
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $course_id = isset($_GET['course_id']) ? $_GET['course_id'] : null;
    $type = isset($_GET['type']) ? $_GET['type'] : null;
    
    // Build the query with filters
    $query = "SELECT a.*, c.courseName, c.courseID
              FROM announcementtable a 
              LEFT JOIN coursestable c ON a.course_id = c.courseID 
              WHERE (a.expires_at IS NULL OR a.expires_at > NOW())";
    
    $params = [];
    $types = '';
    
    // Add course filter if specified
    if ($course_id) {
        $query .= " AND a.course_id = ?";
        $params[] = $course_id;
        $types .= 's';
    }
    
    // Add type filter if specified
    if ($type) {
        $query .= " AND a.type = ?";
        $params[] = $type;
        $types .= 's';
    }
    
    $query .= " ORDER BY a.created_at DESC";
    
    // Add limit
    if ($limit > 0) {
        $query .= " LIMIT ?";
        $params[] = $limit;
        $types .= 'i';
    }
    
    // Prepare and execute the statement
    if (!empty($params)) {
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception('Database prepare error: ' . $conn->error);
        }
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query($query);
        if (!$result) {
            throw new Exception('Database query error: ' . $conn->error);
        }
    }
    
    $announcements = [];
    while ($row = $result->fetch_assoc()) {
        // Calculate time ago
        $created_time = new DateTime($row['created_at']);
        $current_time = new DateTime();
        $interval = $current_time->diff($created_time);
        
        if ($interval->days > 0) {
            $time_ago = $interval->days . ' day' . ($interval->days > 1 ? 's' : '') . ' ago';
        } elseif ($interval->h > 0) {
            $time_ago = $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
        } elseif ($interval->i > 0) {
            $time_ago = $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
        } else {
            $time_ago = 'Just now';
        }
        
        // Determine course display
        $course_display = 'General';
        if ($row['courseName']) {
            $course_display = $row['courseName'];
        } elseif ($row['course_id']) {
            $course_display = $row['courseID'] ?: $row['course_id'];
        }
        
        // Check if announcement is expiring soon (within 24 hours)
        $is_expiring = false;
        if ($row['expires_at']) {
            $expires_time = new DateTime($row['expires_at']);
            $time_until_expiry = $expires_time->diff($current_time);
            $is_expiring = ($time_until_expiry->days == 0 && $time_until_expiry->h < 24 && !$time_until_expiry->invert);
        }
        
        $announcements[] = [
            'id' => $row['id'],
            'type' => $row['type'],
            'course_id' => $row['course_id'],
            'course_name' => $course_display,
            'message' => $row['message'],
            'created_at' => $row['created_at'],
            'expires_at' => $row['expires_at'],
            'time_ago' => $time_ago,
            'is_expiring' => $is_expiring,
            'formatted_date' => $created_time->format('M j, Y g:i A')
        ];
    }
    
    // Close statement if used
    if (isset($stmt)) {
        $stmt->close();
    }
    
    echo json_encode([
        'success' => true,
        'data' => $announcements,
        'count' => count($announcements),
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}

$conn->close();
?>