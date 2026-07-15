<?php

session_start();

require "includes/database_connect.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION["user_id"];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | PG Life</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>

    <header class="header sticky-top">
        <nav class="navbar navbar-expand-lg navbar-light bg-white">

            <div class="container-fluid">

                <a class="navbar-brand" href="index.php">
                    <img src="img/logo.png" alt="PG Life" height="42">
                </a>

                <button class="navbar-toggler" type="button"
                    data-toggle="collapse"
                    data-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-end"
                    id="navbarContent">

                    <ul class="navbar-nav align-items-center">

                        <li class="nav-item mr-3">
                            <span class="nav-name">
                              <strong>Hi, <?php echo $_SESSION["full_name"]; ?></strong>
                            </span>
                        </li>

                        <li class="nav-item">
                           <a href="dashboard.php" class="nav-link">
                                <i class="fas fa-user mr-1"></i>
                                Dashboard
                            </a>
                        </li>

                        <li class="nav-divider"></li>

                        <li class="nav-item">
                            <a href="api/logout.php" class="nav-link">
                                <i class="fas fa-sign-out-alt mr-1"></i>
                                Logout
                            </a>
                        </li>

                    </ul>

                </div>

            </div>

        </nav>
    </header>


    <div class="breadcrumb-container">

    <nav aria-label="breadcrumb">

        <ol class="breadcrumb page-container">

            <li class="breadcrumb-item">
                <a href="index.php">Home</a>
            </li>

            <li class="breadcrumb-item active">
                Dashboard
            </li>

        </ol>

    </nav>

</div>
<?php

$sql = "SELECT * FROM users WHERE id = $user_id";

$result = mysqli_query($conn, $sql);

$user = mysqli_fetch_assoc($result);

?>
        <div class="my-profile page-container">

        <div class="container">

            <h1 class="mb-4">
                My Profile
            </h1>

            <div class="row align-items-center">

               
                

                <div class="col-md-12">

                    <div class="row no-gutters justify-content-between align-items-end">

                        <div class="profile-details">

                            <h2 class="profile-name">
    <?php echo $user["full_name"]; ?>
</h2>

                            <div class="profile-info">

                                <div class="detail-label">
                                    Email
                                </div>

                                <div class="detail-value">
                                    <?php echo $user["email"]; ?>
                                </div>

                            </div>

                            <div class="profile-info">

                                <div class="detail-label">
                                    Phone Number
                                </div>

                                <div class="detail-value">
                                    +91 <?php echo $user["phone"]; ?>
                                </div>

                            </div>

                            <div class="profile-info">

                                <div class="detail-label">
                                    College
                                </div>

                                <div class="detail-value">
                            <?php echo $user["college_name"]; ?>
                                </div>

                            </div>

                        </div>

                       <div class="edit-profile">

    <a href="edit_profile.php">
        Edit Profile
    </a>

</div>

                    </div>

                </div>

            </div>

        </div>

    </div>


   <?php

$sql = "SELECT p.*
        FROM interested_users_properties iup
        INNER JOIN properties p
        ON iup.property_id = p.id
        WHERE iup.user_id = $user_id";

$result = mysqli_query($conn, $sql);

?>
    <div class="my-interested-properties">

        <div class="page-container">

            <div class="container">

                <h1 class="mb-4">
                    My Interested Properties
                </h1>
                <?php

while($property = mysqli_fetch_assoc($result))
{

?>
<?php

$property_images = glob("img/properties/" . $property['id'] . "/*");

$rating = (
    $property['rating_clean'] +
    $property['rating_food'] +
    $property['rating_safety']
) / 3;

?>

<div class="property-card row property-id-<?php echo $property['id']; ?>">

    <div class="image-container col-md-4">
        <img src="<?php echo $property_images[0]; ?>" class="property-image" />
    </div>

    <div class="content-container col-md-8">

        <div class="row no-gutters justify-content-between">

            <div class="star-container" title="<?php echo round($rating,1); ?>">

                <?php

                $fullStars = floor($rating);

                for($i=1;$i<=5;$i++)
                {
                    if($i <= $fullStars)
                    {
                        echo '<i class="fas fa-star"></i>';
                    }
                    else
                    {
                        echo '<i class="far fa-star"></i>';
                    }
                }

                ?>

            </div>

            <div class="interested-container">

                <i class="fas fa-heart interested-dashboard"
                   data-property="<?php echo $property['id']; ?>">
                </i>

            </div>

        </div>

        <div class="detail-container">

            <div class="property-name">
                <?php echo $property['name']; ?>
            </div>

            <div class="property-address">
                <?php echo $property['address']; ?>
            </div>

            <div class="property-gender">
                <img src="img/<?php echo $property['gender']; ?>.png">
            </div>

        </div>

        <div class="row no-gutters">

            <div class="rent-container col-6">

                <div class="rent">
                    ₹ <?php echo number_format($property['rent']); ?>/-
                </div>

                <div class="rent-unit">
                    per month
                </div>

            </div>

            <div class="button-container col-6">

                <a href="property_detail.php?property_id=<?php echo $property['id']; ?>"
                   class="btn btn-primary">

                    View

                </a>

            </div>

        </div>

    </div>

</div>
<?php

}

?>
                               

            </div>

        </div>

    </div>
            <div class="footer">

            <div class="page-container footer-container">

                <div class="footer-cities">

                    <div class="footer-city">
                        <a href="property_list.php">PG in Delhi</a>
                    </div>

                    <div class="footer-city">
                        <a href="property_list.php">PG in Mumbai</a>
                    </div>

                    <div class="footer-city">
                        <a href="property_list.php">PG in Bangalore</a>
                    </div>

                    <div class="footer-city">
                        <a href="property_list.php">PG in Hyderabad</a>
                    </div>

                </div>

                <div class="footer-copyright">
                    © 20XX Copyright PG Life
                </div>

            </div>

        </div>

    </div>

    <script src="js/jquery.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>