<?php 
// This code is for redirecting to different pages if the credentials are correct.
session_start();
include "config.php";

if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
    // Admin
    if ($_SESSION['role'] == 'admin') {
        header("Location: home.php");
        exit();
    }
    // Student
    else if ($_SESSION['role'] == 'student') { 
        header("Location: pages/student.php");    
        exit();
    }
    // Restaurant
    else if ($_SESSION['role'] == 'restaurant') { 
        header("Location:restaurant.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>