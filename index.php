<!DOCTYPE html>
<?php
require_once('initialize.php');
/*
if (isset($_GET['page']) || isset($_GET['allbooks'])) {
	
	if (isset($_GET['page']))
		$page = $_GET['page'];
	else
		$page = 1;
	
	$query1 = "SELECT count(*) FROM _Book";
	$stmt = $conn->prepare($query1);
	$stmt->execute();
	$numrows = $stmt->fetchColumn();
	$stmt = null;
	
	$limit1 = 5;
	$total_pages = ceil($numrows/$limit1);
	$starting_limit1 = ($page-1)*$limit1;
	
	$query2  = "SELECT Id,Title,Summary,Price,ISBN FROM _Book ORDER BY Title LIMIT ?,?";
	$stmt = $conn->prepare($query2);

	$stmt->bindParam(1, $starting_limit1, \PDO::PARAM_INT);
	$stmt->bindParam(2, $limit1, \PDO::PARAM_INT);

	$stmt->execute();
	
	$book_table = $stmt->fetchAll(PDO::FETCH_NUM);
		
	for ($page=1; $page <= $total_pages ; $page++):?>
		<a href='<?php echo "?page=$page"; ?>' class="links"><?php  echo $page; ?></a>
	<?php endfor;
	
}

$query ="SELECT Id,Title,Summary,Price,ISBN FROM _Book order by title limit 5;
SELECT Id,FirstName,LastName,Email FROM _Author;
SELECT Id,CategoryName FROM _Category;
SELECT Author,Book FROM _Author_Book;
SELECT Book,Category FROM _Book_Category;";


$stmt = $conn->prepare($query);
$stmt->execute();
$tmp = $stmt->fetchAll(PDO::FETCH_NUM);
$stmt->nextRowset();
$author_table = $stmt->fetchAll(PDO::FETCH_NUM);
$stmt->nextRowset();
$category_table = $stmt->fetchAll(PDO::FETCH_NUM);
$stmt->nextRowset();
$author_book_table = $stmt->fetchAll(PDO::FETCH_NUM);
$stmt->nextRowset();
$book_category_table = $stmt->fetchAll(PDO::FETCH_NUM);
$stmt = null;
$conn = null;

if (!isset($_GET['page']) && !isset($_GET['allbooks']))
	$book_table = $tmp;

//echo json_encode($book_table,JSON_UNESCAPED_UNICODE);
*/


?>
<html lang="da">
	<head>
		<title>Books</title>
		<meta name="description" content="" />
		<script src="https://code.jquery.com/jquery-latest.min.js"></script>
		
		<link rel="stylesheet" type="text/css" href="./Style/style.css">
	</head>
	<body>
		<div id="menubuttonsdiv">
			<button type="button" class="menubutton" id="booksbutton" onclick="showAllBooks(false);">Bøger</button>
			<!--<button type="button" class="menubutton" id="booksbutton" onclick="test(0);">Bøger</button>-->
			<button type="button" class="menubutton" id="authorsbutton" onclick="showAllAuthors();">Forfattere</button>
			<div class="dropdown">
				<button type="button" class="menubutton" id="categoriesbutton">Kategorier</button>
				<div class="dropdown-content">
					<?php
						for ($x = 0; $x < count($category_table); $x++)
							echo "<button type='button' onclick='showAllCategory(\"".$category_table[$x][1]."\")'>".$category_table[$x][1]."</button>";
					?>
				</div>
			</div>
			
		</div>
		<div id="textbuttondiv">
			<input type="search" id="searchtext" placeholder="Skriv søgeord her">
			<button type="button" id="searchbutton" onclick="doSearch();">Søg</button>
			<button type="button" id="addbookbutton" onclick="addBook();">Tilføj bog</button>
			<button type="button" id="addauthorbutton" onclick="addAuthor();">Tilføj forfatter</button>
		</div>
		<div id="maindiv"></div>
	</body>
</html>

