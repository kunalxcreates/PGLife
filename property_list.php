 <?php
session_start();
require "includes/database_connect.php";

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;

if (!isset($_GET['city'])) {
    header("Location: index.php");
    exit;
}

$city_name = trim($_GET['city']);

$sql = "SELECT * FROM cities WHERE LOWER(name)=LOWER('$city_name')";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Something went wrong!");
}

$city = mysqli_fetch_assoc($result);

if (!$city) {
    die("Sorry! We do not have any PG listed in this city.");
}

$city_id = $city['id'];

$order = "";

if (isset($_GET['sort'])) {

    if ($_GET['sort'] == "high") {
        $order = " ORDER BY rent DESC";
    }

    elseif ($_GET['sort'] == "low") {
        $order = " ORDER BY rent ASC";
    }
}

$sql = "SELECT * FROM properties WHERE city_id='$city_id'" . $order;
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Something went wrong!");
}

$properties = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Best PG's in <?php echo $city_name; ?> | PG Life</title>

    <?php include "includes/head_links.php"; ?>

    <link href="css/property_list.css" rel="stylesheet">

</head>

<body>

<?php
include "includes/header.php";
?>
<div id="loading">
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb py-2">
            <li class="breadcrumb-item">
                <a href="index.php">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <?php echo $city_name; ?>
            </li>
        </ol>
    </nav>

    <div class="page-container">
        <div class="filter-bar row justify-content-around">
            <div class="col-auto" data-toggle="modal" data-target="#filter-modal">
                <img src="img/filter.png" alt="filter" />
                <span>Filter</span>
            </div>
            <div class="col-auto">
    <a href="property_list.php?city=<?php echo urlencode($city_name); ?>&sort=high" style="text-decoration:none;color:inherit;">
        <img src="img/desc.png" alt="sort-desc" />
        <span>Highest rent first</span>
    </a>
</div>
            <div class="col-auto">
    <a href="property_list.php?city=<?php echo urlencode($city_name); ?>&sort=low" style="text-decoration:none;color:inherit;">
        <img src="img/asc.png" alt="sort-asc" />
        <span>Lowest rent first</span>
    </a>
</div>
        </div>
<?php
foreach ($properties as $property) {

    $property_images = glob("img/properties/" . $property['id'] . "/*");

    $sql = "SELECT COUNT(*) AS interested_users
            FROM interested_users_properties
            WHERE property_id=" . $property['id'];

    $result = mysqli_query($conn, $sql);
    $interested_users = mysqli_fetch_assoc($result);
?>
        <div class="property-card row">
            <div class="image-container col-md-4">
                <img src="<?php echo $property_images[0]; ?>" />
            </div>
            <div class="content-container col-md-8">
                <div class="row no-gutters justify-content-between">
                    <?php
$rating = (
    $property['rating_clean'] +
    $property['rating_food'] +
    $property['rating_safety']
) / 3;
?>

<div class="star-container" title="<?php echo round($rating, 1); ?>">

<?php
$fullStars = floor($rating);

for ($i = 1; $i <= 5; $i++) {

    if ($i <= $fullStars) {
        echo '<i class="fas fa-star"></i>';
    } else {
        echo '<i class="far fa-star"></i>';
    }
}
?>

</div>
                    <div class="interested-container">
                      <i id="interested-button-<?php echo $property['id']; ?>"
   class="is-interested-image far fa-heart interested-button"
   data-property="<?php echo $property['id']; ?>">
</i>
    <div class="interested-text">
    <span id="interested-user-count-<?php echo $property['id']; ?>">
        <?php echo $interested_users['interested_users']; ?>
    </span> interested
</div>
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
                       <img src="img/<?php echo $property['gender']; ?>.png" />
                    </div>
                </div>
                <div class="row no-gutters">
                    <div class="rent-container col-6">
                        <div class="rent">
    ₹ <?php echo number_format($property['rent']); ?>/-
</div>
                        <div class="rent-unit">per month</div>
                    </div>
                    <div class="button-container col-6">
                        <a href="property_detail.php?property_id=<?php echo $property['id']; ?>" class="btn btn-primary">
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
    <div class="modal fade" id="filter-modal" tabindex="-1" role="dialog" aria-labelledby="filter-heading" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="filter-heading">Filters</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <h5>Gender</h5>
                    <hr />
                    <div>
                        <button class="btn btn-outline-dark btn-active">
                            No Filter
                        </button>
                        <button class="btn btn-outline-dark">
                            <i class="fas fa-venus-mars"></i>Unisex
                        </button>
                        <button class="btn btn-outline-dark">
                            <i class="fas fa-mars"></i>Male
                        </button>
                        <button class="btn btn-outline-dark">
                            <i class="fas fa-venus"></i>Female
                        </button>
                    </div>
                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-success">Okay</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="signup-modal" tabindex="-1" role="dialog" aria-labelledby="signup-heading" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="signup-heading">Signup with PGLife</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="signup-form" class="form" role="form">
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" name="full_name" placeholder="Full Name" maxlength="30" required>
                        </div>

                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-phone-alt"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" name="phone" placeholder="Phone Number" maxlength="10" minlength="10" required>
                        </div>

                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                            </div>
                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                        </div>

                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                            <input type="password" class="form-control" name="password" placeholder="Password" minlength="6" required>
                        </div>

                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-university"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" name="college_name" placeholder="College Name" maxlength="150" required>
                        </div>

                        <div class="form-group">
                            <span>I'm a</span>
                            <input type="radio" class="ml-3" id="gender-male" name="gender" value="male" /> Male
                            <label for="gender-male">
                            </label>
                            <input type="radio" class="ml-3" id="gender-female" name="gender" value="female" />
                            <label for="gender-female">
                                Female
                            </label>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-primary">Create Account</button>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <span>Already have an account?
                        <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#login-modal">Login</a>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-heading" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="login-heading">Login with PGLife</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="login-form" class="form" role="form">
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>
                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                        </div>

                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                            <input type="password" class="form-control" name="password" placeholder="Password" minlength="6" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-primary">Login</button>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <span>
                        <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#signup-modal">Click here</a>
                        to register a new account
                    </span>
                </div>
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
            <div class="footer-copyright">© 20XX Copyright PG Life </div>
        </div>
    </div>

  <script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/toggle_interested.js"></script>
</body>

</html>

