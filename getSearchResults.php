<?php
require_once('initialize.php');
	
	if (isset($_POST['searchtext']))
		$txt = $_POST['searchtext'];
		//$txt = '"%'.$_POST['searchtext'].'%"';
	else
		$txt = '';
	
	
	/*$data = [
		'txt' => $txt,
	];
	*/
	//$data = array();
	//$data[] = $txt;
	
	//$query = "select b.Id,b.Title,b.Price from _Book as b where b.Title like ".$txt.";";
	
	
	
	
	$query = "
	select distinct a.Id,a.FirstName,a.LastName,a.Email from _Author as a inner join _Author_Book as ab on a.Id = ab.Author inner join _Book as b on b.Id = ab.Book where b.Title like :str union
	select distinct a.Id,a.FirstName,a.LastName,a.Email from _Author as a where a.FirstName or a.LastName like :str union
	select distinct a.Id,a.FirstName,a.LastName,a.Email from _Author as a inner join _Author_Book as ab on a.Id = ab.Author inner join _Book_Category as bc on ab.Book = bc.Book inner join _Category as c on bc.Category = c.Id where c.CategoryName like :str;
	
	select ab.Author,ab.Book from _Author_Book as ab inner join _Book as b on ab.Book = b.Id where b.Title like :str union
	select ab.Author,ab.Book from _Author_Book as ab inner join _Author as a on ab.Author = a.Id where a.FirstName or a.LastName like :str union
	select ab.Author,ab.Book from _Author_Book as ab inner join _Book_Category as bc on ab.Book = bc.Book inner join _Category as c on bc.Category = c.Id where c.CategoryName like :str;
	
	select b.Id,b.Title,b.Summary,b.Price,b.Isbn from _Book as b where b.Title like :str union
	select b.Id,b.Title,b.Summary,b.Price,b.Isbn from _Book as b inner join _Author_Book as ab on b.Id = ab.Book inner join _Author as a on a.Id = ab.Author where a.FirstName or a.LastName like :str union
	select b.Id,b.Title,b.Summary,b.Price,b.Isbn from _Book as b inner join _Book_Category as bc on b.Id = bc.Book inner join _Category as c on c.Id = bc.Category where c.CategoryName like :str order by Title;
	
	select bc.Book,bc.Category from _Book_Category as bc inner join _Book as b on b.Id = bc.Book where b.Title like :str union
	select bc.Book,bc.Category from _Book_Category as bc inner join _Author_Book as ab on ab.Book = bc.Book inner join _Author as a on a.Id = ab.Author where a.FirstName or a.LastName like :str union
	select bc.Book,bc.Category from _Book_Category as bc inner join _Category as c on c.Id = bc.Category where c.CategoryName like :str;
	
	select c.Id,c.CategoryName from _Category as c inner join _Book_Category as bc on c.Id = bc.Category inner join _Book as b on b.Id = bc.Book where b.Title like :str union
	select c.Id,c.CategoryName from _Category as c inner join _Book_Category as bc on c.Id = bc.Category inner join _Author_Book as ab on ab.Book = bc.Book inner join _Author as a on a.Id = ab.Author where a.FirstName or a.LastName like :str union
	select c.Id,c.CategoryName from _Category as c where c.CategoryName like :str;";
	
	
	
	/*
	$query2 = "select b.Id,Title,Summary,Price,Isbn from _Book as b inner join _Author_Book as ab on b.Id = ab.Book where ab.Author in (select * from (select Id from _Author as a order by Firstname limit ?,?) as t);
				select ab.Author,ab.Book from _Author_Book as ab where ab.Author in (select * from (select Id from _Author as a order by Firstname limit ?,?) as t);
				select distinct Id,Firstname,Lastname,Email from _Author order by Firstname limit ?,?;
				select bc.Book,bc.Category from _Book_Category as bc where bc.Book in (select * from (select Id from _Book as b order by Title limit ?,?) as t);
				select c.Id,c.Categoryname from _Category as c inner join _Book_Category as bc on c.Id = bc.Category where bc.Book in (select * from (select Id from _Book as b order by Title limit ?,?) as t);";
	*/
	
	
	//$limit = 5;
	//$starting_limit = ($page-1)*$limit;
	
	
	//$query = "select * from _Book where Title like :num ;";
	//$query = "select distinct a.Id,a.FirstName,a.LastName,a.Email from _Author as a inner join _Author_Book as ab on a.Id = ab.Author inner join _Book as b on b.Id = ab.Book where b.Title like :num;";
	$val = "%".$txt."%";
	$stmt = $conn->prepare($query);
	$stmt->bindValue(':str', $val,PDO::PARAM_STR);
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
	//echo $result[0][0];
	$result = array($author_table,$author_book_table,$book_table,$book_category_table,$category_table);
	echo json_encode($result,JSON_UNESCAPED_UNICODE);
	//echo json_encode($arr,JSON_UNESCAPED_UNICODE);
	
	
	
	/*$stmt->nextRowset();
	$author_book_table = $stmt->fetchAll(PDO::FETCH_NUM);
	$stmt->nextRowset();
	$book_table = $stmt->fetchAll(PDO::FETCH_NUM);
	$stmt->nextRowset();
	$book_category_table = $stmt->fetchAll(PDO::FETCH_NUM);
	$stmt->nextRowset();
	$category_table = $stmt->fetchAll(PDO::FETCH_NUM);
	//$stmt->nextRowset();
	//$numrows = $stmt->fetchAll(PDO::FETCH_NUM);
	*/
	
	//$total_pages = ceil($numrows[0][0]/$limit);
		
	//$arr = array();
	//$arr[] = $author_table;
	
	/*$arr[] = $author_book_table;
	$arr[] = $book_table;
	$arr[] = $book_category_table;
	$arr[] = $category_table;
	*/
	
	

?>