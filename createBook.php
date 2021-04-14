<?php
require_once('initialize.php');

$title = $_POST['title'];
$summary = $_POST['summary'];
$price = $_POST['price'];
$isbn = $_POST['isbn'];
if (isset($_POST['caton']))
	$caton = $_POST['caton'];
else
	$caton = array();
if (isset($_POST['auton']))
	$auton = $_POST['auton'];
else
	$auton = array();	

$query = "CALL stp_InsertBook(?,?,?,?);";
$data = array($title,$summary,$price,$isbn);
$stmt = $conn->prepare($query);
$stmt->execute($data);
$x = $stmt->fetchAll(PDO::FETCH_NUM);
echo json_encode($x,JSON_UNESCAPED_UNICODE);
$stmt = null;
$conn = null;


?>