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
              <button><i>Self-Enroll</i></button>
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
              <tr>
                <td>Japanese Language and Culture</td>
                <td>Ane S. Thesia</td>
                <td>05-13-25</td>
                <td>Pending Approval</td>
                <td>
                  <div class="button">
                    <button type="button" class="acceptBtn">Accept</button>
                    <button type="button" class="rejectBtn">Reject</button>
                  </div>
                </td>
              </tr>
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
              <tr>
                <td>Agricultural Crops Production NC II</td>
                <td>10</td>
                <td>65%</td>
                <td>
                  <button type="button" id="stateBtn" style="background-color: #006aff; color: white">
                    Enabled
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
      <section id="activities-modules">
        <div class="container">
          <form action="" class="uploadActForms">
            <button type="button" id="createActivity">Create Activity</button>
            <button type="button" id="uploadModule">Upload Module</button>
            <div class="createAct">
              <label for="actTitle">
                Activity Title
                <input type="text" name="actTitle" id="actTitle" />
              </label>
              <select name="courseActivityOption" id="courseActivityOption">
                <option value="Agricultural Crops Production NC II">
                  Agricultural Crops Production NC II
                </option>
                <option value="Automotive Servicing NC I">
                  Automotive Servicing NC I
                </option>
                <option value="Bread and Pastry Production NC II">
                  Bread and Pastry Production NC II
                </option>
                <option value="Dressmaking NC II">Dressmaking NC II</option>
                <option value="Hairdressing NC II">Hairdressing NC II</option>
                <option value="Japanese Language and Culture">
                  Japanese Language and Culture
                </option>
                <option value="Driving NC II">Driving NC II</option>
                <option value="Tailoring NC II">Tailoring NC II</option>
              </select>
              <label for="uploadAct">
                Upload Activity
                <input type="file" name="uploadAct" id="uploadAct" />
              </label>
              <input type="submit" value="Upload Activity" />
            </div>

            <div class="addModule">
              <label>Module Title
                <input type="text" name="moduleTitle" id="moduleTitle" /></label>
              <select name="courseModuleOption" id="courseModuleOption">
                <option value="Agricultural Crops Production NC II">
                  Agricultural Crops Production NC II
                </option>
                <option value="Automotive Servicing NC I">
                  Automotive Servicing NC I
                </option>
                <option value="Bread and Pastry Production NC II">
                  Bread and Pastry Production NC II
                </option>
                <option value="Dressmaking NC II">Dressmaking NC II</option>
                <option value="Hairdressing NC II">Hairdressing NC II</option>
                <option value="Japanese Language and Culture">
                  Japanese Language and Culture
                </option>
                <option value="Driving NC II">Driving NC II</option>
                <option value="Tailoring NC II">Tailoring NC II</option>
              </select>
              <label>Select Module<input type="file" name="uploadModuleFile" id="uploadModuleFile" /></label>
              <input type="submit" value="Upload Module" />
            </div>
          </form>
          <table>
            <thead>
              <tr>
                <th>Activity</th>
                <th>Course</th>
                <th>Due</th>
                <th>Actions</th>
              </tr>
            </thead>

            <tbody>
              <tr>
                <td>Quiz 1 Agriculture</td>
                <td>Agricultural Crops Production NC II</td>
                <td>02-23-2025</td>
                <td>
                  <div class="buttons">
                    <button type="button" class="editAct">Edit</button>
                    <button type="button" class="deleteAct" style="background-color: red">
                      Delete
                    </button>
                    <form action="" class="editActForm"></form>
                  </div>
                </td>
              </tr>
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
              <tr>
                <td>Ane S. Thesia</td>
                <td>anesthesia.d@domain.com</td>
                <td>Agricultural Crops Production NC II</td>
                <td>50%</td>
              </tr>
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
              <tr>
                <td>Ane S. Thesia</td>
                <td>Course</td>
                <td>Activity 1</td>
                <td>06-27-2025</td>
                <td>
                  <button type="button" class="viewSubmissions">
                    Add Grade and Comment
                  </button>
                  <form class="gradeActivity" action=""></form>
                </td>
              </tr>
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
      setInterval(loadAnnouncements, 60000); // Auto-refresh every 60 seconds
    });
  </script>

  <!-- NAVIGATION TABS -->
  <script>
    const tabElements = document.querySelectorAll(
      ".dashboard, .enrollment-request, .courses, .activities-modules, .trainees, .submissions, .profile, .redir"
    );
    const sectionElements = document.querySelectorAll(
      "#dashboard, #enrollment-request, #courses, #activities-modules, #trainees, #submissions, #profile"
    );

    function handleTabAndSectionClick(clickedTab) {
      const tabClass = clickedTab.classList[0];
      const targetId = tabClass === "redir" ? "enrollment-request" : tabClass;
      const matchingSection = document.getElementById(targetId);

      tabElements.forEach((el) => el.classList.remove("active"));
      sectionElements.forEach((el) => el.classList.remove("active"));

      const correctNav = document.querySelector(`.${targetId}`);
      if (correctNav) correctNav.classList.add("active");

      if (matchingSection) matchingSection.classList.add("active");

    }

    window.addEventListener("DOMContentLoaded", () => {
      const defaultTab = document.querySelector(".dashboard");
      if (defaultTab) handleTabAndSectionClick(defaultTab);
    });

    tabElements.forEach((tab) => {
      tab.addEventListener("click", () => handleTabAndSectionClick(tab));
    });
  </script>

  <!-- ENROLLMENT REQUEST FUNCTIONS -->
  <script>
    const acceptBtns = document.querySelectorAll(".acceptBtn");
    const rejectBtns = document.querySelectorAll(".rejectBtn");

    function handleDecision(btn, message) {
      alert(message);

      const row = btn.closest("tr");
      row.querySelectorAll("button").forEach((b) => (b.disabled = true));

      row.remove();
    }

    acceptBtns.forEach((btn) => {
      btn.addEventListener("click", () =>
        handleDecision(btn, "Enrollment Accepted")
      );
    });

    rejectBtns.forEach((btn) => {
      btn.addEventListener("click", () =>
        handleDecision(btn, "Enrollment Rejected")
      );
    });
  </script>

  <!-- COURSES FUNCTIONS -->
  <script>
    const stateBtn = document.getElementById("stateBtn");

    stateBtn.addEventListener("click", () => {
      if (stateBtn.textContent === "Enabled") {
        stateBtn.textContent = "Disabled";
        stateBtn.style.backgroundColor = "#bd1919";
      } else {
        stateBtn.textContent = "Enabled";
        stateBtn.style.backgroundColor = "#006aff";
      }
    });
  </script>

  <!-- ACTIVITES AND MODULES FUNCTIONS -->
  <script>
    const createActivity = document.querySelector("#createActivity");
    const uploadModule = document.querySelector("#uploadModule");
    const createAct = document.querySelector(".createAct");
    const addModule = document.querySelector(".addModule");
    const uploadAct = document.querySelector("#uploadAct");
    const uploadActLabel = uploadAct.closest("label");
    const uploadModuleFile = document.querySelector("#uploadModuleFile");
    const uploadModuleLabel = uploadModuleFile.closest("label");
    const editAct = document.querySelectorAll(".editAct");
    const editActModule = document.querySelectorAll(".editActModule");
    const deleteAct = document.querySelectorAll(".deleteAct");

    const table = document.querySelector("#activitiesTable tbody");

    createActivity.addEventListener("click", () => {
      if (createAct.classList.contains("active")) {
        createAct.classList.remove("active");
      } else {
        createAct.classList.add("active");
        addModule.classList.remove("active");
      }
    });

    uploadModule.addEventListener("click", () => {
      if (addModule.classList.contains("active")) {
        addModule.classList.remove("active");
      } else {
        createAct.classList.remove("active");
        addModule.classList.add("active");
      }
    });

    uploadAct.addEventListener("change", () => {
      uploadActLabel.childNodes[0].textContent =
        uploadAct.files.length > 0 ? "Ready to Upload " : "Upload Activity ";
    });

    uploadModuleFile.addEventListener("change", () => {
      uploadModuleLabel.childNodes[0].textContent =
        uploadModuleFile.files.length > 0 ?
        "Ready to Upload " :
        "Select Module ";
    });

    createAct
      .querySelector('input[type="submit"]')
      .addEventListener("click", (e) => {
        e.preventDefault();

        const actTitleInput = document.querySelector("#actTitle");
        const courseInput = document.querySelector("#courseActivityOption");

        const actTitleVal = actTitleInput.value.trim();
        const courseVal = courseInput.value;

        if (actTitleVal && table) {
          const row = table.insertRow();
          row.innerHTML = `
      <td>${actTitleVal}</td>
      <td>${courseVal}</td>
      <td><button class="deleteAct">Delete</button></td>
    `;

          // Clear and hide form
          actTitleInput.value = "";
          uploadAct.value = "";
          uploadActLabel.childNodes[0].textContent = "Upload Activity ";
          createAct.classList.remove("active");
        }
      });

    addModule
      .querySelector('input[type="submit"]')
      .addEventListener("click", (e) => {
        e.preventDefault();

        const moduleTitleInput = document.querySelector("#moduleTitle");
        const courseModuleInput = document.querySelector(
          "#courseModuleOption"
        );

        const moduleTitleVal = moduleTitleInput.value.trim();
        const courseModuleVal = courseModuleInput.value;

        if (moduleTitleVal && table) {
          const row = table.insertRow();
          row.innerHTML = `
      <td>${moduleTitleVal}</td>
      <td>${courseModuleVal}</td>
      <td><button class="deleteAct">Delete</button></td>
    `;

          // Clear and hide form
          moduleTitleInput.value = "";
          uploadModuleFile.value = "";
          uploadModuleLabel.childNodes[0].textContent = "Select Module ";
          addModule.classList.remove("active");
        }
      });

    document.addEventListener("DOMContentLoaded", () => {
      const editAct = document.querySelectorAll(".editAct");

      editAct.forEach((btn) => {
        btn.addEventListener("click", () => {
          const row = btn.closest("tr");
          const course = row.cells[0].textContent.trim();
          const editForm = row.querySelector(".editActForm");

          if (editForm.dataset.activeRow === row.rowIndex.toString()) {
            editForm.innerHTML = "";
            editForm.removeAttribute("data-active-row");
            editForm.classList.remove("active");
            return;
          }

          editForm.dataset.activeRow = row.rowIndex.toString();
          editForm.classList.add("active");

          editForm.innerHTML = `
        <p>
            Edit Name: <input type="text" value="${course}">
        </p>
        <label class="file-label">
            Upload New File
            <input type="file" class="new-activity-file" hidden>
        </label>
        <span>
            <button type="submit">Save</button>
            <button type="button" class="cancelEdit">Cancel</button>
        </span>
      `;

          const fileInput = editForm.querySelector(".new-activity-file");
          const fileLabel = editForm.querySelector(".file-label");

          fileInput.addEventListener("change", () => {
            fileLabel.firstChild.textContent =
              fileInput.files.length > 0 ?
              "Ready to Upload" :
              "Upload New File";
          });

          editForm
            .querySelector(".cancelEdit")
            .addEventListener("click", () => {
              editForm.innerHTML = "";
              editForm.removeAttribute("data-active-row");
              editForm.classList.remove("active");
            });

          editForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const newName = editForm
              .querySelector("input[type='text']")
              .value.trim();
            if (newName) {
              row.cells[0].textContent = newName;
            }
            editForm.innerHTML = "";
            editForm.removeAttribute("data-active-row");
            editForm.classList.remove("active");
          });
        });
      });
    });

    deleteAct.forEach((btn) => {
      btn.addEventListener("click", () => {
        const row = btn.closest("tr");
        if (row) row.remove();
      });
    });
  </script>

  <!-- TRAINEES FUNCTIONS -->
  <script>
    const viewButtons = document.querySelectorAll(".viewSubmissions");

    viewButtons.forEach((btn) => {
      btn.addEventListener("click", () => {
        const row = btn.closest("tr");
        const name = row.cells[0].textContent.trim();
        const dateSubmitted = row.cells[3].textContent.trim();
        const gradeForm = row.querySelector(".gradeActivity");

        gradeForm.innerHTML = `
            <p><strong>Submitted by: </strong>${name}</p>
            <p><strong>Date Submitted: </strong>${dateSubmitted}</p>
            <p><strong>File: </strong><a href="#" class="file-link">View Submission</a></p>
            <label>
            Grade Activity:
            <input type="number" name="grade" min="0" max="100" required>
            </label>
            <label>
            Add Comment:
            <textarea name="comment" rows="3" cols="30" placeholder="Add your comment here"></textarea>
            </label>
            <button type="submit" class="gradeBtn">Grade</button>
            <button type="button" class="closeGradeBtn">Close</button>
          `;

        gradeForm
          .querySelector(".closeGradeBtn")
          .addEventListener("click", () => {
            gradeForm.innerHTML = "";
          });

        gradeForm.addEventListener("submit", (e) => {
          e.preventDefault();
          alert(`Grade submitted for ${name}`);
          gradeForm.innerHTML = "";
          btn.disabled = true;
          btn.textContent = "Submission Graded";
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
</body>

</html>