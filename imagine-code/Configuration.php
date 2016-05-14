<?php

// SQL Configuration Information
$sql_username = "test";
$sql_password = "test";
$sql_database = "test";

// Root folder
$root = "C:/inetpub/wwwroot/immutable/";
$url  = "http://localhost/immutable/";

// Access levels (weight values)
$blog_post_level = 3;
$blog_delete_level = 3;
$blog_update_level = 3;
$article_post_level = 3;
$article_delete_level = 3;
$blog_update_level = 3;
$project_post_level = 3;
$project_delete_level = 3;
$blog_update_level = 3;
$category_post_level = 3;
$category_delete_level = 3;
$category_update_level = 3;

include_once($root.'lib/SqlManager.php');
$sqlManager = new SqlManager($sql_username, $sql_password, $sql_database);

?>