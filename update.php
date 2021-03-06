<?php
   /**
    * Update posts
    * Name: Andy Soutcharith
    * Date: March 23, 2022
    * Description: Logged in user can update/edit their own posts
    */

    require('DbConnect.php');
    require('authenticate.php');
    
    if (isset($_POST['movieName']) && isset($_POST['description']) && isset($_POST['movieId']) &&  
        isset($_POST['movieGenre']) && isset($_POST['directorName']) && isset($_POST['releaseDate']) &&
        !empty($_POST['description']) && !empty($_POST['movieName']) && !empty($_POST['directorName']) && !empty($_POST['movieGenre'])
        && !empty($_POST['releaseDate']) && isset($_POST['movieId']) && isset($_POST['update']))
    {
        $movieName = filter_input(INPUT_POST, 'movieName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $movieId = filter_input(INPUT_POST, 'movieId', FILTER_SANITIZE_NUMBER_INT);
        $directorName = filter_input(INPUT_POST, 'directorName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $movieGenre = filter_input(INPUT_POST, 'movieGenre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $releaseDate = filter_input(INPUT_POST, 'releaseDate', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        
        $query = "UPDATE movies SET movieName = :movieName, description = :description, directorName = :directorName, movieGenre = :movieGenre, releaseDate = :releaseDate, notes = :notes WHERE movieId = :movieId;";
        $statement = $db->prepare($query);
        $statement->bindValue(':movieName', $movieName);        
        $statement->bindValue(':directorName', $directorName);
        $statement->bindValue(':movieGenre', $movieGenre); 
        $statement->bindValue(':releaseDate', $releaseDate);          
        $statement->bindValue(':description', $description);
        $statement->bindValue(':notes', $notes);
        $statement->bindValue(':movieId', $movieId, PDO::PARAM_INT);
        
        
        if($statement->execute())
        {
            header("Location:index.php");
            exit();
        }
    }
    else if (isset($_GET['movieId'])) 
    {   
        $movieId = filter_input(INPUT_GET, 'movieId', FILTER_SANITIZE_NUMBER_INT);

        $query = "SELECT * FROM movies WHERE movieId = :movieId";
        $statement = $db->prepare($query);
        $statement->bindValue(':movieId', $movieId, PDO::PARAM_INT);

        $statement->execute();
        $movies = $statement->fetch();
    } 

 
    if ($_POST)
    {
        if (empty($_POST['movieName']) || empty($_POST['description']) || empty($_POST['directorName']) || empty($_POST['movieGenre']) || empty($_POST['releaseDate']))
        {
            header('Location: errorRedirect.html');
        }
    }

    if (isset($_POST['delete']))
    {
        $query = "DELETE FROM movies WHERE movieId = :movieId LIMIT 1";
        $statement = $db->prepare($query);
        
        $movieId = filter_input(INPUT_GET, 'movieId', FILTER_SANITIZE_NUMBER_INT);
        
        $statement->bindValue('movieId', $movieId, PDO::PARAM_INT);
        if ($statement->execute())
        {
            header("Location: index.php");
        }
            
        $movies = $statement->fetch();    
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="styles.css">
        <title>Edit Post</title>
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
                <form method="post">
                <input type="hidden" name="movieId" value="<?= $movies['movieId'] ?>">
                    <label for="movieName" placeholder="Enter the movie's name">Movie's Name</label>
                    <input id="movieName" type="text" name="movieName" class="form-control" placeholder="Enter the movie's name" value="<?= $movies['movieName'] ?>">

                    <label for="directorName">Director's Name(s)</label>
                    <input id="directorName" type="text" name="directorName" class="form-control" placeholder="Enter the director's names" value="<?= $movies['directorName'] ?>">

                    <label for="movieGenre">Movie's Genre(s)</label>
                    <input id="movieGenre" type="text" name="movieGenre" class="form-control"  placeholder="Enter the movie genre(s)" value="<?= $movies['movieGenre'] ?>">

                    <label for="releaseDate">Release Date</label>
                    <input id="releaseDate" type="date" name="releaseDate" class="form-control" value="<?= $movies['releaseDate'] ?>">
                    
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Plot of the movie?"><?= $movies['description'] ?></textarea>

                    <label for="notes">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="(optional) Opinion on the movie?"><?= $movies['notes'] ?></textarea>

                    <input type="submit" name="update" value="update">
                    <input type="submit" name="delete" value="delete" onclick="return confirm('Are you sure you wish to delete this post?')">
                </form>
            </div>
        </div>
    </body>
</html>