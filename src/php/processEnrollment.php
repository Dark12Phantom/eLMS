<?php
require_once "DatabaseConnection.php";
require_once "authentication.php";
authenticate();

header('Content-Type: application/json');

try {
    $enrollmentId = $_POST['enrollment_id'] ?? null;
    $action = $_POST['action'] ?? null;
    
    if (!$enrollmentId || !$action) {
        echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
        exit;
    }
    
    if (!in_array($action, ['accept', 'reject'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
    }
    
    $conn->autocommit(false);
    
    try {
        $getEnrollmentSql = "SELECT user_id, course_id FROM enrollmenttable WHERE id = ? AND status = 'pending'";
        $getStmt = $conn->prepare($getEnrollmentSql);
        $getStmt->bind_param("i", $enrollmentId);
        $getStmt->execute();
        $enrollment = $getStmt->get_result()->fetch_assoc();
        
        if (!$enrollment) {
            throw new Exception('Enrollment request not found or already processed');
        }
        
        if ($action === 'accept') {
            $updateSql = "UPDATE enrollmenttable SET status = 'approved' WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("i", $enrollmentId);
            $updateStmt->execute();
            
            $insertEnrolledSql = "INSERT INTO enrolledtable (course_id, user_id, enrollment_id, status) VALUES (?, ?, ?, 'Approved')";
            $insertStmt = $conn->prepare($insertEnrolledSql);
            $insertStmt->bind_param("iii", $enrollment['course_id'], $enrollment['user_id'], $enrollmentId);
            $insertStmt->execute();
            
            $message = 'Enrollment approved successfully';
        } else {
            $updateSql = "UPDATE enrollmenttable SET status = 'denied' WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("i", $enrollmentId);
            $updateStmt->execute();
            
            $message = 'Enrollment rejected successfully';
        }
        
        $conn->commit();
        echo json_encode(['success' => true, 'message' => $message]);
        
    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error processing enrollment: ' . $e->getMessage()]);
} finally {
    $conn->autocommit(true);
}
?>