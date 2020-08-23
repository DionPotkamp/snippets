<?php
$languagesDir = array_diff(scandir(__DIR__.'/languages'), array('..', '.', '.htaccess', 'language.php', 'languageInterface.php'));
$languages = array();

foreach ($languagesDir as $language) 
    array_push($languages, str_replace('.php', '', $language));

if(isset($_GET['lang']) && !empty($_GET['lang'])) {
    $lang = $_GET['lang'];
    
    $req = require_once 'languages/' . $lang . '.php';

    echo json_encode((new $lang())->getAllowedArguments());
} else {
    echo json_encode($languages);
}
