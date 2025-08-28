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
    $activityId = $_POST['activity_id'] ?? null;
    $title = $_POST['title'] ?? null;
    $description = $_POST['description'] ?? '';
    $dueDate = $_POST['due_date'] ?? null;
    $userId = $_SESSION['userID'];
    
    if (!$activityId || !$title) {
        echo json_encode(['success' => false, 'message' => 'Activity ID and title are required']);
        exit;
    }
    
    // Get user's numeric ID
    $userSql = "SELECT id FROM userstable WHERE userID = ?";
    $stmt = $conn->prepare($userSql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $userResult = $stmt->get_result();
    
    if ($userResult->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }
    
    $userIdNumber = $userResult->fetch_assoc()['id'];
    
    // Verify the activity belongs to this trainer
    $verifySql = "SELECT id FROM activitiestable WHERE id = ? AND created_by = ?";
    $stmt = $conn->prepare($verifySql);
    $stmt->bind_param("ii", $activityId, $userIdNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Activity not found or not authorized']);
        exit;
    }
    
    // Handle file upload if provided
    $filePath = null;
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/activities/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = uniqid() . '_' . $_FILES['file']['name'];
        $filePath = $uploadDir . $fileName;
        
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
            echo json_encode(['success' => false, 'message' => 'File upload failed']);
            exit;
        }
        $filePath = 'uploads/activities/' . $fileName;
    }
    
    // Update activity
    if ($filePath) {
        $updateSql = "UPDATE activitiestable SET title = ?, description = ?, due_date = ?, file_path = ? WHERE id = ? AND created_by = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ssssii", $title, $description, $dueDate, $filePath, $activityId, $userIdNumber);
    } else {
        $updateSql = "UPDATE activitiestable SET title = ?, description = ?, due_date = ? WHERE id = ? AND created_by = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("sssii", $title, $description, $dueDate, $activityId, $userIdNumber);
    }
    
    $stmt->execute();
    
    echo json_encode([
        'success' => true,
        'message' => 'Activity updated successfully'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error updating activity: ' . $e->getMessage()
    ]);
}
?>