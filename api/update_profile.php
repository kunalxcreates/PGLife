<?php

session_start();

require "../includes/database_connect.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: ../index.php");
    exit;

}

$user_id = $_SESSION["user_id"];

$full_name = mysqli_real_escape_string($conn, $_POST["full_name"]);
$email = mysqli_real_escape_string($conn, $_POST["email"]);
$phone = mysqli_real_escape_string($conn, $_POST["phone"]);
$college_name = mysqli_real_escape_string($conn, $_POST["college_name"]);

$sql = "UPDATE users
SET
full_name='$full_name',
email='$email',
phone='$phone',
college_name='$college_name'
WHERE id=$user_id";

mysqli_query($conn, $sql);

$_SESSION["full_name"] = $full_name;

header("Location: ../dashboard.php");

?>