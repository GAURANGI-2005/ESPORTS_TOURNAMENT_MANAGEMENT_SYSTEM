<?php

$conn = new mysqli("localhost","root","","esports_db",3307);

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

$player_name = $_POST['player_name'];
$age = $_POST['age'];
$role = $_POST['role'];
$nationality = $_POST['nationality'];
$gamer_tag = $_POST['gamer_tag'];
$team_id = $_POST['team_id'];   // this is the foreign key

$sql = "INSERT INTO player (player_name, age, role, nationality, gamer_tag, team_id)
VALUES ('$player_name','$age','$role','$nationality','$gamer_tag','$team_id')";

if(mysqli_query($conn,$sql)){
    echo "Player added successfully";
} else {
    echo "Error: " . mysqli_error($conn);
}

?>