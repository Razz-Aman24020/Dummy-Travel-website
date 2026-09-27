<?php

$host = "localhost";
$userName = "root";  
$password = ""; 
$database = "login";


$conn = new mysqli($host, $userName, $password, $database);

// if($conn->connect_error){
//     die("Connection failed: " . $conn->connect_error);
// }

// echo "<h1>Connected successfully</h1>";
?>



<!-- if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include("config.php");

    $name = $_POST['id'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    $phone = $_POST['Phone'];

    $data = $conn->prepare(
        "INSERT INTO `user` (`name`, `Email`, `Password`, `Phone`) VALUES (?, ?, ?, ?)"
    );
    $data->bind_param("ssss", $name, $email, $password, $phone);

    if ($data->execute()) {
        echo "<br>Insertion successful";
    } else {
        echo "<br>Insertion unsuccessful: " . htmlspecialchars($data->error);
    }

    $data->close();
    $conn->close(); -->