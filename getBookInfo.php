<?php
require_once('initialize.php');

$id = $_POST['id'];


$data = [
    'id' => $id,
];


//$query = "CALL stp_GetBookByBookId(:id); CALL stp_GetAuthorByBookId(:id);";
$query = "SELECT Id,FirstName,LastName,Email FROM _Author AS a INNER JOIN _Author_Book as ab ON a.Id = ab.Author WHERE ab.Book=:id;
		  SELECT Id,Title,Summary,Price,ISBN FROM _Book WHERE Id=:id;
		  SELECT Id,CategoryName FROM _Category AS c INNER JOIN _Book_Category as bc ON c.Id = bc.Category WHERE bc.Book=:id";
/*
$query = "SELECT Title,Summary,Price,ISBN FROM _Book WHERE Id=:id;
		  CALL stp_GetAuthorByBookId(:id);";
		  //SELECT FirstName,LastName,Email FROM _Author WHERE ";
*/

$stmt = $conn->prepare($query);
$stmt->execute($data);
//$stmt->execute();
$authorinfo = $stmt->fetchAll(PDO::FETCH_NUM);
$stmt->nextRowset();
$bookinfo = $stmt->fetchAll(PDO::FETCH_NUM);
$stmt->nextRowset();
$categoryinfo = $stmt->fetchAll(PDO::FETCH_NUM);

$arr = array();
$arr[0] = $authorinfo;
$arr[1] = $bookinfo;
$arr[2] = $categoryinfo;

/*
$toJson = array();
foreach ($arr as $key => $value) {
	foreach($value as $k =>$v){
		if(isset($toJson[$k])){
			$toJson[$k][] = $v;
		}else{
			$toJson[$k] = array();
			array_push($toJson[$k], $v);
		}
	}
}
*/
//echo json_encode($bookinfo);
//echo var_dump($bookinfo);
//echo gettype($bookinfo);
echo json_encode($arr,JSON_UNESCAPED_UNICODE);
//echo $bookinfo;
//echo json_encode($authorinfo);
//echo 'hey';
//echo json_encode($arr);

$stmt = null;
$conn = null;


?>