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
    <title>Forgot Password</title>
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
                        <h3>Forgot Password</h3>
                        <p>Enter your email to receive an OTP for resetting your password.</p>

                        <?php if (!empty($errors)) : ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach ($errors as $error) : ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="backend/otp/send_otp.php" method="post">
                            <input class="form-control" type="email" name="email" placeholder="Enter your email" required>
                            <div class="form-button">
                                <button id="submit" type="submit" class="ibtn">Send OTP</button>
                            </div>
                        </form>

                        <div class="other-links">
                            <a href="login.php">Back to login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
