<?php
   /**
    * View page
    * Name: Andy Soutcharith
    * Date: March 23, 2022
    * Description: View a post
    */

    require('DbConnect.php');
        
    $query = "SELECT * FROM movies";
    $statement = $db->prepare($query);

    $commentId = filter_input(INPUT_GET, 'comments.movieId', FILTER_SANITIZE_NUMBER_INT);

    $statement->bindValue('id', $id, PDO::PARAM_INT);
    $statement->execute();

    $row = $statement->fetch();

    if ($_POST && !empty($_POST['userName']) && !empty($_POST['content']))
    {
        $commentUserName = filter_input(INPUT_POST, 'commentUserName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $commentContent = filter_input(INPUT_POST, 'commentContent', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        $query = "INSERT INTO movies (commentUserName, commentContent) VALUES (:commentUserName, :commentContent)";
        $statement = $db->prepare($query);

        $statement->bindValue(":commentUserName", $commentUserName);
        $statement->bindValue(":commentContent", $commentContent);
 
        if ($statement->execute())
        {
            header('Location: index.php');    
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="styles.css">
        <title>Full post</title>
    </head>
    <div id="header">
            <ul id="nav-buttons">
                <li><a class="text-decoration-none" href="index.php"><h1>Andy's Movie Blog</h1></a></li>
                <div class="post-buttons">
                <li><a id="new-post" href="create.php">New Post</a></li>
                <li><a id="home-page" href="index.php">Home</a></li>
                </div>
            </ul>                         
        </div> 
    <body>
        <div class="container">
        <a class="text-decoration-none" href="index.php"><h1><?= $row['movieName'] ?></h1></a>
        <h4><?= "Released: " . $row['releaseDate'] ?><button type="button" class="btn btn-light"><a class="text-decoration-none" href="update.php?movieId=<?= $row['movieId'] ?>">Edit post</a></button></h4>
            <small><?= date("F j, Y, g:i a", strtotime($row['postTime'])) ?></small>
            <h5><?= "Directors: " . $row['directorName'] ?></h5>
            <h5><?= "Genres: " . $row['movieGenre'] ?></h5>
            <h4><?= "Release Date: " . $row['releaseDate'] ?></h4>
            <p><?= $row['description'] ?></p>
            <p><?= "Additional Notes: " . $row['notes'] ?></p>
        </div>

        <div class="container">
            <div class="form-group">
                <form method="post" action="description.php">
                    <h2>Comments</h2>
                    <label for="userName">Username</label>
                    <input id="userName" type="text" name="userName" class="form-control" placeholder="Enter Username">

                    <div id="text-area">
                        <label for="content">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="3" placeholder="Content"></textarea>
                        <button type="submit" class="btn btn-primary">Submit</button>     
                    </div>               
                </form>
            </div>
        </div>

        <?php while($row = $statement->fetch()): ?>
            <div class="container">
                <h5><?= "User Name: " . $row['userName'] ?></h5>
                <div>
                    <h5>Content</h5>
                    <p><?= $row['content'] ?></p>
                </div>
                <small><?= "Posted on: " . date("F j, Y, g:i a", strtotime($row['date'])) ?></small>
            </div>
        <?php endwhile ?>
    </body>
</html>