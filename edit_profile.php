<?php

session_start();

require "includes/database_connect.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM users WHERE id = $user_id";

$result = mysqli_query($conn, $sql);

$user = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Profile | PG Life</title>

    <?php require "includes/head_links.php"; ?>

    <link rel="stylesheet" href="css/dashboard.css">

</head>

<body>

<?php require "includes/header.php"; ?>

<div class="breadcrumb-container">

    <nav aria-label="breadcrumb">

        <ol class="breadcrumb page-container">

            <li class="breadcrumb-item">
                <a href="index.php">Home</a>
            </li>

            <li class="breadcrumb-item">
                <a href="dashboard.php">Dashboard</a>
            </li>

            <li class="breadcrumb-item active">
                Edit Profile
            </li>

        </ol>

    </nav>

</div>

<div class="page-container mt-5 mb-5">

    <div class="container">

        <h2 class="mb-4">
            Edit Profile
        </h2>

        <form action="api/update_profile.php" method="POST">

            <div class="form-group">

                <label>Full Name</label>

                <input
                    type="text"
                    class="form-control"
                    name="full_name"
                    value="<?php echo $user['full_name']; ?>"
                    required>

            </div>

            <div class="form-group">

                <label>Email</label>

                <input
                    type="email"
                    class="form-control"
                    name="email"
                    value="<?php echo $user['email']; ?>"
                    required>

            </div>

            <div class="form-group">

                <label>Phone Number</label>

                <input
                    type="text"
                    class="form-control"
                    name="phone"
                    value="<?php echo $user['phone']; ?>"
                    required>

            </div>

            <div class="form-group">

                <label>College Name</label>

                <input
                    type="text"
                    class="form-control"
                    name="college_name"
                    value="<?php echo $user['college_name']; ?>"
                    required>

            </div>

            <button type="submit" class="btn btn-primary">

                Update Profile

            </button>

            <a href="dashboard.php" class="btn btn-secondary ml-2">

                Cancel

            </a>

        </form>

    </div>

</div>

<?php require "includes/footer.php"; ?>

</body>

</html>