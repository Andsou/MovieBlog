<?php
   /**
    * Home page
    * Name: Andy Soutcharith
    * Date: March 23, 2022
    * Description: Home page with all blog posts
    */

   require('dbConnect.php');

   $query = "SELECT * FROM posts LIMIT 5";

   $statement = $db->prepare($query);

   $statement->execute();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="styles.css">
        <title>Home</title>
    </head>
    <body>
        <div class="home">
            <div id="header">
                <a class="title" href="index.php"><h1>Andy's Movie Blog</h1></a>
                <a id="new-post" href="create.php"><button>New Post</button></a>
            </div>
            <?php while($row = $statement->fetch()): ?>
                <h2><a class="title" href="description.php?id=<?= $row['id'] ?>"><?= $row['title'] ?></a></h2>
                <small><?= date("F j, Y, g:i a", strtotime($row['date'])) ?><a href="edit.php?id=<?= $row['id'] ?>">Edit post</a></small>
                <?php if (strlen($row['content']) > 200): ?>                   
                    <p><?= substr_replace($row['content'],"<a href=description.php?id=" . $row['id'] . ">Read more...</a>", 200);?></p>
                <?php else:?>
                    <p><?= $row['content'] ?></p>
                <?php endif ?>
            <?php endwhile ?> 
        </div>
    </body>
</html>