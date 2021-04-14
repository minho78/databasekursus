<?php
require_once('initialize.php');

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];


$query = "INSERT INTO _Author (FirstName,LastName,Email) VALUES (?,?,?);";
$data = array();
$data[] = $firstname;
$data[] = $lastname;
$data[] = $email;

$stmt = $conn->prepare($query);
$stmt->execute($data);
$id = $conn->lastInsertId();
echo $id;

$stmt = null;
$conn = null;

?>