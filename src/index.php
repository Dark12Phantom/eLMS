<?php
require_once 'php/DatabaseConnection.php';
session_start();

if (isset($_SESSION['user'])) {
  $role = $_SESSION['user']['role'];

  switch ($role) {
    case 'admin':
      header("Location: ./html/admin_dashboard.php");
      break;
    case 'student':
      header("Location: ./html/student_dashboard.php");
      break;
    case 'teacher':
      header("Location: ./html/teacher_dashboard.php");
      break;
  }
  exit();
}
?>

<!-- LANDING PAGE -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Benguet Technical School eLMS - Home</title>
  <link rel="icon" href="./images/school.png" type="image/x-icon">
  <link rel="stylesheet" href="./css/index.css" />
</head>

<body>
  <!-- NAVIGATION -->
  <nav>
    <div class="nav-container">
      <div id="logo">
        <img src="./images/school.jpg" alt="SCHOOL LOGO" />
        <div class="text-logo">
          <h1><strong>B</strong>enguet</h1>
          <h1><strong>T</strong>echnical</h1>
          <h1><strong>S</strong>chool</h1>
        </div>
      </div>
      <div id="nav-list">
        <ul>
          <li><a href="#home">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#courses">Courses</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </div>
      <a id="account-popup">
        <div class="content">
          <li>
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FF9102">
              <path
                d="M480-120v-80h280v-560H480v-80h280q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H480Zm-80-160-55-58 102-102H120v-80h327L345-622l55-58 200 200-200 200Z" />
            </svg>
            <p>Login/Signup</p>
        </div>
      </a>
    </div>
  </nav>

  <!-- MAIN CONTENT -->
  <main>
    <section id="home">

      <div class="slider">
        <div class="slides">
          <img src="./uploads/images/agriculture.jpg" alt="">
          <img src="./uploads/images/automotive.jpg" alt="">
          <img src="./uploads/images/breadmaking.jpg" alt="">
          <img src="./uploads/images/dressmaking.webp" alt="">
          <img src="./uploads/images/driving.webp" alt="">
          <img src="./uploads/images/hairdressing.webp" alt="">
          <img src="./uploads/images/japanese.jpg" alt="">
          <img src="./uploads/images/tailoring.webp" alt="">
        </div>
        <div class="desc">
          <h1>Learn Anytime, Anywhere</h1>
          <p>
            Empower your future with our easy and accessible eLearning system.
          </p>
        </div>
      </div>
    </section>
    <section id="courses">
      <h1>Courses Offered</h1>
      <div id="card-scroll">
        <?php
        require_once "php/DatabaseConnection.php";

        $courses = $conn->query("SELECT id, courseName, description, filePath FROM coursestable");
        $counter = 1;

        while ($course = $courses->fetch_assoc()) {
          echo '<div class="card c' . $counter . '">';
          echo '  <img src="' . $course['filePath'] . '" alt="' . $course['courseName'] . '">';
          echo '  <div class="description">';
          echo '    <h2>' . $course['courseName'] . '</h2>';
          echo '    <p class=desc>' . $course['description'] . '</p>';
          echo '    <a href="" class="seeMore">See more...</a>';
          echo '    <div class="list">';

          $stmt = $conn->prepare('SELECT basicPoints FROM basiccompetency WHERE courseID=?');
          $stmt->bind_param('i', $course['id']);
          $stmt->execute();
          $result = $stmt->get_result();

          echo '      <ul>';
          echo '        <p>Basic Competencies</p>';
          while ($row = $result->fetch_assoc()) {
            echo '        <li>' . htmlspecialchars($row['basicPoints']) . '</li>';
          }
          echo '      </ul>';

          $stmt = $conn->prepare('SELECT commonPoints FROM commoncompetency WHERE courseID=?');
          $stmt->bind_param('i', $course['id']);
          $stmt->execute();
          $result = $stmt->get_result();

          echo '      <ul>';
          echo '        <p>Common Competencies</p>';
          while ($row = $result->fetch_assoc()) {
            echo '        <li>' . htmlspecialchars($row['commonPoints']) . '</li>';
          }
          echo '      </ul>';

          $stmt = $conn->prepare('SELECT corePoints FROM corecompetency WHERE courseID=?');
          $stmt->bind_param('i', $course['id']);
          $stmt->execute();
          $result = $stmt->get_result();

          echo '      <ul>';
          echo '        <p>Core Competencies</p>';
          while ($row = $result->fetch_assoc()) {
            echo '        <li>' . htmlspecialchars($row['corePoints']) . '</li>';
          }
          echo '      </ul>';

          echo '    </div>';

          echo '      <button class="linkToAccountPage">Enroll Now</button>';

          echo '    <a href="" class="closeMore">Close</a>';
          echo '  </div>';
          echo '</div>';
          $counter++;
        }
        ?>
      </div>
    </section>
    <section id="about">
      <h1>About Us</h1>
      <h2>
        BENGUET TECHNICAL SCHOOL IS AN LGU-RUN TECHNICAL VOCATIONAL
        INSTITUTION UNDER THE PROVINCIAL GOVERNMENT OF BENGUET. IT OFFERS
        VARIOUS PROGRAMS/SHORT COURSES ACCREDITED BY TESDA.
      </h2>

      <div class="mv">
        <div class="mission">
          <h1>Mission</h1>
          <p>
            To provide inclusive, industry-relevant, and future-ready
            education through technology-driven learning that empowers
            individuals anytime, anywhere.
          </p>
        </div>
        <div class="vision">
          <h1>Vision</h1>
          <p>
            Benguet Technical School envisions a community where every learner
            gains equal access to skills, knowledge, and opportunities for
            lifelong success.
          </p>
        </div>
      </div>
    </section>
    <section id="contact">
      <div class="mail">
        <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#0eed3f">
          <path
            d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z" />
        </svg>
        <p>
          <a href="mailto:pgobts@benguet.gov.ph">pgobts@benguet.gov.ph</a>
        </p>
      </div>
      <div class="phone">
        <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#0eed3f">
          <path
            d="M480-440v-360q0-17 11.5-28.5T520-840h280q17 0 28.5 11.5T840-800v200q0 17-11.5 28.5T800-560H600L480-440Zm80-200h200v-120H560v120Zm0 0v-120 120Zm238 520q-125 0-247-54.5T329-329Q229-429 174.5-551T120-798q0-18 12-30t30-12h162q14 0 25 9.5t13 22.5l26 140q2 16-1 27t-11 19l-97 98q20 37 47.5 71.5T387-386q31 31 65 57.5t72 48.5l94-94q9-9 23.5-13.5T670-390l138 28q14 4 23 14.5t9 23.5v162q0 18-12 30t-30 12ZM241-600l66-66-17-94h-89q5 41 14 81t26 79Zm358 358q39 17 79.5 27t81.5 13v-88l-94-19-67 67ZM241-600Zm358 358Z" />
        </svg>
        <p>(074) 424 7060</p>
      </div>
    </section>

    <div class="account-modal">
      <div id="signup" class="active">
        <div class="close">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FF9102">
            <path
              d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
          </svg>
        </div>
        <h1>Signup</h1>
        <form action="./php/register.php" method="POST" id="registerForm">
          <div class="basic-info">
            <input name="fname" id="fname" type="text" placeholder="First Name" required />
            <input name="mname" id="mname" type="text" placeholder="Middle Name" required />
            <input name="lname" id="lname" type="text" placeholder="Last Name" required />
            <input name="suffix" id="suffix" type="text" placeholder="Name Suffix (Jr., II, III)" />
            <input name="address" id="address" type="text" placeholder="Address" required />
            <select name="gender" name="gender" id="gender" required>
              <option value="" selected disabled hidden>Select Gender</option>
              <option value="M">Male</option>
              <option value="F">Female</option>
            </select>
            <input name="bdate" id="bdate" type="text" onfocus="(this.type='date')" onblur="(this.type='text')"
              placeholder="Birthdate" required />
            <input name="cnum" id="cnum" type="number" placeholder="Mobile Number" required />
            <select name="education" id="education" required>
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
            </select>
          </div>
          <div class="login-info">
            <input name="uname" id="uname" type="text" placeholder="Email (Used to login)" required />
            <input name="password" id="password" type="password" placeholder="Password" required />
          </div>
          <div class="submit">
            <input id="submit" type="submit" value="Register" />
          </div>
        </form>
        <div id="messageBox"></div>
        <span></span>
        <p>Have an account? || <a href="#login">Login instead.</a></p>
      </div>
      <div id="login">
        <div class="close">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FF9102">
            <path
              d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
          </svg>
        </div>
        <h1>Login</h1>
        <form action="./php/login.php" method="POST" id="loginForm">
          <div class="userInput">
            <input id="username" name="username" type="text" placeholder="Username" required />
            <input id="pass" name="pass" type="password" placeholder="Password" required />
          </div>
          <div class="submit">
            <input id="login-button" type="submit" value="Login" />
          </div>
        </form>
        <span></span>
        <p>No account? || <a href="#signup">Signup now.</a></p>
      </div>
    </div>
  </main>
  <footer>
    <p>© 2025 Benguet Technical School. All rights reserved.</p>
  </footer>
