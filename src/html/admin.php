<?php
require_once "../php/authentication.php";
authenticate();

require_once "../php/DatabaseConnection.php";

$courses = [];
$cResult = $conn->query("SELECT courseID, courseName FROM coursestable ORDER BY courseName ASC");
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
                <th>Action</th>
                <th>Assign</th>
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
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";

                    echo "<td><button type='button' class='setTraineeStatusBtn' data-id='{$row['studentID']}'>Set Status</button></td>";

                    $assignedCourses = [];
                    $stmt = $conn->prepare("SELECT courseName FROM studentprogress WHERE studentID = ?");
                    $stmt->bind_param("s", $row['studentID']);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    while ($crs = $res->fetch_assoc()) {
                      $assignedCourses[] = $crs['courseName'];
                    }
                    $stmt->close();

                    echo "<td>";
                    if ($row['status'] === 'active') {
                      echo "<button class='addTaineeCourseBtn' 
                            data-id='" . htmlspecialchars($row['studentID'], ENT_QUOTES) . "' >
                            Add Course
                        </button>";
                    } else {
                      echo "-";
                    }
                    echo "</td>";

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
          <button type="button">Create</button>
          <h4>Announcements</h4>
          <table>
            <thead>
              <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Description</th>
                <th>Date of Creation</th>
                <th>Due Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Enrollment for S.Y. 2025 - 2026</td>
                <td>Announcement</td>
                <td>The enrollment for all courses is now open.</td>
                <td>04-26-2025</td>
                <td>08-01-2025</td>
                <td>
                  <button type="button">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
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
            <tbody>
              <tr>
                <td>ACPN2</td>
                <td>Agricultural Crops Production NC II</td>
                <td>MWFSat 2:00-4:00</td>
                <td>Offered</td>
              </tr>

              <tr>
                <td>ASNC1</td>
                <td>Automotive Servicing NC I</td>
                <td>TThF 10:00-12:00</td>
                <td>Offered</td>
              </tr>

              <tr>
                <td>CSNC2</td>
                <td>Computer Servicing NC II</td>
                <td>N/A</td>
                <td>Not Offered</td>
              </tr>
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
</body>

</html>