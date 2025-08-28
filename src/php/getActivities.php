<?php
require_once "DatabaseConnection.php";
require_once "authentication.php";
authenticate();

header('Content-Type: application/json');

try {
    $userId = $_SESSION['userID'];
    
    $sql = "SELECT a.id, a.title, c.courseName, 
            DATE_FORMAT(a.due_date, '%m-%d-%Y') as dueDate,
            a.created_at
            FROM activitiestable a 
            JOIN coursestable c ON a.course_id = c.id 
            JOIN assignedcourses ac ON c.id = ac.course_id 
            JOIN userstable u ON ac.trainer_id = u.id 
            WHERE u.userID = ?
            ORDER BY a.created_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $activities = [];
    while ($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $activities
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching activities: ' . $e->getMessage()
    ]);
}
?>