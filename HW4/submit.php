<?php
    
    /* CSE 154 AE
       Assignment 4
       Yu Fu
       
       submit.php
       Process the add and delete actions for the todo list
       by editing the file contains the todo list.
       Redirects to todolist.php when finished.
    */
    include("common.php");
    is_not_logged_in();

    $uname = $_SESSION["uname"];
    $filename = "todo_{$uname}.txt";
    $action = $_POST["action"];

    if (strcmp($action, "add") == 0) {
        /* Add */
        if (isset($_POST["item"])) {
            $item = "{$_POST["item"]}\n";
            add_item_to_file($filename, $item);
        } else {
            die("Missing parameter to add.");
        }
    } elseif (strcmp($action, "delete") == 0) {
        /* Delete */
        if (isset($_POST["index"])) {
            $index = $_POST["index"];
            $items = file($filename);
            if ($index >= sizeof($items)) {
                die("IndexOutOfBoundException");
            }
            unset($items[$index]);
            unlink($filename);
            foreach ($items as $item) {
                add_item_to_file($filename, $item);
            }
        } else {
            die("Missing index parameter");
        }
    } else {
        /* Missing/Wrong parameter */
        die("Missing or Wrong action parameter.");
    }

    header("Location: todolist.php");

?>