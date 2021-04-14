<?php
require_once('initialize.php');

	if (isset($_POST['page']))
		$page = $_POST['page'];
	else
		$page = 1;
	
	$query1 = "SELECT count(*) FROM _Author";
	$stmt = $conn->prepare($query1);
	$stmt->execute();
	$numrows = $stmt->fetchColumn();
	$stmt = null;
	
	$limit = 5;
	$total_pages = ceil($numrows/$limit);
	$starting_limit = ($page-1)*$limit;
	/*
	$query2  = "SELECT Id,Title,Summary,Price,ISBN FROM _Book ORDER BY Title LIMIT ?,?;
				SELECT FirstName,LastName,Email FROM _Author as a INNER JOIN _Author_Book as ab ON a.Id = ab.Author WHERE ab.Book = ";
				//"select distinct top(5) b.title,a.id,a.firstname,a.lastname,a.Email from book as b inner join author_book as ab on ab.book = b.Id inner join author as a on a.id = ab.author order by b.title"
	*/
	
	$query2 =	"select b.id,title,summary,price,isbn from _book as b inner join _author_book as ab on b.id = ab.book where ab.author in (select * from (select id from _author as a order by firstname limit ?,?) as t);
				select ab.author,ab.book from _author_book as ab where ab.author in (select * from (select id from _author as a order by firstname limit ?,?) as t);
				Xselect distinct id,firstname,lastname,email from _author order by firstname limit ?,?;
				select bc.book,bc.category from _book_category as bc where bc.book in (select * from (select id from _book as b order by title limit ?,?) as t);
				select c.id,c.categoryname from _category as c inner join _book_category as bc on c.id = bc.category where bc.book in (select * from (select id from _book as b order by title limit ?,?) as t);";
	
	//$query2 = "CALL stp_GetDataFromBooksOnCurrentPage(?,?);";
	$stmt = $conn->prepare($query2);

	$stmt->bindParam(1, $starting_limit, \PDO::PARAM_INT);
	$stmt->bindParam(2, $limit, \PDO::PARAM_INT);
	$stmt->bindParam(3, $starting_limit, \PDO::PARAM_INT);
	$stmt->bindParam(4, $limit, \PDO::PARAM_INT);
	$stmt->bindParam(5, $starting_limit, \PDO::PARAM_INT);
	$stmt->bindParam(6, $limit, \PDO::PARAM_INT);
	$stmt->bindParam(7, $starting_limit, \PDO::PARAM_INT);
	$stmt->bindParam(8, $limit, \PDO::PARAM_INT);
	$stmt->bindParam(9, $starting_limit, \PDO::PARAM_INT);
	$stmt->bindParam(10, $limit, \PDO::PARAM_INT);
	

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

	$arr = array();
	$arr[] = $author_table;
	$arr[] = $author_book_table;
	$arr[] = $book_table;
	$arr[] = $book_category_table;
	$arr[] = $category_table;
	$arr[] = $total_pages;
	
	echo json_encode($arr,JSON_UNESCAPED_UNICODE);

?>