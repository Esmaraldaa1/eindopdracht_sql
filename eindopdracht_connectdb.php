<?php
include('eindopdracht_configdb.php');

$conn = mysqli_connect($host, $username, $password, $database_name);

if($conn === false) // Verbinding is mislukt!
{
    die("Kan geen verbinding maken met de database");
}
?>