<?php
session_start();
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']); // Clear session errors after retrieving them
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="css/iofrm-style.css">
    <link rel="stylesheet" type="text/css" href="css/iofrm-theme3.css">
</head>
<body>
    <div class="form-body">
        <div class="iofrm-layout">
            <div class="img-holder">
                <div class="bg"></div>
                <div class="info-holder"></div>
            </div>
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                        <h3>Register to Your Account</h3>
                        <p>Access your account to unlock more features and manage your plumbing and electrical needs.</p>
                        <div class="page-links">
                            <a href="login.php">Login</a><a href="register.php" class="active">Register</a>
                        </div>

                        <?php if (!empty($errors['user_exist'])): ?>
                            <div class="error-main">
                                <p><?php echo $errors['user_exist']; ?></p>
                            </div>
                        <?php endif; ?>

                        <form action="backend/auth.php" method="POST">
                            <input type="hidden" name="register" value="1">
                            <input class="form-control" type="text" name="full_name" placeholder="Full Name" required>
                            <?php if (!empty($errors['name'])): ?>
                                <div class="error">
                                    <p><?php echo $errors['name']; ?></p>
                                </div>
                            <?php endif; ?>

                            <input class="form-control" type="email" name="email" placeholder="E-mail Address" required>
                            <?php if (!empty($errors['email'])): ?>
                                <div class="error">
                                    <p><?php echo $errors['email']; ?></p>
                                </div>
                            <?php endif; ?>

                            <input class="form-control" type="password" name="password" placeholder="Password" required>
                            <?php if (!empty($errors['password'])): ?>
                                <div class="error">
                                    <p><?php echo $errors['password']; ?></p>
                                </div>
                            <?php endif; ?>

                            <input class="form-control" type="password" name="confirm_password" placeholder="Confirm Password" required>
                            <?php if (!empty($errors['confirm_password'])): ?>
                                <div class="error">
                                    <p><?php echo $errors['confirm_password']; ?></p>
                                </div>
                            <?php endif; ?>

                            <div class="form-button">
                                <button id="submit" type="submit" class="ibtn">Register</button>
                            </div>
                        </form>                        
                        <div class="other-links">
                            <span>Or register with</span>
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-google"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
