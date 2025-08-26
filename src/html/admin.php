<?php
  require_once "../php/authentication.php";
  authenticate();

  require_once "../php/DatabaseConnection.php";

  $courses = [];
  $cResult = $conn->query("SELECT courseID, courseName, status FROM coursestable ORDER BY courseName ASC");
  if ($cResult && $cResult->num_rows > 0) {
    while ($c = $cResult->fetch_assoc()) {
      $courses[] = $c;
    }
  }

  if (isset($_POST['assignCourseBtn'])) {
    $trainerRowID = $_POST['trainerRowID'] ?? null;
    $courseIDs = $_POST['courseID'] ?? [];

    if ($trainerRowID && !empty($courseIDs)) {
      $stmt = $conn->prepare("SELECT trainerID FROM trainerstable WHERE id = ?");
      $stmt->bind_param("i", $trainerRowID);
      $stmt->execute();
      $stmt->bind_result($trainerID);
      $stmt->fetch();
      $stmt->close();

      if (!$trainerID) {
        echo '<script>alert("Invalid trainer selected.");</script>';
        exit;
      }

      foreach ($courseIDs as $courseID) {
        $stmt = $conn->prepare("SELECT courseName FROM coursestable WHERE courseID = ?");
        $stmt->bind_param("s", $courseID);
        $stmt->execute();
        $stmt->bind_result($courseName);
        $stmt->fetch();
        $stmt->close();

        if ($courseName) {
          $insert = $conn->prepare("INSERT INTO trainercourses (trainerID, courseID, courseName) VALUES (?, ?, ?)");
          $insert->bind_param("sss", $trainerID, $courseID, $courseName);
          $insert->execute();
          $insert->close();
        }
      }

      echo '<script>alert("Courses assigned successfully."); window.location.href="' . $_SERVER['PHP_SELF'] . '";</script>';
      exit;
    }
  }

  if (isset($_POST['saveStatusBtn'])) {
    $trainerRowID = $_POST['trainerRowID'] ?? null;
    $status = $_POST['status'] ?? null;

    if ($trainerRowID && $status) {
      $stmt = $conn->prepare("SELECT trainerID FROM trainerstable WHERE id = ?");
      $stmt->bind_param("i", $trainerRowID);
      $stmt->execute();
      $stmt->bind_result($trainerID);
      $stmt->fetch();
      $stmt->close();

      if (!$trainerID) {
        echo '<script>alert("Invalid trainer selected.");</script>';
        exit;
      }

      $stmt = $conn->prepare('UPDATE trainerstable SET status=? WHERE id=?');
      $stmt->bind_param('si', $status, $trainerRowID);

      if ($stmt->execute()) {
        $stmt->close();

        if ($status === 'on leave' || $status === 'dismissed') {
          $del = $conn->prepare("DELETE FROM trainercourses WHERE trainerID = ?");
          $del->bind_param("s", $trainerID);
          $del->execute();
          $del->close();
        }

        echo '<script>alert("Status updated successfully."); window.location.href="' . $_SERVER['PHP_SELF'] . '";</script>';
        exit;
      } else {
        echo '<script>alert("Database error.")</script>';
      }
    }
  }


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Administrator's Page - Benguet Technical School</title>
  <link rel="icon" href="../images/school.png" type="image/x-icon">
  <link rel="stylesheet" href="../css/admin.css" />
</head>

