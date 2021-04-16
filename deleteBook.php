<?php
require_once('initialize.php');

$id = $_POST['bookId'];

$query = "DELETE FROM _Author_Book WHERE Book = :id;
		  DELETE FROM _Book_Category WHERE Book = :id;
		  DELETE FROM _Book WHERE Id = :id;";

echo $id;
$stmt = $conn->prepare($query);
$stmt->bindValue(':id', $id,PDO::PARAM_INT);

$stmt->execute();

$stmt = null;
$conn = null;

?>