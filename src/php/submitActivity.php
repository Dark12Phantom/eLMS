<?php
require_once "DatabaseConnection.php";
require_once "authentication.php";
authenticate();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentUserID = $_SESSION['userID'];
    $activityId = $_POST['activity_id'] ?? null;
    $activityType = $_POST['activity_type'] ?? null;
    $courseID = $_POST['course_id'] ?? null;
    
    if (!$activityId || !$activityType || !$courseID) {
        $_SESSION['error'] = 'Missing required information';
        header('Location: ../pages/student.php');
        exit;
    }
    
    $getStudentIdSql = "SELECT id FROM userstable WHERE userID = ?";
    $stmt = $conn->prepare($getStudentIdSql);
    $stmt->bind_param("s", $studentUserID);
    $stmt->execute();
    $studentResult = $stmt->get_result();
    $studentData = $studentResult->fetch_assoc();
    
    if (!$studentData) {
        $_SESSION['error'] = 'Student not found';
        header('Location: ../pages/student.php');
        exit;
    }
    
    $studentID = $studentData['id'];
    
    if (!isset($_FILES['file-input']) || $_FILES['file-input']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = 'Please select a file to upload';
        header('Location: ../pages/student.php');
        exit;
    }
    
    $uploadDir = '../uploads/submissions/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $file = $_FILES['file-input'];
    $fileName = $file['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    $allowedExtensions = ['pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png'];
    if (!in_array($fileExtension, $allowedExtensions)) {
        $_SESSION['error'] = 'Invalid file type. Please upload PDF, DOC, DOCX, TXT, JPG, JPEG, or PNG files only.';
        header('Location: ../pages/student.php');
        exit;
    }
    
    $uniqueFileName = $studentUserID . '_' . $activityId . '_' . time() . '.' . $fileExtension;
    $uploadPath = $uploadDir . $uniqueFileName;
    $relativePath = 'uploads/submissions/' . $uniqueFileName;
    
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        $conn->autocommit(false);
        
        try {
            $insertSubmissionSql = "INSERT INTO submissionstable (activity_id, student_id, file_path, submitted_at) VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($insertSubmissionSql);
            $stmt->bind_param("iis", $activityId, $studentID, $relativePath);
            $stmt->execute();
            
            $this->updateStudentProgress($studentUserID, $courseID, $activityType, $conn);
            
            $conn->commit();
            $_SESSION['success'] = 'Assignment submitted successfully!';
            
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['error'] = 'Error submitting assignment: ' . $e->getMessage();
        }
        
    } else {
        $_SESSION['error'] = 'Error uploading file. Please try again.';
    }
} else {
    $_SESSION['error'] = 'Invalid request method';
}

$conn->autocommit(true);
header('Location: ../pages/student.php');
exit;

function updateStudentProgress($studentUserID, $courseID, $activityType, $conn) {
    $getCourseIdSql = "SELECT id FROM coursestable WHERE courseID = ?";
    $stmt = $conn->prepare($getCourseIdSql);
    $stmt->bind_param("s", $courseID);
    $stmt->execute();
    $courseResult = $stmt->get_result();
    $courseData = $courseResult->fetch_assoc();
    
    if (!$courseData) {
        throw new Exception('Course not found');
    }
    
    $courseInternalId = $courseData['id'];
    
    $getTrackingIdSql = "SELECT id FROM trackingtable WHERE course_id = ?";
    $stmt = $conn->prepare($getTrackingIdSql);
    $stmt->bind_param("s", $courseID);
    $stmt->execute();
    $trackingResult = $stmt->get_result();
    $trackingData = $trackingResult->fetch_assoc();
    
    if (!$trackingData) {
        throw new Exception('Tracking data not found for course');
    }
    
    $trackingID = $trackingData['id'];
    
    $checkProgressSql = "SELECT * FROM studentprogress WHERE studentID = ? AND course_id = ?";
    $stmt = $conn->prepare($checkProgressSql);
    $stmt->bind_param("ss", $studentUserID, $courseID);
    $stmt->execute();
    $progressResult = $stmt->get_result();
    $progressData = $progressResult->fetch_assoc();
    
    if ($progressData) {
        $updateField = '';
        switch (strtolower($activityType)) {
            case 'activity':
                $updateField = 'submittedActivity = submittedActivity + 1';
                break;
            case 'exam':
                $updateField = 'submittedExam = submittedExam + 1';
                break;
            case 'project':
                $updateField = 'submittedProjects = submittedProjects + 1';
                break;
            default:
                throw new Exception('Invalid activity type');
        }
        
        $updateProgressSql = "UPDATE studentprogress SET {$updateField} WHERE studentID = ? AND course_id = ?";
        $stmt = $conn->prepare($updateProgressSql);
        $stmt->bind_param("ss", $studentUserID, $courseID);
        $stmt->execute();
        
    } else {
        $submittedActivity = ($activityType === 'Activity') ? 1 : 0;
        $submittedExam = ($activityType === 'Exam') ? 1 : 0;
        $submittedProjects = ($activityType === 'Project') ? 1 : 0;
        
        $getCourseNameSql = "SELECT courseName FROM coursestable WHERE courseID = ?";
        $stmt = $conn->prepare($getCourseNameSql);
        $stmt->bind_param("s", $courseID);
        $stmt->execute();
        $courseNameResult = $stmt->get_result();
        $courseNameData = $courseNameResult->fetch_assoc();
        $courseName = $courseNameData['courseName'];
        
        $insertProgressSql = "INSERT INTO studentprogress (studentID, course_id, courseName, trackingID, submittedActivity, submittedExam, submittedProjects, progress) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, 0.00)";
        $stmt = $conn->prepare($insertProgressSql);
        $stmt->bind_param("ssiiii", $studentUserID, $courseID, $courseName, $trackingID, $submittedActivity, $submittedExam, $submittedProjects);
        $stmt->execute();
    }
    
    calculateProgress($studentUserID, $courseID, $conn);
}

function calculateProgress($studentUserID, $courseID, $conn) {
    $getProgressSql = "SELECT submittedActivity, submittedExam, submittedProjects FROM studentprogress WHERE studentID = ? AND course_id = ?";
    $stmt = $conn->prepare($getProgressSql);
    $stmt->bind_param("ss", $studentUserID, $courseID);
    $stmt->execute();
    $progressResult = $stmt->get_result();
    $progressData = $progressResult->fetch_assoc();
    
    $getTotalsSql = "SELECT totalActivity, totalExam, totalProjects FROM trackingtable WHERE course_id = ?";
    $stmt = $conn->prepare($getTotalsSql);
    $stmt->bind_param("s", $courseID);
    $stmt->execute();
    $totalsResult = $stmt->get_result();
    $totalsData = $totalsResult->fetch_assoc();
    
    if ($progressData && $totalsData) {
        $activityProgress = $totalsData['totalActivity'] > 0 ? $progressData['submittedActivity'] / $totalsData['totalActivity'] : 0;
        $examProgress = $totalsData['totalExam'] > 0 ? $progressData['submittedExam'] / $totalsData['totalExam'] : 0;
        $projectProgress = $totalsData['totalProjects'] > 0 ? $progressData['submittedProjects'] / $totalsData['totalProjects'] : 0;
        
        $overallProgress = ($activityProgress + $examProgress + $projectProgress) / 3 * 100;
        
        $updateProgressSql = "UPDATE studentprogress SET progress = ? WHERE studentID = ? AND course_id = ?";
        $stmt = $conn->prepare($updateProgressSql);
        $stmt->bind_param("dss", $overallProgress, $studentUserID, $courseID);
        $stmt->execute();
    }
}
?>