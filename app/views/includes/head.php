<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta name='description' content='Gerrit Rietveld College'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME . ' - ' . $data['title']; ?></title>
    <link rel="stylesheet" href=<?php echo URLROOT . "/public/css/bootstrap.min.css"?>>
    <link rel="stylesheet" href=<?php echo URLROOT . "/public/css/style.css" ?>>
    <?php if ($data['title'] == 'Home'): ?>
        <link rel="stylesheet" href=<?php echo URLROOT . "/public/css/homepage.css"?>>
    <?php elseif ($data['title'] == 'Keuzemodule overzicht'): ?>
        <link rel="stylesheet" href=<?php echo URLROOT . "/public/css/keuzemodule-overzicht.css"?>>
    <?php elseif ($data['title'] == 'Keuzemodule'): ?>
        <link rel="stylesheet" href=<?php echo URLROOT . "/public/css/keuzemodule.css"?>>
    <?php elseif ($data['title'] == 'Inschrijven'): ?>
        <link rel="stylesheet" href=<?php echo URLROOT . "/public/css/inschrijven.css"?>>
    <?php endif; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
    
