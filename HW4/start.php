<!DOCTYPE html>
<html>
    
    <?php
        /* CSE 154 AE
           Assignment 4
           Yu Fu
   
           start.php
           That is the logging in page for the user.
        */
        include("common.php");
        is_logged_in();         /* If is logged in, redirects to todolist.php. */
        head_tag();
    ?>

    <body>

        <?php
            top_banner();
        ?>

        <div id="main">
            <p>
                The best way to manage your tasks. <br />
                Never forget the cow (or anything else) again!
            </p>

            <p>
                Log in now to manage your to-do list. <br />
                If you do not have an account, one will be created for you.
            </p>

            <form id="loginform" action="login.php" method="post">
                <div><input name="name" type="text" size="8" autofocus="autofocus" /> <strong>User Name</strong></div>
                <div><input name="password" type="password" size="8" /> <strong>Password</strong></div>
                <div><input type="submit" value="Log in" /></div>
            </form>

            <?php
                last_login_time();
            ?>

        </div>

        <?php
            bot_banner();
        ?>

    </body>
</html>

<?php
    /* Prints the information of last login time if there exists
       previous logging in. */
    function last_login_time() {
        if (isset($_COOKIE["login_time"])) {
        ?>

            <p>
                <em>(last login from this computer was <?= $_COOKIE["login_time"] ?>)</em>
            </p>

        <?php
        }
    }
?>