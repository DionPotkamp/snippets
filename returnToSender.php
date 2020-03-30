<?php
// Prevent a script from directly being called from the browser
// The script must be included

if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    header('Location: /');
    die();
}
?>

<html lang="en">

<head>
    <title>Return to Sender</title>
    <meta charset="UTF-8">
    <meta name="description" content="Return to Sender">
    <meta name="keywords" content="HTML,PHP,return,sender">
    <meta name="author" content="Dion Potkamp">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
</body>

</html>
