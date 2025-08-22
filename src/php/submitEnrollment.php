<?php
require_once "DatabaseConnection.php";
session_start();

if (!isset($_SESSION['userID'], $_POST['course_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
    exit;
}

$userId = $_SESSION['userID'];
$courseId = $_POST['course_id'];

$check = $conn->prepare("SELECT id FROM enrollmenttable WHERE user_id = ? AND course_id = ?");
$check->bind_param("ii", $userId, $courseId);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'You already enrolled or requested this course']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO enrollmenttable (user_id, course_id, status, enrolled_at) VALUES (?, ?, 'pending', NOW())");
$stmt->bind_param("ii", $userId, $courseId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Enrollment request sent. Status: Pending']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send request']);
}
?>
