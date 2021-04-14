<?php
require_once('initialize.php');

$isbn = $_POST['isbn'];

$query = "SELECT COUNT(*) FROM _Book WHERE ISBN = ?;";
$data = array();
$data[] = (int)$isbn;
$stmt = $conn->prepare($query);
$stmt->execute($data);
$check = $stmt->fetchAll(PDO::FETCH_NUM);
echo $check[0][0];
//echo json_encode((int)$check);

$stmt = null;
$conn = null;


?>