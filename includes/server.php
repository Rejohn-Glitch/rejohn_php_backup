<?php 
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "responsive_test";

$con = mysqli_connect($servername,$username,$password,$db_name);

if($con->connect_error) {
    echo "Error". $con->connect_error;
}

?>