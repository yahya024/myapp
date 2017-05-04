<?php
session_start();
if(isset($_SESSION['admin_id'])){
    require_once 'include/init.php';
    
} else {
    header('Location: index.php');
}

