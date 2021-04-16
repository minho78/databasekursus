<?php
require_once('initialize.php');

$id = $_POST['authorId'];

$query = "DELETE FROM _Author_Book WHERE Author = :id;
		  DELETE FROM _Author WHERE Id = :id;";

$stmt = $conn->prepare($query);
$stmt->bindValue(':id', $id,PDO::PARAM_INT);

$stmt->execute();

$stmt = null;
$conn = null;

?>