<?php

$conn = new mysqli("localhost","root","","esports_db",3307);

$coach_name = $_POST['coach_name'];
$email = $_POST['email'];
$experience = $_POST['experience_years'];
$nationality = $_POST['nationality'];

$sql = "INSERT INTO coach(coach_name,email,experience_years,nationality)
VALUES('$coach_name','$email','$experience','$nationality')";

if(mysqli_query($conn,$sql)){
echo "Coach added successfully";
}else{
echo "Error: " . mysqli_error($conn);
}

?>