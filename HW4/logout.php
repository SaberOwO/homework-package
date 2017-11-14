<?php
    /* CSE 154 AE
       Assignment 4
       Yu Fu
       
       logout.php
       Log out the current user and finish session.
       Redirects to start.php.
    */

    include("common.php");
    is_not_logged_in();

    session_destroy();
    session_regenerate_id(TRUE);

    header("Location: start.php");
?>