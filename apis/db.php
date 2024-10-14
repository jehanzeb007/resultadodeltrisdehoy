<?php

error_reporting(E_ERROR);
ini_set('display_errors', 1);

$hostname='localhost';
$dbname = 'resultadodeltrisdehoy';
$username ='dbAdmin';
$password ='sfs@$5q4q0i5mngfaQ#@fsAG';


$con=mysqli_connect($hostname, $username,  $password , $dbname ) or die("Connection Error!");


?>
