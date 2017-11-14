<?php
    /* CSE 154 AE
       Assignment 4
       Yu Fu
   
       login.php
       Complete the login process.
       Create the account if not exist.
    */

    include("common.php");
    is_logged_in();

    $uname = $_POST["name"];
    $password = $_POST["password"];
    $accounts = read_existing_accounts();

    if (array_key_exists($uname, $accounts)) {
        /* Exist account */
        if (strcmp($password, $accounts[$uname]) == 0) {
            /* Correct password: LOG-IN! */
            log_in($uname);
        }
    } else {
        /* Create account */
        /* 3-8 chars long, begin with lowercase letter,
           consist entriely of lowercase letters and numbers. */
        $uname_pattern = "/^[a-z][a-z0-9]{2,7}$/";

        /* 6-12 chars long, begin with number,
           end with any char that is not a letter or number. */
        $pwd_pattern = "/^[0-9].{4,10}[^0-9a-zA-Z]$/";

        if (preg_match($uname_pattern, $uname) && 
            preg_match($pwd_pattern, $password)) {
            create_account($uname, $password);
            log_in($uname);
        }
    }

    re_login();    /* Wrong password or invalid input. */

    /* Read usernames and corresponding passwords from users.txt.
       Return an array with username => password pairs.
       If users.txt does not exist, return empty array. */
    function read_existing_accounts() {
        if (file_exists("users.txt")) {
            $accounts_temp = file("users.txt");
            $accounts = [];
            foreach ($accounts_temp as $account) {
                list($account_uname, $account_pwd) = explode(":", trim($account));
                $accounts[$account_uname] = $account_pwd;
            }
            return $accounts;
        }
        return array();
    }

    /* Record username into session.
       Record login time in to cookie, expires in 7 days.
       And redirects to todolist.php. */
    function log_in($uname) {
        $_SESSION["uname"] = $uname;
        setcookie("login_time", date("D y M d, g:i:s a"), time() + (86400 * 30), "/");
        header("Location: todolist.php");
        die();
    }

    /* Create new account based on given username and password. */
    function create_account($uname, $pwd) {
        $account_info = "{$uname}:{$pwd}\n";
        add_item_to_file("users.txt", $account_info);
    }

    /* Redirects to start.php if wrong password,
       or invalid input when creating new account. */
    function re_login() {
        header("Location: start.php");
        die();
    }
?>