<body>
  <nav>
    <div class="logo">
      <h1>BTS Administrator</h1>
      <?php

      require_once "../php/DatabaseConnection.php";

      $userId = $_SESSION['userID'];

      $sql = "SELECT userID, firstName, middleName, lastName, suffix, role, profileImage 
        FROM userstable WHERE id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $userId);
      $stmt->execute();
      $result = $stmt->get_result();
      $user = $result->fetch_assoc();

      $fullName = trim("{$user['firstName']} {$user['middleName']} {$user['lastName']} {$user['suffix']}");
      $role = ucfirst($user['role']);
      $UID = trim($user['userID']);
      $profileImg = !empty($user['profileImage']) ? "../" . $user['profileImage'] : "../images/school.png";
      ?>
    </div>
    <div id="admin-profile">
      <img src="<?= htmlspecialchars($profileImg) ?>" alt="Profile Image" />
      <h3 class="name"><?= htmlspecialchars($fullName) ?></h3>
      <p><?= htmlspecialchars($role) ?></p>
      <p class="admin-id-number"><?= htmlspecialchars($UID) ?></p>
    </div>
    <div id="navigation">
      <ul>
        <li>
          <a class="trainerMgt active" href="#trainerMgt">Trainer Management</a>
        </li>
        <li><a class="traineeMgt" href="#traineeMgt">Trainees</a></li>
        <li><a class="postUpdate" href="#postUpdate">Posts/ Updates</a></li>
        <li>
          <a class="courseMgt" href="#courseMgt">Course Management</a>
        </li>
        <li><a class="profile" href="#profile">Profile</a></li>
      </ul>
    </div>
    <div class="logout">
      <a href="../php/logout.php">Log out
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#006dda">
          <path
            d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z" />
        </svg>
      </a>
    </div>
  </nav>

  <main>
    <div id="main-section">
      <section id="trainerMgt">
        <div class="container">
          <h3>Trainers</h3>
          <a href="#" id="addTrainerLink"
            style="margin-bottom: 15px; width:max-content; padding: 5px 10px; border-radius: 15px; border:none; background-color: #0011ff; text-decoration: none; color: #ffffff;">Add
            Trainer</a>

          <div id="popupForm" style="display:none; border:none; border-radius: 8px; padding:15px; background:#f9f9f9;">
            <form method="POST" action="addTrainer.php" enctype="multipart/form-data">
              <label>First Name:</label><input type="text" name="firstName" required><br>
              <label>Middle Name:</label><input type="text" name="middleName"><br>
              <label>Last Name:</label><input type="text" name="lastName" required><br>
              <label>Suffix:</label><input type="text" name="suffix"><br>
              <label>Gender:</label>
              <label for="gender">Gender:</label>
              <select id="gender" name="gender" required>
                <option value="" disabled selected>Select Gender</option>
                <option value="M">Male</option>
                <option value="F">Female</option>
              </select><br>
              <label>Birthdate:</label><input type="date" name="birthDate" required><br>
              <label>Bio:</label><textarea name="bio" placeholder="Input Trainer's Degree"></textarea><br>
              <label>Mobile Number:</label><input type="text" name="mobileNumber"><br>
              <label>Email: </label><input type="email" name="email" value=" " readonly><br>
              <label>Education:</label><select name="education" id="education" required>
                <option value="" selected disabled hidden>
                  Attained Education
                </option>
                <option value="Doctor's Degree">Doctor's Degree</option>
                <option value="Master's Degree">Master's Degree</option>
                <option value="Bachelor's Degree">Bachelor's Degree</option>
                <option value="Associate's Degree">Associate's Degree</option>
                <option value="College">College</option>
              </select><br>
              <div class="buttons">
                <button type="submit">Add Trainer</button>
                <button type="button" id="closeTrainer">Close</button>
              </div>
            </form>
          </div>
          <table>
            <thead>
              <tr>
                <th>Username</th>
                <th>Name</th>
                <th>ID</th>
                <th>Date Hired</th>
                <th>Status</th>
                <th>Action</th>
                <th>Assign</th>
              </tr>
            </thead>
            <tbody>
              <?php
              require_once '../php/DatabaseConnection.php';

              function renderTrainersTable($conn)
              {
                $sql = "SELECT 
            u.id AS userRowID,
            u.userID,
            CONCAT(u.firstName, ' ', IFNULL(u.middleName,''), ' ', u.lastName, ' ', IFNULL(u.suffix,'')) AS fullName,
            u.email,
            t.id AS trainerRowID,
            t.trainerID,
            t.status,
            t.assignedDate
          FROM trainerstable t
          INNER JOIN userstable u ON u.userID = t.trainerID
          WHERE u.role = 'trainer'
          ORDER BY t.assignedDate DESC";

                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['fullName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['userID']) . "</td>";
                    echo "<td>" . (!empty($row['assignedDate']) ? date("m-d-Y", strtotime($row['assignedDate'])) : '-') . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";

                    echo "<td><button type='button' class='setTrainerStatusBtn' data-id='{$row['trainerID']}'>Set Status</button></td>";

                    $assignedCourses = [];
                    $stmt = $conn->prepare("SELECT courseName FROM trainercourses WHERE trainerID = ?");
                    $stmt->bind_param("s", $row['trainerID']);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    while ($crs = $res->fetch_assoc()) {
                      $assignedCourses[] = $crs['courseName'];
                    }
                    $stmt->close();

                    echo "<td>";
                    if ($row['status'] === 'active') {
                      echo "<button class='assignTrainerBtn' 
                            data-id='" . htmlspecialchars($row['trainerID'], ENT_QUOTES) . "' >
                            Assign Courses
                        </button>";
                    } else {
                      echo "-";
                    }
                    echo "</td>";

                    echo "</tr>";
                  }
                } else {
                  echo "<tr><td colspan='8'>No trainers found</td></tr>";
                }
              }

              renderTrainersTable($conn);
              ?>
            </tbody>
          </table>
        </div>
      </section>

      <section id="traineeMgt">
        <div class="container">
          <h3>Trainees/ Students</h3>
          <a href="#" id="addStudentLink"
            style="margin-bottom: 15px; width:max-content; padding: 5px 10px; border-radius: 15px; border:none; background-color: #0011ff; text-decoration: none; color: #ffffff;">Enroll
            Trainee</a>

          <div id="popupStudentForm"
            style="display:none; border:none; border-radius: 8px; padding:15px; background:#f9f9f9;">
            <form method="POST" action="../php/addTrainee.php" enctype="multipart/form-data">
              <label>First Name:</label><input type="text" name="studentFirstName" required><br>
              <label>Middle Name:</label><input type="text" name="studentMiddleName"><br>
              <label>Last Name:</label><input type="text" name="studentLastName" required><br>
              <label>Suffix:</label><input type="text" name="studentSuffix"><br>
              <label for="studentGender">Gender:</label>
              <select id="studentGender" name="studentGender" required>
                <option value="" disabled selected>Select Gender</option>
                <option value="M">Male</option>
                <option value="F">Female</option>
              </select><br>
              <label>Birthdate:</label><input type="date" name="studentBirthDate" required><br>
              <label>Bio:</label><textarea name="studentBio" placeholder="Input Trainer's Degree"></textarea><br>
              <label>Mobile Number:</label><input type="text" name="studentMobileNumber"><br>
              <label>Email: </label><input type="email" id="studentEmail" name="studentEmail" value="" readonly><br>
              <label>Education:</label><select name="studentEducation" id="studentEducation" required>
                <option value="" selected disabled hidden>
                  Highest Attained Education
                </option>
                <option value="Elementary">Elementary</option>
                <option value="JHS">Junior High School</option>
                <option value="SHS">Senior High School</option>
                <option value="College">College</option>
                <option value="Graduate">
                  Graduate School (Doctoral, Masteral, etc.)
                </option>
              </select><br>
              <div class="buttons">
                <button type="submit">Add Trainer</button>
                <button type="button" id="closeTrainee">Close</button>
              </div>
            </form>
          </div>
          <table>
            <thead>
              <tr>
                <th>Username</th>
                <th>Name</th>
                <th>ID</th>
                <th>Date Hired</th>
                <th>Status</th>
                <th>Add Course</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              require_once '../php/DatabaseConnection.php';

              function renderTraineesTable($conn)
              {
                $sql = "SELECT 
            u.id AS userRowID,
            u.userID,
            CONCAT(u.firstName, ' ', IFNULL(u.middleName,''), ' ', u.lastName, ' ', IFNULL(u.suffix,'')) AS fullName,
            u.email,
            t.id AS trainerRowID,
            t.studentID,
            t.status,
            t.enrolledDate
          FROM traineestable t
          INNER JOIN userstable u ON u.userID = t.studentID
          WHERE u.role = 'trainee'
          ORDER BY t.enrolledDate DESC";

                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['fullName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['userID']) . "</td>";
                    echo "<td>" . (!empty($row['enrolledDate']) ? date("m-d-Y", strtotime($row['enrolledDate'])) : '-') . "</td>";
                    $assignedCourses = [];
                    $stmt = $conn->prepare("SELECT courseName FROM studentprogress WHERE studentID = ?");
                    $stmt->bind_param("s", $row['studentID']);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    while ($crs = $res->fetch_assoc()) {
                      $assignedCourses[] = $crs['courseName'];
                    }
                    $stmt->close();

                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";

                    echo "<td>";
                    if ($row['status'] === 'Idle') {
                      echo "<button class='addTaineeCourseBtn' 
                            data-id='" . htmlspecialchars($row['studentID'], ENT_QUOTES) . "' >
                            Add Course
                        </button>";
                    } elseif ($row['status'] === 'Ongoing') {
                      echo "<button class='addMoreTaineeCourseBtn' data-id='" . htmlspecialchars($row['studentID'], ENT_QUOTES) . "'>Add More Course</button>";
                    } else {
                      echo "-";
                    }
                    echo "</td>";

                    echo "<td><button class='seeStudentCourses'>See Courses</button></td>";

                    echo "</tr>";
                  }
                } else {
                  echo "<tr><td colspan='8'>No students found</td></tr>";
                }
              }

              renderTraineesTable($conn);
              ?>
            </tbody>
          </table>
        </div>
      </section>

      <section id="postUpdate">
        <div class="container">
          <h3>Create and Manage Updates, Announcements, and Notices</h3>
          <button type="button" id="createAnnouncementBtn">Create</button>
          
          <!-- Add Announcement Modal -->
          <div id="addAnnouncementModal" class="modal">
            <div class="modal-content">
              <span class="close">&times;</span>
              <h3>Create New Announcement</h3>
              <form id="announcementForm">
                <div class="form-group">
                  <label for="announcementType">Type:</label>
                  <select id="announcementType" name="type" required>
                    <option value="">Select Type</option>
                    <option value="announcement">Announcement</option>
                    <option value="notice">Notice</option>
                    <option value="update">Update</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="announcementCourse">Course (optional):</label>
                  <select id="announcementCourse" name="course_id">
                    <option value="">General Announcement</option>
                    <!-- Course options will be populated dynamically -->
                  </select>
                </div>
                <div class="form-group">
                  <label for="announcementMessage">Message:</label>
                  <textarea id="announcementMessage" name="message" required></textarea>
                </div>
                <div class="form-group">
                  <label for="announcementExpiry">Expiry Date (optional):</label>
                  <input type="date" id="announcementExpiry" name="expires_at">
                </div>
                <button type="submit">Submit</button>
              </form>
            </div>
          </div>
          
          <script>
          document.addEventListener('DOMContentLoaded', function() {
            // Function to delete announcement
            function deleteAnnouncement(id) {
              fetch(`../php/announcements.php?id=${id}`, {
                method: 'DELETE'
              })
                .then(response => {
                  if (!response.ok) {
                    throw new Error('Network response was not ok');
                  }
                  return response.text().then(text => {
                    try {
                      return JSON.parse(text);
                    } catch {
                      throw new Error('Invalid JSON response: ' + text);
                  }
                });
              })
              .then(data => {
                console.log('Submission response:', data);
                if (data.success) {
                  const tableBody = document.getElementById('announcementsTableBody');
                  tableBody.innerHTML = '';
                  
                  data.data.forEach(announcement => {
                    const row = document.createElement('tr');
                    
                    row.innerHTML = `
                      <td>${announcement.type}</td>
                      <td>${announcement.course_id || 'General'}</td>
                      <td>${announcement.message}</td>
                      <td>${new Date(announcement.created_at).toLocaleDateString()}</td>
                      <td>${announcement.expires_at ? new Date(announcement.expires_at).toLocaleDateString() : 'N/A'}</td>
                      <td>
                        <button type="button" class="deleteAnnouncementBtn" data-id="${announcement.id}">Delete</button>
                      </td>
                    `;
                    
                    tableBody.appendChild(row);
                  });
                  
                  // Add event listeners to delete buttons
                  document.querySelectorAll('.deleteAnnouncementBtn').forEach(btn => {
                    btn.addEventListener('click', function() {
                      const id = this.getAttribute('data-id');
                      if (confirm('Are you sure you want to delete this announcement?')) {
                        deleteAnnouncement(id);
                      }
                    });
                  });
                }
              })
              .catch(error => {
                console.error('Submission error:', error);
                console.error('Error loading announcements:', error);
                alert('Error loading announcements: ' + error.message);
              });
            }
            
            // Fetch courses for announcement form
            fetch('../php/getCourses.php')
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  const courseSelect = document.getElementById('announcementCourse');
                  data.courses.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.id;
                    option.textContent = course.courseName;
                    courseSelect.appendChild(option);
                  });
                }
              })
              .catch(error => console.error('Error loading courses:', error));
            
            // Function to load announcements
            function loadAnnouncements() {
              fetch('../php/announcements.php')
                .then(response => response.json())
                .then(data => {
                  if (data.success) {
                    const tableBody = document.getElementById('announcementsTableBody');
                    tableBody.innerHTML = '';
                    
                    data.data.forEach(announcement => {
                      const row = document.createElement('tr');
                      
                      row.innerHTML = `
                        <td>${announcement.type}</td>
                        <td>${announcement.course_id || 'General'}</td>
                        <td>${announcement.message}</td>
                        <td>${new Date(announcement.created_at).toLocaleDateString()}</td>
                        <td>${announcement.expires_at ? new Date(announcement.expires_at).toLocaleDateString() : 'N/A'}</td>
                        <td>
                          <button type="button" class="deleteAnnouncementBtn" data-id="${announcement.id}">Delete</button>
                        </td>
                      `;
                      
                      tableBody.appendChild(row);
                    });
                    
                    // Add event listeners to delete buttons
                    document.querySelectorAll('.deleteAnnouncementBtn').forEach(btn => {
                      btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        if (confirm('Are you sure you want to delete this announcement?')) {
                          deleteAnnouncement(id);
                        }
                      });
                    });
                  }
                })
                .catch(error => {
                  console.error('Error loading announcements:', error);
                  alert('Error loading announcements: ' + error.message);
                });
            }
            
            const announcementForm = document.getElementById('announcementForm');
            
            announcementForm.addEventListener('submit', function(e) {
              e.preventDefault();
              console.log('Announcement submission initiated');
              
              const submitBtn = e.target.querySelector('button[type="submit"]');
              let isSubmitting = false;
              if (submitBtn.disabled || isSubmitting) return;
              isSubmitting = true;
              console.log('Submit button state:', {
                disabled: submitBtn.disabled,
                text: submitBtn.textContent
              });
              submitBtn.disabled = true;
              
              const formData = {
                type: document.getElementById('announcementType').value,
                course_id: document.getElementById('announcementCourse').value || null,
                message: document.getElementById('announcementMessage').value,
                expires_at: document.getElementById('announcementExpiry').value || null
              };
              
              console.log('Submitting announcement:', formData);
              fetch('../php/announcements.php', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
              })
              .then(response => {
                if (!response.ok) {
                  throw new Error('Network response was not ok');
                }
                return response.text().then(text => {
                  try {
                    return JSON.parse(text);
                  } catch {
                    throw new Error('Invalid JSON response: ' + text);
                  }
                });
              })
              .then(data => {
                if (data.success) {
                  alert('Announcement created successfully!');
                  document.getElementById('addAnnouncementModal').style.display = 'none';
                  announcementForm.reset();
                  loadAnnouncements();
                } else {
                  alert('Error: ' + data.message);
                }
                submitBtn.disabled = false;
                  isSubmitting = false;
                  isSubmitting = false;
              })
              .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while creating the announcement: ' + error.message);
                submitBtn.disabled = false;
              });
            });
          });
          </script>
          <h4>Announcements</h4>
          <table>
            <thead>
              <tr>
                <th>Type</th>
                <th>Course</th>
                <th>Message</th>
                <th>Created At</th>
                <th>Expires At</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="announcementsTableBody">
              <!-- Announcements will be loaded dynamically -->
            </tbody>
          </table>
          
          <script>
          document.addEventListener('DOMContentLoaded', function() {
            // Function to load announcements
            function loadAnnouncements() {
              fetch('../php/announcements.php')
                .then(response => {
                  if (!response.ok) throw new Error('Network response was not ok');
                  return response.json();
                })
                .then(data => {
                  if (data.success) {
                    const tableBody = document.getElementById('announcementsTableBody');
                    tableBody.innerHTML = '';
                    
                    data.data.forEach(announcement => {
                      const row = document.createElement('tr');
                      row.innerHTML = `
                        <td>${announcement.type}</td>
                        <td>${announcement.course_id || 'General'}</td>
                        <td>${announcement.message}</td>
                        <td>${new Date(announcement.created_at).toLocaleDateString()}</td>
                        <td>${announcement.expires_at ? new Date(announcement.expires_at).toLocaleDateString() : 'N/A'}</td>
                        <td>
                          <button type="button" class="deleteAnnouncementBtn" data-id="${announcement.id}">Delete</button>
                        </td>
                      `;
                      tableBody.appendChild(row);
                    });
                    
                    // Add event listeners to delete buttons
                    document.querySelectorAll('.deleteAnnouncementBtn').forEach(btn => {
                      btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        if (confirm('Are you sure you want to delete this announcement?')) {
                          deleteAnnouncement(id);
                        }
                      });
                    });
                  }
                })
                .catch(error => {
                  console.error('Error loading announcements:', error);
                  alert('Error loading announcements: ' + error.message);
                });
            }
            
            // Function to delete announcement
            function deleteAnnouncement(id) {
              fetch(`../php/announcements.php?id=${id}`, {
                method: 'DELETE'
              })
                .then(response => {
                  if (!response.ok) throw new Error('Network response was not ok');
                  return response.json();
                })
                .then(data => {
                  if (data.success) {
                    alert('Announcement deleted successfully!');
                    loadAnnouncements();
                  } else {
                    alert('Error: ' + data.message);
                  }
                })
                .catch(error => {
                  console.error('Error:', error);
                  alert('An error occurred while deleting the announcement: ' + error.message);
                });
            }
            
            // Initial load
            loadAnnouncements();
          });
          </script>
        </div>
      </section>

      <section id="courseMgt">
        <div class="container">
          <h3>Current Courses</h3>
          <table>
            <thead>
              <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Schedules</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody id="coursesTableBody">
              <!-- Course data will be loaded dynamically -->
            </tbody>
          </table>
          <button type="button" id="setCourse">Set Course</button>
          <button type="button" id="addCourse">Add Course</button>
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
    <div id="catalog"></div>
  </main>



  <footer>
    <p>© 2025 Benguet Technical School. All rights reserved.</p>
  </footer>

  <!-- ANNOUNCEMENTS -->
  <script>
    // Announcements functionality
    document.addEventListener('DOMContentLoaded', () => {
      const announcementsTable = document.querySelector('#postUpdate table tbody');
      const createAnnouncementBtn = document.querySelector('#postUpdate button');
      
      // Load announcements
      function loadAnnouncements() {
        fetch('../php/announcements.php')
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              announcementsTable.innerHTML = '';
              data.data.forEach(announcement => {
                const row = document.createElement('tr');
                row.innerHTML = `
                  <td>${announcement.message.substring(0, 30)}${announcement.message.length > 30 ? '...' : ''}</td>
                  <td>${announcement.type}</td>
                  <td>${announcement.message}</td>
                  <td>${new Date(announcement.created_at).toLocaleDateString()}</td>
                  <td>${announcement.expires_at ? new Date(announcement.expires_at).toLocaleDateString() : 'N/A'}</td>
                  <td><button type="button" data-id="${announcement.id}">Delete</button></td>
                `;
                announcementsTable.appendChild(row);
              });
            }
          });
      }
      
      // Create announcement modal
      const announcementModal = document.getElementById('addAnnouncementModal');
      const closeModalBtn = announcementModal.querySelector('.close');
      
      createAnnouncementBtn.addEventListener('click', () => {
        announcementModal.style.display = 'block';
      });
      
      closeModalBtn.addEventListener('click', () => {
        announcementModal.style.display = 'none';
      });
      
      window.addEventListener('click', (e) => {
        if (e.target === announcementModal) {
          announcementModal.style.display = 'none';
        }
      });
      
      // Handle form submission
      document.getElementById('announcementForm').addEventListener('submit', (e) => {
        e.preventDefault();
        
        const formData = {
          type: document.getElementById('announcementType').value,
          message: document.getElementById('announcementMessage').value,
          course_id: document.getElementById('announcementCourse').value || null,
          expires_at: document.getElementById('announcementExpiry').value || null
        };
        
        fetch('../php/announcements.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(formData)
        })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              alert('Announcement created successfully!');
              announcementModal.style.display = 'none';
              document.getElementById('announcementForm').reset();
              loadAnnouncements();
            } else {
              alert('Error: ' + data.message);
            }
          });
      });
      
      // Delete announcement
      announcementsTable.addEventListener('click', (e) => {
        if (e.target.tagName === 'BUTTON') {
          const id = e.target.dataset.id;
          if (confirm('Are you sure you want to delete this announcement?')) {
            fetch(`../php/announcements.php?id=${id}`, {
              method: 'DELETE'
            })
              .then(res => res.json())
              .then(data => {
                if (data.success) {
                  loadAnnouncements();
                }
              });
          }
        }
      });
      
      // Initial load
      loadAnnouncements();
    });
    
  </script>
  
  <!-- NAVIGATION -->
  <script>
    const tabElements = document.querySelectorAll(
      ".trainerMgt, .traineeMgt, .postUpdate, .courseMgt, .profile"
    );
    const sectionElements = document.querySelectorAll(
      "#trainerMgt, #traineeMgt, #postUpdate, #courseMgt, #profile"
    );

    function handleTabAndSectionClick(clickedTab) {
      const tabClass = clickedTab.classList[0];
      const matchingSection = document.getElementById(tabClass);

      tabElements.forEach((el) => el.classList.remove("active"));
      sectionElements.forEach((el) => el.classList.remove("active"));

      clickedTab.classList.add("active");
      if (matchingSection) {
        matchingSection.classList.add("active");
      }

      localStorage.setItem("activeTab", tabClass);
    }

    window.addEventListener("DOMContentLoaded", () => {
      const savedTab = localStorage.getItem("activeTab");
      const defaultTab = savedTab ?
        document.querySelector(`.${savedTab}`) :
        document.querySelector(".trainerMgt");

      if (defaultTab) handleTabAndSectionClick(defaultTab);
    });

    tabElements.forEach((tab) => {
      tab.addEventListener("click", () => handleTabAndSectionClick(tab));
    });
  </script>

  <!-- THE ADDING OF TRAINERS -->
  <script>
    document.getElementById('addTrainerLink').addEventListener('click', function (e) {
      e.preventDefault();
      const popup = document.getElementById('popupForm');
      popup.style.display = (popup.style.display === 'flex') ? 'none' : 'flex';
    });
    document.getElementById('closeTrainer').addEventListener('click', function (e) {
      e.preventDefault();
      const popup = document.getElementById('popupForm');
      popup.style.display = "none"
    });


    document.addEventListener("DOMContentLoaded", () => {
      const fname = document.querySelector("input[name='firstName']");
      const lname = document.querySelector("input[name='lastName']");
      const email = document.querySelector("input[name='email']");

      function updateEmail() {
        if (fname.value && lname.value) {
          email.value = (fname.value + "." + lname.value).toLowerCase() + "@bts.gov.ph";
        }
      }
      fname.addEventListener("input", updateEmail);
      lname.addEventListener("input", updateEmail);

      document.querySelector("form").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch("../php/addTrainer.php", {
          method: "POST",
          body: formData
        })
          .then(res => res.json())
          .then(data => {
            if (data.status === "success") {
              alert(
                "Trainer Registered!\n" +
                "UserID: " + data.userID + "\n" +
                "Email: " + data.email + "\n" +
                "Password: " + data.password
              );
              location.reload();
            } else {
              alert("Error: " + data.message);
            }
          });
      });
    });

  </script>

  <!-- SET TRAINER STATUS -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      document.querySelectorAll(".setTrainerStatusBtn").forEach(btn => {
        btn.addEventListener('click', function () {
          const trainerRowID = this.dataset.id;


          const existingPopup = document.querySelector("#trainerStatusPopup, #trainerCoursePopup");
          if (existingPopup) existingPopup.remove();

          if (document.getElementById("trainerStatusPopup")) return;

          const popup = document.createElement('div');
          popup.id = "trainerStatusPopup";
          popup.style = "position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); border-radius: 8px; background-color: #f9f9f9; width: 400px; padding: 15px; box-shadow:0 2px 8px rgba(0,0,0,.2);";
          popup.innerHTML = `
            <h3>Set Status for ${trainerRowID}</h3>
            <form method="POST" style="display:flex; flex-direction:column; align-items:center; gap: 10px; justify-content:center; ">
              <input type="hidden" name="trainerRowID" value="${trainerRowID}">
              <select name="status" style="width: 100%; padding: 10px; font-family: 'Outfit', sans-serif; font-size: 1rem;" required>
                <option value="" selected disabled>Select Status</option>
                <option value="active">Active</option>
                <option value="on leave">On Leave</option>
                <option value="dismissed">Dismissed</option>
              </select>
              <div style="display: flex; width: 100%; justify-content: space-evenly; margin-top: 10px;">
                <button type="submit" name="saveStatusBtn">Save</button>
                <button type="button" id="closePopupBtn">Close</button>
              </div>
            </form>
          `;

          document.body.appendChild(popup);
          document.getElementById('closePopupBtn').addEventListener('click', () => popup.remove());
        })
      })

    })
  </script>
  <!-- SET TRAINER COURSE -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const availableCourses = <?php echo json_encode($courses); ?>;
      document.querySelectorAll(".assignTrainerBtn").forEach(btn => {
        btn.addEventListener('click', function () {
          const trainerRowID = this.dataset.id;

          const existingPopup = document.querySelector("#trainerStatusPopup, #trainerCoursePopup");
          if (existingPopup) existingPopup.remove();

          if (document.getElementById("trainerCoursePopup")) return;

          const popup = document.createElement('div');
          popup.id = "trainerCoursePopup";
          popup.style = "position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); border-radius: 8px; background-color: #f9f9f9; width: 400px; padding: 15px; box-shadow:0 2px 8px rgba(0,0,0,.2);";

          let options = '';
          availableCourses.forEach(c => {
            options += `<option value="${c.courseID}">${c.courseName}</option>`;
          });

          popup.innerHTML = `
                            <h3>Assign Courses for ${trainerRowID}</h3>
                            <form method="POST" style="display:flex; flex-direction:column; gap: 10px;">
                              <input type="hidden" name="trainerRowID" value="${trainerRowID}">
                              <select name="courseID[]" required 
                                style="padding:8px; font-family: 'Outfit', sans-serif; font-size: 1rem; height:max-content;">
                                ${options}
                              </select>
                              <div style="display:flex; justify-content:space-evenly; margin-top: 10px;">
                                <button type="submit" name="assignCourseBtn">Save</button>
                                <button type="button" id="closeAssignPopupBtn">Cancel</button>
                              </div>
                            </form>
                          `;

          document.body.appendChild(popup);
          document.getElementById('closeAssignPopupBtn').addEventListener('click', () => popup.remove());
        });
      });

    })
  </script>

  <!-- THE ADDING OF TRAINEES -->
  <script>
    const addStudentLink = document.getElementById('addStudentLink');
    const popupForm = document.getElementById('popupStudentForm');
    const closeBtn = document.getElementById('closeTrainee');
    const form = document.getElementById('traineeForm');

    addStudentLink.addEventListener('click', function (e) {
      e.preventDefault();
      popupForm.style.display = 'block';
    });

    closeBtn.addEventListener('click', function () {
      popupForm.style.display = 'none';
    });

    document.addEventListener('DOMContentLoaded', function () {
      const fname = document.querySelector("input[name='studentFirstName']");
      const lname = document.querySelector("input[name='studentLastName']");
      const email = document.querySelector("input[name='studentEmail']");

      function updateEmail() {
        if (fname.value && lname.value) {
          email.value = (fname.value + "." + lname.value).toLowerCase() + "@bts.gov.ph";
        }
      }
      fname.addEventListener("input", updateEmail);
      lname.addEventListener("input", updateEmail);

      document.querySelector("form").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch("../php/addTrainee.php", {
          method: "POST",
          body: formData
        })
          .then(res => res.json())
          .then(data => {
            if (data.status === "success") {
              alert(
                "Trainee Enrolled!\n" +
                "UserID: " + data.userID + "\n" +
                "Email: " + data.email + "\n" +
                "Password: " + data.password
              );
              location.reload();
            } else {
              alert("Error: " + data.message);
            }
          });
      });
    });
  </script>
