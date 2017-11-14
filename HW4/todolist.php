<!DOCTYPE html>
<html>
    
    <?php
        /* CSE 154 AE
           Assignment 4
           Yu Fu
   
           common.php
           This is the to-do list page for the user.
*       */
        include("common.php");
        is_not_logged_in();                /* If is not logged in, redirects to start.php. */

        $uname = $_SESSION["uname"];
        $filename = "todo_{$uname}.txt";
        if (!file_exists($filename)) {
            touch($filename);
        }
        $items = file($filename);

        head_tag();
    ?>

    <body>

        <?php
            top_banner();
        ?>

        <div id="main">
            <h2><?= $uname ?>'s To-Do List</h2>
            
            <?php
                print_todolist($items);
            ?>

            <div>
                <a href="logout.php"><strong>Log Out</strong></a>
                <?php
                    logged_in_since();
                ?>
            </div>

        </div>

        <?php
            bot_banner();
        ?>

    </body>
</html>

<?php  
    /* Print the information of last login time */
    function logged_in_since() {
        if (isset($_COOKIE["login_time"])) {
        ?>

            <em>(logged in since <?= $_COOKIE["login_time"] ?>)</em>

        <?php
        }
    }

    /* Print the to-do list. Items are in the given array of strings. 
       Each item is followed by a "Delete" button.
       After all the items, there is an "Add" button. */
    function print_todolist($items) {
    ?>

        <ul id="todolist">

    <?php

        foreach ($items as $item) {
            $item = htmlspecialchars(trim($item));
        ?>

            <li>
                <form action="submit.php" method="post">
                    <input type="hidden" name="action" value="delete" />
                    <input type="hidden" name="index" value="1" />
                    <input type="submit" value="Delete" />
                </form>
                <?= $item ?>
            </li>

        <?php
        }
        ?>  

            <li>
                <form action="submit.php" method="post">
                    <input type="hidden" name="action" value="add" />
                    <input name="item" type="text" size="25" autofocus="autofocus" />
                    <input type="submit" value="Add" />
                </form>
            </li>
        </ul>

    <?php
    }
?>
