<?php
require_once('initialize.php');

	if (isset($_POST['page']))
		$page = $_POST['page'];
	else
		$page = 1;
	
	if (isset($_POST['category']))
		$cat = $_POST['category'];
	else
		$cat = 1;
	
/*	
	$query1 = "SELECT count(*) FROM _Author";
	$stmt = $conn->prepare($query1);
	$stmt->execute();
	$numrows = $stmt->fetchColumn();
	$stmt = null;
	*/
	
	
	/*
	$query2 = "select b.Id,Title,Summary,Price,Isbn from _Book as b inner join _Author_Book as ab on b.Id = ab.Book where ab.Author in (select * from (select Id from _Author as a order by Firstname limit ?,?) as t);
				select ab.Author,ab.Book from _Author_Book as ab where ab.Author in (select * from (select Id from _Author as a order by Firstname limit ?,?) as t);
				select distinct Id,Firstname,Lastname,Email from _Author order by Firstname limit ?,?;
				select bc.Book,bc.Category from _Book_Category as bc where bc.Book in (select * from (select Id from _Book as b order by Title limit ?,?) as t);
				select c.Id,c.Categoryname from _Category as c inner join _Book_Category as bc on c.Id = bc.Category where bc.Book in (select * from (select Id from _Book as b order by Title limit ?,?) as t);";
	*/
	
	
	$limit = 5;
	$starting_limit = ($page-1)*$limit;
	
	$query2 = "CALL stp_GetDataFromCategoryOnCurrentPage(?,?,?);";
	$stmt = $conn->prepare($query2);
	$stmt->bindParam(1, $cat, \PDO::PARAM_INT);
	$stmt->bindParam(2, $starting_limit, \PDO::PARAM_INT);
	$stmt->bindParam(3, $limit, \PDO::PARAM_INT);
	
	$stmt->execute();
	
	
	$author_table = $stmt->fetchAll(PDO::FETCH_NUM);
	$stmt->nextRowset();
	$author_book_table = $stmt->fetchAll(PDO::FETCH_NUM);
	$stmt->nextRowset();
	$book_table = $stmt->fetchAll(PDO::FETCH_NUM);
	$stmt->nextRowset();
	$book_category_table = $stmt->fetchAll(PDO::FETCH_NUM);
	$stmt->nextRowset();
	$category_table = $stmt->fetchAll(PDO::FETCH_NUM);
	$stmt->nextRowset();
	$numrows = $stmt->fetchAll(PDO::FETCH_NUM);
	
	$total_pages = ceil($numrows[0][0]/$limit);
		
	$arr = array();
	$arr[] = $author_table;
	$arr[] = $author_book_table;
	$arr[] = $book_table;
	$arr[] = $book_category_table;
	$arr[] = $category_table;
	$arr[] = $total_pages;
	
	echo json_encode($arr,JSON_UNESCAPED_UNICODE);

?>