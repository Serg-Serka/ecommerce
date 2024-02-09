<?php
require "Controller.php";

if (isset($_POST['route'])) {
    $controller = new Controller($_POST['route']);
    $controller->action();
    $controller = null;
}
