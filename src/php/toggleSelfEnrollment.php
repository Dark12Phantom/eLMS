<?php
require_once "DatabaseConnection.php";
require_once "authentication.php";
authenticate();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    $courseId = $_POST['course_id'] ?? null;
    
    if (!$courseId) {
        echo json_encode(['success' => false, 'message' => 'Course ID required']);
        exit;
    }
    
    // Check if record exists
    $checkSql = "SELECT status FROM coursetracker WHERE course_id = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("i", $courseId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Update existing record
        $row = $result->fetch_assoc();
        $newStatus = ($row['status'] == 1) ? 0 : 1;
        
        $updateSql = "UPDATE coursetracker SET status = ? WHERE course_id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ii", $newStatus, $courseId);
        $stmt->execute();
    } else {
        // Insert new record (default disabled = 0)
        $insertSql = "INSERT INTO coursetracker (course_id, status) VALUES (?, 0)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $newStatus = 0;
    }
    
    echo json_encode([
        'success' => true,
        'status' => $newStatus,
        'message' => 'Self-enrollment status updated successfully'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error updating status: ' . $e->getMessage()
    ]);
}
?>