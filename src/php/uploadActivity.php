<?php
require_once "DatabaseConnection.php";
require_once "authentication.php";
authenticate();

header('Content-Type: application/json');

try {
    $trainerUserID = $_SESSION['userID'];
    
    $getTrainerIdSql = "SELECT id FROM userstable WHERE userID = ?";
    $stmt = $conn->prepare($getTrainerIdSql);
    $stmt->bind_param("s", $trainerUserID);
    $stmt->execute();
    $trainerResult = $stmt->get_result();
    $trainerData = $trainerResult->fetch_assoc();
    
    if (!$trainerData) {
        echo json_encode(['success' => false, 'message' => 'Trainer not found']);
        exit;
    }
    
    $trainerInternalId = $trainerData['id'];
    
    $title = $_POST['actTitle'] ?? '';
    $description = $_POST['description'] ?? '';
    $dueDate = $_POST['dueDate'] ?? null;
    $activityType = $_POST['activityType'] ?? '';
    $courseID = $_POST['courseActivityOption'] ?? '';
    
    if (empty($title) || empty($courseID) || empty($activityType)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
        exit;
    }
    
    $getCourseIdSql = "SELECT id FROM coursestable WHERE courseID = ?";
    $stmt = $conn->prepare($getCourseIdSql);
    $stmt->bind_param("s", $courseID);
    $stmt->execute();
    $courseResult = $stmt->get_result();
    $courseData = $courseResult->fetch_assoc();
    
    if (!$courseData) {
        echo json_encode(['success' => false, 'message' => 'Course not found']);
        exit;
    }
    
    $courseInternalId = $courseData['id'];
    
    $filePath = null;
    if (isset($_FILES['uploadAct']) && $_FILES['uploadAct']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/activities/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $file = $_FILES['uploadAct'];
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        $allowedExtensions = ['pdf', 'doc', 'docx', 'txt'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Only PDF, DOC, DOCX, and TXT files are allowed.']);
            exit;
        }
        
        $uniqueFileName = 'activity_' . time() . '_' . uniqid() . '.' . $fileExtension;
        $uploadPath = $uploadDir . $uniqueFileName;
        $filePath = 'uploads/activities/' . $uniqueFileName;
        
        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
            exit;
        }
    }
    
    $insertSql = "INSERT INTO activitiestable (course_id, created_by, title, description, file_path, due_date, type) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("iisssss", $courseInternalId, $trainerInternalId, $title, $description, $filePath, $dueDate, $activityType);
    
    if ($stmt->execute()) {
        $updateTrackingSql = "UPDATE trackingtable SET ";
        switch (strtolower($activityType)) {
            case 'activity':
                $updateTrackingSql .= "totalActivity = totalActivity + 1";
                break;
            case 'exam':
                $updateTrackingSql .= "totalExam = totalExam + 1";
                break;
            case 'project':
                $updateTrackingSql .= "totalProjects = totalProjects + 1";
                break;
        }
        $updateTrackingSql .= " WHERE course_id = ?";
        
        $updateStmt = $conn->prepare($updateTrackingSql);
        $updateStmt->bind_param("s", $courseID);
        $updateStmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Activity created successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create activity']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>