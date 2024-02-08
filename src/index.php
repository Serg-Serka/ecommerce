<?php
// create Database class instance, get categories, display them
require "backend/Database.php";

$db = Database::getInstance();

if (!$db->connect()) {
    echo $db->getMysqlError();
    die;
}

require 'frontend/index.html';