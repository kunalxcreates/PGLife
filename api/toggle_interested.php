<?php

session_start();

require("../includes/database_connect.php");

if (!isset($_SESSION['user_id'])) {
    echo "login";
    exit;
}

$user_id = $_SESSION['user_id'];
$property_id = $_POST['property_id'];

$sql = "SELECT * FROM interested_users_properties
        WHERE user_id='$user_id'
        AND property_id='$property_id'";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0)
{
    $sql = "INSERT INTO interested_users_properties
            (user_id, property_id)
            VALUES
            ('$user_id', '$property_id')";

    mysqli_query($conn, $sql);

    $sql = "SELECT COUNT(*) AS total
        FROM interested_users_properties
        WHERE property_id='$property_id'";

$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);

echo "added|" . $row['total'];
}
else
{
    $sql = "DELETE FROM interested_users_properties
            WHERE user_id='$user_id'
            AND property_id='$property_id'";

    mysqli_query($conn, $sql);

    $sql = "SELECT COUNT(*) AS total
        FROM interested_users_properties
        WHERE property_id='$property_id'";

$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);

echo "removed|" . $row['total'];
}

mysqli_close($conn);

?>