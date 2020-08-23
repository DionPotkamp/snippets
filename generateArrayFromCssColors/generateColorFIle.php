<?php
if ( $_SERVER["REQUEST_METHOD"] !== "POST" &&
    !isset($_POST['submit']) ||
      empty($_POST['submit']) ) {
    header('Location: index.html');
    exit();
}

function dd($_ = null){
    $args = func_get_args();
    echo '<code><pre>';
    foreach ($args as $arg) {
        if (is_string($arg)) var_dump(htmlentities($arg));
        var_dump($arg);
        echo '<hr>';
    }
    echo '</pre></code>';
    die();
}

function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if (empty($data) || $data === null) 
        return '';
    return $data;
}

if (empty($_POST['language']) || empty($_POST['argument'])) {
    header('Location: index.html');
}

/**
 * Use one of the folowing url's to obtain the data;
 */
$url = 'https://www.w3schools.com/colors/color_tryit.asp';
// $url = 'generated/.colorSiteBackup'; // Local

$dumpColorArray = isset($_POST['dump']) && $_POST['dump'] === 'on' ? true : false;

require_once 'app.php';

$data = [
    'language' => validate($_POST['language']),
    'argument' => validate($_POST['argument']),
];

$app = new app($url, $data);

if ($dumpColorArray) 
    dd($app->getColorsArray());

