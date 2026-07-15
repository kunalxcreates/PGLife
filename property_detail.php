<?php
session_start();
require "includes/database_connect.php";

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;

if (!isset($_GET['property_id'])) {
    header("Location: index.php");
    exit;
}

$property_id = $_GET['property_id'];

$sql = "SELECT * FROM properties WHERE id='$property_id'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Something went wrong!");
}
$property = mysqli_fetch_assoc($result);

$property_images = glob("img/properties/" . $property['id'] . "/*");
$sql = "SELECT a.*
        FROM amenities a
        INNER JOIN properties_amenities pa
        ON a.id = pa.amenity_id
        WHERE pa.property_id = $property_id";

$result = mysqli_query($conn, $sql);

$amenities = mysqli_fetch_all($result, MYSQLI_ASSOC);
$sql = "SELECT * FROM testimonials
        WHERE property_id = $property_id";

$result = mysqli_query($conn, $sql);

$testimonials = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (!$property) {
    die("Property not found!");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $property['name']; ?> | PG Life</title>

    <?php include "includes/head_links.php"; ?>

    <link href="css/property_detail.css" rel="stylesheet">

</head>

<body>
    <?php
include "includes/header.php";
?>
<?php
if (isset($_SESSION["booking_success"])) {
    unset($_SESSION["booking_success"]);
?>
<script>
alert("🎉 Booking Request Received!\n\nThank you for choosing PG Life.\n\nOur team will contact you shortly on your registered email.\n\nPlease ensure your email address is correct. You can update it anytime from Dashboard → Edit Profile.");
</script>
<?php
}
?>
<?php
if (isset($_SESSION["booking_exists"])) {
    unset($_SESSION["booking_exists"]);
?>
<script>
alert("You have already requested booking for this property.");
</script>
<?php
}
?>
    <div id="loading">
    </div>

   <nav aria-label="breadcrumb">
    <ol class="breadcrumb py-2">
        <li class="breadcrumb-item">
            <a href="index.php">Home</a>
        </li>

        <li class="breadcrumb-item active" aria-current="page">
            <?php echo $property['name']; ?>
        </li>
    </ol>
</nav>

    <div id="property-images" class="carousel slide" data-ride="carousel">
       <ol class="carousel-indicators">

<?php foreach ($property_images as $index => $image) { ?>

<li data-target="#property-images"
    data-slide-to="<?php echo $index; ?>"
    class="<?php if($index == 0) echo 'active'; ?>">
</li>

<?php } ?>

</ol>
        <div class="carousel-inner">

<?php foreach ($property_images as $index => $image) { ?>

<div class="carousel-item <?php if($index == 0) echo 'active'; ?>">
    <img class="d-block w-100" src="<?php echo $image; ?>" alt="slide">
</div>

<?php } ?>

</div>
        <a class="carousel-control-prev" href="#property-images" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#property-images" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <div class="property-summary page-container">
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
$halfStar = ($rating - $fullStars >= 0.5);

for($i=1; $i<=$fullStars; $i++){
    echo '<i class="fas fa-star"></i>';
}

if($halfStar){
    echo '<i class="fas fa-star-half-alt"></i>';
    $fullStars++;
}

while($fullStars < 5){
    echo '<i class="far fa-star"></i>';
    $fullStars++;
}
?>

</div>

            <div class="interested-container">
               <i id="interested-button-<?php echo $property_id; ?>"
   class="is-interested-image far fa-heart interested-button"
   data-property="<?php echo $property_id; ?>">
</i>
                <div class="interested-text">
                   <span id="interested-user-count-<?php echo $property_id; ?>" class="interested-user-count">
    <?php
    $sql = "SELECT COUNT(*) AS interested_users
            FROM interested_users_properties
            WHERE property_id=" . $property['id'];

    $result = mysqli_query($conn, $sql);
    $interested_users = mysqli_fetch_assoc($result);

    echo $interested_users['interested_users'];
    ?>
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
             <?php if($user_id == NULL) { ?>

<a href="#" class="btn btn-primary"
   data-toggle="modal"
   data-target="#login-modal">
    Book Now
</a>

<?php } else { ?>

<a href="api/book_property.php?property_id=<?php echo $property_id; ?>"
   class="btn btn-primary">
    Book Now
</a>

<?php } ?>
            </div>
        </div>
    </div>

    <div class="property-amenities">
    <div class="page-container">
        <h1>Amenities</h1>

        <div class="row">

<?php

$types = array("Building", "Common Area", "Bedroom", "Washroom");

foreach($types as $type)
{
?>

<div class="col-md-3">
    <h5><?php echo $type; ?></h5>

<?php
foreach($amenities as $amenity)
{
    if($amenity['type'] == $type)
    {
?>

<div class="amenity-container">
    <img src="img/amenities/<?php echo $amenity['icon']; ?>.svg">
    <span><?php echo $amenity['name']; ?></span>
</div>

<?php
    }
}
?>

</div>

<?php
}
?>

        </div>
    </div>
</div>
    <div class="property-about page-container">
        <h1>About the Property</h1>
       <p>
    <?php echo $property['description']; ?>
</p>
    </div>

    <div class="property-rating">
        <div class="page-container">
            <h1>Property Rating</h1>
            <div class="row align-items-center justify-content-between">
                <div class="col-md-6">
                    <div class="rating-criteria row">
                        <div class="col-6">
                            <i class="rating-criteria-icon fas fa-broom"></i>
                            <span class="rating-criteria-text">Cleanliness</span>
                        </div>
                        <div class="rating-criteria-star-container col-6" title="4.3">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>

                    <div class="rating-criteria row">
                        <div class="col-6">
                            <i class="rating-criteria-icon fas fa-utensils"></i>
                            <span class="rating-criteria-text">Food Quality</span>
                        </div>
                        <div class="rating-criteria-star-container col-6" title="3.4">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                        </div>
                    </div>

                    <div class="rating-criteria row">
                        <div class="col-6">
                            <i class="rating-criteria-icon fa fa-lock"></i>
                            <span class="rating-criteria-text">Safety</span>
                        </div>
                        <div class="rating-criteria-star-container col-6" title="4.8">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="rating-circle">
                        <div class="total-rating">4.2</div>
                        <div class="rating-circle-star-container">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="property-testimonials page-container">

    <h1>What people say</h1>

    <?php foreach($testimonials as $testimonial){ ?>

    <div class="testimonial-block">

        <div class="testimonial-image-container">
            <img class="testimonial-img" src="img/man.png">
        </div>

        <div class="testimonial-text">
            <i class="fa fa-quote-left"></i>
            <p><?php echo $testimonial['content']; ?></p>
        </div>

        <div class="testimonial-name">
            - <?php echo $testimonial['user_name']; ?>
        </div>

    </div>

    <?php } ?>

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
    <script src="js/toggle_interested.js"></script>
</body>

</html>
