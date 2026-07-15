/*
Local development configuration.
Change these credentials before deploying to a live server.
*/
<?php
$conn = mysqli_connect("localhost", "root", "", "pg_life");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL! please contact the admin.";
    return;
}