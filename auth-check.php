<?php
require 'Vhelper.php';
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}elseif ($_SESSION['role'] !== 'admin' && $_SESSION['role'] == 'user') {
        header("Location: tenant.php");
        exit();
}else{

}
