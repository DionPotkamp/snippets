<?php
if ( 
    !isset($_POST['submit']) ||
    empty($_POST['submit'])
) {
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

/**
 * Use one of the folowing url's to obtain the data;
 */
$url = 'https://www.w3schools.com/colors/color_tryit.asp';
// $url = 'generated/.colorSiteBackup'; // Local

$dumpColorArray = isset($_POST['dump']) && $_POST['dump'] === 'on' ? true : false ;

/**
 * To generate a file with all the colors, fill in one of the possible languages.
 * Some languages have options, these will be inside the brackets.
 * Possible languages: php, js, css, python(dictionairy, variables)
 * 
 */

require_once 'app.php';

$app = new app($url, $_POST);

if ($dumpColorArray) 
    dd($app->getColorsArray());

