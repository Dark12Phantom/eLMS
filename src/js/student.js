class StudentDashboard {
    constructor() {
        this.init();
    }

    init() {
        this.initializeNavigation();
        this.initializeCourseEnrollment();
        this.initializeActivities();
        this.initializeProfile();
        this.initializeAnnouncements();
    }

    initializeNavigation() {
        const tabElements = document.querySelectorAll(
            ".dashboard, .courses, .activities, .enrollment, .profile"
        );
        const sectionElements = document.querySelectorAll(
            "#dashboard, #courses, #activities, #enrollment, #profile"
        );

        const handleTabClick = (clickedTab) => {
            const tabClass = clickedTab.classList[0];
            const matchingSection = document.getElementById(tabClass);

            tabElements.forEach(el => el.classList.remove("active"));
            sectionElements.forEach(el => el.classList.remove("active"));

            clickedTab.classList.add("active");
            if (matchingSection) {
                matchingSection.classList.add("active");
            }

            localStorage.setItem("activeTab", tabClass);
        };

        window.addEventListener("DOMContentLoaded", () => {
            const savedTab = localStorage.getItem("activeTab");
            const defaultTab = savedTab 
                ? document.querySelector(`.${savedTab}`)
                : document.querySelector(".dashboard");

            if (defaultTab) handleTabClick(defaultTab);
        });

        tabElements.forEach(tab => {
            tab.addEventListener("click", () => handleTabClick(tab));
        });
    }

    initializeCourseEnrollment() {
        const popup = document.getElementById("popup-enrollment");
        const closePopupBtn = document.getElementById("closePopup");
        const submitBtn = document.getElementById("submitEnrollment");
        const popupCourseName = document.getElementById("popupCourseName");
        let selectedCourseId = null;

        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('enrollBtn') && !e.target.disabled) {
                selectedCourseId = e.target.getAttribute("data-course-id");
                popupCourseName.textContent = e.target.getAttribute("data-course-name");
                popup.style.display = "block";
            }
        });

        if (closePopupBtn) {
            closePopupBtn.addEventListener("click", () => {
                popup.style.display = "none";
                selectedCourseId = null;
            });
        }

        if (submitBtn) {
            submitBtn.addEventListener("click", async () => {
                if (!selectedCourseId) return;

                try {
                    const response = await fetch("../php/submitEnrollment.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `course_id=${selectedCourseId}`
                    });

                    const data = await response.json();
                    alert(data.message);

                    if (data.success) {
                        popup.style.display = "none";
                        location.reload();
                    }
                } catch (error) {
                    console.error('Enrollment error:', error);
                    alert('An error occurred during enrollment. Please try again.');
                }
            });
        }
    }

    initializeActivities() {
        const popup = document.querySelector("#popup");
        const button = document.querySelector(".button > button");

        if (button && popup) {
            button.addEventListener("click", () => {
                popup.classList.toggle("active");
            });
        }

        document.addEventListener('change', (e) => {
            if (e.target.type === 'file' && e.target.id.startsWith('file-input-')) {
                const fileName = e.target.closest('.button-group').querySelector('#file-name');
                const fileSVGLabel = e.target.closest('.button-group').querySelector('svg');
                
                if (e.target.files && e.target.files.length > 0) {
                    fileName.textContent = e.target.files[0].name;
                    if (fileSVGLabel) fileSVGLabel.style.display = "none";
                } else {
                    fileName.textContent = "Choose File";
                    if (fileSVGLabel) fileSVGLabel.style.display = "";
                }
            }
        });
    }

    initializeProfile() {
            this.loadProfile();
    }

    async loadProfile() {
        const container = document.querySelector("#profile .container");
        
        try {
            const response = await fetch('../php/getProfile.php');
            const responseData = await response.json();

            if (responseData.status !== "success") {
                container.innerHTML = `<p>${responseData.message}</p>`;
                return;
            }

            this.renderProfile(responseData.data, container);
        } catch (error) {
            console.error('Profile loading error:', error);
            container.innerHTML = '<p>Error loading profile. Please refresh the page.</p>';
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
        return profileImageDiv;
    }

    createDetailsSection(data) {
        const detailsDiv = document.createElement("div");
        detailsDiv.className = "details";

        const fields = this.getProfileFields(data);

        fields.forEach(field => {
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

        fields.forEach(field => {
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
                fields.forEach(f => {
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
                    reader.onload = e => img.src = e.target.result;
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    async saveProfile() {
        const formData = new FormData();
        const fields = ["profile-name", "profile-mail", "profile-contact", "profile-bio"];
        
        fields.forEach(fieldId => {
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
                body: formData
            });
            
            const result = await response.json();
            alert(result.message);
            
            if (result.status === "success") {
                location.reload();
            }
        } catch (error) {
            console.error('Profile save error:', error);
            alert('An error occurred while saving the profile.');
        }
    }

    initializeAnnouncements() {
        const notificationsList = document.querySelector('.notifications-list');
        const refreshBtn = document.getElementById('refreshNotifications');

        const loadAnnouncements = async () => {
            try {
                const response = await fetch('../php/getAnnouncements.php');
                const { success, data, message } = await response.json();

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
                notificationsList.innerHTML = '<div class="error">Error loading announcements. Please try refreshing.</div>';
            }
        };

        if (refreshBtn) {
            refreshBtn.addEventListener('click', loadAnnouncements);
        }
        
        loadAnnouncements();
        setInterval(loadAnnouncements, 60000);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new StudentDashboard();
});