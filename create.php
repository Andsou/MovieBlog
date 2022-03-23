<?php
   /**
    * Create
    * Name: Andy Soutcharith
    * Date: March 23, 2022
    * Description: Create posts
    */

    require('authenticate.php');
    require('dbConnect.php');

    if ($_POST && !empty($_POST['movieName']) && !empty($_POST['description']) && !empty($_POST['directorName']) && !empty($_POST['movieGenre']) && !empty($_POST['releaseDate']))
    {
        $movieName = filter_input(INPUT_POST, 'movieName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $directorName = filter_input(INPUT_POST, 'directorName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $movieGenre = filter_input(INPUT_POST, 'movieGenre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $releaseDate = filter_input(INPUT_POST, 'releaseDate', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $query = "INSERT INTO movies (movieName, description, directorName, movieGenre, releaseDate) VALUES (:movieName, :description, :directorName, :movieGenre, :releaseDate)";
        $statement = $db->prepare($query);

        $statement->bindValue(":title", $title);
        $statement->bindValue(":content", $content);

        if($statement->execute())
        {
            header('Location: index.php');
        }
    }

    if ($_POST)
    {
        if (empty($_POST['movieName']) && empty($_POST['description']) && empty($_POST['directorName']) && empty($_POST['movieGenre']) && empty($_POST['releaseDate']))
        {
            header('Location: errorRedirect.html');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="styles.css">
        <title>Create new post</title>
    </head>
    <body>
        <div class="home">
            <a class="title" href="index.php"><h1>Andy's Movie Blog</h1></a>
            <form method="post" action="create.php">
                <label for="movieName">Movie's Name</label>
                <input id="movieName" name="movieName">

                <label for="directorName">Director's Name</label>
                <input id="directorName" name="directorName">

                <label for="movieGenre">Movie's Genre(s)</label>
                <input id="movieGenre" name="movieGenre">

                <label for="releaseDate">Release Date</label>
                <input id="releaseDate" name="releaseDate">
                
                <div id="text-area">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="10" cols="50"></textarea>
                </div>
                <div id="text-area">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" rows="10" cols="50"></textarea>
                    <input type="submit" value="Submit Post">
                </div>
            </form>
        </div>
    </body>
</html>