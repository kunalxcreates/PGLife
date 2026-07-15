<div class="modal fade"
     id="signup-modal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="signup-heading"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="signup-heading">
                    Signup with PGLife
                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <form id="signup-form" method="post" action="api/signup_submit.php">

                    <div class="input-group form-group">

                        <div class="input-group-prepend">

                            <span class="input-group-text">

                                <i class="fas fa-user"></i>

                            </span>

                        </div>

                        <input
                            type="text"
                            class="form-control"
                            name="full_name"
                            placeholder="Full Name"
                            required>

                    </div>

                    <div class="input-group form-group">

                        <div class="input-group-prepend">

                            <span class="input-group-text">

                                <i class="fas fa-phone-alt"></i>

                            </span>

                        </div>

                        <input
                            type="text"
                            class="form-control"
                            name="phone"
                            placeholder="Phone Number"
                            required>

                    </div>

                    <div class="input-group form-group">

                        <div class="input-group-prepend">

                            <span class="input-group-text">

                                <i class="fas fa-envelope"></i>

                            </span>

                        </div>

                        <input
                            type="email"
                            class="form-control"
                            name="email"
                            placeholder="Email"
                            required>

                    </div>

                    <div class="input-group form-group">

                        <div class="input-group-prepend">

                            <span class="input-group-text">

                                <i class="fas fa-lock"></i>

                            </span>

                        </div>

                        <input
                            type="password"
                            class="form-control"
                            name="password"
                            placeholder="Password"
                            minlength="6"
                            required>

                    </div>

                    <div class="input-group form-group">

                        <div class="input-group-prepend">

                            <span class="input-group-text">

                                <i class="fas fa-university"></i>

                            </span>

                        </div>

                        <input
                            type="text"
                            class="form-control"
                            name="college"
                            placeholder="College Name"
                            required>

                    </div>

                    <div class="form-group">

                        <span>I'm a</span>

                        <input
                            type="radio"
                            id="gender-male"
                            name="gender"
                            value="male"
                            class="ml-3">

                        <label for="gender-male">
                            Male
                        </label>

                        <input
                            type="radio"
                            id="gender-female"
                            name="gender"
                            value="female"
                            class="ml-3">

                        <label for="gender-female">
                            Female
                        </label>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary btn-block">

                        Create Account

                    </button>

                </form>

            </div>

            <div class="modal-footer">

                <span>

                    Already have an account?

                    <a href="#"
                       data-dismiss="modal"
                       data-toggle="modal"
                       data-target="#login-modal">

                        Login

                    </a>

                </span>

            </div>

        </div>

    </div>

</div>