</body>

<!-- COURSE CARDS -->
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const seeMore = document.querySelectorAll(".seeMore");
    const closeMore = document.querySelectorAll(".closeMore");

    seeMore.forEach(link => {
      link.addEventListener("click", e => {
        e.preventDefault();
        const card = link.closest(".card");

        if (card.classList.contains("active")) return;

        seeMore.forEach(l => {
          if (l !== link) l.style.pointerEvents = "none";
        });

        card.classList.add("active");
      });
    });

    closeMore.forEach(link => {
      link.addEventListener("click", e => {
        e.preventDefault();
        const card = link.closest(".card");
        card.classList.remove("active");

        seeMore.forEach(l => l.style.pointerEvents = "auto");
      });
    });
  })
</script>

<!-- ACCOUNT MODAL SCRIPT -->
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const accountPopup = document.querySelector("#account-popup");
    const accountModal = document.querySelector(".account-modal");
    const enrollNowButton = document.querySelectorAll(".linkToAccountPage");
    const closePages = document.getElementsByClassName("close");

    enrollNowButton.forEach(button => {
      button.addEventListener("click", () => {
        accountModal.classList.toggle("active");
      });
    });
    for (let i = 0; i < closePages.length; i++) {
      closePages[i].addEventListener("click", () => {
        accountModal.classList.remove("active");
      });
    }
    accountPopup.addEventListener("click", () => {
      if (accountModal.classList.contains("active")) {
        accountModal.classList.remove("active");
      } else {
        accountModal.classList.add("active");
        signupPage.classList.remove("active");
        loginPage.classList.add("active");
      }
    });
  })
