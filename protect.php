<?php
//session_start();
//if (!isset($_SESSION["logged_in"])){
//    header("location:login.php");
//    //checking if session does not exist
//}
session_start();
if (!isset($_SESSION["id"])){
    header("location:login.php");
    //checking if session does not exist
}