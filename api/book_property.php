<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require "../includes/database_connect.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION["user_id"];

if (!isset($_GET["property_id"])) {
    header("Location: ../index.php");
    exit;
}

$property_id = $_GET["property_id"];

/* Check if already booked */

$sql = "SELECT * FROM bookings
        WHERE user_id = $user_id
        AND property_id = $property_id";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {

    $_SESSION["booking_exists"] = true;

    header("Location: ../property_detail.php?property_id=$property_id");
    exit;
}

/* Insert booking */

$sql = "INSERT INTO bookings (user_id, property_id, status)
        VALUES ($user_id, $property_id, 'Pending')";

mysqli_query($conn, $sql);

/* Success Message */

$_SESSION["booking_success"] = true;

header("Location: ../property_detail.php?property_id=$property_id");
exit;

?>