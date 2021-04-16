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
$id = $stmt->fetchAll(PDO::FETCH_NUM);

$arr = array();
$arr[] = $id;
echo json_encode($arr,JSON_UNESCAPED_UNICODE);
$stmt = null;

$query = "";
$data = array();
for ($x=0; $x<count($caton);$x++) {
	$query .= "INSERT INTO _Book_Category (Book,Category) VALUES (?,?);";
	$data[] = $id[0][0];
	$data[] = $caton[$x];
}
for ($x=0; $x<count($auton);$x++) {
	$query .= "INSERT INTO _Author_Book (Author,Book) VALUES (?,?);";
	$data[] = $auton[$x];
	$data[] = $id[0][0];
}
$stmt = $conn->prepare($query);
$stmt->execute($data);


$stmt = null;
$conn = null;


?>