<?php
    /**
    * Search Page
    * Name: Andy Soutcharith
    * Date: April 6, 2022
    * Description: Search query page
    */

    require('DbConnect.php');

    if (isset($_POST["submit"])) {
        $search = $_POST["search"];
        $query = "SELECT * FROM movies WHERE movieName LIKE '%$search%'";

        $statement = $db->prepare($query);
        $statement->execute();

    }
   
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <title>Search</title>
    </head>

    <body>
        <div id="header">
            <ul id="nav-buttons">
                <li><a class="text-decoration-none" href="index.php"><h1>Andy's Movie Blog</h1></a></li>
                <div class="post-buttons">
                <li><a id="new-post" href="create.php">New Post</a></li>
                <li><a id="home-page" href="index.php">Home</a></li>
            </ul> 
            
            <form action="search.php" method="POST">
                <label>Search</label>
                <input type="text" name="search">
                <input type="submit" name="submit">
            </form>
        </div> 

        <div class="container">
            <?php if ($row = $statement->fetch()): ?>
                <h2><a class="text-decoration-none" href="description.php?movieId=<?= $row['movieId'] ?>"><?= $row['movieName'] ?></a></h2>
                    <h4><?= "Released: " . $row['releaseDate'] ?><button type="button" class="btn btn-light"><a class="text-decoration-none" href="update.php?movieId=<?= $row['movieId'] ?>">Edit post</a></button></h4>
                    <small><?= "Posted on: " . date("F j, Y, g:i a", strtotime($row['postTime'])) ?></small>
                    <?php if (strlen($row['description']) > 500): ?>                   
                        <p><?= substr_replace($row['description'],"<a href=description.php?movieId=" . $row['movieId'] . ">Read more...</a>", 500);?></p>
                    <?php else:?>
                        <p><?= $row['description'] ?></p>
                    <?php endif ?>

            <?php endif; ?>
        </div>
    </body>
</html>