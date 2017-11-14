<?php
/* CSE 154 AE
   Assignment 5
   Yu Fu
   
   common.php
   Contains shared PHP code or function used by multiple other files.
*/

    /* Print the head tag, including meta information,
       title, and style of all pages. */
    function head_tag() {
    ?>

        <head>
            <title>My Movie Database (MyMDb)</title>
            <meta charset="utf-8" />
            <link href="https://webster.cs.washington.edu/images/kevinbacon/favicon.png" type="image/png" rel="shortcut icon" />
            <link href="bacon.css" type="text/css" rel="stylesheet" />
        </head>

    <?php
    }

    /* Produce the top banner of pages. */
    function top_banner() {
    ?>

        <div id="banner">
            <a href="mymdb.php"><img src="https://webster.cs.washington.edu/images/kevinbacon/mymdb.png" alt="banner logo" /></a>
            My Movie Database
        </div>

    <?php
    }


    /* Produce the search all and search kevin forms. */
    function search_forms() {
    ?>

        <!-- form to search for every movie by a given actor -->
        <form action="search-all.php" method="get">
            <fieldset>
                <legend>All movies</legend>
                <div>
                    <input name="firstname" type="text" size="12" placeholder="first name" autofocus="autofocus" /> 
                    <input name="lastname" type="text" size="12" placeholder="last name" /> 
                    <input type="submit" value="go" />
                </div>
            </fieldset>
        </form>
        <!-- form to search for movies where a given actor was with Kevin Bacon -->
        <form action="search-kevin.php" method="get">
            <fieldset>
                <legend>Movies with Kevin Bacon</legend>
                <div>
                    <input name="firstname" type="text" size="12" placeholder="first name" /> 
                    <input name="lastname" type="text" size="12" placeholder="last name" /> 
                    <input type="submit" value="go" />
                </div>
            </fieldset>
        </form>

    <?php
    }

    /* Produce the bottom banner. */
    function bot_banner() {
    ?>

        <div id="w3c">
            <a href="https://webster.cs.washington.edu/validate-html.php"><img src="https://webster.cs.washington.edu/images/w3c-html.png" alt="Valid HTML5" /></a>
            <a href="https://webster.cs.washington.edu/validate-css.php"><img src="https://webster.cs.washington.edu/images/w3c-css.png" alt="Valid CSS" /></a>
        </div>

    <?php
    }

    /* Produce the top part of page. */
    function top_page() {
    ?>

        <!DOCTYPE html>
        <html>

            <?php
                head_tag();
            ?>

            <body>
                <div id="frame">

                    <?php
                        top_banner();
                    ?>

                    <div id="main">

    <?php
    }

    /* Produce the bottom part of page. */
    function bot_page() {
    ?>

                        <?php
                            search_forms();
                        ?>

                    </div> <!-- end of #main div -->
                
                    <?php
                        bot_banner();
                    ?>
                    
                </div> <!-- end of #frame div -->
            </body>
        </html>

    <?php
    }

    /* Initialize a PDO object that contains the imdb database. */
    function init_database() {
        $db = new PDO("mysql:dbname=imdb", "yf23", "SfShNKfY8F");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }

    /* Return the ID for a given actor's name in the given database.
       If more than one actors have the same name,
       the actor who has appeared in the most movies
       will be chosen. Breaking ties by choosing the actor
       with lower-numbered ID.
       Return the empty string if given name is not found.  */
    function search_aid($first_name, $last_name, $db) {
        $first_name = $db->quote("{$first_name}%");
        $last_name = $db->quote($last_name);
        $rows = $db->query("SELECT id
                              FROM actors
                             WHERE first_name LIKE $first_name
                               AND last_name = $last_name
                          ORDER BY film_count DESC, id
                             LIMIT 1");
        if ($rows->rowCount() > 0) {
            /* If not empty, return the actor id of the result. */
            return $rows->fetch()["id"];
        } else {
            /* Return empty String if given name not found. */
            return "";
        }
    }

    /* Generate a table contains the movie information
       from the given rows selected from the database. */
    function generate_table($rows, $table_cap) {
    ?>

        <table>
            <caption><?= $table_cap ?></caption>
            <tr>
                <th><strong>#</strong></th>
                <th class="title-col"><strong>Title</strong></th>
                <th><strong>Year</strong></th>
            </tr>

    <?php
        for ($i = 0; $i < $rows->rowCount(); $i++) { 
            $row = $rows->fetch();
    ?>

            <tr>
                <td><?= $i+1 ?></td>
                <td class="title-col"><?= $row["name"] ?></td>
                <td><?= $row["year"] ?></td>
            </tr>

    <?php
        }
    ?>

        </table>

    <?php
    }
?>
