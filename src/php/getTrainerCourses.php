<?php
require_once "DatabaseConnection.php";
require_once "authentication.php";
authenticate();

header('Content-Type: application/json');

try {
    $userId = $_SESSION['userID'];
    
    $sql = "SELECT c.id, c.courseName, 
            COUNT(DISTINCT et.user_id) as studentCount,
            COALESCE(AVG(sp.progress), 0) as avgProgress,
            ct.status as selfEnrollStatus
            FROM coursestable c 
            JOIN assignedcourses ac ON c.id = ac.course_id 
            JOIN userstable u ON ac.trainer_id = u.id 
            LEFT JOIN enrolledtable et ON c.id = et.course_id AND et.status = 'approved'
            LEFT JOIN studentprogress sp ON c.courseID = sp.course_id
            LEFT JOIN coursetracker ct ON c.id = ct.course_id
            WHERE u.userID = ?
            GROUP BY c.id, c.courseName, ct.status";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $courses = [];
    while ($row = $result->fetch_assoc()) {
        $courses[] = [
            'id' => $row['id'],
            'courseName' => $row['courseName'],
            'studentCount' => $row['studentCount'],
            'avgProgress' => round($row['avgProgress'], 1),
            'selfEnrollStatus' => $row['selfEnrollStatus'] ?? 1 // Default enabled
        ];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $courses
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching courses: ' . $e->getMessage()
    ]);
}
?>