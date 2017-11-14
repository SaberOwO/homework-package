<?php
/* CSE 154 AE
   Assignment 5
   Yu Fu
   
   search-kevin.php
   Search and print the result of all movies of the given actor
   performed with Kevin Bacon.
*/
   
    include("common.php");

    $db = init_database();

    $first_name = $_GET["firstname"];
    $last_name = $_GET["lastname"];
    $actor_id = search_aid($first_name, $last_name, $db);

    top_page();

    if (strlen($actor_id) > 0) {
        /* There is an actor with given name */
        /* Query to find all movies in which the actor performed 
           with Kevin Bacon. */
        $rows = $db->query("SELECT m.name, m.year 
                              FROM movies m
                              JOIN roles r1 ON r1.movie_id = m.id
                              JOIN roles r2 ON r2.movie_id = m.id
                              JOIN actors a ON a.id = r1.actor_id
                             WHERE a.first_name = 'Kevin'
                               AND a.last_name = 'Bacon'
                               AND r2.actor_id = $actor_id
                          ORDER BY m.year DESC, m.name");
        if ($rows->rowCount() > 0) {
        ?>
            
            <h1>Results for <?= $first_name ?> <?= $last_name ?></h1>

        <?php
            generate_table($rows, "Films with {$first_name} {$last_name} and Kevin Bacon");
        } else {
        ?>

            <p><?= $first_name ?> <?= $last_name ?> wasn't in any films with Kevin Bacon.</p>

        <?php
        }
    } else {
    ?>

        <p>Actor <?= $first_name ?> <?= $last_name ?> not found.</p>

    <?
    }
    bot_page();
?>