<!-- TRAINEE SHOW COURSES -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const modal = document.getElementById('studentCoursesModal');
      const closeBtn = document.getElementById('closeCoursesModal');
      const modalContent = document.getElementById('modalContent');

      document.addEventListener('click', function (e) {
        if (e.target.classList.contains('seeStudentCourses')) {
          const row = e.target.closest('tr');
          const studentID = row.cells[2].textContent.trim();

          modal.style.display = 'block';
          modalContent.innerHTML = '<div class="loading">Loading student courses...</div>';

          fetchStudentCourses(studentID);
        }
      });

      closeBtn.onclick = function () {
        modal.style.display = 'none';
      };

      window.onclick = function (event) {
        if (event.target == modal) {
          modal.style.display = 'none';
        }
      };

      function fetchStudentCourses(studentID) {
        fetch('../php/getStudentCourses.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ studentID: studentID })
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              displayStudentCourses(data.studentInfo, data.courses);
            } else {
              modalContent.innerHTML = `<div class="error-message">Error: ${data.message}</div>`;
            }
          })
          .catch(error => {
            console.error('Error:', error);
            modalContent.innerHTML = '<div class="error-message">An error occurred while fetching course data.</div>';
          });
      }

      function displayStudentCourses(studentInfo, courses) {
        let html = `
            <div class="student-info">
                <h3>Student Information</h3>
                <p><strong>Name:</strong> ${studentInfo.fullName}</p>
                <p><strong>Student ID:</strong> ${studentInfo.userID}</p>
            </div>
            
            <div class="courses-container">
                <h3>Enrolled Courses</h3>
        `;

        if (courses.length === 0) {
          html += '<div class="no-courses">No courses found for this student.</div>';
        } else {
          courses.forEach(course => {
            const progress = parseFloat(course.progress) || 0;
            const progressClass = getProgressClass(progress);
            const lastUpdated = course.last_updated ? new Date(course.last_updated).toLocaleDateString() : 'N/A';

            html += `
                    <div class="course-card">
                        <div class="course-header">
                            <div class="course-name">${course.courseName}</div>
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-fill ${progressClass}" style="width: ${progress}%">
                                        ${progress.toFixed(1)}%
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="course-details">
                            <div class="detail-item">
                                <div class="detail-label">Course ID</div>
                                <div class="detail-value">${course.course_id}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Activities</div>
                                <div class="detail-value">${course.submittedActivity || 0}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Exams</div>
                                <div class="detail-value">${course.submittedExam || 0}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Projects</div>
                                <div class="detail-value">${course.submittedProjects || 0}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Last Updated</div>
                                <div class="detail-value">${lastUpdated}</div>
                            </div>
                        </div>
                    </div>
                `;
          });
        }

        html += '</div>';
        modalContent.innerHTML = html;
      }

      function getProgressClass(progress) {
        if (progress >= 100) return 'complete';
        if (progress >= 75) return 'high';
        if (progress >= 50) return 'medium';
        return 'low';
      }
    });
  </script>
