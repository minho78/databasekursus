<?php
require_once('initialize.php');

$id = $_POST['id'];
$title = $_POST['title'];
$summary = $_POST['summary'];
$price = $_POST['price'];
$isbn = $_POST['isbn'];
if (isset($_POST['caton']))
	$caton = $_POST['caton'];//json_decode($_POST['caton'],false);//
else
	$caton = array();
if (isset($_POST['catoff']))
	$catoff = $_POST['catoff'];//json_decode($_POST['catoff'],false);//
else
	$catoff = array();

if (isset($_POST['auton']))
	$auton = $_POST['auton'];
else
	$auton = array();
if (isset($_POST['autoff']))
	$autoff = $_POST['autoff'];
else
	$autoff = array();

echo $isbn;

/*
$data = [
    'title' => $title,
	'summary' => $summary,
	'price' => $price,
	'isbn' => $isbn,
	'id' => $id,
	//'caton' => $caton,
	//'catoff' => $catoff,
];
*/

/*
$query = "SELECT Id,FirstName,LastName,Email FROM _Author AS a INNER JOIN _Author_Book as ab ON a.Id = ab.Author WHERE ab.Book=:id;
		  SELECT Id,Title,Summary,Price,ISBN FROM _Book WHERE Id=:id;
		  SELECT Id,CategoryName FROM _Category AS c INNER JOIN _Book_Category as bc ON c.Id = bc.Category WHERE bc.Book=:id";
*/

$query = "UPDATE _Book SET Title=?,Summary=?,Price=?,ISBN=? WHERE Id=?;";
$data = array();
$data[] = $title;
$data[] = $summary;
$data[] = $price;
$data[] = $isbn;
$data[] = $id;

for ($x=0; $x<count($catoff);$x++) {
	$query .= "DELETE FROM _Book_Category WHERE Book=? AND Category=?;";
	$data[] = $id;
	$data[] = $catoff[$x];
}

for ($x=0; $x<count($caton);$x++) {
	$query .= "INSERT INTO _Book_Category (Book,Category) VALUES (?,?);";
	$data[] = $id;
	$data[] = $caton[$x];
}


for ($x=0; $x<count($autoff);$x++) {
	$query .= "DELETE FROM _Author_Book WHERE Book=? AND Author=?;";
	$data[] = $id;
	$data[] = $autoff[$x];
}

for ($x=0; $x<count($auton);$x++) {
	$query .= "INSERT INTO _Author_Book (Author,Book) VALUES (?,?);";
	$data[] = $auton[$x];
	$data[] = $id;
}
	
//$query += "DELETE FROM _Book_Category WHERE Book=:id AND Category=:";

$stmt = $conn->prepare($query);
$stmt->execute($data);
//$stmt->execute();


$stmt = null;
$conn = null;


?>