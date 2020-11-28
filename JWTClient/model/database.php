<?php
// Database connection
$dsn = "mysql:host=localhost;dbname=JWTClient";
$dbUsername = "root";
$dbPassword = "root";

try {
   $db = new PDO($dsn, $dbUsername, $dbPassword);
   //set up error reporting on server
   $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
   $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   error_reporting(E_ALL);
} catch (PDOException $ex) {
   //echo "Connection Failure Error is " . $ex->getMessage();
   // redirect to an error page passing the error message
   header("Location:error.php?msg=" . $ex->getMessage());
}








