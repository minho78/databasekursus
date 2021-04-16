<?php
require_once('initialize.php');

$id = $_POST['id'];
$data = [
    'id' => $id,
];

$query = "SELECT Id,FirstName,LastName,Email FROM _Author WHERE Id=:id;
		  SELECT Id,Title,Summary,Price,ISBN FROM _Book AS b INNER JOIN _Author_Book AS ab ON b.Id = ab.Book WHERE ab.Author=:id;
		  SELECT Id,CategoryName FROM _Category AS c INNER JOIN _Book_Category AS bc ON c.Id = bc.Category INNER JOIN _Author_Book AS ab ON ab.Book = bc.Book WHERE ab.Author=:id";

$stmt = $conn->prepare($query);
$stmt->execute($data);
$authorinfo = $stmt->fetchAll(PDO::FETCH_NUM);
$stmt->nextRowset();
$bookinfo = $stmt->fetchAll(PDO::FETCH_NUM);
$stmt->nextRowset();
$categoryinfo = $stmt->fetchAll(PDO::FETCH_NUM);

$arr = array();
$arr[0] = $authorinfo;
$arr[1] = $bookinfo;
$arr[2] = $categoryinfo;

echo json_encode($arr,JSON_UNESCAPED_UNICODE);

$stmt = null;
$conn = null;
	
?>