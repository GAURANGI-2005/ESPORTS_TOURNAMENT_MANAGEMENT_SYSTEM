<?php

$conn = new mysqli("localhost","root","","esports_db",3307);

if($conn->connect_error){
    die("Connection failed: ".$conn->connect_error);
}

$team_name = $_POST['team_name'];
$region = $_POST['region'];
$ranking = $_POST['ranking'];
$founded_year = $_POST['founded_year'];
$coach_id = $_POST['coach_id'];

$sql = "INSERT INTO team (team_name, region, ranking, founded_year, coach_id)
VALUES ('$team_name','$region','$ranking','$founded_year','$coach_id')";

mysqli_query($conn,$sql);

echo "Team added successfully";

?>