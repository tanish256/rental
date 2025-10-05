<?php
require 'Vhelper.php';

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}elseif ($_SESSION['role'] == 'user') {
        header("Location: tenant.php");
        exit();
}elseif ($_SESSION['role'] == 'admin') {
        header("Location: dashboard.php");
        exit();
}else{

}
