<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once 'src/Book.php';
    require_once 'src/Author.php';

    $server = 'mysql:host=localhost;dbname=library_catalog_test';
$username = 'root';
$password = 'root';
$DB = new PDO($server, $username, $password);







 ?>
