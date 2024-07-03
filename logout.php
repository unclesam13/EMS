<?php
require 'classes/Session.php';

Session::start();
Session::destroy();
header('Location: login.php');
?>
