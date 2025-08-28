<?php
require_once "../php/authentication.php";
authenticate();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student's Page - Benguet Technical School</title>
  <link rel="icon" href="../images/school.png" type="image/x-icon">
  <link rel="stylesheet" href="../css/student.css" />
</head>

<body>
  <nav>
    <div class="logo">
      <h1>BTS Student</h1>
    </div>
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
    <div id="student-profile">
      <img src="<?= htmlspecialchars($profileImg) ?>" alt="Profile Image" />
      <h3 class="name"><?= htmlspecialchars($fullName) ?></h3>
      <p><?= htmlspecialchars($role) ?></p>
      <p class="student-id-number"><?= htmlspecialchars($UID) ?></p>
    </div>
    <div id="navigation">
      <ul>
        <li><a class="dashboard active" href="#dashboard">Dashboard</a></li>
        <li><a class="courses" href="#courses">My Courses</a></li>
        <li><a class="activities" href="#activities">My Activities</a></li>
        <li><a class="enrollment" href="#enrollment">Enrollment</a></li>
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
      <section id="dashboard">
        <div class="container">
          <div id="dash-cards">
            <div class="card c1">
              <h3>Enrolled Courses</h3>
              <?php
              require_once "../php/DatabaseConnection.php";
              $sqlEnrolled = "SELECT COUNT(*) AS total FROM enrolledtable WHERE user_id = ? AND status = 'Approved'";
              $stmt = $conn->prepare($sqlEnrolled);
              $stmt->bind_param("i", $_SESSION['userID']);
              $stmt->execute();
              $res = $stmt->get_result();
              $totalEnrolled = ($row = $res->fetch_assoc()) ? $row['total'] : 0;
              ?>
              <h2><?= $totalEnrolled ?></h2>
              <p><i>Courses</i></p>
            </div>
            <div class="card c2">
              <h3>Hours Studied</h3>
              <?php

              $sqlHours = "SELECT SUM(time) AS totalHours FROM timetracker WHERE user_id = ?";
              $stmt = $conn->prepare($sqlHours);
              $stmt->bind_param("i", $_SESSION['userID']);
              $stmt->execute();
              $res = $stmt->get_result();
              $totalHours = isset($row['totalHours']) ? $row['totalHours'] : 0;
              ?>
              <h2><?= $totalHours ?></h2>
              <p><i>Hours</i></p>
            </div>
            <div class="card c3">
              <h3>Average Grade</h3>
              <?php
              $sqlAvg = "SELECT COALESCE(AVG(total_grade), 0) AS avgGrade FROM finalgradestable WHERE user_id = ?";
              $stmt = $conn->prepare($sqlAvg);
              $stmt->bind_param("i", $_SESSION['userID']);
              $stmt->execute();
              $res = $stmt->get_result();
              $row = $res->fetch_assoc();
              $averageGrade = isset($row['avgGrade']) ? round($row['avgGrade'], 2) : 0; // round to 2 decimals
              ?>
              <h2><?= $averageGrade ?>%</h2>
              <p><i>Avg</i></p>
            </div>
            <div class="card c4">
              <h3>My Courses (Quick Access)</h3>
              <div class="inside-container">
                <?php
                $userId = $_SESSION['userID'];

                $sqlCourses = "SELECT en.id AS enrollmentId, c.id AS courseId, c.courseName, t.trainerName
                   FROM enrolledtable en
                   JOIN coursestable c ON en.course_id = c.id
                   JOIN assignedcourses ac ON c.id = ac.course_id
                   JOIN trainerstable t ON ac.trainer_id = t.id
                   WHERE en.user_id = ? AND en.status = 'Approved'
                   LIMIT 2";

                $stmt = $conn->prepare($sqlCourses);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                  while ($course = $result->fetch_assoc()) {
                    $sqlModule = "SELECT file_path, title FROM modulestable 
                      WHERE course_id = ? 
                      ORDER BY created_at DESC 
                      LIMIT 1";
                    $stmtMod = $conn->prepare($sqlModule);
                    $stmtMod->bind_param("i", $course['courseId']);
                    $stmtMod->execute();
                    $resMod = $stmtMod->get_result();
                    $module = $resMod->fetch_assoc();
                    $moduleName = $module ? $module['title'] : "No recent modules";
                ?>
                    <div class="wrapper">
                      <div class="infos">
                        <h4><?= htmlspecialchars($course['courseName']) ?></h4>
                        <p class="trainer"><i><?= htmlspecialchars($course['trainerName']) ?> (Trainer)</i></p>
                        <p class="recent-module"><i><?= htmlspecialchars($moduleName) ?></i></p>
                      </div>
                      <?php if ($module): ?>
                        <button class="materials">
                          <a href="../<?= htmlspecialchars($module['file_path']) ?>" download>Download</a>
                        </button>
                      <?php else: ?>
                        <button class="materials" disabled>No Materials</button>
                      <?php endif; ?>
                    </div>
                <?php }
                } else {
                  echo "<p style='text-align:center; width:100%;'>No courses yet</p>";
                }
                ?>
              </div>
            </div>

            <div class="card c5">
              <h3>Enrollment Requests</h3>
              <?php
              $userId = $_SESSION['userID'];

              $sqlRequests = "SELECT COUNT(*) AS pendingCount 
                  FROM enrollmenttable 
                  WHERE user_id = ? AND status = 'Pending'";
              $stmt = $conn->prepare($sqlRequests);
              $stmt->bind_param("i", $userId);
              $stmt->execute();
              $res = $stmt->get_result();
              $pendingCount = ($row = $res->fetch_assoc()) ? $row['pendingCount'] : 0;

              if ($pendingCount > 0) {
                echo "<p>You have {$pendingCount} pending request(s)</p>";
              } else {
                echo "<p>No pending requests</p>";
              }
              ?>
            </div>

          </div>
        </div>
      </section>
      <section id="courses">
        <div class="container">
          <h2>My Courses</h2>
          <table>
            <thead>
              <tr>
                <th>Course</th>
                <th>Instructor</th>
                <th>Progress</th>
                <th>Materials</th>
              </tr>
            </thead>
            <tbody>
              <?php
              require_once "../php/DatabaseConnection.php";

              $userId = $_SESSION['userID'];

              $sqlMy = "SELECT en.id AS enrollmentId, 
                     c.id AS courseId, 
                     c.courseName, 
                     en.status, 
                     t.trainerName
              FROM enrolledtable en
              JOIN coursestable c 
                  ON en.course_id = c.id
              JOIN assignedcourses ac 
                  ON c.id = ac.course_id
              JOIN trainerstable t 
                  ON ac.trainer_id = t.id
              WHERE en.user_id = ? 
                AND en.status = 'Approved'";

              $stmt = $conn->prepare($sqlMy);
              $stmt->bind_param("i", $userId);
              $stmt->execute();
              $result = $stmt->get_result();

              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($row['courseName']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['trainerName']) . "</td>";
                  echo "<td>In Progress</td>";

                  $sqlMat = "SELECT file_path FROM modulestable WHERE course_id = ?";
                  $stmtMat = $conn->prepare($sqlMat);
                  $stmtMat->bind_param("i", $row['courseId']);
                  $stmtMat->execute();
                  $resMat = $stmtMat->get_result();

                  echo "<td>";
                  if ($resMat->num_rows > 0) {
                    while ($mat = $resMat->fetch_assoc()) {
                      $file = htmlspecialchars($mat['file_path']);
                      echo "<a href='../uploads/$file' download>" . basename($file) . "</a><br>";
                    }
                  } else {
                    echo "No materials";
                  }
                  echo "</td>";

                  echo "</tr>";
                }
              } else {
                echo "<tr><td colspan='4' style='text-align:center;'>No Ongoing Courses. Enroll Now!</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
        <div class="container">
          <h2>Available Courses</h2>
          <table>
            <?php

            require_once "../php/DatabaseConnection.php";

            $student_id = $_SESSION['userID'];

            $sqlAvailable = "SELECT c.id AS course_id, 
                                    c.courseName, 
                                    e.status
                              FROM coursestable c
                              LEFT JOIN enrollmenttable e 
                                    ON e.course_id = c.id 
                                    AND e.user_id = ?
                              ";

            $stmt = $conn->prepare($sqlAvailable);
            $stmt->bind_param("i", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();

            echo "
                  <thead>
                    <tr>
                      <th>Courses</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  ";

            while ($row = $result->fetch_assoc()) {
              $courseName = $row['courseName'];
              $status = ucfirst($row['status'] ?? "No Record");

              $disabled = ($status !== "No Record") ? "disabled" : "";

              echo "
                    <tr>
                      <td>{$courseName}</td>
                      <td>{$status}</td>
                      <td>
                        <button type='button' class='enrollBtn' 
                                data-course-id='{$row['course_id']}' 
                                data-course-name='{$courseName}' 
                                {$disabled}>
                          Enroll
                        </button>
                      </td>
                    </tr>
                    ";
            }

            echo "</tbody>";

            ?>
          </table>
        </div>

        <div id="popup-enrollment">
          <div>
            <h3 id="popupCourseName"></h3>
            <p>Note: The personal information you provided</p>
            <p>on your profile will be submitted to the school.</p>
            <div class="buttons">
              <button id="closePopup">Close</button>
              <button id="submitEnrollment">Submit Enrollment</button>
            </div>
          </div>
        </div>
      </section>
      <section id="activities">
        <div class="container">
          <h2>My Activities</h2>
          <table>
            <?php
            include '../php/studentActivity.php';
            ?>
          </table>
        </div>
      </section>

      <section id="enrollment">
        <div class="container">
          <h2>Enrollment History</h2>
          <table>
            <thead>
              <tr>
                <th>Course</th>
                <th>Requested Date</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php

              $studentID = $_SESSION['userID'];

              $query = "SELECT c.courseName, e.enrolled_at AS requestDate, e.status
          FROM enrollmenttable e
          JOIN coursestable c ON e.course_id = c.id
          WHERE e.user_id = ?";
              $stmt = $conn->prepare($query);
              $stmt->bind_param("i", $studentID);
              $stmt->execute();
              $result = $stmt->get_result();

              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($row['courseName']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['requestDate']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                  echo "</tr>";
                }
              } else {
                echo "<tr><td colspan='3'>No Enrollment Records Found</td></tr>";
              }
              ?>
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

  <!-- NAVIGATION TABS -->
  <script>
    const tabElements = document.querySelectorAll(
      ".dashboard, .courses, .activities, .enrollment, .profile"
    );
    const sectionElements = document.querySelectorAll(
      "#dashboard, #courses, #activities, #enrollment, #profile"
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
        document.querySelector(".dashboard");

      if (defaultTab) handleTabAndSectionClick(defaultTab);
    });

    tabElements.forEach((tab) => {
      tab.addEventListener("click", () => handleTabAndSectionClick(tab));
    });
  </script>

  <!-- COURSE LOGIC -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const popup = document.getElementById("popup-enrollment");
      const closePopupBtn = document.getElementById("closePopup");
      const submitBtn = document.getElementById("submitEnrollment");
      const popupCourseName = document.getElementById("popupCourseName");
      let selectedCourseId = null;

      document.querySelectorAll(".enrollBtn").forEach(btn => {
        btn.addEventListener("click", () => {
          selectedCourseId = btn.getAttribute("data-course-id");
          popupCourseName.textContent = btn.getAttribute("data-course-name");
          popup.style.display = "block";
        });
      });

      closePopupBtn.addEventListener("click", () => {
        popup.style.display = "none";
        selectedCourseId = null;
      });

      submitBtn.addEventListener("click", () => {
        if (!selectedCourseId) return;

        fetch("../php/submitEnrollment.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `course_id=${selectedCourseId}`
          })
          .then(res => res.json())
          .then(data => {
            alert(data.message);
            if (data.success) {
              location.reload();
            }
          });
      });
    });
  </script>


  <!-- ACTIVITIES FUNCTIONS -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const popup = document.querySelector("#popup");
      const button = document.querySelector(".button > button");

      button.addEventListener("click", () => {
        if (popup.classList.contains("active")) {
          popup.classList.remove("active");
        } else {
          popup.classList.add("active");
        }
      });

      const fileInput = document.querySelector("#file-input");
      const fileName = document.querySelector("#file-name");
      const fileSVGLabel = document.querySelector("#file-label > svg");

      fileInput.addEventListener("change", function() {
        if (this.files && this.files.length > 0) {
          fileName.childNodes[0].nodeValue = this.files[0].name;
          fileSVGLabel.style.display = "none";
        } else {
          fileName.textContent = "Choose File";
          fileSVGLabel.style.display = "";
        }
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
      <path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/>
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
          value: `${data.firstName || ''} ${data.middleName || ''} ${data.lastName || ''} ${data.suffix || ''}`.trim()
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
          document.getElementById("edit-" + f.id).value = document.getElementById(f.id).value;
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

  <!-- ANNOUNCEMENTS SYSTEM -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const notificationsList = document.querySelector('.notifications-list');
      const refreshBtn = document.getElementById('refreshNotifications');

      async function loadAnnouncements() {
        try {
          const response = await fetch('../php/getAnnouncements.php');
          const {
            success,
            data,
            message
          } = await response.json();

          notificationsList.innerHTML = '';

          if (success && data.length > 0) {
            data.forEach(announcement => {
              const item = document.createElement('div');
              item.className = 'notification-item';
              item.innerHTML = `
                <div class="notification-header">
                  <span class="type ${announcement.type.toLowerCase()}">${announcement.type}</span>
                  <small>${new Date(announcement.created_at).toLocaleDateString()}</small>
                </div>
                <p class="message">${announcement.message}</p>
                ${announcement.expires_at ? 
                  `<small class="expiry">Expires: ${new Date(announcement.expires_at).toLocaleDateString()}</small>` : ''}
              `;
              notificationsList.appendChild(item);
            });
          } else {
            notificationsList.innerHTML = `<div class="no-notifications">${message || 'No announcements available'}</div>`;
          }
        } catch (error) {
          console.error('Error loading announcements:', error);
          notificationsList.innerHTML =
            '<div class="error">Error loading announcements. Please try refreshing.</div>';
        }
      }

      refreshBtn.addEventListener('click', loadAnnouncements);
      loadAnnouncements();
      setInterval(loadAnnouncements, 60000);
    });
  </script>
</body>

</html>