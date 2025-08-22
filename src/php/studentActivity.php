<?php
$studentID = $_SESSION['userID'] ?? NULL;


$joinTable = 'SELECT 
    a.id AS activity_id, 
    a.title AS title, 
    c.courseID AS courseID, 
    c.courseName AS courseName, 
    a.due_date, 
    a.file_path AS file_path,
    g.grade,
    s.id AS submission_id,
    g.graded_at,
    g.feedback FROM activitiestable a
    JOIN coursestable c ON a.course_id = c.id
    LEFT JOIN submissionstable s on s.activity_id = a.id AND s.student_id = ?
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
            $status = "Graded";
        } elseif ($row['submission_id'] !== null) {
            $status = "Submitted";
        } else {
            $status = "Pending";
        }

        echo "
            <tr>
                <td>" . htmlspecialchars($row['title']) . "</td>
                <td>" . htmlspecialchars($row['courseName']) . "</td>
                <td>" . htmlspecialchars(date("d-m-Y", strtotime($row['due_date']))) . "</td>
                <td>";
        if ($status === "Pending") {
            echo '<form class="button" method="POST" enctype="multipart/form-data" action="submitActivity.php">
                    <input type="hidden" name="activity_id" value="' . $row['activity_id'] . '">
                    <div id="popup">
                      <div class="button-group">
                        <label id="file-label" for="file-input-' . $row['activity_id'] . '">
                          <span id="file-name">Choose File</span>
                          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#006aff">
                            <path d="M440-200h80v-167l64 64 56-57-160-160-160 160 57 56 63-63v167ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 
                                     23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520Z"/>
                          </svg>
                        </label>
                        <input type="file" name="file-input" id="file-input-' . $row['activity_id'] . '" />
                      </div>
                      <button type="submit">Upload</button>
                    </div>
                  </form>';
        } elseif ($status === "Graded") {
            echo "<button onclick=\"alert('Feedback: " . htmlspecialchars($row['feedback']) . "')\">View Feedback</button>";
        } else {
            echo "-";
        }

        echo "</td>
                <td>";
                if(!empty($row["file_path"])){
                    echo "<a href='" .htmlspecialchars(($row["file_path"])) . "'target='_blank'>Download</a>";
                }else {
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
                <th>Course</th>
                <th>Due</th>
                <th>Status</th>
                <th>Actions</th>
                <th>View File</th>
              </tr>
            </thead>
            <tbody>
              <tr class='noData' colspan='6'>
              <td>
              <p>No files yet. Activities will appear here!</p></td></tr>
            </tbody>";
}

?>