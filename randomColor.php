<?php 
    header("Refresh:.7");
    $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)]
?>

<html lang="en">

<head>
    <title>Random Color Generator</title>
    <meta charset="UTF-8">
    <meta name="description" content="Random Color Generator">
    <meta name="keywords" content="HTML,CSS,PHP,random,color,generator">
    <meta name="author" content="Dion Potkamp">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="background-color:<?= $color; ?>;">
</body>

</html>
