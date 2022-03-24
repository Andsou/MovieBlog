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
        $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $query = "INSERT INTO movies (movieName, description, directorName, movieGenre, releaseDate, notes) VALUES (:movieName, :description, :directorName, :movieGenre, :releaseDate, :notes)";
        $statement = $db->prepare($query);

        $statement->bindValue(":movieName", $movieName);
        $statement->bindValue(":description", $description);
        $statement->bindValue(":directorName", $directorName);
        $statement->bindValue(":movieGenre", $movieGenre);
        $statement->bindValue(":releaseDate", $releaseDate);
        $statement->bindValue(":notes", $notes);

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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="styles.css">
        <title>Create new post</title>
    </head>
    <body>
        <div id="header">
            <ul id="nav-buttons">
                <li><a class="text-decoration-none" href="index.php"><h1>Andy's Movie Blog</h1></a></li>

                <div class="post-buttons">
                <li><a id="new-post" href="create.php">New Post</a></li>
                <li><a id="home-page" href="index.php">Home</a></li>
                </div>
            </ul>                         
        </div> 

        <div class="container">
            <div class="form-group">
                <form method="post" action="create.php">
                    <label for="movieName" placeholder="Enter the movie's name">Movie's Name</label>
                    <input id="movieName" type="text" name="movieName" class="form-control" placeholder="Enter the movie's name">

                    <label for="directorName">Director's Name(s)</label>
                    <input id="directorName" type="text" name="directorName" class="form-control" placeholder="Enter the director's names">

                    <label for="movieGenre">Movie's Genre(s)</label>
                    <input id="movieGenre" type="text" name="movieGenre" class="form-control"  placeholder="Enter the movie genre(s)">

                    <label for="releaseDate">Release Date</label>
                    <input id="releaseDate" type="date" name="releaseDate" class="form-control">
                    
                    <div id="text-area">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Plot of the movie?"></textarea>
                    </div>
                    <div id="text-area">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="(optional) Opinion on the movie?"></textarea>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>