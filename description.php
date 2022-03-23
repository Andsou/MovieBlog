<?php
   /**
    * View page
    * Name: Andy Soutcharith
    * Date: March 23, 2022
    * Description: View a post
    */

    require('dbConnect.php');
        
    $query = "SELECT * FROM movies WHERE movieId = :MovieId LIMIT 1";
    $statement = $db->prepare($query);

    $movieId = filter_input(INPUT_GET, 'movieId', FILTER_SANITIZE_NUMBER_INT);

    $statement->bindValue('movieId', $movieId, PDO::PARAM_INT);
    $statement->execute();

    $row = $statement->fetch();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="styles.css">
        <title>Full post</title>
    </head>
    <body>
        <div class="home">
        <a class="title" href="index.php"><h1><?= $row['movieName'] ?></h1></a>
            <small><?= date("F j, Y", strtotime($row['postTime'])) ?><a href="update.php?id=<?= $row['movieId'] ?>">Edit post</a></small>
            <p><?= $row['description'] ?></p>
        </div>
    </body>
</html>