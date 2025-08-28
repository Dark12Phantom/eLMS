<?php
require_once "DatabaseConnection.php";
require_once "authentication.php";
authenticate();

header('Content-Type: application/json');

try {
    $userId = $_SESSION['userID'];
    
    $sql = "SELECT e.id, c.courseName, 
            CONCAT(u.firstName, ' ', u.middleName, ' ', u.lastName, ' ', u.suffix) as studentName,
            DATE_FORMAT(e.enrolled_at, '%m-%d-%y') as dateRequested,
            e.status
            FROM enrollmenttable e 
            JOIN coursestable c ON e.course_id = c.id 
            JOIN userstable u ON e.user_id = u.id 
            JOIN assignedcourses ac ON c.id = ac.course_id 
            JOIN userstable t ON ac.trainer_id = t.id 
            WHERE t.userID = ? AND e.status = 'pending' 
            ORDER BY e.enrolled_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $requests = [];
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $requests
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching enrollment requests: ' . $e->getMessage()
    ]);
}
?>