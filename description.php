<?php
   /**
    * View page
    * Name: Andy Soutcharith
    * Date: March 23, 2022
    * Description: View a post
    */

    require('DbConnect.php');

    // Join all 3 tables in the database together
    $query = "SELECT * FROM movies 
              LEFT JOIN comments on movies.movieId = comments.movieIdFk
              LEFT JOIN images on images.movieIds = movies.movieId 
              WHERE movieId = :movieId
              ORDER BY comments DESC";

    $statement = $db->prepare($query);          
    $id = filter_input(INPUT_GET, 'movieId', FILTER_SANITIZE_NUMBER_INT); 
    $statement->bindValue('movieId', $id, PDO::PARAM_INT);
    $statement->execute();

    $row = $statement->fetch();
        
    // Inserts comment into the comments database
    if (isset($_POST['submit-comment']) && !empty($_POST['userName']) && !empty($_POST['content'])) {
        $commentUserName = filter_input(INPUT_POST, 'userName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $commentContent = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $movieId = filter_input(INPUT_POST, 'movieId', FILTER_SANITIZE_NUMBER_INT); 

        $query = "INSERT INTO comments (userName, content, movieIdFk) VALUES (:userName, :content, :movieIdFk);";
        $statement = $db->prepare($query);
        $statement->bindValue(":userName", $commentUserName);
        $statement->bindValue(":content", $commentContent);
        $statement->bindValue(":movieIdFk", $movieId);
      
 
        if ($statement->execute()) {
            header("Location:description.php?movieId=$movieId");
        }        
    }

    // Handles the uploading of the image
    if (isset($_POST['upload'])) {
        $target = "images/" . basename($_FILES['image']['name']);
        $image = $_FILES['image']['name'];
        $movieId = filter_input(INPUT_POST, 'movieId', FILTER_SANITIZE_NUMBER_INT);
        
        $result = move_uploaded_file($_FILES['tmp_name'], $target);
        $orig_image = imagecreatefromjpeg($target);
        $image_info = getimagesize($target); 
        $width_orig  = $image_info[0]; // current width as found in image file
        $height_orig = $image_info[1]; // current height as found in image file
        $width = 1024; // new image width
        $height = 768; // new image height
        $destination_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($destination_image, $orig_image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        // This will just copy the new image over the original at the same filePath.
        imagejpeg($destination_image, $target, 100);

        $query = "INSERT INTO images (image, movieIds) VALUES ('$image', :movieIds)"; 



        $statement = $db->prepare($query);
        $statement->bindValue(':movieIds', $movieId, PDO::PARAM_INT);

        if ($statement->execute()) {
            header("Location: index.php");
        } 

        $image = $statement->fetch();
    }

    // Sort comments by newest to oldest
    $query = "SELECT * FROM comments 
    WHERE movieIdFk = ".$row['movieId']."
    ORDER BY date DESC";

    $statement = $db->prepare($query);          
    $statement->execute();

    $comment = $statement->fetch();

    // Deleting comments
    if (isset($_POST['delete'])) {
        $query = "DELETE FROM comments WHERE commentId = :commentId LIMIT 1";
        $statement = $db->prepare($query);
        
        $movieId = filter_input(INPUT_GET, 'commentId', FILTER_SANITIZE_NUMBER_INT);
        
        $statement->bindValue('commentId', $movieId, PDO::PARAM_INT);
        $statement->execute();
        
        $movies = $statement->fetch();   

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
        
        <form action="search.php" method="POST">
            <label>Search</label>
            <input type="text" name="search">
            <input type="submit" name="submit">
        </form>
    </div> 
    <body>
        <div class="container">
        <a class="text-decoration-none" href="index.php"><h1><?= $row['movieName'] ?></h1></a>

        <form method="post" action="description.php" enctype="multipart/form-data">
            <input type="hidden" name="movieId" value="<?= $row['movieId'] ?>">
                <div>
                    <input type="file" name="image">
                </div>
            <input type="submit" value="Upload" name="upload">
        </form>

        
        <div>        
            <img src="images/<?= $row['image'] ?>">
        </div>

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
                <input type="hidden" name="movieId" value="<?= $row['movieId'] ?>">
                    <h2>Comments</h2>
                    <label for="userName">Username</label>
                    <input id="userName" type="text" name="userName" class="form-control" placeholder="Enter Username">

                    <div id="text-area">
                        <label for="content">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="3" placeholder="Content"></textarea>
                        <button type="submit" name="submit-comment" class="btn btn-primary">Submit</button>     
                    </div>               
                </form>
            </div>
        </div>
        
        <?php while($comments = $statement->fetch()): ?>
            <div class="container">
                <h4>Username</h4>
                <h5><?= $comments['userName'] ?></h5>
                <div>
                    <h5>Content</h5>
                    <p><?= $comments['content'] ?></p>
                </div>
                <small><?= "Posted on: " . $comments['date'] ?></small>
                <form method="post">
                <input type="submit" name="delete" value="delete">
                </form>
            </div>
        <?php endwhile ?>
    </body>
</html>