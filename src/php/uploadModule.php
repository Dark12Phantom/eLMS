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
    $moduleTitle = $_POST['moduleTitle'] ?? null;
    $courseName = $_POST['courseModuleOption'] ?? null;
    $description = $_POST['moduleDescription'] ?? '';
    
    if (!$moduleTitle || !$courseName) {
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
    
    // Handle file upload
    $filePath = null;
    if (isset($_FILES['uploadModuleFile']) && $_FILES['uploadModuleFile']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/modules/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = uniqid() . '_' . $_FILES['uploadModuleFile']['name'];
        $filePath = $uploadDir . $fileName;
        
        if (!move_uploaded_file($_FILES['uploadModuleFile']['tmp_name'], $filePath)) {
            echo json_encode(['success' => false, 'message' => 'File upload failed']);
            exit;
        }
        $filePath = 'uploads/modules/' . $fileName;
    }
    
    // Insert module
    $insertSql = "INSERT INTO modulestable (course_id, title, description, file_path) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("isss", $courseId, $moduleTitle, $description, $filePath);
    $stmt->execute();
    
    echo json_encode([
        'success' => true,
        'message' => 'Module uploaded successfully',
        'module_id' => $conn->insert_id
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error uploading module: ' . $e->getMessage()
    ]);
}
?>