<script>
	/*
	var book_table = <?php echo json_encode($book_table);?>;
	var author_table = <?php echo json_encode($author_table);?>;
	var category_table = <?php echo json_encode($category_table);?>;
	var author_book_table = <?php echo json_encode($author_book_table);?>;
	var book_category_table = <?php echo json_encode($book_category_table);?>;
	var dat = author_table;
	*/
	var author_book_table = [];
	var book_category_table = [];
	
	
	$('document').ready(function(){
		$('#searchtext').bind("enterKey",function(e){
		   doSearch();
		});
		$('#searchtext').keyup(function(e){
			if(e.keyCode == 13)
			{
				$(this).trigger("enterKey");
			}
		});
		const params = new URLSearchParams(window.location.search);
		if (params.get('allbooks') !== null)
			showAllBooks(true);
		if (params.get('allauthors') !== null)
			showAllAuthors(true);
		if (params.get('page') !== null)
			showAllBooks(true,params.get('page'));
		if (params.get('newbook') !== null)
			addBook(true);
		if (params.get('id') !== null) {
			if (typeof window.book_table !== 'undefined' && typeof window.author_table !== 'undefined' && typeof window.category_table !== 'undefined' && typeof window.author_book_table !== 'undefined' && typeof window.book_category_table !== 'undefined')
				showBookInfoDetails(params.get('id'));
		}
		/*
		
		if (params.get('id') !== null)
			getBookInfo(params.get('id'),true);
			//showBookInfoDetails([[],[params.get('id')],[]],true);
		
		if (params.get('newauthor') !== null)
			addAuthor(true);
		if (params.get('aid') !== null)
			showAuthorDetails(params.get('aid'),true);
			//	showAuthorInfo(params.get('aid'),true);
		
		*/
	});
	
	// ----------------------------------------------------------------------------------------------- //
	
	function doSearch() {
		var searchtext = $('#searchtext').val();
		var ind = getBookIdsMatchingSearchText(searchtext);
		$('#maindiv').empty();
		for (var i=0; i<ind.length; i++) {
			showBookInfo(ind[i]);
		}		
	}
	
	function getBookIdsMatchingSearchText(searchtext) {
		searchtext = searchtext.toLowerCase().trim();
		if (!searchtext)
			return [];
		
		var id = [];
		for (var i=0; i<book_table.length; i++) {
			if (book_table[i][1].toLowerCase().search(searchtext) != -1)
				id.push(book_table[i][0]);
			else if (book_table[i][2].toLowerCase().search(searchtext) != -1)
				id.push(book_table[i][0]);
			else if (book_table[i][3].toLowerCase().search(searchtext) != -1)
				id.push(book_table[i][0]);
			else if (book_table[i][4].toLowerCase().search(searchtext) != -1)
				id.push(book_table[i][0]);
		}
		
		var aut_ids = [];
		for (i=0; i<author_table.length; i++) {
			if (author_table[i][1].toLowerCase().search(searchtext) != -1 || author_table[i][2].toLowerCase().search(searchtext) != -1)
				aut_ids.push(author_table[i][0]);
		}
		for (i=0; i<author_book_table.length; i++) {
			for (var j=0; j<aut_ids.length; j++) {
				if (author_book_table[i][0] == aut_ids[j])
					id.push(author_book_table[i][1]);
			}
		}
		
		var cat_ids = [];
		for (i=0; i<category_table.length; i++) {
			if (category_table[i][1].toLowerCase().search(searchtext) != -1)
				cat_ids.push(category_table[i][0]);
		}
		for (i=0; i<book_category_table.length; i++) {
			for (var j=0; j<cat_ids.length; j++) {
				if (book_category_table[i][1] == cat_ids[j])
					id.push(book_category_table[i][0]);
			}
		}
		
		id = id.unique();
		
		//var fn = window["cmpPrice"]; // OR e.g. fn = window["cmpTitle"];
		//id.sort(fn);
		return id;
	}
	
	// ----------------------------------------------------------------------------------------------- //
	
	
	function showAllBooks(go,page) {
		console.log("showAllBooks");
		if (!go) {
			//$('#maindiv').empty();
			window.location.href="index.php?allbooks";
			return;
		}
		if (typeof page === 'undefined')
			page = 1;
			
		$.post("getBooksOnPage.php", {
			page: page
		},
		function(data,status){
			//console.log(data);
			var dat = JSON.parse(data);
			window.author_table = dat[0];
			window.author_book_table = dat[1];
			window.book_table = dat[2];
			window.book_category_table = dat[3];
			window.category_table = dat[4];
			var totalPages = dat[5];
			//showBookInfoDetails([dat[1],dat[0],dat[2]],true);
			for (var i=0; i<dat[2].length; i++)
				showBookInfo(dat[2][i][0]);
			showPageNavigation(totalPages);
		});
	}
	
	function showPageNavigation(totalPages) {
		$('#maindiv').prepend($('<div id="nav">'));
		for (var i=1; i<=totalPages; i++) {
			$('<a href="?page='+i+'" class="links">').html(i).appendTo('#nav');
		}
	}
	
	
	
	
	
	/*
	
	function showAllBooks(go) {
		if (!go) {
			$('#maindiv').empty();
			window.location.href="index.php?allbooks";
		}
		//$('#maindiv').empty();
		var ids = [];
		for (var i=0; i<book_table.length; i++)
			ids.push(book_table[i][0]);
		
		var fn = window["cmpTitle"];
		ids.sort(fn);
		
		for (var i=0; i<ids.length; i++)
			showBookInfo(ids[i]);
	}
	*/
	
	
	
	function showAllAuthors(go,page) {
		if (!go) {
			$('#maindiv').empty();
			window.location.href="index.php?allauthors";
			return;
		}
		
		if (typeof page === 'undefined')
			page = 1;
		
		$.post("getAuthorsOnPage.php", {
			page: page
		},
		function(data,status){
			var dat = JSON.parse(data);
			window.author_table = dat[0];
			window.author_book_table = dat[1];
			window.book_table = dat[2];
			window.book_category_table = dat[3];
			window.category_table = dat[4];
			var totalPages = dat[5];
			//showBookInfoDetails([dat[1],dat[0],dat[2]],true);
			for (var i=0; i<dat[2].length; i++)
				showBookInfo(dat[2][i][0]);
			showPageNavigation(totalPages);
		});
		
		/*
		//$('#maindiv').empty();
		var ids = [];
		for (var i=0; i<author_table.length; i++)
			ids.push(author_table[i][0]);
		
		//var fn = window["cmpLastname"];//fn = window["cmpFirstname"];
		//ids.sort(fn);
		
		for (var i=0; i<ids.length; i++)
			showAuthorInfo(ids[i],true);
		*/
	}
	
	
	
	
	
	function showAllCategory(cat) {
		catid = getCategoryIdFromCategoryName(cat);
		
		$('#maindiv').empty();
		var bookid = [];
		for (var i=0; i<book_category_table.length; i++) {
			if (book_category_table[i][1] == catid)
				bookid.push(book_category_table[i][0]);
		}
		
		//var fn = window["cmpTitle"];
		//bookid.sort(fn);
		
		for (var i=0; i<bookid.length; i++)
			showBookInfo(bookid[i]);
	}
	
	// ----------------------------------------------------------------------------------------------- //
	
	function addAuthor(go) {
		if (!go) {
			$('#maindiv').empty();
			window.location.href="index.php?newauthor";
		}
		//showAuthorInfo(1);
		showNewAuthorInfo();
		//$('#authoreditbutton').trigger('click');
		//$('.saveeditbutton').trigger('click');
	}
	
	function addBook(go) {
		if (!go) {
			$('#maindiv').empty();
			window.location.href="index.php?newbook";
		}
		/*
		var d1 = [[-1,'','','']];
		var d2 = [[-1,'','',0,0]];
		var d3 = [[-1,'']];		
		*/
		
		//showBookInfoDetails([d1,d2,d3]);
		showBookInfoDetails(-1);
		toggleHiddenElements();
		$('#bookeditbutton').attr("value","Gem oplysninger");
		//$('#bookeditbutton').trigger('click');
		
		
	}
	
	function addCategory() {
		
	}
	
	
	// ----------------------------------------------------------------------------------------------- //
	
	
	
	function showBookInfo(bookId) {
		var authorIds = findAuthorId(bookId);
		var authorlist = $('<div class="bookauthor">');
		var aid;
		for (var i=0; i<authorIds.length; i++) {
			aid = authorIds[i];
			if (i>0)
				authorlist.append($('<label>').text(', '));
			authorlist.append($('<label>').text(authorFullname(authorIds[i])).attr('aid',aid).click(function(){showAuthorDetails($(this).attr('aid'))}));
		}
		
		categoryIds = findCategoryId(bookId);
		var categorylist = $('<div class="category">');
		for (var i=0; i<categoryIds.length; i++)
			categorylist.append($('<span>').append($('<label class="catname">').text(categoryName(categoryIds[i]))));
		
		$('#maindiv').append(
			$('<div class="book">').append(categorylist).append(
				$('<div class="title_author">').append(
					$('<div class="title">').append(
						$('<label>').text(bookTitle(bookId)).click(
							function(){
								//getBookInfo(bookId);
								showBookInfoDetails(bookId);
								//showBookDetails(bookId);
							}
						)
					)
				).append(authorlist)
			).append(
				$('<div class="price">').append(
					$('<label>').addClass('price_label').text(bookPrice(bookId))
				).append(
					$('<label>').text(' kr.')
				)
			)
		);
	}
	
	/*
	function showBookDetails(bookId,go) {
		console.log('showBookDetails');
		if (!go)
			window.location.href="index.php?id="+bookId;
		//var index = getBookIndexFromBookId(bookId);
		$('#maindiv').empty();
		//showBookInfo(bookId);
		getBookInfo(bookId);
	}
	*/
	
	function getBookInfo(bookId,go) {
		if (!go) {
			$('#maindiv').empty();
			window.location.href="index.php?id="+bookId;
		}
		if (bookId == -1) {
			var d1 = [[-1,'','','']];
			var d2 = [[-1,'','',0,1]];
			var d3 = [[-1,'']];
			showBookInfoDetails([d1,d2,d3]);
		}
		else {
			$.post("getBookInfo.php", {
				id: bookId	
			},
			function(data,status){
				var dat = JSON.parse(data);
				showBookInfoDetails(dat,true);
			});
		}
	}
	
	
	
	function getAuthorInfo(authorId,go) {
		if (!go) {
			$('#maindiv').empty();
			window.location.href="index.php?aid="+authorId;
		}
		if (authorId == -1) {
			var d1 = [[-1,'','','']];
			var d2 = [[-1,'','',0,1]];
			var d3 = [[-1,'']];
			showAuthorDetails([d1,d2,d3]);
		}
		else {
			$.post("getAuthorInfo.php", {
				id: authorId	
			},
			function(data,status){
				var dat = JSON.parse(data);
				showAuthorDetails(dat,true);
			});
		}
	}
	
	
	//function showBookInfoDetails(data) {
	function showBookInfoDetails(bookId) {
			$('#maindiv').empty();
		console.log('showBookInfoDetails');
		
		if (bookId == -1) {
			var authorIds = [];
			var bookInfo = [];
			var categoryIds = [];
		}
		else {
			var authorIds = findAuthorId(bookId);
			var bookInfo = window.book_table[getBookIndexFromBookId(bookId)];
			var categoryIds = findCategoryId(bookId);
		}
		
		//var authorInfo = findAuthorId(bookId);
		
		
		/*
		
		//$('#bookeditbutton').click();
		*/
		
		
		/*
		var authorInfo = data[0];
		var bookInfo = data[1];
		var categoryInfo = data[2];
		var bookId = bookInfo[0][0];
		*/
		var numAuthors = authorIds.length;
		var numCategories = categoryIds.length;
		var authorlist = $('<div class="bookauthor">');
		var aid;
		var aut_dropdown = $('<div class="dropdown-content">');
		
		for (var i=0; i<numAuthors; i++) {
			var autid = authorIds[i][2];
			var authorName = author_table[i][1]+" "+author_table[i][2];
			var selectedaut = false;
			for(var j=0; j<numAuthors; j++) {if (autid==authorIds[j][0]) selectedaut = true;}
			aut_dropdown.append($('<div class="dropdown-item">')
			.append($('<input type=checkbox>').addClass('autcb').attr('id','cbaut'+autid).prop("checked",selectedaut)).append($("<label for='cbaut'"+autid+">").html(authorName)));
		}
		
		/*
		<?php
			for ($x = 0; $x < count($author_table); $x++) {
				//echo "console.log('".$author_table[$x][2]."');";
				$author_id = $author_table[$x][0];
				$author_name = $author_table[$x][1]." ".$author_table[$x][2];
				echo "var selectedaut = false;";
				echo "for(var i=0; i<numAuthors; i++) {if (".$author_id."==authorInfo[i][0]) selectedaut = true;}";
				echo "aut_dropdown.append($('<div class=\"dropdown-item\">')";
				echo ".append($('<input type=\"checkbox\">').addClass('autcb').attr('id','cbaut".$author_id."').prop('checked',selectedaut)).append($('<label for=\"cbaut".$author_id."\">').html('".$author_name."')));";
			}
		?>
		*/
		authorlist.append(
			$('<div class="dropdown">').append(
				$('<button class="catdropdownbutton">').html('Forfattere').hide()
			).append(aut_dropdown)
		);
		
		for (var i=0; i<numAuthors; i++) {
			var fullname = authorFullname(authorIds[i]);//authorInfo[i][1] + ' '+authorInfo[i][2];
			aid = authorIds[i];//authorInfo[i][0];
			if (i>0)
				authorlist.append($('<label>').text(', '));
			authorlist.append(
				$('<label>').text(fullname).attr('aid',aid).click(function(){showAuthorDetails($(this).attr('aid'))})
			);
		}
		
		var cat_dropdown = $('<div class="dropdown-content">');
		
		for (var i=0; i<numCategories; i++) {
			var catid = categoryIds[i];//categoryInfo[i][0];
			var catname = categoryName(catid);//categoryInfo[i][1];
			var selectedcat = false;
			for(var j=0; j<numCategories; j++) {if (catid == categoryIds[j]) selectedcat = true;}
			cat_dropdown.append($('<div class=dropdown-item>')
			.append($('<input type="checkbox">').addClass('catcb').attr('id','cb'+catid).prop('checked',selectedcat)).append($('<label for=cb'+catid+'>').html(catname)));
		}
		/*
		<?php
			for ($x = 0; $x < count($category_table); $x++) {
				$category_id = $category_table[$x][0];
				$category_name = $category_table[$x][1];
				echo "var selectedcat = false;";
				echo "for(var i=0; i<numCategories; i++) {if (".$category_id."==categoryInfo[i][0]) selectedcat = true;}";
				echo "cat_dropdown.append($('<div class=\"dropdown-item\">')";
				echo ".append($('<input type=\"checkbox\">').addClass('catcb').attr('id','cb".$category_id."').prop('checked',selectedcat)).append($('<label for=\"cb".$category_id."\">').html('".$category_name."')));";
			}
		?>
		*/
		var categorylist = $('<div class="category">');
		for (var i=0; i<numCategories; i++) {
			categorylist.append(
				$('<span>').append(
					$('<label class="catname">').text(categoryName(categoryIds[i]))//categoryInfo[i][1])
				)
			);
		}
				
		categorylist.append(
			$('<div class="dropdown">').append(
				$('<button class="catdropdownbutton">').html('Kategorier').hide()
			).append(cat_dropdown)
		);
		$('#maindiv').append(
			$('<div class="book">').append(categorylist).append(
				$('<div class="title_author">').append(
					$('<div class="title">').append(
						$('<label>').text(bookInfo[1])//.click(function(){getBookInfo(bookId);})
					).append(
						$('<input placeholder="Titel">').attr('type','text').val(bookInfo[1]).hide()
					)
				).append(authorlist)
			).append(
				$('<div class="price">').append(
					$('<label>').addClass('price_label').text(bookInfo[3])
				).append(
					$('<input>').attr('type','text').val(bookInfo[3]).hide()
				).append(
					$('<label>').text(' kr.'))
			).append(
				$('<input class="editbutton">').attr('id','bookeditbutton').attr('type','button').val("Ret oplysninger").on("click",
					function(e){
						if($('.title label').is(":hidden")) {
							saveBookInfo(bookId,categoryIds,authorIds);
							//saveBookInfo(bookId,categoryInfo,authorInfo);
							$('#bookeditbutton').attr("value","Ret oplysninger");
						}
						else
							$('#bookeditbutton').attr("value","Gem oplysninger");
						toggleHiddenElements();
					})
			).on("click",function(e){
				$(this).addClass("selected").siblings().removeClass("selected");
			})
		);
		$('.title_author').append($('<div class="summary">').append($('<label>').text(bookInfo[2])).append($('<textarea placeholder="Resumé">').attr('rows',10).attr('cols',30).val(bookInfo[2]).hide()));
		$('.title_author').append($('<div class="isbn">').append($('<label>').text('ISBN: ')).append($('<label>').addClass('isbn_label').text(bookInfo[4])).append($('<input>').attr('type','number').val(bookInfo[4]).hide()));
		$('.editbutton').show();
	}
	
	function toggleHiddenElements() {
		$('.title input').toggle();
		$('.title label').toggle();
		//$('.saveeditbutton').toggle();
		//$('.editbutton').toggle();
		$('.summary textarea').toggle();
		$('.summary label').toggle();
		$('.isbn input').toggle();
		$('.isbn_label').toggle();
		$('.price input').toggle();
		$('.price_label').toggle();
		$('.bookauthor > label').toggle();
		$('.catdropdownbutton').toggle();
		$('.catname').toggle();
	}
	
	
	
	function showAuthorInfo(authorId,go) {
		if (!go) {
			$('#maindiv').empty();
			window.location.href="index.php?aid="+authorId;
		}
		$('#maindiv').append(
			$('<div class="author">').append(
				$('<div class="authorname">').append(
					$('<label>').text(authorFullname(authorId)).attr('aid',authorId).click(
						function(){
							showAuthorDetails($(this).attr('aid'))
						}
				))).append(
			$('<div class="authoremail">').append(
				$('<label>').text(authorEmail(authorId))))
		)
	}
	
	
	function showAuthorDetails(authorId,go) {
		console.log('showAuthorDetails');
		if (!go) {
			$('#maindiv').empty();
			window.location.href="index.php?aid="+authorId;
		}
		var bookIds = findBooksfromAuthor(authorId);
		//$('#maindiv').empty();
		showAuthorInfo(authorId,true);
		$('.authorname').append(
			$('<div>').append(
				$('<input type="text" class="firstname" id="firstname'+authorId+'">').val(authorFirstname(authorId))
			).append($('<input type="text" class="lastname" id="lastname'+authorId+'">').val(authorLastname(authorId))
			).hide()
		)
		$('.authoremail').append(
			$('<input type="text" class="email" id="email'+authorId+'">').val(authorEmail(authorId)).hide()
		)
		
		$('.author').append(
			$('<div class="authorbooks">').html('Forfatterens bøger:<br/>')
		).append(
			$('<input class="editbutton">').attr('id','authoreditbutton').attr('type','button').val("Ret oplysninger").on("click",
				function(e){
					$('.authorname>label').toggle();
					$('.authorname>div').toggle();
					$('.authoremail>label').toggle();
					$('.authoremail>input').toggle();
					//$('.bookauthor>label').toggle();
					if(!$('.authorname>label').is(":hidden")) {
						saveAuthorInfo(authorId);
						$('#authoreditbutton').attr("value","Ret oplysninger");
					}
					else
						$('#authoreditbutton').attr("value","Gem oplysninger");
				}
			)
		);
		for (var i=0; i<bookIds.length;i++)
			$('.authorbooks').append($('<div class="authorbook">').text(bookTitle(bookIds[i])).attr('bid',bookIds[i]).click(function(){getBookInfo($(this).attr('bid'));}));
	}	
	
	
	
	function saveBookInfo(bookId,catIds,autIds) {
		var title = $('.title input').val().trim();
		var summary = $('.summary textarea').val();
		var price = $('.price input').val();
		var isbn = $('.isbn input').val();
		
		if (!title) {
			alert('Bogen skal have en titel.');
			toggleHiddenElements();
			window.location.href="index.php?newbook";
			return;
		}
		
		var cat_off = [];
		var cat_on = [];
		$('.catcb').each(function(){
			var thiscatid = Number($(this).attr('id').slice(2));
			var thison = this.checked;
			var oldon = false;
			for (var i=0; i<catIds.length; i++) {
				if (catIds[i] == thiscatid)
					oldon = true;
			}
			if (thison && !oldon)
				cat_on.push(thiscatid);
			else if (!thison && oldon)
				cat_off.push(thiscatid);
		});
		
		var aut_off = [];
		var aut_on = [];
		$('.autcb').each(function(){
			var thisautid = Number($(this).attr('id').slice(5));
			var thison = this.checked;
			var oldon = false;
			for (var i=0; i<autIds.length; i++) {
				if (autIds[i] == thisautid)
					oldon = true;
			}
			if (thison && !oldon)
				aut_on.push(thisautid);
			else if (!thison && oldon)
				aut_off.push(thisautid);
		});
		if (bookId == -1) {
			$.post("ISBNexists.php", {
				isbn: isbn
			},
			function(data,status){
				if (data == 0) {
					$.post("createBook.php", {
						title: title,
						summary: summary,
						price: price,
						isbn: isbn,
						caton: cat_on,
						auton: aut_on
					},
					function(data,status){
						var dat = JSON.parse(data);
						$('.title label').text(title);
						$('.summary label').text(summary);
						$('.price_label').text(price);
						$('.isbn_label').text(isbn);
						window.location.href="index.php?id="+dat[0][0];
					});
				}
				else {
					alert('ISBN skal være unik.');
					window.location.href="index.php?newbook";
				}
			});
		}
		else {
			$.post("updateBookInfo.php", {
				id: bookId,
				title: title,
				summary: summary,
				price: price,
				isbn: isbn,
				caton: cat_on,
				catoff: cat_off,
				auton: aut_on,
				autoff: aut_off
			},
			function(data,status){
				$('.title label').text(title);
				$('.summary label').text(summary);
				$('.price_label').text(price);
				$('.isbn_label').text(isbn);
				window.location.href="index.php?id="+bookId;
			});
		}
	}
	
	
	
	
	function saveAuthorInfo(authorId) {
		var firstname = $('.firstname').val();
		var lastname = $('.lastname').val();
		var email = $('.email').val();
		
		$.post("updateAuthorInfo.php", {
			id: authorId,
			firstname: firstname,
			lastname: lastname,
			email: email
		},
		function(data,status){
			window.location.href="index.php?aid="+authorId;
		});
	}
	
	function createAuthor() {
		var firstname = $('.firstname').val().trim();
		var lastname = $('.lastname').val().trim();
		var email = $('.email').val().trim();
		
		if (!firstname || !lastname || !email) {
			alert('Fornavn, efternavn og email skal angives.');
			window.location.href="index.php?newauthor";
			return;
		}
			
		
		$.post("createAuthor.php", {
			firstname: firstname,
			lastname: lastname,
			email: email
		},
		function(data,status){
			window.location.href="index.php?aid="+data;
		});
	}
	
	function showNewAuthorInfo() {
		$('#maindiv').append(
			$('<div class="author">').append(
				$('<div class="authorname">')
			).append(
				$('<div class="authoremail">')
			)
		)
		$('.authorname').append(
			$('<div>').append(
				$('<input type="text" class="firstname" placeholder="Fornavn">').val('')
			).append($('<input type="text" class="lastname" placeholder="Efternavn">').val('')
			)
		)
		$('.authoremail').append(
			$('<input type="text" class="email" placeholder="Email">').val('')
		)
		
		$('.author').append(
			$('<input class="editbutton">').attr('id','authoreditbutton').attr('type','button').val("Gem oplysninger").on("click",
				function(e){
					$('.authorname>label').toggle();
					$('.authorname>div').toggle();
					$('.authoremail>label').toggle();
					$('.authoremail>input').toggle();
					//$('.bookauthor>label').toggle();
					if(!$('.authorname>label').is(":hidden")) {
						createAuthor($('.firstname').val(),$('.lastname').val(),$('.email').val());
						$('#authoreditbutton').attr("value","Gem oplysninger");
					}
					else
						$('#authoreditbutton').attr("value","Ret oplysninger");
				}
			)
		);
	}
	
	
	
	/*
	function showNewAuthorInfo(authorId,go) {
		if (!go)
			window.location.href="index.php?aid="+authorId;
		var authorIndex = findAuthorIndex([authorId]);
		
		$('#maindiv').append(
			$('<div class="author">').append(
				$('<div class="authorname">').append($('<label>').text(author_table[authorIndex][1]+' '+author_table[authorIndex][2]).attr('aid',authorId).click(function(){showAuthorDetails($(this).attr('aid'))}))
				.append($('<div>').append($('<input type="text" class="firstname" id="firstname'+authorId+'">').val('asdfkhaskl')).append($('<input type="text" class="lastname" id="lastname'+authorId+'">').val('adfldznfl'))))
				.append($('<div class="authoremail">').append($('<label>').text(author_table[authorIndex][3]).hide()).append($('<input type="text" class="email" id="email'+authorId+'">').val('afadsdf')))
		.append($('<input class="editbutton">').attr('id','authoreditbutton').attr('type','button').val("Ret oplysninger").on("click",function(e){
				//$('.authorname>label').hide();
				$('.authorname>div').show();
				//$('.authoremail>label').hide();
				$('.authoremail>input').show();
				$('.saveeditbutton').show();
				//$('#authoreditbutton').hide();
				//$('.bookauthor > label').hide();
			})).append($('<input class="saveeditbutton">').attr('type','button').val("Gem oplysninger").on("click",function(e){
				$('.authorname>label').show();
				//$('.authorname>div').hide();
				$('.authoremail>label').show();
				//$('.authoremail>input').hide();
				//$('.saveeditbutton').hide();
				$('#authoreditbutton').show();
				$('.bookauthor > label').show();
				//saveAuthorInfo(authorId);
			})));
	}
	*/
	
	
	
	
	function findBooksfromAuthor(authorId) {
		bookIds = [];
		for (var i=0; i<author_book_table.length; i++) {
			if (parseInt(author_book_table[i][0],10) == authorId) {
				var bi = parseInt(author_book_table[i][1],10);
				bookIds.push(bi);
			}
		}
		return bookIds;
	}
	
	function getBookIndexFromBookId(bookId) {
		var index = -1;
		for (var i=0; i<book_table.length; i++) {
			if (parseInt(book_table[i][0],10) == bookId) {
				index = i;
			}
		}
		return index;
	}
	
	function bookTitle(id) {
		var index = getBookIndexFromBookId(id);
		return book_table[index][1];
	}
	function bookSummary(id) {
		var index = getBookIndexFromBookId(id);
		return book_table[index][2];
	}
	function bookPrice(id) {
		var index = getBookIndexFromBookId(id);
		return book_table[index][3];
	}
	function bookISBN(id) {
		var index = getBookIndexFromBookId(id);
		return book_table[index][4];
	}
	
	function authorFirstname(id) {
		var index = getAuthorIndexFromAuthorId(id);
		return author_table[index][1];
	}
	function authorLastname(id) {
		var index = getAuthorIndexFromAuthorId(id);
		return author_table[index][2];
	}
	function authorFullname(id) {
		var fullname = authorFirstname(id).trim() + ' ' + authorLastname(id).trim();
		return fullname.trim();
	}
	function authorEmail(id) {
		var index = getAuthorIndexFromAuthorId(id);
		return author_table[index][3];
	}
	
	function categoryName(id) {
		var index = getCategoryIndexFromCategoryId(id);
		return category_table[index][1];
	}
	
	function getAuthorIndexFromAuthorId(authorId) {
		var index = -1;
		for (var i=0; i<author_table.length; i++) {
			if (parseInt(author_table[i][0],10) == authorId) {
				index = i;
			}
		}
		return index;
	}
	
	function getCategoryIdFromCategoryName(catname) {
		var catid = -1;
		for (var i=0; i<category_table.length; i++) {
			if (category_table[i][1] == catname)
				catid = category_table[i][0];
		}
		return catid;
	}
	
	function getCategoryIndexFromCategoryId(catId) {
		var index = -1;
		for (var i=0; i<category_table.length; i++) {
			if (parseInt(category_table[i][0],10) == catId) {
				index = i;
			}
		}
		return index;
	}
	
	function findCategoryIndex(ids) {	
		console.log(ids);
		var index = [];
		for (var i=0; i<ids.length; i++) {
			for (var j=0; j<category_table.length; j++) {
				if (category_table[j][0] == ids[i])
					index.push(j);
			}
		}
		return index;
	}
	
	function findAuthorId(bookId) {
		ids = [];
		for (var i=0; i<window.author_book_table.length; i++) {
			if (window.author_book_table[i][1]==bookId)
				ids.push(window.author_book_table[i][0]);
		}
		return ids;
	}
	
	function findAuthorIndex(ids) {
		var index = [];
		for (var i=0; i<ids.length; i++) {
			for (var j=0; j<author_table.length; j++) {
				if (parseInt(author_table[j][0],10) == ids[i])
					index.push(j);
			}
		}
		return index;
	}
	
	function findCategoryId(bookId) {
		ids = [];
		for (var i=0; i<book_category_table.length; i++) {
			if (book_category_table[i][0]==bookId)
				ids.push(book_category_table[i][1]);
		}
		return ids;
	}
	
	
	
	// ----------------------------------------------------------------------------------------------- //
	
	function cmpPrice(a,b) {
		var x = book_table[getBookIndexFromBookId(a)][3];
		var y = book_table[getBookIndexFromBookId(b)][3];
		return x-y;
	}
	
	function cmpTitle(a,b) {
		var x = book_table[getBookIndexFromBookId(a)][1].toLowerCase();
		var y = book_table[getBookIndexFromBookId(b)][1].toLowerCase();
		if (x < y)
			return -1;
		if (x > y)
			return 1;
		return 0;
	}
	
	function cmpFirstname(a,b) {
		var x = author_table[getAuthorIndexFromAuthorId(a)][1].toLowerCase();
		var y = author_table[getAuthorIndexFromAuthorId(b)][1].toLowerCase();
		if (x < y)
			return -1;
		if (x > y)
			return 1;
		return 0;
	}
	
	function cmpLastname(a,b) {
		var x = author_table[getAuthorIndexFromAuthorId(a)][2].toLowerCase();
		var y = author_table[getAuthorIndexFromAuthorId(b)][2].toLowerCase();
		if (x < y)
			return -1;
		if (x > y)
			return 1;
		return 0;
	}
	
	/*
	function cmpCategoryName(a,b) {
		var x = category_table[getCategoryIndexFromCategoryId(a)][1];
		var y = category_table[getCategoryIndexFromCategoryId(b)][1];
		if (x < y)
			return -1;
		if (x > y)
			return 1;
		return 0;
	}
	*/
	
	/*
	function changestate(obj) {
		if (obj.checked)
            obj.parentNode.classList.add("checked");
        else
            obj.parentNode.classList.remove("checked");
	}
	*/
	
	// ----------------------------------------------------------------------------------------------- //

	Array.prototype.unique = function () {
        var arrVal = this;
        var uniqueArr = [];
        for (var i = arrVal.length; i--; ) {
            var val = arrVal[i];
            if ($.inArray(val, uniqueArr) === -1)
                uniqueArr.unshift(val);
        }
        return uniqueArr;
    }
	
	// ----------------------------------------------------------------------------------------------- //
	
</script>