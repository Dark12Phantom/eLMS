<?php

require 'DatabaseConnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = trim($_POST['fname']);
    $middleName = trim($_POST['mname']);
    $lastName = trim($_POST['lname']);
    $nameSuffix = trim($_POST['suffix']);

    $age = isset($_POST['age']) ? (int) $_POST['age'] : null;
    $address = trim($_POST['address']);
    $gender = $_POST['gender'];
    $bdate = isset($_POST['bdate']) ? $_POST['bdate'] : null;
    $role = "trainee";

    $cnum = $_POST['cnum'];
    $mobileNumber = preg_replace('/^0/', '+63', trim($cnum));
    $education = trim($_POST['education']);
    $email = trim($_POST['uname']);
    $password = trim($_POST['password']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $bio = "Student of Benguet Technical School";
    $prefix = '2025S-';
    $query = 'SELECT userID FROM userstable ORDER BY id DESC LIMIT 1';
    $result = $conn->query( $query );

    if($result && $row = $result->fetch_assoc()){
        $lastUserID = (int)substr($row['userID'], 3);
        $newID = $lastUserID + 1;
    }else{
        $newID = 1;
    }

    $userID = $prefix . str_pad($newID, 6, "0", STR_PAD_LEFT);

    $sqlSend = "INSERT INTO userstable
                (userID, firstName, middleName, lastName, suffix, gender, age, birthDate, bio, role, mobileNumber, email, password, education)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $statement = $conn->prepare($sqlSend);
    $statement->bind_param(
        "ssssssisssssss",
        $userID,
        $firstName,
        $middleName,
        $lastName,
        $nameSuffix,
        $gender,
        $age,
        $bdate,
        $bio,
        $role,
        $mobileNumber,
        $email,
        $hashedPassword,
        $education
    );

    if($statement->execute()){
        echo json_encode(["status" => "success", "message" => "User Registered! $userID"]);
    }else{
        echo json_encode(["status" => "error", "message" => "Error: " . $statement->error]);
    }

    $statement->close();
    $conn->close();
}

?>