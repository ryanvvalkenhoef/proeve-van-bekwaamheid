<?php
require_once 'includes/autoloader.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='resources/css/bootstrap.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="resources/css/custom.css">
    <title>Login</title>
</head>

<body>
    <?php
        $authObj = new Auth(); // Initiate Auth class
        // Check condition of session and direct to index.php if present
        if (isset($_SESSION["user_login"])) header("Location: /index.php");
    ?>
    <form method="post" enctype="multipart/form-data" class="form-horizontal">
        <!-- Login form -->
        <div class="form-group">
            <label class="col-sm-3 control-label">Username or Email</label>
            <div class="col-sm-6">
                <input type="text" name="username_email" class="form-control" placeholder="Username or email" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Password</label>
            <div class="col-sm-6">
                <input type="password" name="password" class="form-control" placeholder="Password" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9 m-t-15">
                <input type="submit" name="btnlogin" class="btn btn-success" value="Login">
            </div>
        </div>
    </form>
    <?php
        // Optioneel: Check of het inlog formulier voor administratoren verzonden werd. Gebruiker inloggen
        if (isset($_REQUEST['btnlogin'])) echo $authObj->login();
        // Show messages if present
        if (isset($errorMsg) || isset($loginMsg)) echo $authObj->showMessages();
    ?>
</body>

</html>