<!-- TRAINEE COURSES -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const availableCourses = <?php echo json_encode($courses); ?>;

      console.log('Available Courses: ', availableCourses)

      document.addEventListener('click', function (e) {
        if (e.target.classList.contains('addTaineeCourseBtn') || e.target.classList.contains('addMoreTaineeCourseBtn')) {
          const studentID = e.target.getAttribute('data-id');
          const isAddMore = e.target.classList.contains('addMoreTaineeCourseBtn');

          showCourseAssignmentModal(studentID, isAddMore);
        }
      });

      function showCourseAssignmentModal(studentID, isAddMore) {
        const existingModal = document.getElementById('courseAssignmentModal');
        if (existingModal) {
          existingModal.remove();
        }

        const modal = document.createElement('div');
        modal.id = 'courseAssignmentModal';
        modal.className = 'modal';
        modal.style.display = 'block';

        const rows = document.querySelectorAll('tbody tr');
        let studentName = 'Student';
        rows.forEach(row => {
          if (row.cells[2].textContent.trim() === studentID) {
            studentName = row.cells[1].textContent.trim();
          }
        });

        const modalContent = `
            <div class="modal-content" style="max-width: 600px;">
                <span class="close" id="closeCourseModal">&times;</span>
                <div style="margin-top: 30px;">
                    <h3>${isAddMore ? 'Add More Courses' : 'Assign Courses'} for ${studentName}</h3>
                    <p><strong>Student ID:</strong> ${studentID}</p>
                    
                    <div style="margin: 20px 0;">
                        <h4>Available Courses:</h4>
                        <div id="coursesList" style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
                            <!-- Courses will be loaded here -->
                        </div>
                    </div>
                    
                    <div style="margin: 20px 0;">
                        <label>
                            <input type="checkbox" id="selectAllCourses"> Select All Courses
                        </label>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                        <button id="assignCoursesBtn" class="btn-primary">Assign Selected Courses</button>
                        <button id="cancelAssignmentBtn" class="btn-secondary">Cancel</button>
                    </div>
                    
                    <div id="assignmentStatus" style="margin-top: 15px; display: none;"></div>
                </div>
            </div>
        `;

        modal.innerHTML = modalContent;
        document.body.appendChild(modal);

        loadAvailableCourses(studentID, isAddMore);

        document.getElementById('closeCourseModal').onclick = () => modal.remove();
        document.getElementById('cancelAssignmentBtn').onclick = () => modal.remove();

        document.getElementById('selectAllCourses').onchange = function () {
          const checkboxes = document.querySelectorAll('#coursesList input[type="checkbox"]');
          checkboxes.forEach(cb => cb.checked = this.checked);
        };

        document.getElementById('assignCoursesBtn').onclick = () => assignCoursesToStudent(studentID);

        window.onclick = function (event) {
          if (event.target === modal) {
            modal.remove();
          }
        };
      }

      function loadAvailableCourses(studentID, isAddMore) {
        const coursesList = document.getElementById('coursesList');
        coursesList.innerHTML = '<div class="loading">Loading courses...</div>';

        if (isAddMore) {
          fetch('../php/getStudentCourses.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ studentID: studentID })
          })
            .then(response => response.json())
            .then(data => {
              const enrolledCourses = data.success ? data.courses.map(c => {
                const matchingCourse = availableCourses.find(ac => ac.courseName === c.courseName);
                return matchingCourse ? matchingCourse.courseID : null;
              }).filter(id => id !== null) : [];
              displayAvailableCourses(enrolledCourses);
            })
            .catch(() => {
              displayAvailableCourses([]);
            });
        } else {
          displayAvailableCourses([]);
        }
      }

      function displayAvailableCourses(enrolledCourses = []) {
        const coursesList = document.getElementById('coursesList');
        let html = '';

        const offeredCourses = availableCourses.filter(course => course.status === 'Offered');

        if (!offeredCourses || offeredCourses.length === 0) {
          coursesList.innerHTML = '<p>No courses available for enrollment</p>';
          return;
        }

        offeredCourses.forEach(course => {
          const isEnrolled = enrolledCourses.includes(course.courseID);
          const disabledAttr = isEnrolled ? 'disabled' : '';
          const checkedAttr = isEnrolled ? 'checked' : '';
          const labelClass = isEnrolled ? 'style="color: #999; text-decoration: line-through;"' : '';

          html += `
            <div style="margin: 8px 0; padding: 8px; border-radius: 4px; ${isEnrolled ? 'background-color: #f5f5f5;' : ''}">
                <label ${labelClass}>
                    <input type="checkbox" value="${course.courseID}" ${disabledAttr} ${checkedAttr}>
                    <strong>${course.courseID}</strong> - ${course.courseName}
                    ${isEnrolled ? '<span style="color: #28a745; margin-left: 10px;">(Already Enrolled)</span>' : ''}
                </label>
            </div>
        `;
        });

        coursesList.innerHTML = html;
      }
      function assignCoursesToStudent(studentID) {
        const selectedCourses = [];
        const checkboxes = document.querySelectorAll('#coursesList input[type="checkbox"]:checked:not([disabled])');

        checkboxes.forEach(cb => {
          selectedCourses.push(cb.value);
        });

        if (selectedCourses.length === 0) {
          alert('Please select at least one course to assign.');
          return;
        }

        const assignBtn = document.getElementById('assignCoursesBtn');
        const statusDiv = document.getElementById('assignmentStatus');

        assignBtn.disabled = true;
        assignBtn.textContent = 'Assigning...';
        statusDiv.style.display = 'block';
        statusDiv.innerHTML = '<div class="loading">Assigning courses...</div>';

        fetch('../php/addStudentCourse.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            studentID: studentID,
            courseIDs: selectedCourses
          })
        })
          .then(response => {
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
              return response.text().then(text => {
                throw new Error(`Server returned non-JSON response: ${text}`);
              });
            }
            return response.json();
          })
          .then(data => {
            if (data.success) {
              statusDiv.innerHTML = `<div style="color: green; padding: 10px; background: #d4edda; border-radius: 4px;">${data.message}</div>`;
              setTimeout(() => {
                location.reload();
              }, 2000);
            } else {
              statusDiv.innerHTML = `<div style="color: red; padding: 10px; background: #f8d7da; border-radius: 4px;">Error: ${data.message}</div>`;
              assignBtn.disabled = false;
              assignBtn.textContent = 'Assign Selected Courses';
            }
          })
          .catch(error => {
            console.error('Error:', error);
            statusDiv.innerHTML = `<div style="color: red; padding: 10px; background: #f8d7da; border-radius: 4px;">Error: ${error.message}</div>`;
            assignBtn.disabled = false;
            assignBtn.textContent = 'Assign Selected Courses';
          });
      }
    });
  </script>

  <!-- PROFILE FUNCTIONS -->
  <script>
    document.addEventListener("DOMContentLoaded", async () => {
      const container = document.querySelector("#profile .container");

      const response = await fetch('../php/getProfile.php');
      const responseData = await response.json();

      if (responseData.status !== "success") {
        container.innerHTML = `<p>${responseData.message}</p>`;
        return;
      }

      const data = responseData.data;

      const form = document.createElement("form");
      form.id = "profileForm";
      form.className = "capsule";

      const profileImageDiv = document.createElement("div");
      profileImageDiv.className = "profile-image";

      const img = document.createElement("img");
      img.id = "profilePic";
      img.src = data.profileImage ? `../${data.profileImage}` : "../images/school.png";
      img.alt = "Profile";

      const label = document.createElement("label");
      label.htmlFor = "image-edit";
      label.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#ffffff">
        <path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
      </svg>
      `;
      label.style.display = "none";

      const fileInput = document.createElement("input");
      fileInput.type = "file";
      fileInput.name = "image-edit";
      fileInput.id = "image-edit";
      fileInput.accept = "image/*";

      profileImageDiv.append(img, label, fileInput);

      const detailsDiv = document.createElement("div");
      detailsDiv.className = "details";

      const fields = [{
        label: "Name",
        id: "profile-name",
        value: `${data.firstName || ''} ${data.middleName || ''} ${data.lastName || ''} ${data.suffix || ''}`
          .trim()
      },
      {
        label: "Email",
        id: "profile-mail",
        value: data.email || ""
      },
      {
        label: "Contact",
        id: "profile-contact",
        value: data.mobileNumber || ""
      },
      {
        label: "Bio",
        id: "profile-bio",
        value: data.bio || ""
      }
      ];

      fields.forEach(f => {
        const labelEl = document.createElement("label");
        labelEl.htmlFor = f.id;
        labelEl.textContent = f.label;

        let inputEl;
        if (f.id === "profile-bio") {
          inputEl = document.createElement("textarea");
          inputEl.style.height = "100px";
          inputEl.style.whiteSpace = "pre-wrap";
          inputEl.value = f.value;
        } else {
          inputEl = document.createElement("input");
          inputEl.type = "text";
          inputEl.value = f.value;
        }
        inputEl.id = f.id;
        inputEl.disabled = true;
        detailsDiv.append(labelEl, inputEl);
      });

      const editButton = document.createElement("button");
      editButton.type = "button";
      editButton.id = "editButton";
      editButton.textContent = "Edit Profile";
      detailsDiv.appendChild(editButton);

      const editDiv = document.createElement("div");
      editDiv.className = "details-edit";
      editDiv.style.display = "none";

      fields.forEach(f => {
        const labelEl = document.createElement("label");
        labelEl.htmlFor = "edit-" + f.id;
        labelEl.textContent = f.label;

        let inputEl;
        if (f.id === "profile-bio") {
          inputEl = document.createElement("textarea");
          inputEl.style.height = "100px";
          inputEl.style.whiteSpace = "pre-wrap";
          inputEl.value = f.value;
        } else {
          inputEl = document.createElement("input");
          inputEl.type = "text";
          inputEl.value = f.value;
          if (f.id === "profile-mail" || f.id === "profile-contact") {
            inputEl.disabled = true;
          }
        }
        inputEl.id = "edit-" + f.id;
        editDiv.append(labelEl, inputEl);
      });

      const saveButton = document.createElement("button");
      saveButton.type = "button";
      saveButton.id = "saveButton";
      saveButton.textContent = "Save Profile";
      editDiv.appendChild(saveButton);

      form.append(profileImageDiv, detailsDiv, editDiv);
      container.appendChild(form);

      editButton.addEventListener("click", () => {
        fields.forEach(f => {
          document.getElementById("edit-" + f.id).value = document.getElementById(f
            .id).value;
        });
        detailsDiv.style.display = "none";
        editDiv.style.display = "flex";
        label.style.display = "flex";
      });

      saveButton.addEventListener("click", async () => {
        const formData = new FormData();
        fields.forEach(f => {
          formData.append(f.id, document.getElementById("edit-" + f.id).value);
        });
        if (fileInput.files[0]) formData.append("profileImage", fileInput.files[0]);

        const res = await fetch("../php/updateProfile.php", {
          method: "POST",
          body: formData
        });
        const result = await res.json();
        alert(result.message);
        if (result.status === "success") location.reload();
      });

      fileInput.addEventListener("change", () => {
        const file = fileInput.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = e => img.src = e.target.result;
          reader.readAsDataURL(file);
        }
      });
    });
  </script>
<!-- COURSES TABLE GENERATION -->
  <script>
    // Load courses data for the Course Management section
    document.addEventListener('DOMContentLoaded', function() {
      const coursesTableBody = document.getElementById('coursesTableBody');
      const setCourseBtn = document.getElementById('setCourse');
      const addCourseBtn = document.getElementById('addCourse');
      const setCourseModal = document.getElementById('setCourseModal');
      const addCourseModal = document.getElementById('addCourseModal');
      const closeSetCourseModal = document.getElementById('closeSetCourseModal');
      const closeAddCourseModal = document.getElementById('closeAddCourseModal');
      const cancelSetCourse = document.getElementById('cancelSetCourse');
      const cancelAddCourse = document.getElementById('cancelAddCourse');
      const courseSelect = document.getElementById('courseSelect');
      const setCourseForm = document.getElementById('setCourseForm');
      const addCourseForm = document.getElementById('addCourseForm');
      
      let coursesData = [];
      
      // Function to load courses
      function loadCourses() {
        // Fetch courses data from the server
        fetch('../php/getCourses.php')
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              coursesData = data.courses;
              
              // Clear any existing content
              coursesTableBody.innerHTML = '';
              
              // Clear and populate the course select dropdown
              courseSelect.innerHTML = '<option value="">-- Select a course --</option>';
              
              // Check if there are courses to display
              if (coursesData && coursesData.length > 0) {
                // Populate the table with course data
                coursesData.forEach(course => {
                  // Add to table
                  const row = document.createElement('tr');
                  
                  // Create and append table cells
                  const codeCell = document.createElement('td');
                  codeCell.textContent = course.courseID;
                  row.appendChild(codeCell);
                  
                  const nameCell = document.createElement('td');
                  nameCell.textContent = course.courseName;
                  row.appendChild(nameCell);
                  
                  const scheduleCell = document.createElement('td');
                  scheduleCell.textContent = course.courseSchedule || 'N/A';
                  row.appendChild(scheduleCell);
                  
                  const statusCell = document.createElement('td');
                  statusCell.textContent = course.status === '1' ? 'Offered' : 'Not Offered';
                  row.appendChild(statusCell);
                  
                  // Add the row to the table
                  coursesTableBody.appendChild(row);
                  
                  // Add to dropdown
                  const option = document.createElement('option');
                  option.value = course.courseID;
                  option.textContent = `${course.courseID} - ${course.courseName}`;
                  courseSelect.appendChild(option);
                });
              } else {
                // Display a message if no courses are found
                const row = document.createElement('tr');
                const cell = document.createElement('td');
                cell.colSpan = 4;
                cell.textContent = 'No courses found';
                cell.style.textAlign = 'center';
                row.appendChild(cell);
                coursesTableBody.appendChild(row);
              }
            } else {
              // Display error message if the request failed
              console.error('Failed to load courses:', data.message);
              const row = document.createElement('tr');
              const cell = document.createElement('td');
              cell.colSpan = 4;
              cell.textContent = 'Error loading courses. Please try again later.';
              cell.style.textAlign = 'center';
              row.appendChild(cell);
              coursesTableBody.appendChild(row);
            }
          })
          .catch(error => {
            console.error('Error fetching courses:', error);
            const row = document.createElement('tr');
            const cell = document.createElement('td');
            cell.colSpan = 4;
            cell.textContent = 'Error loading courses. Please try again later.';
            cell.style.textAlign = 'center';
            row.appendChild(cell);
            coursesTableBody.appendChild(row);
          });
      }
      
      // Initial load of courses
      loadCourses();
      
      // Set Course button click handler
      setCourseBtn.addEventListener('click', function() {
        setCourseModal.style.display = 'block';
      });
      
      // Add Course button click handler
      addCourseBtn.addEventListener('click', function() {
        addCourseModal.style.display = 'block';
      });
      
      // Close modal handlers
      closeSetCourseModal.addEventListener('click', function() {
        setCourseModal.style.display = 'none';
      });
      
      closeAddCourseModal.addEventListener('click', function() {
        addCourseModal.style.display = 'none';
      });
      
      cancelSetCourse.addEventListener('click', function() {
        setCourseModal.style.display = 'none';
      });
      
      cancelAddCourse.addEventListener('click', function() {
        addCourseModal.style.display = 'none';
      });
      
      // Close modals when clicking outside
      window.addEventListener('click', function(event) {
        if (event.target === setCourseModal) {
          setCourseModal.style.display = 'none';
        }
        if (event.target === addCourseModal) {
          addCourseModal.style.display = 'none';
        }
      });
      
      // Course select change handler
      courseSelect.addEventListener('change', function() {
        const selectedCourseID = this.value;
        if (selectedCourseID) {
          const selectedCourse = coursesData.find(course => course.courseID === selectedCourseID);
          if (selectedCourse) {
            document.getElementById('courseStatus').value = selectedCourse.status === '1' ? 'Offered' : 'Not Offered';
            document.getElementById('courseSchedule').value = selectedCourse.courseSchedule || '';
          }
        }
      });
      
      // Set Course form submit handler
      setCourseForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const statusDiv = document.getElementById('setCourseStatus');
        statusDiv.style.display = 'block';
        statusDiv.innerHTML = '<div style="color: blue; padding: 10px; background: #cce5ff; border-radius: 4px;">Updating course...</div>';
        
        const formData = new FormData(this);
        
        fetch('../php/updateCourse.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            statusDiv.innerHTML = `<div style="color: green; padding: 10px; background: #d4edda; border-radius: 4px;">${data.message}</div>`;
            setTimeout(() => {
              setCourseModal.style.display = 'none';
              loadCourses(); // Reload courses after update
            }, 2000);
          } else {
            statusDiv.innerHTML = `<div style="color: red; padding: 10px; background: #f8d7da; border-radius: 4px;">Error: ${data.message}</div>`;
          }
        })
        .catch(error => {
          console.error('Error:', error);
          statusDiv.innerHTML = `<div style="color: red; padding: 10px; background: #f8d7da; border-radius: 4px;">Error: ${error.message}</div>`;
        });
      });
      
      // Add Course form submit handler
      addCourseForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const statusDiv = document.getElementById('addCourseStatus');
        statusDiv.style.display = 'block';
        statusDiv.innerHTML = '<div style="color: blue; padding: 10px; background: #cce5ff; border-radius: 4px;">Adding course...</div>';
        
        const formData = new FormData(this);
        
        fetch('../php/addCourse.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            statusDiv.innerHTML = `<div style="color: green; padding: 10px; background: #d4edda; border-radius: 4px;">${data.message}</div>`;
            setTimeout(() => {
              addCourseModal.style.display = 'none';
              addCourseForm.reset(); // Reset the form
              loadCourses(); // Reload courses after adding
            }, 2000);
          } else {
            statusDiv.innerHTML = `<div style="color: red; padding: 10px; background: #f8d7da; border-radius: 4px;">Error: ${data.message}</div>`;
          }
        })
        .catch(error => {
          console.error('Error:', error);
          statusDiv.innerHTML = `<div style="color: red; padding: 10px; background: #f8d7da; border-radius: 4px;">Error: ${error.message}</div>`;
        });
      });
    });
  </script>

  <div id="studentCoursesModal" class="modal">
    <div class="modal-content">
      <span class="close" id="closeCoursesModal">&times;</span>
      <div id="modalContent">
        <div class="loading">Loading...</div>
      </div>
    </div>
  </div>
  
  <!-- Course Management Modals -->
  <div id="setCourseModal" class="modal">
    <div class="modal-content">
      <span class="close" id="closeSetCourseModal">&times;</span>
      <h3>Set Course Status</h3>
      <form id="setCourseForm">
        <div style="margin-bottom: 20px;">
          <label for="courseSelect">Select Course:</label>
          <select id="courseSelect" name="courseID" required>
            <option value="">-- Select a course --</option>
            <!-- Courses will be loaded dynamically -->
          </select>
        </div>
        <div style="margin-bottom: 20px;">
          <label for="courseStatus">Status:</label>
          <select id="courseStatus" name="status" required>
            <option value="Offered">Offered</option>
            <option value="Not Offered">Not Offered</option>
          </select>
        </div>
        <div style="margin-bottom: 20px;">
          <label for="courseSchedule">Schedule (optional):</label>
          <input type="text" id="courseSchedule" name="courseSchedule" placeholder="e.g. MWF 9:00-11:00">
        </div>
        <div id="setCourseStatus" style="margin-top: 15px; display: none;"></div>
        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
          <button type="submit" class="btn-primary">Update Course</button>
          <button type="button" id="cancelSetCourse" class="btn-secondary">Cancel</button>
        </div>
      </form>
    </div>
  </div>
  
  <div id="addCourseModal" class="modal">
    <div class="modal-content">
      <span class="close" id="closeAddCourseModal">&times;</span>
      <h3>Add New Course</h3>
      <form id="addCourseForm">
        <div style="margin-bottom: 20px;">
          <label for="newCourseID">Course ID:</label>
          <input type="text" id="newCourseID" name="courseID" required placeholder="e.g. CSNC2">
        </div>
        <div style="margin-bottom: 20px;">
          <label for="newCourseName">Course Name:</label>
          <input type="text" id="newCourseName" name="courseName" required placeholder="e.g. Computer Servicing NC II">
        </div>
        <div style="margin-bottom: 20px;">
          <label for="newCourseSchedule">Schedule:</label>
          <input type="text" id="newCourseSchedule" name="courseSchedule" placeholder="e.g. MWF 9:00-11:00">
        </div>
        <div style="margin-bottom: 20px;">
          <label for="newCourseDescription">Description:</label>
          <textarea id="newCourseDescription" name="description" rows="4" placeholder="Enter course description"></textarea>
        </div>
        <div style="margin-bottom: 20px;">
          <label for="newCourseStatus">Status:</label>
          <select id="newCourseStatus" name="status" required>
            <option value="Offered">Offered</option>
            <option value="Not Offered">Not Offered</option>
          </select>
        </div>
        <div id="addCourseStatus" style="margin-top: 15px; display: none;"></div>
        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
          <button type="submit" class="btn-primary">Add Course</button>
          <button type="button" id="cancelAddCourse" class="btn-secondary">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>