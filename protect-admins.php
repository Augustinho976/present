<?php
//lgin other than admin then can't access protected pages and redirects to sell.php

if (isset($_SESSION["admin"]) and $_SESSION["admin"] == 0){
    header("location:sell.php");
    //checking if session does not exist
}