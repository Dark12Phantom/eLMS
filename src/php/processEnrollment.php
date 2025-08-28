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
    $enrollmentId = $_POST['enrollment_id'] ?? null;
    $action = $_POST['action'] ?? null; // 'accept' or 'reject'
    
    if (!$enrollmentId || !$action) {
        echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
        exit;
    }
    
    $conn->begin_transaction();
    
    // Update enrollment status
    $status = ($action === 'accept') ? 'approved' : 'denied';
    $updateSql = "UPDATE enrollmenttable SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("si", $status, $enrollmentId);
    $stmt->execute();
    
    if ($action === 'accept') {
        // Get enrollment details
        $detailsSql = "SELECT user_id, course_id FROM enrollmenttable WHERE id = ?";
        $stmt = $conn->prepare($detailsSql);
        $stmt->bind_param("i", $enrollmentId);
        $stmt->execute();
        $details = $stmt->get_result()->fetch_assoc();
        
        if ($details) {
            // Add to enrolled table
            $enrollSql = "INSERT INTO enrolledtable (course_id, user_id, enrollment_id, status) VALUES (?, ?, ?, 'approved')";
            $stmt = $conn->prepare($enrollSql);
            $stmt->bind_param("iii", $details['course_id'], $details['user_id'], $enrollmentId);
            $stmt->execute();
        }
    }
    
    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'message' => ($action === 'accept') ? 'Enrollment accepted successfully' : 'Enrollment rejected successfully'
    ]);
    
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => 'Error processing enrollment: ' . $e->getMessage()
    ]);
}
?>