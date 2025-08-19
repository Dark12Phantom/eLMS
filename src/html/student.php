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
    <link rel="stylesheet" href="../css/student.css" />
  </head>
  <body>
    <nav>
      <div class="logo">
        <h1>BTS Student</h1>
      </div>
      <div id="student-profile">
        <img src="" />
        <h3 class="name">Ane S. Thesia</h3>
        <p>Trainee</p>
        <p class="student-id-number">123456789</p>
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
        <a href="../php/logout.php"
          >Log out
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="24px"
            viewBox="0 -960 960 960"
            width="24px"
            fill="#006dda"
          >
            <path
              d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"
            />
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
                <h2>2</h2>
                <p><i>Courses</i></p>
              </div>
              <div class="card c2">
                <h3>Hours Studied</h3>
                <h2>42</h2>
                <p><i>Hours</i></p>
              </div>
              <div class="card c3">
                <h3>Average Grade</h3>
                <h2>84%</h2>
                <p><i>Avg</i></p>
              </div>
              <div class="card c4">
                <h3>My Courses (Quick Access)</h3>
                <div class="inside-container">
                  <div class="wrapper">
                    <div class="infos">
                      <h4>Agricultural Crops Production NC II</h4>
                      <p class="trainer"><i>John Doe (Trainer)</i></p>
                    </div>
                    <button class="materials">Materials</button>
                  </div>
                  <div class="wrapper">
                    <div class="infos">
                      <h4>Automotive Servicing NC I</h4>
                      <p class="trainer"><i>Juan dela Cruz (Trainer)</i></p>
                    </div>
                    <button class="materials">Materials</button>
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
                <tr>
                  <td>Agricultural Crops Production NC II</td>
                  <td>Ruby X. Cube</td>
                  <td>50%</td>
                  <td>
                    <div class="content">
                      <a href="">Agriculture-1.pdf</a>
                    </div>
                    <div class="content">
                      <a href="">Slides-AgCH1.pptx</a>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Automotive Servicing NC I</td>
                  <td>Juan dela Cruz (Trainer)</td>
                  <td>50%</td>
                  <td>
                    <div class="content">
                      <a href="">ASNC1-1.pdf</a>
                    </div>
                    <div class="content">
                      <a href="">Slides-ASCH1.pdf</a>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="container">
            <h2>Available Courses</h2>
            <table>
              <thead>
                <tr>
                  <th>Courses</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Agricultural Crops Production NC II</td>
                  <td>Ongoing</td>
                  <td>
                    <form action="">
                      <button type="button" disabled>Enroll</button>
                    </form>
                  </td>
                </tr>
                <tr>
                  <td>Automotive Servicing NC I</td>
                  <td>Ongoing</td>
                  <td>
                    <button type="button" disabled>Enroll</button>
                  </td>
                </tr>
                <tr>
                  <td>Bread and Pastry Production NC II</td>
                  <td>No Record</td>
                  <td>
                    <button type="button">Enroll</button>
                  </td>
                </tr>
                <tr>
                  <td>Dressmaking NC II</td>
                  <td>No Redord</td>
                  <td>
                    <button type="button">Enroll</button>
                  </td>
                </tr>
                <tr>
                  <td>Hairdressing NC II</td>
                  <td>No Record</td>
                  <td>
                    <button type="button">Enroll</button>
                  </td>
                </tr>
                <tr>
                  <td>Japanese Language and Culture</td>
                  <td>Erolling</td>
                  <td>
                    <button type="button" disabled>Enroll</button>
                  </td>
                </tr>
                <tr>
                  <td>Driving NC II</td>
                  <td>No Record</td>
                  <td>
                    <button type="button">Enroll</button>
                  </td>
                </tr>
                <tr>
                  <td>Tailoring NC II</td>
                  <td>No Record</td>
                  <td>
                    <button type="button">Enroll</button>
                  </td>
                </tr>
              </tbody>
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
              <thead>
                <tr>
                  <th>Activity</th>
                  <th>Course</th>
                  <th>Due</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>

              <tbody>
                <tr>
                  <td>Quiz 1 Agriculture</td>
                  <td>Agricultural Crops Production NC II</td>
                  <td>02-23-2025</td>
                  <td>Pending</td>
                  <td>
                    <form class="button">
                      <button>Upload</button>
                      <div id="popup">
                        <div class="button-group">
                          <label id="file-label" for="file-input">
                            <span id="file-name">Choose File</span>
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              height="24px"
                              viewBox="0 -960 960 960"
                              width="24px"
                              fill="#006aff"
                            >
                              <path
                                d="M440-200h80v-167l64 64 56-57-160-160-160 160 57 56 63-63v167ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"
                              />
                            </svg>
                          </label>
                          <input
                            type="file"
                            name="file-input"
                            id="file-input"
                          />
                        </div>
                        <button>Upload</button>
                      </div>
                    </form>
                  </td>
                </tr>
                <tr>
                  <td>Activity 1 - ASNC1</td>
                  <td>Automotive Servicing NC I</td>
                  <td>03-30-2025</td>
                  <td>Submitted</td>
                  <td>
                    <div class="button">
                      <button disabled="disabled">✔ Submitted</button>
                    </div>
                  </td>
                </tr>
              </tbody>
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
                <tr>
                  <td>Japanese Language and Culture</td>
                  <td>05-13-25</td>
                  <td>On Review</td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>
        <section id="profile">
          <h2>My Profile</h2>
          <div class="container">
            <form class="capsule">
              <div class="profile-image">
                <img
                  src=""
                  alt=""
                  id="profilePic"
                  src="default.jpg"
                  alt="Profile"
                />
                <label for="image-edit">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    height="24px"
                    viewBox="0 -960 960 960"
                    width="24px"
                    fill="#ffffff"
                  >
                    <path
                      d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"
                    />
                  </svg>
                </label>
                <input
                  type="file"
                  name="image-edit"
                  id="image-edit"
                  accept="image/*"
                />
              </div>
              <div class="details">
                <label for="profile-name">Name</label>
                <input
                  id="profile-name"
                  type="text"
                  disabled
                  value="Ane S. Thesia"
                />

                <label for="profile-mail">Email</label>
                <input
                  id="profile-mail"
                  type="text"
                  disabled
                  value="anesthesia.d@domain.com"
                />

                <label for="profile-contact">Contact</label>
                <input
                  id="profile-contact"
                  type="text"
                  disabled
                  value="+63 969 202 1214"
                />

                <label for="profile-bio">Bio</label>
                <textarea
                  id="profile-bio"
                  disabled
                  style="
                    height: 100px;
                    overflow-wrap: break-word;
                    justify-content: flex-start;
                    align-items: flex-start;
                    white-space: pre-wrap;
                  "
                >
