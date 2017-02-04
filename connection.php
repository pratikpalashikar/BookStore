<?php
/**
 * Created by PhpStorm.
 * User: Pratik
 * Date: 11/11/2016
 * Time: 1:52 PM
 */
// we connect to localhost at port 3307
$servername = "127.0.0.1:3307";
$username = "root";
$password = "";
$db = "cheapbooks";

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>



