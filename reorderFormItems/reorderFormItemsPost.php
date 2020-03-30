<?php
require __DIR__ . '/reorderFormItemsController.php';
use controller\reorderFormItemsController as reorderController;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reorder = new reorderController();
    $reorder->saveSortOrder($_POST);
} else {
    header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/reorderFormItems.php?message=warning');
    die();
}