<?php

$host="localhost";
$dbname="market";
$user="root";
$password="";

try{

$conn=new PDO(
"mysql:host=$host;dbname=$dbname;charset=utf8",
$user,
$password
);

$conn->setAttribute(
PDO::ATTR_ERRMODE,
PDO::ERRMODE_EXCEPTION
);

}catch(PDOException $e){

die("Database error: ".$e->getMessage());

}
?>