</script>
<!-- LOGIN SIGNUP LOGIC -->
<script>
  const loginPage = document.querySelector("#login");
  const signupPage = document.querySelector("#signup");
  document.querySelector("#login > p > a").addEventListener("click", () => {
    if (loginPage.classList.contains("active")) {
      loginPage.classList.remove("active");
      signupPage.classList.add("active");
    }
  });
  document.querySelector("#signup > p > a").addEventListener("click", () => {
    if (signupPage.classList.contains("active")) {
      signupPage.classList.remove("active");
      loginPage.classList.add("active");
    }
  });

  const registerForm = document.getElementById("registerForm");
  registerForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    const response = await fetch("./php/register.php", {
      method: "POST",
      body: formData,
    });

    const result = await response.json();

    const messageBox = document.getElementById("messageBox");
    messageBox.textContent = result.message;
    messageBox.style.color = result.status === "success" ? "green" : "red";

    if (result.status === "success") {
      setTimeout(() => {
        location.reload();
      }, 1000);
    }
  });
</script>

<!-- USERS -->
<script>
  const loginForm = document.querySelector("#login form");

  loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(loginForm);

    const response = await fetch("./php/login.php", {
      method: "POST", body: formData
    });

    const res = await response.json();

    if (res.status === "success") {
      alert(`Welcome, ${res.role}! Redirecting...`);
      setTimeout(function () {
        window.location.href = res.redirect;
      }, 300);
    } else if (res.status === "error") {
      alert(res.message);
      location.reload();
    }
  });
</script>

<!-- SLIDER -->
<script>
  const slides = document.querySelector(".slides");
  const slideCount = slides.children.length;
  let index = 0;
  const pauseTime = 3000;
  const transitionTime = 500;

  function nextSlide() {
    index++;

    if (index >= slideCount) {
      index = 0;
      slides.style.transition = "none";
      slides.style.transform = `translateX(0px)`;
      slides.offsetWidth;
    } else {
      slides.style.transition = `transform ${transitionTime}ms ease`;
      const slideWidth = slides.children[0].offsetWidth;
      slides.style.transform = `translateX(-${slideWidth * index}px)`;
    }
  }

  setInterval(nextSlide, pauseTime + transitionTime);
</script>

</html>