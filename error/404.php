<?php
$error = $_SERVER['REDIRECT_STATUS'];

$errorTitle = '';
$errorMsg = '';

// Handle 404 Error
if ($error == 404) {
    $errorTitle = '404 Page Not Found';
    $errorMsg = 'The document/file requested was not found on this server.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='resources/css/bootstrap.min.css' rel='stylesheet'>
    <title>Error 404</title>
</head>
<body>
    <div class="jumbotron text-center" style="margin-bottom:0">
        <h1><?php echo $errorTitle; ?></h1>
        <h5><?php echo $errorMsg; ?></h5>
    </div>
</body>
</html>