<?php
if ( 
    !isset($_GET['file']) ||
    empty($_GET['file'])
    ) {
    header('Location: index.html');
    exit();
}

$file = $_GET['file'];

header("Content-Description: File Transfer"); 
header("Content-Type: application/octet-stream"); 
header('Content-Disposition: attachment; filename="'.basename($file).'"' );

readfile ($file);

exit();
