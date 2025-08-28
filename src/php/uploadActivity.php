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
    $userId = $_SESSION['userID'];
    $actTitle = $_POST['actTitle'] ?? null;
    $courseName = $_POST['courseActivityOption'] ?? null;
    $dueDate = $_POST['dueDate'] ?? null;
    $description = $_POST['description'] ?? '';
    
    if (!$actTitle || !$courseName) {
        echo json_encode(['success' => false, 'message' => 'Title and course are required']);
        exit;
    }
    
    // Get course ID from course name
    $courseSql = "SELECT id FROM coursestable WHERE courseName = ?";
    $stmt = $conn->prepare($courseSql);
    $stmt->bind_param("s", $courseName);
    $stmt->execute();
    $courseResult = $stmt->get_result();
    
    if ($courseResult->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Course not found']);
        exit;
    }
    
    $courseId = $courseResult->fetch_assoc()['id'];
    
    // Get user ID from userID
    $userSql = "SELECT id FROM userstable WHERE userID = ?";
    $stmt = $conn->prepare($userSql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $userResult = $stmt->get_result();
    $userIdNumber = $userResult->fetch_assoc()['id'];
    
    // Handle file upload
    $filePath = null;
    if (isset($_FILES['uploadAct']) && $_FILES['uploadAct']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/activities/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = uniqid() . '_' . $_FILES['uploadAct']['name'];
        $filePath = $uploadDir . $fileName;
        
        if (!move_uploaded_file($_FILES['uploadAct']['tmp_name'], $filePath)) {
            echo json_encode(['success' => false, 'message' => 'File upload failed']);
            exit;
        }
        $filePath = 'uploads/activities/' . $fileName;
    }
    
    // Insert activity
    $insertSql = "INSERT INTO activitiestable (course_id, created_by, title, description, file_path, due_date, type) VALUES (?, ?, ?, ?, ?, ?, 'activity')";
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("iissss", $courseId, $userIdNumber, $actTitle, $description, $filePath, $dueDate);
    $stmt->execute();
    
    echo json_encode([
        'success' => true,
        'message' => 'Activity uploaded successfully',
        'activity_id' => $conn->insert_id
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error uploading activity: ' . $e->getMessage()
    ]);
}
?>