<?php
require_once "../php/authentication.php";
authenticate();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Trainer's Page - Benguet Technical School</title>
  <link rel="icon" href="../images/school.png" type="image/x-icon">
  <link rel="stylesheet" href="../css/trainer.css" />
</head>

<body>
  <nav>
    <div class="logo">
      <h1>BTS Trainer</h1>
      <?php

      require_once "../php/DatabaseConnection.php";

      $userId = $_SESSION['userID'];

      $sql = "SELECT userID, firstName, middleName, lastName, suffix, role, profileImage 
        FROM userstable WHERE userID = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $userId);
      $stmt->execute();
      $result = $stmt->get_result();
      $user = $result->fetch_assoc();

      $fullName = trim("{$user['firstName']} {$user['middleName']} {$user['lastName']} {$user['suffix']}");
      $role = ucfirst($user['role']);
      $UID = trim($user['userID']);
      $profileImg = !empty($user['profileImage']) ? "../" . $user['profileImage'] : "../images/school.png";
      ?>
    </div>
    <div id="trainer-profile">
      <img src="<?= htmlspecialchars($profileImg) ?>" alt="Profile Image" />
      <h3 class="name"><?= htmlspecialchars($fullName) ?></h3>
      <p><?= htmlspecialchars($role) ?></p>
      <p class="triner-id-number"><?= htmlspecialchars($UID) ?></p>
    </div>
    <div id="navigation">
      <ul>
        <li><a class="dashboard active" href="#dashboard">Dashboard</a></li>
        <li>
          <a class="enrollment-request" href="#enrollment-request">Enrollment Requests</a>
        </li>
        <li><a class="courses" href="#courses">My Courses</a></li>
        <li>
          <a class="activities-modules" href="#activities-modules">Activities & Modules</a>
        </li>
        <li><a class="trainees" href="#trainees">Trainees</a></li>
        <li><a class="submissions" href="#submissions">Submissions</a></li>
        <li><a class="profile" href="#profile">Profile</a></li>
      </ul>
    </div>
    <div class="logout">
      <a href="../php/logout.php">Log out
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#0011ff"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
      </a>
    </div>
  </nav>

  <main>
    <div id="main-section">
      <section id="dashboard">
        <div class="container">
          <div id="dash-cards">
            <div class="card c1">
              <h3>Courses</h3>
              <h2>2</h2>
              <p><i>Courses</i></p>
            </div>
            <div class="card c2">
              <h3>Trainees</h3>
              <h2>4</h2>
              <p><i>Trainees</i></p>
            </div>
            <div class="card c3">
              <h3>Pending Submissions</h3>
              <h2>1</h2>
              <p><i>Submissions</i></p>
            </div>
            <div class="card c4">
              <h3>Recent Enrollment Requests (Quick Access)</h3>
              <div class="inside-container">
                <div class="wrapper">
                  <div class="infos">
                    <h4>Japanese Language and Culture</h4>
                    <p class="trainer"><i>Ane S. Thesia</i></p>
                  </div>
                  <a href="#enrollment-request" class="redir">Go to</a>
                </div>
              </div>
            </div>
            <div class="card c5">
              <h3>Enrollment Requests</h3>
              <p>No pending requests</p>
            </div>
          </div>
        </div>
      </section>
      
      <section id="enrollment-request">
        <div class="container">
          <table>
            <thead>
              <tr>
                <th>Requested Course</th>
                <th>Requested By</th>
                <th>Date Requested</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </section>
      
      <section id="courses">
        <div class="container">
          <table>
            <thead>
              <tr>
                <th>Course</th>
                <th>Students</th>
                <th>Average Progress</th>
                <th>Self-Enroll State</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </section>
      
      <section id="activities-modules">
        <div class="container">
          <div class="uploadActForms">
            <button type="button" id="createActivity">Create Activity</button>
            <button type="button" id="uploadModule">Upload Module</button>

            <form class="createAct">
              <label for="actTitle">
                Activity Title
                <input type="text" name="actTitle" id="actTitle" required />
              </label>
              <label for="actDescription">
                Description
                <textarea name="description" id="actDescription" rows="3"></textarea>
              </label>
              <label for="dueDate">
                Due Date
                <input type="date" name="dueDate" id="dueDate" />
              </label>
              <label for="activityType">
                Activity Type
                <select name="activityType" id="activityType" required>
                  <option value="">Select Type</option>
                  <option value="Activity">Activity</option>
                  <option value="Exam">Exam</option>
                  <option value="Project">Project</option>
                </select>
              </label>
              <select name="courseActivityOption" id="courseActivityOption" required>
                <option value="">Select Course</option>
                <?php
                $trainerUserID = $_SESSION['userID'];
                $getCoursesSql = "SELECT c.courseID, c.courseName 
                                  FROM coursestable c 
                                  JOIN assignedcourses ac ON c.id = ac.course_id 
                                  JOIN userstable u ON ac.trainer_id = u.id 
                                  WHERE u.userID = ?";
                $stmt = $conn->prepare($getCoursesSql);
                $stmt->bind_param("s", $trainerUserID);
                $stmt->execute();
                $coursesResult = $stmt->get_result();
                
                while ($courseRow = $coursesResult->fetch_assoc()) {
                  echo "<option value='" . htmlspecialchars($courseRow['courseID']) . "'>" . htmlspecialchars($courseRow['courseName']) . "</option>";
                }
                ?>
              </select>
              <label for="uploadAct">
                Upload Activity File (Optional)
                <input type="file" name="uploadAct" id="uploadAct" accept=".pdf,.doc,.docx,.txt" />
              </label>
              <button type="submit">Upload Activity</button>
              <button type="button" class="cancelBtn">Cancel</button>
            </form>

            <form class="addModule">
              <label for="moduleTitle">
                Module Title
                <input type="text" name="moduleTitle" id="moduleTitle" required />
              </label>
              <label for="moduleDescription">
                Description
                <textarea name="moduleDescription" id="moduleDescription" rows="3"></textarea>
              </label>
              <select name="courseModuleOption" id="courseModuleOption" required>
                <option value="">Select Course</option>
                <?php
                $stmt = $conn->prepare($getCoursesSql);
                $stmt->bind_param("s", $trainerUserID);
                $stmt->execute();
                $coursesResult = $stmt->get_result();
                
                while ($courseRow = $coursesResult->fetch_assoc()) {
                  echo "<option value='" . htmlspecialchars($courseRow['courseID']) . "'>" . htmlspecialchars($courseRow['courseName']) . "</option>";
                }
                ?>
              </select>
              <label for="uploadModuleFile">
                Select Module File
                <input type="file" name="uploadModuleFile" id="uploadModuleFile" accept=".pdf,.doc,.docx,.txt"
                  required />
              </label>
              <button type="submit">Upload Module</button>
              <button type="button" class="cancelModuleBtn">Cancel</button>
            </form>
          </div>

          <table id="activitiesTable">
            <thead>
              <tr>
                <th>Activity</th>
                <th>Course</th>
                <th>Type</th>
                <th>Due Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </section>
      
      <section id="trainees">
        <div class="container">
          <table>
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Course</th>
                <th>Progress</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </section>
      
      <section id="submissions">
        <div class="container">
          <table>
            <thead>
              <tr>
                <th>Trainee</th>
                <th>Course</th>
                <th>Activity</th>
                <th>Date Submitted</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </section>
      
      <section id="profile">
        <h2>My Profile</h2>
        <div class="container">
          <form id="profileForm" class="capsule">
          </form>
        </div>
      </section>
    </div>
    <div id="catalog">
      <div class="notifications-panel">
        <div class="notifications-header">
          <h3>Latest Announcements</h3>
          <button id="refreshNotifications">⟳</button>
        </div>
        <div class="notifications-list"></div>
      </div>
    </div>
  </main>

  <footer>
    <p>© 2025 Benguet Technical School. All rights reserved.</p>
  </footer>

  <script src="../js/trainer.js"></script>
</body>

</html>