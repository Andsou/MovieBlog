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

                    <label for="directorName">Director's Name</label>
                    <input id="directorName" type="text" name="directorName" class="form-control">

                    <label for="movieGenre">Movie's Genre(s)</label>
                    <input id="movieGenre" type="text" name="movieGenre" class="form-control">

                    <label for="releaseDate">Release Date</label>
                    <input id="releaseDate" name="releaseDate" class="form-control">
                    
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
        </div>
    </body>
</html>