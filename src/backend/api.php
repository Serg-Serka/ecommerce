<?php
require 'Database.php';
$db = Database::getInstance();

$categories = $db->getAllCategories();

echo json_encode($categories);

