<?php
require_once('initialize.php');

$id = $_POST['id'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];


$query = "UPDATE _Author SET FirstName=?,LastName=?,Email=? WHERE Id=?;";
$data = array();
$data[] = $firstname;
$data[] = $lastname;
$data[] = $email;
$data[] = $id;

$stmt = $conn->prepare($query);
$stmt->execute($data);

$stmt = null;
$conn = null;

?>