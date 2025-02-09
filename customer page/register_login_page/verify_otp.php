<?php
session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
unset($_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
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
                        <h3>Verify OTP</h3>
                        <p>Enter the OTP sent to your email along with your new password.</p>

                        <?php if (!empty($errors)) : ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach ($errors as $error) : ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="backend/otp/check_otp.php" method="post">
                            <input class="form-control" type="text" name="otp" placeholder="Enter OTP" required>
                            <input class="form-control" type="password" name="new_password" placeholder="New Password" required>
                            <input class="form-control" type="password" name="confirm_password" placeholder="Confirm Password" required>
                            <div class="form-button">
                                <button id="submit" type="submit" class="ibtn">Reset Password</button>
                            </div>
                        </form>

                        <div class="other-links">
                            <a href="forget_password.php">Resend OTP</a>
                            <a href="login.php">Back to login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
