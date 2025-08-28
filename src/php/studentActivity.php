<?php
$studentUserID = $_SESSION['userID'] ?? NULL;

if (!$studentUserID) {
    echo "<p>Please log in to view activities.</p>";
    return;
}

$getStudentIdSql = "SELECT id FROM userstable WHERE userID = ?";
$stmt = $conn->prepare($getStudentIdSql);
$stmt->bind_param("s", $studentUserID);
$stmt->execute();
$studentResult = $stmt->get_result();
$studentData = $studentResult->fetch_assoc();

if (!$studentData) {
    echo "<p>Student not found.</p>";
    return;
}

$studentID = $studentData['id'];

$joinTable = 'SELECT 
    a.id AS activity_id, 
    a.title AS title, 
    a.type AS activity_type,
    c.courseID AS courseID, 
    c.courseName AS courseName, 
    a.due_date, 
    a.file_path AS file_path,
    g.grade,
    s.id AS submission_id,
    g.graded_at,
    g.feedback 
FROM activitiestable a
JOIN coursestable c ON a.course_id = c.id
LEFT JOIN submissionstable s ON s.activity_id = a.id AND s.student_id = ?
LEFT JOIN gradestable g ON g.submission_id = s.id
ORDER BY a.due_date ASC';

$stmt = $conn->prepare($joinTable);
$stmt->bind_param("i", $studentID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<thead>
              <tr>
                <th>Activity</th>
                <th>Type</th>
                <th>Course</th>
                <th>Due</th>
                <th>Status</th>
                <th>Actions</th>
                <th>View File</th>
              </tr>
            </thead>
            <tbody>";
    while ($row = $result->fetch_assoc()) {
        if ($row['grade'] !== null) {
            $status = "Graded ({$row['grade']}/100)";
        } elseif ($row['submission_id'] !== null) {
            $status = "Submitted";
        } else {
            $status = "Pending";
        }

        echo "<tr>
                <td>" . htmlspecialchars($row['title']) . "</td>
                <td>" . htmlspecialchars($row['activity_type']) . "</td>
                <td>" . htmlspecialchars($row['courseName']) . "</td>
                <td>" . htmlspecialchars(date("d-m-Y", strtotime($row['due_date']))) . "</td>
                <td>{$status}</td>
                <td>";

        if ($status === "Pending") {
            echo '<form class="button" method="POST" enctype="multipart/form-data" action="../php/submitActivity.php">
                    <input type="hidden" name="activity_id" value="' . $row['activity_id'] . '">
                    <input type="hidden" name="activity_type" value="' . htmlspecialchars($row['activity_type']) . '">
                    <input type="hidden" name="course_id" value="' . htmlspecialchars($row['courseID']) . '">
                    <div id="popup">
                      <div class="button-group">
                        <label id="file-label" for="file-input-' . $row['activity_id'] . '">
                          <span id="file-name">Choose File</span>
                          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#006aff">
                            <path d="M440-200h80v-167l64 64 56-57-160-160-160 160 57 56 63-63v167ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 
                                     23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520Z"/>
                          </svg>
                        </label>
                        <input type="file" name="file-input" id="file-input-' . $row['activity_id'] . '" required />
                      </div>
                      <button type="submit">Upload</button>
                    </div>
                  </form>';
        } elseif (strpos($status, "Graded") === 0 && !empty($row['feedback'])) {
            echo "<button onclick=\"alert('Grade: " . htmlspecialchars($row['grade']) . "/100\\nFeedback: " . htmlspecialchars($row['feedback']) . "')\">View Feedback</button>";
        } else {
            echo "-";
        }

        echo "</td>
                <td>";
        if (!empty($row["file_path"])) {
            echo "<a href='" . htmlspecialchars($row["file_path"]) . "' target='_blank'>Download</a>";
        } else {
            echo "-";
        }

        echo "</td>
            </tr>";
    }

    echo "</tbody>";
} else {
    echo "<thead>
              <tr>
                <th>Activity</th>
                <th>Type</th>
                <th>Course</th>
                <th>Due</th>
                <th>Status</th>
                <th>Actions</th>
                <th>View File</th>
              </tr>
            </thead>
            <tbody>
              <tr class='noData'>
                <td colspan='7'>
                  <p>No files yet. Activities will appear here!</p>
                </td>
              </tr>
            </tbody>";
}
?>