<?php
require 'Vhelper.php';

if (!isset($_SESSION['role'])) {
    header("Location: ../pages/login.php");
    exit();
}elseif ($_SESSION['role'] == 'user') {
        header("Location: ../pages/tenant.php");
        exit();
}else{

}
