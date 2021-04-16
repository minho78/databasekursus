<!DOCTYPE html>
<?php
require_once('initialize.php');

$query = "SELECT Id,FirstName,LastName,Email FROM _Author;
		  SELECT Id,CategoryName FROM _Category;";
$stmt = $conn->prepare($query);
$stmt->execute();
$author_table = $stmt->fetchAll(PDO::FETCH_NUM);
$stmt->nextRowset();
$category_table = $stmt->fetchAll(PDO::FETCH_NUM);
$stmt = null;
$conn = null;

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
			<button type="button" class="menubutton" id="authorsbutton" onclick="showAllAuthors();">Forfattere</button>
			<div class="dropdown">
				<button type="button" class="menubutton" id="categoriesbutton">Kategorier</button>
				<div class="dropdown-content">
					<?php
						for ($x = 0; $x < count($category_table); $x++)
							echo "<button type='button' onclick='showAllCategory(".$category_table[$x][0].")'>".$category_table[$x][1]."</button>";
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

	var full_author_table = <?php echo json_encode($author_table);?>;
	var full_category_table = <?php echo json_encode($category_table);?>;
	
	var author_book_table = [];
	var book_category_table = [];
	
	
	$('document').ready(function(){
		$('#searchtext').bind("enterKey",function(e){
		   doSearch();
		});
		$('#searchtext').keyup(function(e){
			if(e.keyCode == 13)
				$(this).trigger("enterKey");
		});
		const params = new URLSearchParams(window.location.search);
		if (params.get('allbooks') !== null)
			showAllBooks(true);
		if (params.get('allauthors') !== null)
			showAllAuthors(true);
		if (params.get('allcats') !== null) {
			if (params.get('cpage') !== null)
				showAllCategory(params.get('allcats'),true,params.get('cpage'));
			else
				showAllCategory(params.get('allcats'),true,1);
		}
		if (params.get('apage') !== null)
			showAllAuthors(true,params.get('apage'));
		if (params.get('bpage') !== null)
			showAllBooks(true,params.get('bpage'));
		if (params.get('newbook') !== null)
			addBook(true);
		if (params.get('newauthor') !== null)
			addAuthor(true);
		if (params.get('bid') !== null)
			getBookDetails(params.get('bid'));
		if (params.get('aid') !== null)
			getAuthorDetails(params.get('aid'));
		if (params.get('search') !== null)
			doSearch(true,params.get('search'));
	});
	
	// ----------------------------------------------------------------------------------------------- //
	
	function doSearch(go,searchtext) {
		if (!go) {
			var searchtext = $('#searchtext').val().toLowerCase().trim();
			window.location.href="index.php?search="+searchtext;
			return;
		}
		if (!searchtext)
			return [];
		
		$.post("getSearchResults.php", {
			searchtext: searchtext
		},
		function(data,status){
			var dat = JSON.parse(data);
			window.author_table = dat[0];
			window.author_book_table = dat[1];
			window.book_table = dat[2];
			window.book_category_table = dat[3];
			window.category_table = dat[4];
			$('#maindiv').empty();
			for (var i=0; i<dat[2].length; i++)
				showBookInfo(dat[2][i][0]);
		});
	}
	
	// ----------------------------------------------------------------------------------------------- //
	
	
	function showAllBooks(go,page) {
		if (!go) {
			window.location.href="index.php?allbooks";
			return;
		}
		if (typeof page === 'undefined')
			page = 1;
			
		$.post("getBooksOnPage.php", {
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
			for (var i=0; i<dat[2].length; i++)
				showBookInfo(dat[2][i][0]);
			showPageNavigation(totalPages,'b');
		});
	}
	
		
	function showAllAuthors(go,page) {
		if (!go) {
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
			for (var i=0; i<dat[0].length; i++)
				showAuthorInfo(dat[0][i][0],true);
			showPageNavigation(totalPages,'a');
		});
	}
	
	
	function showAllCategory(catid,go,page) {
		if (!go) {
			window.location.href="index.php?allcats="+catid;
			return;
		}
		
		if (typeof page === 'undefined')
			page = 1;
		
		$.post("getCategoryOnPage.php", {
			page: page,
			category: catid
		},
		function(data,status){
			var dat = JSON.parse(data);
			window.author_table = dat[0];
			window.author_book_table = dat[1];
			window.book_table = dat[2];
			window.book_category_table = dat[3];
			window.category_table = dat[4];
			var totalPages = dat[5];
			for (var i=0; i<dat[2].length; i++) {
				showBookInfo(dat[2][i][0]);
			}
			showPageNavigation(totalPages,'c');
		});		
		
		$('#maindiv').empty();
		var bookid = [];
		for (var i=0; i<book_category_table.length; i++) {
			if (book_category_table[i][1] == catid)
				bookid.push(book_category_table[i][0]);
		}
		
		for (var i=0; i<bookid.length; i++)
			showBookInfo(bookid[i]);
		
	}
	
	
	function showPageNavigation(totalPages,type) {
		$('#maindiv').prepend($('<div id="nav">'));
		var intxt = '';
		
		if (type=='c') {
			var params = new URLSearchParams(window.location.search);
			if (params.get('allcats') !== null) {
				intxt += '?allcats='+params.get('allcats')+'&';
			}
			intxt += 'cpage';
		}
		else
			intxt += '?'+type+'page';
		
		for (var i=1; i<=totalPages; i++) {
			$('<a href="'+intxt+'='+i+'" class="links">').html(i).appendTo('#nav');
		}
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
			authorlist.append($('<label>').text(authorFullname(authorIds[i])).attr('aid',aid).click(function(){window.location.href="index.php?aid="+$(this).attr('aid');}));
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
								window.location.href="index.php?bid="+bookId;
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
	
	function showBookDetails(bookId,go,data) {
		if (bookId != -1) {
			if (!go) {
				getBookDetails(bookId);
				return;
			}
			var aut_dat = data[0];
			var book_dat = data[1];
			var cat_dat = data[2];
		}
		else {
			var aut_dat = [];
			var book_dat = [['','','',0,0]];
			var cat_dat = [];
		}
		var aid;
		var numAuthors = aut_dat.length;
		var numCategories = cat_dat.length;
				
		var authorlist = $('<div class="bookauthor">');
		for (var i=0; i<numAuthors; i++) {
			var fullname = aut_dat[i][1]+' '+aut_dat[i][2];
			aid = aut_dat[i][0];
			if (i>0)
				authorlist.append($('<label>').text(', '));
			authorlist.append(
				$('<label>').text(fullname).attr('aid',aid).click(function(){showAuthorDetails($(this).attr('aid'))})
			);
		}
				
		var categorylist = $('<div class="category">');
		for (var i=0; i<numCategories; i++) {
			var catname = cat_dat[i][1];
			categorylist.append(
				$('<span>').append(
					$('<label class="catname">').text(catname)
				)
			);
		}
		
		makeDropdowns(authorlist,categorylist,aut_dat,cat_dat);
		
		$('#maindiv').append(
			$('<div class="book">').append(categorylist).append(
				$('<div class="title_author">').append(
					$('<div class="title">').append(
						$('<label>').text(book_dat[0][1])
					).append(
						$('<input placeholder="Titel">').attr('type','text').val(book_dat[0][1]).hide()
					)
				).append(authorlist)
			).append(
				$('<div class="price">').append(
					$('<label>').addClass('price_label').text(book_dat[0][3])
				).append(
					$('<input>').attr('type','text').val(book_dat[0][3]).hide()
				).append(
					$('<label>').text(' kr.'))
			).append(
				$('<input class="editbutton">').attr('id','bookeditbutton').attr('type','button').val("Ret oplysninger").on("click",
					function(e){
						if($('.title label').is(":hidden")) {
							var aut_ids = []; var cat_ids = [];
							for (var i=0; i<aut_dat.length; i++) {aut_ids.push(aut_dat[i]);}
							for (var i=0; i<cat_dat.length; i++) {cat_ids.push(cat_dat[i]);}
							saveBookInfo(bookId,aut_ids,cat_ids);
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
		
		$('.title_author').append($('<div class="summary">').append($('<label>').text(book_dat[0][2])).append($('<textarea placeholder="Resumé">').attr('rows',10).attr('cols',30).val(book_dat[0][2]).hide()));
		$('.title_author').append($('<div class="isbn">').append($('<label>').text('ISBN: ')).append($('<label>').addClass('isbn_label').text(book_dat[0][4])).append($('<input>').attr('type','number').val(book_dat[0][4]).hide()));
		$('.editbutton').show();
		
		if (bookId == -1)
			toggleHiddenElements();
	}
	
		
	function getBookDetails(bookId) {
		$.post("getBook.php", {
			id: bookId
		},
		function(data,status){
			var dat = JSON.parse(data);
			showBookDetails(bookId,true,dat);
		});
	}
	
	
	function saveBookInfo(bookId,autIds,catIds) {
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
				if (catIds[i][0] == thiscatid) {
					oldon = true;
				}
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
				if (autIds[i][0] == thisautid)
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
						window.location.href="index.php?bid="+dat[0][0];
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
				window.location.href="index.php?bid="+bookId;
			});
		}
	}
	
	
	function addBook(go) {
		if (!go) {
			$('#maindiv').empty();
			window.location.href="index.php?newbook";
			return;
		}
		showBookDetails(-1,true);
	}
	
	
	// ----------------------------------------------------------------------------------------------- //
	
	
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
	
	
	function showAuthorDetails(authorId,go,data) {
		if (!go) {
			getAuthorDetails(authorId);
			return;
		}
		var aut_dat = data[0];
		var book_dat = data[1];
		var cat_dat = data[2];
		
		var aid;
		var numAuthors = aut_dat.length;
		var numCategories = cat_dat.length;
		
		$('#maindiv').empty();
		
		$('#maindiv').append(
			$('<div class="author">').append(
				$('<div class="authorname">').append(
					$('<label>').text(aut_dat[0][1]+' '+aut_dat[0][2]).attr('aid',authorId))).append(
			$('<div class="authoremail">').append(
				$('<label>').text(aut_dat[0][3])))
		)
		
		$('.authorname').append(
			$('<div>').append(
				$('<input type="text" class="firstname" id="firstname'+authorId+'">').val(aut_dat[0][1])
			).append($('<input type="text" class="lastname" id="lastname'+authorId+'">').val(aut_dat[0][2])
			).hide()
		)
		$('.authoremail').append(
			$('<input type="text" class="email" id="email'+authorId+'">').val(aut_dat[0][3]).hide()
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
		for (var i=0; i<book_dat.length;i++)
			$('.authorbooks').append($('<div class="authorbook">').text(book_dat[i][1]).attr('bid',book_dat[i][0]).click(function(){window.location.href="index.php?bid="+$(this).attr('bid');}));
	}
	
	
	function getAuthorDetails(authorId) {
		$.post("getAuthor.php", {
			id: authorId
		},
		function(data,status){
			var dat = JSON.parse(data);
			showAuthorDetails(authorId,true,dat);
		});
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
	
	
	function addAuthor(go) {
		if (!go) {
			$('#maindiv').empty();
			window.location.href="index.php?newauthor";
			return;
		}
		showNewAuthorInfo();
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
	
	// ----------------------------------------------------------------------------------------------- //
	
	
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
	
	
	function makeDropdowns(alist,clist,aut_dat,cat_dat) {
		var aut_dropdown = $('<div class="dropdown-content">');
		var cat_dropdown = $('<div class="dropdown-content">');
		var numAuthors = window.full_author_table.length;
		var numCategories = window.full_category_table.length;
		for (var i=0; i<numAuthors; i++) {
			var autid = full_author_table[i][0];
			var authorName = full_author_table[i][1]+" "+full_author_table[i][2];
			var selectedaut = false;
			for(var j=0; j<aut_dat.length; j++) {if (autid==aut_dat[j][0]) selectedaut = true;}
			aut_dropdown.append($('<div class="dropdown-item">')
			.append($('<input type=checkbox>').addClass('autcb').attr('id','cbaut'+autid).prop("checked",selectedaut)).append($("<label for='cbaut'"+autid+">").html(authorName)));
		}
		alist.append(
			$('<div class="dropdown">').append(
				$('<button class="catdropdownbutton">').html('Forfattere').hide()
			).append(aut_dropdown)
		);
				
		for (var i=0; i<numCategories; i++) {
			var catid = full_category_table[i][0];//categoryInfo[i][0];
			var catname = full_category_table[i][1];//categoryInfo[i][1];
			var selectedcat = false;
			for(var j=0; j<cat_dat.length; j++) {if (catid == cat_dat[j][0]) selectedcat = true;}
			cat_dropdown.append($('<div class=dropdown-item>')
			.append($('<input type="checkbox">').addClass('catcb').attr('id','cb'+catid).prop('checked',selectedcat)).append($('<label for=cb'+catid+'>').html(catname)));
		}
		clist.append(
			$('<div class="dropdown">').append(
				$('<button class="catdropdownbutton">').html('Kategorier').hide()
			).append(cat_dropdown)
		);
	}
	
	
	// ----------------------------------------------------------------------------------------------- //
	
	
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
		return window.book_table[index][1];
	}
	function bookPrice(id) {
		var index = getBookIndexFromBookId(id);
		return window.book_table[index][3];
	}
	function authorFirstname(id) {
		var index = getAuthorIndexFromAuthorId(id);
		return window.author_table[index][1];
	}
	function authorLastname(id) {
		var index = getAuthorIndexFromAuthorId(id);
		return window.author_table[index][2];
	}
	function authorFullname(id) {
		var fullname = authorFirstname(id).trim() + ' ' + authorLastname(id).trim();
		return fullname.trim();
	}
	function authorEmail(id) {
		var index = getAuthorIndexFromAuthorId(id);
		return window.author_table[index][3];
	}
	
	function categoryName(id) {
		var index = getCategoryIndexFromCategoryId(id);
		return window.category_table[index][1];
	}
	
	function getAuthorIndexFromAuthorId(authorId) {
		var index = -1;
		for (var i=0; i<window.author_table.length; i++) {
			if (parseInt(window.author_table[i][0],10) == authorId) {
				index = i;
			}
		}
		return index;
	}
	
	function getCategoryIndexFromCategoryId(catId) {
		var index = -1;
		for (var i=0; i<window.category_table.length; i++) {
			if (parseInt(window.category_table[i][0],10) == catId) {
				index = i;
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
	
	function findCategoryId(bookId) {
		ids = [];
		for (var i=0; i<window.book_category_table.length; i++) {
			if (window.book_category_table[i][0]==bookId)
				ids.push(window.book_category_table[i][1]);
		}
		return ids;
	}	
	
	
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
	
	/*
	
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
	*/
	
	
	/*
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
	*/
	
	/*
	
	function findBooksfromAuthor(authorId) {
		bookIds = [];
		for (var i=0; i<window.author_book_table.length; i++) {
			if (parseInt(window.author_book_table[i][0],10) == authorId) {
				var bi = parseInt(window.author_book_table[i][1],10);
				bookIds.push(bi);
			}
		}
		return bookIds;
	}
	*/
	/*
	function bookSummary(id) {
		var index = getBookIndexFromBookId(id);
		return window.book_table[index][2];
	}
	function bookISBN(id) {
		var index = getBookIndexFromBookId(id);
		return window.book_table[index][4];
	}
	*/
	
	/*
	function findCategoryIndex(ids) {	
		console.log(ids);
		var index = [];
		for (var i=0; i<ids.length; i++) {
			for (var j=0; j<window.category_table.length; j++) {
				if (window.category_table[j][0] == ids[i])
					index.push(j);
			}
		}
		return index;
	}
	
	function findAuthorIndex(ids) {
		var index = [];
		for (var i=0; i<ids.length; i++) {
			for (var j=0; j<window.author_table.length; j++) {
				if (parseInt(window.author_table[j][0],10) == ids[i])
					index.push(j);
			}
		}
		return index;
	}
	*/
	
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
	/*
	function getCategoryIdFromCategoryName(catname) {
		var catid = -1;
		for (var i=0; i<window.category_table.length; i++) {
			if (window.category_table[i][1] == catname)
				catid = window.category_table[i][0];
		}
		return catid;
	}
	*/
	
	
	
	
</script>