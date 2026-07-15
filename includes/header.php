<div class="header sticky-top">

   <nav class="navbar navbar-expand-md navbar-light bg-white px-3">

        <a class="navbar-brand" href="index.php">
           <img src="img/logo.png" alt="PG Life">
        </a>

        <button class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#my-navbar">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse justify-content-end" id="my-navbar">

           <ul class="navbar-nav ml-auto align-items-center">
                <?php
                //Check if user is loging or not
                if (!isset($_SESSION["user_id"])) {
                ?>

                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#signup-modal">
                            <i class="fas fa-user"></i>Signup
                        </a>
                    </li>

                    <div class="nav-vl"></div>

                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#login-modal">
                            <i class="fas fa-sign-in-alt"></i>Login
                        </a>
                    </li>

                <?php
                } else {
                ?>

                    <div class="nav-name">
                        Hi, <?php echo $_SESSION["full_name"]; ?>
                    </div>

                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-user"></i>Dashboard
                        </a>
                    </li>

                    <div class="nav-vl"></div>

                    <li class="nav-item">
                        <a class="nav-link" href="api/logout.php"
                            <i class="fas fa-sign-out-alt"></i>Logout
                        </a>
                    </li>

                <?php
                }
                ?>

            </ul>

        </div>

    </nav>

</div>