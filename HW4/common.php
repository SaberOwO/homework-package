<?php

/* CSE 154 AE
   Assignment 4
   Yu Fu
   
   common.php
   Contains shared PHP code or function used by multiple other files.
*/

    /* Print the head tag, including meta information,
       title, and style of both pages. */
    function head_tag() {
    ?>

        <head>
            <meta charset="utf-8" />
            <title>Remember the Cow</title>
            <link href="https://webster.cs.washington.edu/css/cow-provided.css" type="text/css" rel="stylesheet" />
            <link href="https://webster.cs.washington.edu/images/todolist/favicon.ico" type="image/ico" rel="shortcut icon" />
        </head>
    
    <?php
    }

    /* Create the top banner. */
    function top_banner() {
    ?>

        <div class="headfoot">
            <h1>
                <img src="https://webster.cs.washington.edu/images/todolist/logo.gif" alt="logo" />
                Remember<br />the Cow
            </h1>
        </div>

    <?php
    }

    /* Create the bottom banner. */
    function bot_banner() {
    ?>

        <div class="headfoot">
            <p>
                &quot;Remember The Cow is nice, but it's a total copy of another site.&quot; - PCWorld<br />
                All pages and content &copy; Copyright CowPie Inc.
            </p>

            <div id="w3c">
                <a href="https://webster.cs.washington.edu/validate-html.php">
                    <img src="https://webster.cs.washington.edu/images/w3c-html.png" alt="Valid HTML" /></a>
                <a href="https://webster.cs.washington.edu/validate-css.php">
                    <img src="https://webster.cs.washington.edu/images/w3c-css.png" alt="Valid CSS" /></a>
            </div>
        </div>

    <?php
    }

    /* Redirect to start.php if the user is not logged in. */
    function is_not_logged_in() {
        session_start();
        if (!isset($_SESSION["uname"])) {
            header("Location: start.php");
            die();
        }
    }

    /* Redirect to todolist.php if the user is logged in. */
    function is_logged_in() {
        session_start();
        if (isset($_SESSION["uname"])) {
            header("Location: todolist.php");
            die();
        }
    }

    /* Append a given string in to given file.
       If the file does not exist, a file will be created. */
    function add_item_to_file($filename, $input) {
        $myfile = fopen($filename, "a");
        fwrite($myfile, $input);
        fclose($myfile);
    }
?>