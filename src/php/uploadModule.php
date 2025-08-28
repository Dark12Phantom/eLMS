<?php
require_once "DatabaseConnection.php";
require_once "authentication.php";
authenticate();

header('Content-Type: application/json');

try {
    $title = $_POST['moduleTitle'] ?? '';
    $description = $_POST['moduleDescription'] ?? '';
    $courseID = $_POST['courseModuleOption'] ?? '';
    
    if (empty($title) || empty($courseID) || !isset($_FILES['uploadModuleFile'])) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields and select a file']);
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
    
    if ($_FILES['uploadModuleFile']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'File upload error']);
        exit;
    }
    
    $uploadDir = '../uploads/modules/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $file = $_FILES['uploadModuleFile'];
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    $allowedExtensions = ['pdf', 'doc', 'docx', 'txt'];
    if (!in_array($fileExtension, $allowedExtensions)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type. Only PDF, DOC, DOCX, and TXT files are allowed.']);
        exit;
    }
    
    $uniqueFileName = 'module_' . time() . '_' . uniqid() . '.' . $fileExtension;
    $uploadPath = $uploadDir . $uniqueFileName;
    $filePath = 'uploads/modules/' . $uniqueFileName;
    
    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
        exit;
    }
    
    $insertSql = "INSERT INTO modulestable (course_id, title, description, file_path) 
                  VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("isss", $courseInternalId, $title, $description, $filePath);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Module uploaded successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload module']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>