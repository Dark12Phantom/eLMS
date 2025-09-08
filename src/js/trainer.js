class TrainerDashboard {
  constructor() {
    this.init();
  }

  init() {
    this.initializeNavigation();
    this.initializeFormHandlers();
    this.initializeProfile();
    this.initializeAnnouncements();
    this.loadDashboardData();
  }

  initializeNavigation() {
    const tabElements = document.querySelectorAll(
      ".dashboard, .enrollment-request, .courses, .activities-modules, .trainees, .submissions, .profile, .redir"
    );
    const sectionElements = document.querySelectorAll(
      "#dashboard, #enrollment-request, #courses, #activities-modules, #trainees, #submissions, #profile"
    );

    const showSectionById = (id) => {
      let targetId = id === "redir" ? "enrollment-request" : id;
      const tab =
        id === "redir"
          ? document.querySelector(".redir")
          : document.querySelector(`.${targetId}`);
      const section = document.getElementById(targetId);

      if (!tab || !section) return;

      tabElements.forEach((el) => el.classList.remove("active"));
      sectionElements.forEach((el) => el.classList.remove("active"));

      tab.classList.add("active");
      section.classList.add("active");

      if (typeof this.initializeSection === "function") {
        this.initializeSection(targetId);
      }

      localStorage.setItem("activeTab", targetId);
    };

    const handleTabClick = (tab) => {
      const tabClass = tab.classList[0];
      const targetId = tabClass === "redir" ? "enrollment-request" : tabClass;
      location.hash = targetId;
    };

    window.addEventListener("DOMContentLoaded", () => {
      const hash = location.hash.replace("#", "");
      const savedTab = localStorage.getItem("activeTab");

      if (hash) {
        showSectionById(hash);
      } else if (savedTab) {
        showSectionById(savedTab);
      } else {
        showSectionById("dashboard");
      }
    });

    window.addEventListener("hashchange", () => {
      const hash = location.hash.replace("#", "");
      if (hash) showSectionById(hash);
    });

    tabElements.forEach((tab) => {
      tab.addEventListener("click", () => handleTabClick(tab));
    });

    this.handleRedirection();
  }

  handleRedirection() {
    const redirLinks = document.querySelectorAll(".redir");
    redirLinks.forEach((link) => {
      link.addEventListener("click", (e) => {
        e.preventDefault();
        location.hash = "enrollment-request";
      });
    });
  }

  initializeFormHandlers() {
    this.initializeActivityForm();
    this.initializeModuleForm();
    this.initializeFormToggles();
  }

  initializeActivityForm() {
    const createActForm = document.querySelector(".createAct");
    if (!createActForm) return;

    const submitBtn = createActForm.querySelector('button[type="submit"]');
    const cancelBtn = createActForm.querySelector(".cancelBtn");

    if (submitBtn) {
      submitBtn.addEventListener("click", async (e) => {
        e.preventDefault();
        await this.handleActivitySubmission();
      });
    }

    if (cancelBtn) {
      cancelBtn.addEventListener("click", () => {
        this.resetForm(createActForm);
      });
    }
  }

  initializeModuleForm() {
    const moduleForm = document.querySelector(".addModule");
    if (!moduleForm) return;

    const submitBtn = moduleForm.querySelector('button[type="submit"]');
    const cancelBtn = moduleForm.querySelector(".cancelModuleBtn");

    if (submitBtn) {
      submitBtn.addEventListener("click", async (e) => {
        e.preventDefault();
        await this.handleModuleSubmission();
      });
    }

    if (cancelBtn) {
      cancelBtn.addEventListener("click", () => {
        this.resetForm(moduleForm);
      });
    }
  }

  initializeFormToggles() {
    const createActivityBtn = document.getElementById("createActivity");
    const uploadModuleBtn = document.getElementById("uploadModule");

    if (createActivityBtn) {
      createActivityBtn.addEventListener("click", () => {
        this.loadCourseOptions();
        this.toggleForm(".createAct", ".addModule");
      });
    }

    if (uploadModuleBtn) {
      uploadModuleBtn.addEventListener("click", () => {
        this.loadCourseOptions();
        this.toggleForm(".addModule", ".createAct");
      });
    }
  }

  async loadCourseOptions() {
    try {
      const activityDropdown = document.querySelector("#courseActivityOption");
      const moduleDropdown = document.querySelector("#courseModuleOption");

      if (
        activityDropdown &&
        activityDropdown.options.length > 1 &&
        moduleDropdown &&
        moduleDropdown.options.length > 1
      ) {
        return;
      }

      const response = await fetch("../php/getTrainerCourses.php");
      const result = await response.json();

      if (result.success && result.data.length > 0) {
        this.populateCourseDropdowns(result.data);
      } else {
        this.showDropdownError(activityDropdown, "No courses available");
        this.showDropdownError(moduleDropdown, "No courses available");
      }
    } catch (error) {
      console.error("Error loading course options:", error);
      const activityDropdown = document.querySelector("#courseActivityOption");
      const moduleDropdown = document.querySelector("#courseModuleOption");
      this.showDropdownError(activityDropdown, "Error loading courses");
      this.showDropdownError(moduleDropdown, "Error loading courses");
    }
  }

  showDropdownError(dropdown, message) {
    if (!dropdown) return;
    dropdown.innerHTML = "";
    const option = document.createElement("option");
    option.value = "";
    option.textContent = message;
    option.disabled = true;
    dropdown.appendChild(option);
  }

  populateCourseDropdowns(courses) {
    const activityDropdown = document.querySelector("#courseActivityOption");
    const moduleDropdown = document.querySelector("#courseModuleOption");

    if (!activityDropdown || !moduleDropdown) return;

    activityDropdown.innerHTML = activityDropdown.querySelector("option")
      ? activityDropdown.innerHTML.split("</option>")[0] + "</option>"
      : '<option value="">Select Course</option>';

    moduleDropdown.innerHTML = moduleDropdown.querySelector("option")
      ? moduleDropdown.innerHTML.split("</option>")[0] + "</option>"
      : '<option value="">Select Course</option>';

    courses.forEach((course) => {
      const courseId = course.courseID || course.id || course.course_id;
      const courseName = course.courseName || "Unnamed Course";

      if (courseId && courseName) {
        const activityOption = document.createElement("option");
        activityOption.value = courseId;
        activityOption.textContent = courseName;
        activityDropdown.appendChild(activityOption);

        const moduleOption = document.createElement("option");
        moduleOption.value = courseId;
        moduleOption.textContent = courseName;
        moduleDropdown.appendChild(moduleOption);
      }
    });
  }

  toggleForm(showForm, hideForm) {
    const formToShow = document.querySelector(showForm);
    const formToHide = document.querySelector(hideForm);

    if (formToShow && formToHide) {
      if (formToShow.classList.contains("active")) {
        formToShow.classList.remove("active");
      } else {
        formToShow.classList.add("active");
        formToHide.classList.remove("active");
      }
    }
  }

  resetForm(form) {
    form.classList.remove("active");
    form.reset();

    const fileLabels = form.querySelectorAll("label");
    fileLabels.forEach((label) => {
      const text = label.textContent;
      if (text.includes("Ready to Upload")) {
        label.firstChild.textContent =
          label.getAttribute("data-original-text") || "Select File";
      }
    });
  }

  async handleActivitySubmission() {
    const form = document.querySelector(".createAct");
    const formData = new FormData();

    const title = form.querySelector("#actTitle").value.trim();
    const description = form.querySelector("#actDescription").value.trim();
    const dueDate = form.querySelector("#dueDate").value;
    const activityType = form.querySelector("#activityType").value;
    const course = form.querySelector("#courseActivityOption").value;
    const file = form.querySelector("#uploadAct").files[0];

    if (!title || !course) {
      alert("Please fill in all required fields");
      return;
    }

    formData.append("actTitle", title);
    formData.append("description", description);
    formData.append("dueDate", dueDate);
    formData.append("activityType", activityType);
    formData.append("courseActivityOption", course);
    if (file) {
      formData.append("uploadAct", file);
    }

    try {
      const response = await fetch("../php/uploadActivity.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        this.resetForm(form);
        this.loadActivities();
      } else {
        alert("Error: " + result.message);
      }
    } catch (error) {
      console.error("Error uploading activity:", error);
      alert("An error occurred while uploading the activity");
    }
  }

  async handleModuleSubmission() {
    const form = document.querySelector(".addModule");
    const formData = new FormData();

    const title = form.querySelector("#moduleTitle").value.trim();
    const description = form.querySelector("#moduleDescription").value.trim();
    const course = form.querySelector("#courseModuleOption").value;
    const file = form.querySelector("#uploadModuleFile").files[0];

    if (!title || !course || !file) {
      alert("Please fill in all required fields");
      return;
    }

    formData.append("moduleTitle", title);
    formData.append("moduleDescription", description);
    formData.append("courseModuleOption", course);
    formData.append("uploadModuleFile", file);

    console.log("Module form values:");
    console.log("Title:", title);
    console.log("Description:", description);
    console.log("Course ID:", course);
    console.log("File:", file ? file.name : "No file");

    try {
      const response = await fetch("../php/uploadModule.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        this.resetForm(form);
        this.loadModules();
      } else {
        alert("Error: " + result.message);
      }
    } catch (error) {
      console.error("Error uploading module:", error);
      alert("An error occurred while uploading the module");
    }
  }

  initializeSection(sectionId) {
    const sectionHandlers = {
      dashboard: () => this.loadDashboardData(),
      "enrollment-request": () => this.loadEnrollmentRequests(),
      courses: () => this.loadTrainerCourses(),
      "activities-modules": () => this.loadActivities() && this.loadModules(),
      trainees: () => this.loadTrainees(),
      submissions: () => this.loadSubmissions(),
    };

    const handler = sectionHandlers[sectionId];
    if (handler) handler();
  }

  async loadDashboardData() {
    try {
      const response = await fetch("../php/getDashboardData.php");
      const result = await response.json();

      if (result.success) {
        this.updateDashboardCards(result.data);
      }
    } catch (error) {
      console.error("Error loading dashboard data:", error);
    }
  }

  updateDashboardCards(data) {
    const updateCard = (selector, value) => {
      const element = document.querySelector(selector);
      if (element) element.textContent = value;
    };

    updateCard(".c1 h2", data.courses);
    updateCard(".c2 h2", data.trainees);
    updateCard(".c3 h2", data.pendingSubmissions);

    const recentEnrollmentDiv = document.querySelector(".c4 .inside-container");
    if (recentEnrollmentDiv) {
      if (data.recentEnrollment) {
        recentEnrollmentDiv.innerHTML = `
                    <div class="wrapper">
                        <div class="infos">
                            <h4>${data.recentEnrollment.courseName}</h4>
                            <p class="trainer"><i>${data.recentEnrollment.studentName}</i></p>
                        </div>
                        <a href="#enrollment-request" class="redir">Go to</a>
                    </div>
                `;
      } else {
        recentEnrollmentDiv.innerHTML = "<p>No recent enrollment requests</p>";
      }
    }
    updateCard(".c5 h2", data.totalEnrollments);
  }

  async loadEnrollmentRequests() {
    try {
      const response = await fetch("../php/getEnrollmentRequests.php");
      const result = await response.json();

      if (result.success) {
        this.renderEnrollmentRequests(result.data);
      }
    } catch (error) {
      console.error("Error loading enrollment requests:", error);
    }
  }

  renderEnrollmentRequests(requests) {
    const tbody = document.querySelector("#enrollment-request tbody");
    if (!tbody) return;

    tbody.innerHTML = "";

    if (requests.length === 0) {
      tbody.innerHTML =
        '<tr><td colspan="5">No pending enrollment requests</td></tr>';
      return;
    }

    requests.forEach((request) => {
      const row = document.createElement("tr");
      row.innerHTML = `
                <td>${request.courseName}</td>
                <td>${request.studentName}</td>
                <td>${request.dateRequested}</td>
                <td>Pending Approval</td>
                <td>
                    <div class="button">
                        <button type="button" class="acceptBtn" data-id="${request.id}">Accept</button>
                        <button type="button" class="rejectBtn" data-id="${request.id}">Reject</button>
                    </div>
                </td>
            `;
      tbody.appendChild(row);
    });

    this.addEnrollmentEventListeners();
  }

  addEnrollmentEventListeners() {
    const acceptBtns = document.querySelectorAll(".acceptBtn");
    const rejectBtns = document.querySelectorAll(".rejectBtn");

    acceptBtns.forEach((btn) => {
      btn.addEventListener("click", () =>
        this.processEnrollment(btn.dataset.id, "accept", btn)
      );
    });

    rejectBtns.forEach((btn) => {
      btn.addEventListener("click", () =>
        this.processEnrollment(btn.dataset.id, "reject", btn)
      );
    });
  }

  async processEnrollment(enrollmentId, action, buttonElement) {
    try {
      const formData = new FormData();
      formData.append("enrollment_id", enrollmentId);
      formData.append("action", action);

      const response = await fetch("../php/processEnrollment.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        const row = buttonElement.closest("tr");
        if (row) row.remove();
        this.loadDashboardData();
      } else {
        alert("Error: " + result.message);
      }
    } catch (error) {
      console.error("Error processing enrollment:", error);
      alert("An error occurred while processing the request");
    }
  }

  async loadTrainerCourses() {
    try {
      const response = await fetch("../php/getTrainerCourses.php");
      const result = await response.json();

      if (result.success) {
        this.renderTrainerCourses(result.data);
      }
    } catch (error) {
      console.error("Error loading courses:", error);
    }
  }

  renderTrainerCourses(courses) {
    const tbody = document.querySelector("#courses tbody");
    if (!tbody) return;

    tbody.innerHTML = "";

    courses.forEach((course, index) => {
      const row = document.createElement("tr");

      console.log(`Course ${index}:`, course);
      console.log(
        `selfEnrollStatus for ${course.courseName}:`,
        course.selfEnrollStatus
      );

      const isEnabled = course.selfEnrollStatus === "enabled";
      const statusColor = isEnabled ? "#006aff" : "#bd1919";
      const statusText = isEnabled ? "Enabled" : "Disabled";
      const courseId = course.courseID;

      row.innerHTML = `
                <td>${course.courseName}</td>
                <td>${course.studentCount}</td>
                <td>${course.avgProgress}%</td>
                <td>
                    <button type="button" class="stateBtn" data-id="${courseId}" 
                            data-status="${isEnabled}"
                            style="background-color: ${statusColor}; color: white">
                        ${statusText}
                    </button>
                </td>
            `;
      tbody.appendChild(row);
    });

    this.addStateButtonListeners();
  }

  addStateButtonListeners() {
    const stateBtns = document.querySelectorAll(".stateBtn");
    stateBtns.forEach((btn) => {
      btn.addEventListener("click", () =>
        this.toggleSelfEnrollment(btn.dataset.id, btn)
      );
    });
  }

  async toggleSelfEnrollment(courseId, buttonElement) {
    const formData = new FormData();
    formData.append("course_id", courseId);

    const response = await fetch("../php/toggleSelfEnrollment.php", {
      method: "POST",
      body: formData,
    });

    const result = await response.json();

    if (result.success) {
      const isEnabled = result.status === "enabled" || result.status === true;
      const newText = isEnabled ? "Enabled" : "Disabled";
      const newColor = isEnabled ? "#006aff" : "#bd1919";

      buttonElement.textContent = newText;
      buttonElement.style.backgroundColor = newColor;
    } else {
      alert("Error: " + result.message);
    }
  }

  async loadActivities() {
    try {
      const response = await fetch("../php/getActivities.php");
      const result = await response.json();

      if (result.success) {
        this.renderActivities(result.data);
      }
    } catch (error) {
      console.error("Error loading activities:", error);
    }
  }

  renderActivities(activities) {
    const tbody = document.querySelector("#activitiesTable tbody");
    if (!tbody) return;

    tbody.innerHTML = "";

    if (activities.length === 0) {
      tbody.innerHTML = '<tr><td colspan="6">No activities found</td></tr>';
      return;
    }

    activities.forEach((activity) => {
      const row = document.createElement("tr");
      row.setAttribute("data-activity-id", activity.id);
      row.innerHTML = `
    <td>${activity.title}</td>
    <td>${activity.courseName}</td>
    <td>${activity.type}</td>
    <td>${activity.startDate}</td>
    <td>${activity.dueDate || "No due date"}</td>
    <td>
        <div class="buttons">
            <button type="button" class="editAct" onclick="trainerDashboard.handleEditActivity(this)">Edit</button>
            <button type="button" class="deleteAct" data-id="${
              activity.id
            }" style="background-color: red">Delete</button>
        </div>
    </td>
    <td>
        ${
          activity.file_path && activity.file_path.trim() !== ""
            ? `<a href="${activity.file_path}" target="_blank">View Activity</a>`
            : "-"
        }
    </td>
`;
      tbody.appendChild(row);
    });

    this.addDeleteEventListeners();
  }

  getActivityIdFromRow(row) {
    return row.getAttribute("data-activity-id");
  }

  handleEditActivity(button) {
    const row = button.closest("tr");
    const activityId = this.getActivityIdFromRow(row);

    if (activityId) {
      this.openEditModal(activityId);
    } else {
      alert("Unable to identify activity for editing");
    }
  }

  addDeleteEventListeners() {
    const deleteBtns = document.querySelectorAll(".deleteAct");
    deleteBtns.forEach((btn) => {
      btn.addEventListener("click", async () => {
        if (confirm("Are you sure you want to delete this activity?")) {
          await this.deleteActivity(btn.dataset.id, btn);
        }
      });
    });
  }

  async openEditModal(activityId) {
    try {
      console.log("Fetching activity data for ID:", activityId);
      const response = await fetch(`../php/editActivity.php?id=${activityId}`);
      const result = await response.json();
      console.log("Server response:", result);

      if (result.success) {
        const activity = result.data;
        this.showEditModal(activity);
        await this.populateEditForm(activity);
      } else {
        alert("Error fetching activity data: " + result.message);
        console.error("Error details:", result);
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred while fetching activity data");
    }
  }

  showEditModal(activity) {
    const modalHTML = `
        <div id="editActivityModal" class="modal" style="display: block; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000;">
            <div class="modal-content" style="position: relative; background-color: white; margin: 5% auto; padding: 20px; width: 80%; max-width: 600px; border-radius: 8px; max-height: 80vh; overflow-y: auto;">
                <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2>Edit Activity</h2>
                    <span class="close" onclick="trainerDashboard.closeEditModal()" style="cursor: pointer; font-size: 24px;">&times;</span>
                </div>
                <form id="editActivityForm" enctype="multipart/form-data">
                    <input type="hidden" id="editActivityId" name="activityId" value="${activity.id}">
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="editActTitle" style="display: block; margin-bottom: 5px;">Activity Title:</label>
                        <input type="text" id="editActTitle" name="actTitle" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="editDescription" style="display: block; margin-bottom: 5px;">Description:</label>
                        <textarea id="editDescription" name="description" rows="4" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="editDueDate" style="display: block; margin-bottom: 5px;">Due Date:</label>
                        <input type="datetime-local" id="editDueDate" name="dueDate" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="editActivityType" style="display: block; margin-bottom: 5px;">Activity Type:</label>
                        <select id="editActivityType" name="activityType" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="">Select Type</option>
                            <option value="activity">Activity</option>
                            <option value="exam">Exam</option>
                            <option value="project">Project</option>
                        </select>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="editCourseOption" style="display: block; margin-bottom: 5px;">Course:</label>
                        <select id="editCourseOption" name="courseActivityOption" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="">Select Course</option>
                        </select>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label id="uploadNewFile" for="editUploadAct" style="display: block; margin-bottom: 5px;">Upload New File (optional):</label>
                        <input type="file" id="editUploadAct" name="uploadAct" accept=".pdf,.doc,.docx,.txt" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <small>Current file: <span id="currentFileName"></span></small>
                    </div>
                    
                    <div class="form-actions" style="text-align: right; margin-top: 20px;">
                        <button type="button" onclick="trainerDashboard.closeEditModal()" style="margin-right: 10px; padding: 10px 20px; background-color: #ccc; border: none; border-radius: 4px; cursor: pointer;">Cancel</button>
                        <button type="submit" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Update Activity</button>
                    </div>
                </form>
            </div>
        </div>
    `;

    const existingModal = document.getElementById("editActivityModal");
    if (existingModal) {
      existingModal.remove();
    }

    document.body.insertAdjacentHTML("beforeend", modalHTML);

    document
      .getElementById("editActivityForm")
      .addEventListener("submit", (e) => this.handleEditSubmit(e));
  }

  async populateEditForm(activity) {
    document.getElementById("editActTitle").value = activity.title || "";
    document.getElementById("editDescription").value =
      activity.description || "";
    document.getElementById("editDueDate").value = activity.due_date
      ? this.formatDateTimeLocal(activity.due_date)
      : "";
    document.getElementById("editActivityType").value = activity.type || "";

    const currentFileSpan = document.getElementById("currentFileName");
    if (activity.file_path) {
      const fileName = activity.file_path.split("/").pop();
      currentFileSpan.textContent = fileName;
    } else {
      currentFileSpan.textContent = "No file uploaded";
    }

    await this.populateEditCourseDropdown(activity.courseID);
  }

  async populateEditCourseDropdown(selectedCourseId = null) {
    try {
      const response = await fetch("../php/getTrainerCourses.php");
      const result = await response.json();

      const select = document.getElementById("editCourseOption");
      if (!select) return;

      if (result.success && result.data.length > 0) {
        result.data.forEach((course) => {
          const option = document.createElement("option");
          option.value = course.courseID || course.id;
          option.textContent = course.courseName;
          if ((course.courseID || course.id) === selectedCourseId) {
            option.selected = true;
          }
          select.appendChild(option);
        });
      }
    } catch (error) {
      console.error("Error loading courses for edit modal:", error);
    }
  }

  formatDateTimeLocal(dateString) {
    const date = new Date(dateString);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    const hours = String(date.getHours()).padStart(2, "0");
    const minutes = String(date.getMinutes()).padStart(2, "0");

    return `${year}-${month}-${day}T${hours}:${minutes}`;
  }

  async handleEditSubmit(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    try {
      const response = await fetch("../php/editActivity.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        alert("Activity updated successfully!");
        this.closeEditModal();
        this.loadActivities();
      } else {
        alert("Error updating activity: " + result.message);
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred while updating the activity");
    }
  }

  closeEditModal() {
    const modal = document.getElementById("editActivityModal");
    if (modal) {
      modal.remove();
    }
  }

  async deleteActivity(activityId, buttonElement) {
    try {
      const formData = new FormData();
      formData.append("activity_id", activityId);

      const response = await fetch("../php/deleteActivity.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        const row = buttonElement.closest("tr");
        if (row) row.remove();
      } else {
        alert("Error: " + result.message);
      }
    } catch (error) {
      console.error("Error deleting activity:", error);
      alert("An error occurred while deleting the activity");
    }
  }

  async loadModules() {
    try {
      const response = await fetch("../php/getModules.php");
      const result = await response.json();
      console.log(result);

      if (result.success) {
        this.renderModules(result.data);
      }
    } catch (error) {
      console.error("Error loading modeules:", error);
    }
  }

  renderModules(modules) {
    const tbody = document.querySelector("#modulesTable tbody");
    if (!tbody) return;

    tbody.innerHTML = "";

    if (modules.length === 0) {
      tbody.innerHTML = '<tr><td colspan="6">No modules found</td></tr>';
      return;
    }

    modules.forEach((module) => {
      const row = document.createElement("tr");
      row.setAttribute("data-module-id", module.id);
      row.innerHTML = `
                <td>${module.title}</td>
                <td>${module.courseName}</td>
                <td>${module.created_at}</td>
                <td>
                    <div class="buttons">
                        <button type="button" class="editModule" onclick="trainerDashboard.handleEditModule(this)">Edit</button>
                        <button type="button" class="deleteModule" data-id="${module.id}" style="background-color: red">Delete</button>
                    </div>
                </td>
                <td><a href="${module.file_path}" target="_blank">View Module</a></td>
            `;
      tbody.appendChild(row);
    });

    this.addDeleteModuleEventListeners();
  }

  addDeleteModuleEventListeners() {
    const deleteBtns = document.querySelectorAll(".deleteModule");
    deleteBtns.forEach((btn) => {
      btn.addEventListener("click", async () => {
        if (confirm("Are you sure you want to delete this module?")) {
          await this.deleteModule(btn.dataset.id, btn);
        }
      });
    });
  }

  async deleteModule(moduleId, buttonElement) {
    try {
      const formData = new FormData();
      formData.append("module_id", moduleId);
      console.log(moduleId);

      const response = await fetch("../php/deleteModule.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        const row = buttonElement.closest("tr");
        if (row) row.remove();
      } else {
        alert("Error: " + result.message);
      }
    } catch (error) {
      console.error("Error deleting module:", error);
      alert("An error occurred while deleting the module");
    }
  }

  getModuleIdFromRow(row) {
    return row.getAttribute("data-module-id");
  }

  handleEditModule(button) {
    const row = button.closest("tr");
    const moduleId = this.getModuleIdFromRow(row);

    if (moduleId) {
      this.openEditModuleModal(moduleId);
    } else {
      alert("Unable to identify module for editing");
    }
  }

  async openEditModuleModal(moduleId) {
    try {
      console.log("Fetching module data for ID:", moduleId);
      const response = await fetch(`../php/editModule.php?id=${moduleId}`);
      const result = await response.json();
      console.log("Server response:", result);

      if (result.success) {
        const module = result.data;
        this.showEditModuleModal(module);
        await this.populateEditModuleForm(module);
      } else {
        alert("Error fetching module data: " + result.message);
        console.error("Error details:", result);
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred while fetching module data");
    }
  }

  showEditModuleModal(module) {
    const modalHTML = `
        <div id="editModuleModal" class="modal" style="display: block; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000;">
            <div class="modal-content" style="position: relative; background-color: white; margin: 5% auto; padding: 20px; width: 80%; max-width: 600px; border-radius: 8px; max-height: 80vh; overflow-y: auto;">
                <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2>Edit Module</h2>
                    <span class="close" onclick="trainerDashboard.closeEditModuleModal()" style="cursor: pointer; font-size: 24px;">&times;</span>
                </div>
                <form id="editModuleForm" enctype="multipart/form-data">
                    <input type="hidden" id="editModuleId" name="moduleId" value="${module.id}">
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="editModuleTitle" style="display: block; margin-bottom: 5px;">Module Title:</label>
                        <input type="text" id="editModuleTitle" name="moduleTitle" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="editDescription" style="display: block; margin-bottom: 5px;">Description:</label>
                        <textarea id="editDescription" name="description" rows="4" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="editCourseOption" style="display: block; margin-bottom: 5px;">Course:</label>
                        <select id="editCourseOption" name="courseModuleOption" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="">Select Course</option>
                        </select>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label id="uploadNewFile" for="editUploadModule" style="display: block; margin-bottom: 5px;">Upload New File (optional):</label>
                        <input type="file" id="editUploadModule" name="uploadModuleFile" accept=".pdf,.doc,.docx,.txt" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <small>Current file: <span id="currentFileName"></span></small>
                    </div>
                    
                    <div class="form-actions" style="text-align: right; margin-top: 20px;">
                        <button type="button" onclick="trainerDashboard.closeEditModuleModal()" style="margin-right: 10px; padding: 10px 20px; background-color: #ccc; border: none; border-radius: 4px; cursor: pointer;">Cancel</button>
                        <button type="submit" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Update Module</button>
                    </div>
                </form>
            </div>
        </div>
    `;

    const existingModal = document.getElementById("editModuleModal");
    if (existingModal) {
      existingModal.remove();
    }

    document.body.insertAdjacentHTML("beforeend", modalHTML);

    document
      .getElementById("editModuleForm")
      .addEventListener("submit", (e) => this.handleEditModuleSubmit(e));
  }

  closeEditModuleModal() {
    const modal = document.getElementById("editModuleModal");
    if (modal) {
      modal.remove();
    }
  }

  async handleEditModuleSubmit(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    try {
      const response = await fetch("../php/editModule.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        alert("Module updated successfully!");
        this.closeEditModal();
        this.loadActivities();
      } else {
        alert("Error updating module: " + result.message);
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred while updating the module");
    }
  }

  async populateEditModuleForm(module) {
    document.getElementById("editModuleTitle").value = module.title || "";
    document.getElementById("editDescription").value = module.description || "";

    const currentFileSpan = document.getElementById("currentFileName");
    if (module.file_path) {
      const fileName = module.file_path.split("/").pop();
      currentFileSpan.textContent = fileName;
    } else {
      currentFileSpan.textContent = "No file uploaded";
    }

    await this.populateEditCourseDropdown(module.courseID);
  }

  async loadTrainees() {
    try {
      const response = await fetch("../php/getTrainees.php");
      const result = await response.json();

      if (result.success) {
        this.renderTrainees(result.data);
      }
    } catch (error) {
      console.error("Error loading trainees:", error);
    }
  }

  renderTrainees(trainees) {
    const tbody = document.querySelector("#trainees tbody");
    if (!tbody) return;

    tbody.innerHTML = "";

    if (trainees.length === 0) {
      tbody.innerHTML = '<tr><td colspan="4">No trainees found</td></tr>';
      return;
    }

    trainees.forEach((trainee) => {
      const row = document.createElement("tr");
      row.innerHTML = `
                <td>${trainee.name}</td>
                <td>${trainee.email}</td>
                <td>${trainee.courseName}</td>
                <td>${trainee.progress}</td>
            `;
      tbody.appendChild(row);
    });
  }

  async loadSubmissions() {
    try {
      const response = await fetch("../php/getSubmissions.php");
      const result = await response.json();

      if (result.success) {
        this.renderSubmissions(result.data);
      }
    } catch (error) {
      console.error("Error loading submissions:", error);
    }
  }

  renderSubmissions(submissions) {
    const tbody = document.querySelector("#submissions tbody");
    if (!tbody) return;

    tbody.innerHTML = "";

    if (submissions.length === 0) {
      tbody.innerHTML = '<tr><td colspan="5">No submissions found</td></tr>';
      return;
    }

    submissions.forEach((submission) => {
      const row = document.createElement("tr");
      const actionButton =
        submission.gradeStatus === "Graded"
          ? `<button type="button" class="viewSubmissions" disabled>Already Graded (${submission.grade}/100)</button>`
          : `<button type="button" class="viewSubmissions" 
             data-submission-id="${submission.id}" 
             data-name="${submission.traineeName}" 
             data-date="${submission.dateSubmitted}" 
             data-file="${submission.filePath}">Add Grade and Comment</button>`;

      row.innerHTML = `
              <td>${submission.traineeName}</td>
              <td>${submission.courseName}</td>
              <td>${submission.activityTitle}</td>
              <td>${submission.dateSubmitted}</td>
              <td>
                  ${actionButton}
                  <form class="gradeActivity" action=""></form>
              </td>
          `;
      tbody.appendChild(row);
    });

    this.addGradeEventListeners();
  }

  addGradeEventListeners() {
    const viewButtons = document.querySelectorAll(
      ".viewSubmissions:not([disabled])"
    );

    viewButtons.forEach((btn) => {
      btn.addEventListener("click", () => {
        const row = btn.closest("tr");
        const gradeForm = row.querySelector(".gradeActivity");

        if (gradeForm.innerHTML.trim() === "") {
          gradeForm.innerHTML = `
                    <p><strong>Submitted by: </strong>${btn.dataset.name}</p>
                    <p><strong>Date Submitted: </strong>${btn.dataset.date}</p>
                    <p><strong>File: </strong><a href="../${btn.dataset.file}" target="_blank" class="file-link">View Submission</a></p>
                    <label>
                        Score of Student:
                        <input type="number" name="score" min="0" max="100" step="0.01" required>
                    </label>
                    <label>
                        Total Items:
                        <input type="number" name="total" min="0" max="100" step="0.01" required>
                    </label>
                    <label style="display: none;">
                        Activity Grade:
                        <input type="number" name="grade" min="0" max="100" step="0.01" readonly required>
                    </label>
                    <label>
                        Add Comment:
                        <textarea name="comment" rows="3" cols="30" placeholder="Add your feedback here"></textarea>
                    </label>
                    <button type="submit" class="gradeBtn">Grade</button>
                    <button type="button" class="closeGradeBtn">Close</button>
                `;

          gradeForm
            .querySelector(".closeGradeBtn")
            .addEventListener("click", () => {
              gradeForm.innerHTML = "";
            });

          gradeForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            await this.submitGrade(btn.dataset.submissionId, gradeForm, btn);
          });
        } else {
          gradeForm.innerHTML = "";
        }
      });
    });
  }

  async submitGrade(submissionId, gradeForm, buttonElement) {
    try {
      const score = parseInt(
        gradeForm.querySelector('input[name="score"]').value
      );

      const total = parseInt(
        gradeForm.querySelector('input[name="total"]').value
      );

      let grade = 0;
      if (total > 0 && !isNaN(score)) {
        grade = (score / total) * 100;
      }

      const gradeInput = gradeForm.querySelector('input[name="grade"]');

      gradeInput.value = grade.toFixed(2);

      console.log(gradeInput);

      const comment = gradeForm.querySelector('textarea[name="comment"]').value;

      if (isNaN(grade) || grade < 0 || grade > 100) {
        alert("Please enter a valid grade between 0 and 100.");
        return;
      }

      const passingGrade = 75;
      const remarks = grade >= passingGrade ? "passed" : "failed";

      const formData = new FormData();
      formData.append("submission_id", submissionId);
      formData.append("score", score);
      formData.append("total", total);
      formData.append("grade", grade.toFixed(2));
      formData.append("feedback", comment);
      formData.append("remarks", remarks);

      const response = await fetch("../php/gradeSubmission.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message + ` (${remarks.toUpperCase()})`);
        gradeForm.innerHTML = "";
        buttonElement.disabled = true;
        buttonElement.textContent = `Graded (${grade}/100) - ${remarks.toUpperCase()}`;
        this.loadDashboardData();
      } else {
        alert("Error: " + result.message);
      }
    } catch (error) {
      console.error("Error submitting grade:", error);
      alert("An error occurred while submitting the grade");
    }
  }

  initializeProfile() {
    this.loadProfile();
  }

  async loadProfile() {
    const container = document.querySelector("#profile .container");

    try {
      const response = await fetch("../php/getProfile.php");
      const responseData = await response.json();

      if (responseData.status !== "success") {
        container.innerHTML = `<p>${responseData.message}</p>`;
        return;
      }

      this.renderProfile(responseData.data, container);
    } catch (error) {
      console.error("Profile loading error:", error);
      container.innerHTML =
        "<p>Error loading profile. Please refresh the page.</p>";
    }
  }

  renderProfile(data, container) {
    const form = document.createElement("form");
    form.id = "profileForm";
    form.className = "capsule";

    const profileImageDiv = this.createProfileImageSection(data);
    const detailsDiv = this.createDetailsSection(data);
    const editDiv = this.createEditSection(data);

    form.append(profileImageDiv, detailsDiv, editDiv);
    container.appendChild(form);

    this.attachProfileEventListeners(data);
  }

  createProfileImageSection(data) {
    const profileImageDiv = document.createElement("div");
    profileImageDiv.className = "profile-image";

    const img = document.createElement("img");
    img.id = "profilePic";
    img.src = data.profileImage
      ? `../${data.profileImage}`
      : "../images/school.png";
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
    return profileImageDiv;
  }

  createDetailsSection(data) {
    const detailsDiv = document.createElement("div");
    detailsDiv.className = "details";

    const fields = this.getProfileFields(data);

    fields.forEach((field) => {
      const labelEl = document.createElement("label");
      labelEl.htmlFor = field.id;
      labelEl.textContent = field.label;

      let inputEl;
      if (field.id === "profile-bio") {
        inputEl = document.createElement("textarea");
        inputEl.style.height = "100px";
        inputEl.style.whiteSpace = "pre-wrap";
      } else {
        inputEl = document.createElement("input");
        inputEl.type = "text";
      }

      inputEl.id = field.id;
      inputEl.value = field.value;
      inputEl.disabled = true;

      detailsDiv.append(labelEl, inputEl);
    });

    const editButton = document.createElement("button");
    editButton.type = "button";
    editButton.id = "editButton";
    editButton.textContent = "Edit Profile";
    detailsDiv.appendChild(editButton);

    return detailsDiv;
  }

  createEditSection(data) {
    const editDiv = document.createElement("div");
    editDiv.className = "details-edit";
    editDiv.style.display = "none";

    const fields = this.getProfileFields(data);

    fields.forEach((field) => {
      const labelEl = document.createElement("label");
      labelEl.htmlFor = "edit-" + field.id;
      labelEl.textContent = field.label;

      let inputEl;
      if (field.id === "profile-bio") {
        inputEl = document.createElement("textarea");
        inputEl.style.height = "100px";
        inputEl.style.whiteSpace = "pre-wrap";
      } else {
        inputEl = document.createElement("input");
        inputEl.type = "text";
        if (field.id === "profile-mail" || field.id === "profile-contact") {
          inputEl.disabled = true;
        }
      }

      inputEl.id = "edit-" + field.id;
      inputEl.value = field.value;

      editDiv.append(labelEl, inputEl);
    });

    const saveButton = document.createElement("button");
    saveButton.type = "button";
    saveButton.id = "saveButton";
    saveButton.textContent = "Save Profile";
    editDiv.appendChild(saveButton);

    return editDiv;
  }

  getProfileFields(data) {
    return [
      {
        label: "Name",
        id: "profile-name",
        value: `${data.firstName || ""} ${data.middleName || ""} ${
          data.lastName || ""
        } ${data.suffix || ""}`.trim(),
      },
      {
        label: "Email",
        id: "profile-mail",
        value: data.email || "",
      },
      {
        label: "Contact",
        id: "profile-contact",
        value: data.mobileNumber || "",
      },
      {
        label: "Bio",
        id: "profile-bio",
        value: data.bio || "",
      },
    ];
  }

  attachProfileEventListeners(data) {
    const editButton = document.getElementById("editButton");
    const saveButton = document.getElementById("saveButton");
    const fileInput = document.getElementById("image-edit");
    const img = document.getElementById("profilePic");
    const label = document.querySelector("label[for='image-edit']");
    const detailsDiv = document.querySelector(".details");
    const editDiv = document.querySelector(".details-edit");

    if (editButton) {
      editButton.addEventListener("click", () => {
        const fields = this.getProfileFields(data);
        fields.forEach((f) => {
          const editInput = document.getElementById("edit-" + f.id);
          const originalInput = document.getElementById(f.id);
          if (editInput && originalInput) {
            editInput.value = originalInput.value;
          }
        });
        detailsDiv.style.display = "none";
        editDiv.style.display = "flex";
        label.style.display = "flex";
      });
    }

    if (saveButton) {
      saveButton.addEventListener("click", async () => {
        await this.saveProfile();
      });
    }

    if (fileInput) {
      fileInput.addEventListener("change", () => {
        const file = fileInput.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = (e) => (img.src = e.target.result);
          reader.readAsDataURL(file);
        }
      });
    }
  }

  async saveProfile() {
    const formData = new FormData();
    const fields = [
      "profile-name",
      "profile-mail",
      "profile-contact",
      "profile-bio",
    ];

    fields.forEach((fieldId) => {
      const input = document.getElementById("edit-" + fieldId);
      if (input) {
        formData.append(fieldId, input.value);
      }
    });

    const fileInput = document.getElementById("image-edit");
    if (fileInput && fileInput.files[0]) {
      formData.append("profileImage", fileInput.files[0]);
    }

    try {
      const response = await fetch("../php/updateProfile.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();
      alert(result.message);

      if (result.status === "success") {
        location.reload();
      }
    } catch (error) {
      console.error("Profile save error:", error);
      alert("An error occurred while saving the profile.");
    }
  }

  initializeAnnouncements() {
    const notificationsList = document.querySelector(".notifications-list");
    const refreshBtn = document.getElementById("refreshNotifications");

    const loadAnnouncements = async () => {
      try {
        const response = await fetch("../php/getAnnouncements.php");
        const { success, data, message } = await response.json();

        if (notificationsList) {
          notificationsList.innerHTML = "";

          if (success && data.length > 0) {
            data.forEach((announcement) => {
              const item = document.createElement("div");
              item.className = "notification-item";
              item.innerHTML = `
                            <div class="notification-header">
                                <span class="type ${announcement.type.toLowerCase()}">${
                announcement.type
              }</span>
                                <small>${new Date(
                                  announcement.created_at
                                ).toLocaleDateString()}</small>
                            </div>
                            <p class="message">${announcement.message}</p>
                            ${
                              announcement.expires_at
                                ? `<small class="expiry">Expires: ${new Date(
                                    announcement.expires_at
                                  ).toLocaleDateString()}</small>`
                                : ""
                            }
                        `;
              notificationsList.appendChild(item);
            });
          } else {
            notificationsList.innerHTML = `<div class="no-notifications">${
              message || "No announcements available"
            }</div>`;
          }
        }
      } catch (error) {
        console.error("Error loading announcements:", error);
        if (notificationsList) {
          notificationsList.innerHTML =
            '<div class="error">Error loading announcements. Please try refreshing.</div>';
        }
      }
    };

    if (refreshBtn) {
      refreshBtn.addEventListener("click", loadAnnouncements);
    }

    loadAnnouncements();
    setInterval(loadAnnouncements, 60000);
  }
}

let trainerDashboard;

document.addEventListener("DOMContentLoaded", () => {
  trainerDashboard = new TrainerDashboard();
});
