<?php


ob_start();
session_start();
var_dump(isset($_SESSION['admin']));