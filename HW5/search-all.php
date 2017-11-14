<?php
/* CSE 154 AE
   Assignment 5
   Yu Fu
   
   search-all.php
   Search and print the result of all movies of the given actor.
*/

    include("common.php");

    $db = init_database();

    $first_name = $_GET["firstname"];
    $last_name = $_GET["lastname"];
    $actor_id = search_aid($first_name, $last_name, $db);

    top_page();

    if (strlen($actor_id) > 0) {
        /* There is an actor with given name */
        /* Query to find a complete list of movies in which
           the given actor has performed. */
        $rows = $db->query("SELECT m.name, m.year 
                              FROM movies m
                              JOIN roles r ON r.movie_id = m.id
                             WHERE r.actor_id = $actor_id
                          ORDER BY m.year DESC, m.name");
    ?>

        <h1>Results for <?= $first_name ?> <?= $last_name ?></h1>

    <?php
        generate_table($rows, "All films");
    } else {
    ?>

        <p>Actor <?= $first_name ?> <?= $last_name ?> not found.</p>

    <?
    }
    bot_page();
?>