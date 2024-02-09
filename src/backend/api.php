<?php
require "Controller.php";

if (isset($_POST['route'])) {
    $controller = new Controller($_POST['route'], json_decode($_POST['params']));
    $controller->action();
    $controller = null;
}