A freelancer aiming to have additional training and expanding knowledge on every existing field.</textarea
                >

                <button id="editButton" type="button">Edit Profile</button>
              </div>
              <div class="details-edit" style="display: none">
                <label for="edit-profile-name">Name</label>
                <input id="edit-profile-name" type="text" />

                <label for="edit-profile-mail">Email</label>
                <input id="edit-profile-mail" type="text" />

                <label for="edit-profile-contact">Contact</label>
                <input id="edit-profile-contact" type="text" />

                <label for="edit-profile-bio">Bio</label>
                <textarea
                  id="edit-profile-bio"
                  style="
                    height: 100px;
                    overflow-wrap: break-word;
                    justify-content: flex-start;
                    align-items: flex-start;
                    white-space: pre-wrap;
                  "
                ></textarea>

                <button id="saveButton" type="button">Save Profile</button>
              </div>
            </form>
          </div>
        </section>
      </div>
      <div id="catalog">
        <div class="calendar-container">
          <div id="calendar-header"></div>
          <div id="calendar"></div>
        </div>
        <div class="todo">
          <div class="todo-container">
            <div class="todo-header">
              <h3>To-do</h3>
              <p>+</p>
            </div>
            <div class="todo-content">
              <div class="container">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                  <!--!Font Awesome Free v7.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                  <path
                    fill="#FFD43B"
                    d="M439.4 96L448 96C483.3 96 512 124.7 512 160L512 512C512 547.3 483.3 576 448 576L192 576C156.7 576 128 547.3 128 512L128 160C128 124.7 156.7 96 192 96L200.6 96C211.6 76.9 232.3 64 256 64L384 64C407.7 64 428.4 76.9 439.4 96zM376 176C389.3 176 400 165.3 400 152C400 138.7 389.3 128 376 128L264 128C250.7 128 240 138.7 240 152C240 165.3 250.7 176 264 176L376 176zM256 320C256 302.3 241.7 288 224 288C206.3 288 192 302.3 192 320C192 337.7 206.3 352 224 352C241.7 352 256 337.7 256 320zM288 320C288 333.3 298.7 344 312 344L424 344C437.3 344 448 333.3 448 320C448 306.7 437.3 296 424 296L312 296C298.7 296 288 306.7 288 320zM288 448C288 461.3 298.7 472 312 472L424 472C437.3 472 448 461.3 448 448C448 434.7 437.3 424 424 424L312 424C298.7 424 288 434.7 288 448zM224 480C241.7 480 256 465.7 256 448C256 430.3 241.7 416 224 416C206.3 416 192 430.3 192 448C192 465.7 206.3 480 224 480z"
                  />
                </svg>
                <p>Assignments</p>
              </div>
            </div>
          </div>
        </div>
        <div class="notification-container">
          <div class="notification-header">
            <h3>Notifications</h3>
          </div>
          <div class="notification-content">
            <p>Notifications appear here</p>
          </div>
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
      }

      window.addEventListener("DOMContentLoaded", () => {
        const defaultTab = document.querySelector(".dashboard");
        if (defaultTab) handleTabAndSectionClick(defaultTab);
      });

      tabElements.forEach((tab) => {
        tab.addEventListener("click", () => handleTabAndSectionClick(tab));
      });
    </script>

    <!-- CALENDAR -->
    <script>
      const calendarHeader = document.getElementById("calendar-header");
      const calendar = document.getElementById("calendar");

      const today = new Date();
      const year = today.getFullYear();
      const month = today.getMonth();

      const monthNames = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ];

      async function phHolidays(year) {
        const response = await fetch(
          `https://date.nager.at/api/v3/PublicHolidays/${year}/PH`
        );
        const holidays = await response.json();
        return holidays.map((h) => h.date);
      }

      function renderCalendar(year, month, holidayDates) {
        calendarHeader.innerText = `${monthNames[month]} ${year}`;
        calendar.innerHTML = "";

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const todayStr = today.toISOString().split("T")[0];

        for (let i = 0; i < firstDay; i++) {
          const empty = document.createElement("div");
          empty.className = "day";
          calendar.appendChild(empty);
        }

        for (let day = 1; day <= daysInMonth; day++) {
          const d = new Date(year, month, day);
          const isDate = d.toISOString().split("T")[0];
          const cell = document.createElement("div");
          cell.className = "day";
          cell.innerText = day;

          const dayOfWeek = d.getDay();
          if (dayOfWeek === 0) cell.classList.add("sunday");
          if (holidayDates.includes(isDate)) cell.classList.add("holiday");
          if (isDate === todayStr) cell.classList.add("today");

          calendar.appendChild(cell);
        }
      }

      async function initCalendar(year, month) {
        const holidayDates = await phHolidays(year);
        renderCalendar(year, month, holidayDates);
      }

      initCalendar(year, month);
    </script>

    <!-- COURSE LOGIC -->
    <script>
      let lastClickedButton = null;

      document.querySelectorAll("table tbody button").forEach((button) => {
        button.addEventListener("click", function () {
          lastClickedButton = this;
          const courseName = this.closest("tr").querySelector("td").textContent;
          document.getElementById("popupCourseName").textContent = courseName;
          document.getElementById("popup-enrollment").style.display = "flex";
        });
      });

      document.getElementById("closePopup").addEventListener("click", () => {
        document.getElementById("popup-enrollment").style.display = "none";
      });

      document
        .getElementById("submitEnrollment")
        .addEventListener("click", () => {
          if (lastClickedButton) {
            lastClickedButton.disabled = true;
          }
          document.getElementById("popup-enrollment").style.display = "none";
        });
    </script>

    <!-- ACTIVITIES FUNCTIONS -->
    <script>
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

      fileInput.addEventListener("change", function () {
        if (this.files && this.files.length > 0) {
          fileName.childNodes[0].nodeValue = this.files[0].name;
          fileSVGLabel.style.display = "none";
        } else {
          fileName.textContent = "Choose File";
          fileSVGLabel.style.display = "";
        }
      });
    </script>

    <!-- PROFILE FUNCTIONS -->
    <script>
      const detailsContainer = document.querySelector(".details");
      const editDetailsContainer = document.querySelector(".details-edit");

      const detailsDisplay =
        detailsContainer.querySelectorAll("input, textarea");
      const editDetails =
        editDetailsContainer.querySelectorAll("input, textarea");

      document.getElementById("editButton").addEventListener("click", () => {
        detailsDisplay.forEach((input, i) => {
          editDetails[i].value = input.value;
        });

        detailsContainer.style.display = "none";
        editDetailsContainer.style.display = "flex";
      });

      document.getElementById("saveButton").addEventListener("click", () => {
        editDetails.forEach((input, i) => {
          detailsDisplay[i].value = input.value;
        });

        detailsContainer.style.display = "flex";
        editDetailsContainer.style.display = "none";
      });

      document
        .getElementById("image-edit")
        .addEventListener("change", function () {
          const file = this.files[0];
          if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
              document.getElementById("profilePic").src = e.target.result;
            };
            reader.readAsDataURL(file);
          }
        });
    </script>
  </body>
</html>
