<?php
/*
 * Name: Andy Soutcharith
 * Program: Business Information Technology
 * Course: ADEV-1008 Programming
 * Created: 2021/04/13
 * Updated: 2021/04/13
 */

    require('dbConnect.php');

    $query = "SELECT * FROM posts LIMIT 5";

    $statement = $db->prepare($query);

    $statement->execute();
 ?>