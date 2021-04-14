-- Creating table Author
DROP TABLE IF EXISTS `_Author`;
CREATE TABLE `_Author`
(
    Id INT NOT NULL AUTO_INCREMENT,
    FirstName VARCHAR(50) CHARACTER SET utf8 NOT NULL,
	LastName VARCHAR(50) CHARACTER SET utf8,
	Email VARCHAR(50) CHARACTER SET utf8 UNIQUE NOT NULL,
    PRIMARY KEY (Id)
);

-- Creating table Book
DROP TABLE IF EXISTS `_Book`;
CREATE TABLE `_Book`
(
    Id INT NOT NULL AUTO_INCREMENT,
    Title VARCHAR(50) CHARACTER SET utf8 NOT NULL,
	Summary VARCHAR(200) CHARACTER SET utf8,
	Price FLOAT,
	ISBN BIGINT UNIQUE NOT NULL,
    PRIMARY KEY (Id)
);

-- Creating table Category
DROP TABLE IF EXISTS `_Category`;
CREATE TABLE `_Category`
(
    Id INT NOT NULL AUTO_INCREMENT,
    CategoryName VARCHAR(50) CHARACTER SET utf8 UNIQUE NOT NULL,
    PRIMARY KEY (Id)
);

-- Creating table Author_Book
DROP TABLE IF EXISTS `_Author_Book`;
CREATE TABLE `_Author_Book`
(
    Author INT NOT NULL,
    Book INT NOT NULL,
    PRIMARY KEY (Author, Book),
	FOREIGN KEY (Author) REFERENCES _Author(Id),
	FOREIGN KEY (Book) REFERENCES _Book(Id)
);

-- Creating table Book_Category
DROP TABLE IF EXISTS `_Book_Category`;
CREATE TABLE `_Book_Category`
(
    Book INT NOT NULL,
	Category INT NOT NULL,
    PRIMARY KEY (Book, Category),
	FOREIGN KEY (Book) REFERENCES _Book(Id),
	FOREIGN KEY (Category) REFERENCES _Category(Id)
);

-- Inserting data to table Author
INSERT INTO _Author
(FirstName, LastName, Email)
VALUES
('Hans Christjan','Andersen','hca@fanzy.dk'),
('Astrid','Lundgren','al@fanzy.dk'),
('Cecilie','Bødker','cb@fanzy.dk'),
('Caren','Blixen','id@fanzy.dk'),
('Dennis','Jørgensen','dj@fanzy.dk'),
('Tove','Ditlewsen','td@fanzy.dk'),
('Søren','Kirkegaard','sk@fanzy.dk'),
('Johan V.','Jensen','jvj@fanzy.dk'),
('Claus','Rifbjerg','cr@fanzy.dk'),
('Bjørn','Reuter','br@fanzy.dk');

-- Inserting data to table Author using stored procedure
CALL stp_InsertAuthor('Hans Christjan','Andersen','hca@fanzy.dk');
CALL stp_InsertAuthor('Astrid','Lundgren','al@fanzy.dk');
CALL stp_InsertAuthor('Cecilie','Bødker','cb@fanzy.dk');
CALL stp_InsertAuthor('Caren','Blixen','id@fanzy.dk');
CALL stp_InsertAuthor('Dennis','Jørgensen','dj@fanzy.dk');
CALL stp_InsertAuthor('Tove','Ditlewsen','td@fanzy.dk');
CALL stp_InsertAuthor('Søren','Kirkegaard','sk@fanzy.dk');
CALL stp_InsertAuthor('Johan V.','Jensen','jvj@fanzy.dk');
CALL stp_InsertAuthor('Claus','Rifbjerg','cr@fanzy.dk');
CALL stp_InsertAuthor('Bjørn','Reuter','br@fanzy.dk');

-- Inserting data to table Book
INSERT INTO _Book2
(Title, Summary, Price, ISBN)
VALUES
('Lækre desserter','',199,9785128726353),
('Den blå traktor','',149,9788832539011),
('Uldstrik','',399,9785032410386),
('Robotter og tærter','',449,9780576360753),
('Den lille marsmand','',139,9785396962163),
('Karper og kapper','',529,9788649846258),
('Fantastiske håndklæder','',679,9786505878191),
('Er alle hunde grønne?','',229,9780465242023),
('Kontrabassisten','',349,9780817584542),
('En jakke, to jakker...','',389,9788215154725),
('Sommerhusstil','',299,9782237320427),
('Retter med skvadderkål','',179,9788651674870),
('Den musikalske kat','',349,9780800214333),
('Forårsløg','',129,9782549402262),
('Farver og striber','',469,9787695633812);

-- Inserting data to table Book using stored procedure
CALL stp_InsertBook('Lækre desserter','',199,9785128726353);
CALL stp_InsertBook('Den blå traktor','',149,9788832539011);
CALL stp_InsertBook('Uldstrik','',399,9785032410386);
CALL stp_InsertBook('Robotter og tærter','',449,9780576360753);
CALL stp_InsertBook('Den lille marsmand','',139,9785396962163);
CALL stp_InsertBook('Karper og kapper','',529,9788649846258);
CALL stp_InsertBook('Fantastiske håndklæder','',679,9786505878191);
CALL stp_InsertBook('Er alle hunde grønne?','',229,9780465242023);
CALL stp_InsertBook('Kontrabassisten','',349,9780817584542);
CALL stp_InsertBook('En jakke, to jakker...','',389,9788215154725);
CALL stp_InsertBook('Sommerhusstil','',299,9782237320427);
CALL stp_InsertBook('Retter med skvadderkål','',179,9788651674870);
CALL stp_InsertBook('Den musikalske kat','',349,9780800214333);
CALL stp_InsertBook('Forårsløg','',129,9782549402262);
CALL stp_InsertBook('Farver og striber','',469,9787695633812);

-- Inserting data to table Category
INSERT INTO _Category
(CategoryName)
VALUES
('Mad'),
('Mode'),
('Sci-Fi'),
('Krimi'),
('Historie'),
('Sport'),
('Kærlighed'),
('Teknologi'),
('Uddannelse'),
('Gør det selv'),
('Hus og have'),
('Børnebog');

-- Inserting data to table Category using stored procedure
CALL stp_InsertCategory('Mad');
CALL stp_InsertCategory('Mode');
CALL stp_InsertCategory('Sci-Fi');
CALL stp_InsertCategory('Børnebog');

-- Inserting data to table Author_Book
INSERT INTO _Author_Book2
(Author, Book)
VALUES
(8,1),
(3,2),
(7,3),
(4,3),
(6,4),
(5,5),
(9,6),
(1,7),
(10,8),
(4,8),
(6,9),
(2,9),
(3,10),
(1,11),
(5,11),
(10,12),
(9,13),
(8,14),
(5,15),
(3,15);

-- Inserting data to table Book_Category
INSERT INTO _Book_Category2
(Category, Book)
VALUES
(1,1),
(4,2),
(2,3),
(1,4),
(3,4),
(3,5),
(4,5),
(1,6),
(2,6),
(2,7),
(1,8),
(4,8),
(4,9),
(2,10),
(4,10),
(2,11),
(1,12),
(4,13),
(1,14),
(2,15);

-- Stored procedure stp_InsertAuthor
DELIMITER $$
DROP PROCEDURE IF EXISTS `stp_InsertAuthor` $$
CREATE PROCEDURE `stp_InsertAuthor` (
    IN FirstName VARCHAR(50),
    IN LastName VARCHAR(50),
    IN Email VARCHAR(50))
BEGIN
	INSERT INTO _Author2
    	(FirstName, LastName, Email)
    VALUES
        (FirstName,LastName,Email);
	SELECT LAST_INSERT_ID();
END$$
DELIMITER ;

CALL stp_InsertAuthor('Salmon','Roadie','sr2@fanzy.dk');

-- Stored procedure stp_DeleteAuthor
DELIMITER $$
DROP PROCEDURE IF EXISTS `stp_DeleteAuthor` $$
CREATE PROCEDURE `stp_DeleteAuthor` (
    IN AuthorId INT)
BEGIN
	DELETE FROM _Author2
	WHERE Id = AuthorId;
	SELECT ROW_COUNT();
END$$
DELIMITER ;

CALL stp_DeleteAuthor(12);

-- Stored procedure stp_InsertBook
DELIMITER $$
DROP PROCEDURE IF EXISTS `stp_InsertBook` $$
CREATE PROCEDURE `stp_InsertBook` (
    IN Title VARCHAR(50),
    IN Summary VARCHAR(200),
    IN Price FLOAT,
    IN ISBN BIGINT)
BEGIN
	INSERT INTO _Book
    	(Title, Summary, Price, ISBN)
    VALUES
        (Title, Summary, Price, ISBN);
	SELECT LAST_INSERT_ID();
END$$
DELIMITER ;

CALL stp_InsertBook('Towels','No summary',365,14923);

-- Stored procedure stp_DeleteBook
DELIMITER $$
DROP PROCEDURE IF EXISTS `stp_DeleteBook` $$
CREATE PROCEDURE `stp_DeleteBook` (
    IN BookId INT)
BEGIN
	DELETE FROM _Book2
	WHERE Id = BookId;
	SELECT ROW_COUNT();
END$$
DELIMITER ;

CALL stp_DeleteBook(16);

-- Stored procedure stp_InsertCategory
DELIMITER $$
DROP PROCEDURE IF EXISTS `stp_InsertCategory` $$
CREATE PROCEDURE `stp_InsertCategory` (
    IN CategoryName VARCHAR(50))
BEGIN
	INSERT INTO _Category2
    	(CategoryName)
    VALUES
        (CategoryName);
	SELECT LAST_INSERT_ID();
END$$
DELIMITER ;

CALL stp_InsertCategory('Krimi');

-- Stored procedure stp_DeleteCategory
DELIMITER $$
DROP PROCEDURE IF EXISTS `stp_DeleteCategory` $$
CREATE PROCEDURE `stp_DeleteCategory` (
    IN CategoryId INT)
BEGIN
	DELETE FROM _Category2
	WHERE Id = CategoryId;
	SELECT ROW_COUNT();
END$$
DELIMITER ;

CALL stp_DeleteCategory(5);

-- Stored procedure stp_InsertAuthorBook
DELIMITER $$
DROP PROCEDURE IF EXISTS `stp_InsertAuthorBook` $$
CREATE PROCEDURE `stp_InsertAuthorBook` (
    IN AuthorId INT,
	IN BookId INT)
BEGIN
	INSERT INTO _Author_Book2
    	(Author, Book)
    VALUES
        (AuthorId, BookId);
	SELECT LAST_INSERT_ID();
END$$
DELIMITER ;

CALL stp_InsertAuthorBook(23,32);

-- Stored procedure stp_DeleteAuthorBook
DELIMITER $$
DROP PROCEDURE IF EXISTS `stp_DeleteAuthorBook` $$
CREATE PROCEDURE `stp_DeleteAuthorBook` (
    IN AuthorId INT,
	IN BookId INT)
BEGIN
	DELETE FROM _Author_Book2
	WHERE Author = AuthorId AND Book = BookId;
	SELECT ROW_COUNT();
END$$
DELIMITER ;

CALL stp_DeleteAuthorBook(1,1);

-- Stored procedure stp_InsertBookCategory
DELIMITER $$
DROP PROCEDURE IF EXISTS `stp_InsertBookCategory` $$
CREATE PROCEDURE `stp_InsertBookCategory` (
    IN BookId INT,
	IN CategoryId INT)
BEGIN
	INSERT INTO _Book_Category2
    	(Book, Category)
    VALUES
        (BookId, CategoryId);
	SELECT LAST_INSERT_ID();
END$$
DELIMITER ;

CALL stp_InsertBookCategory(46,13);

-- Stored procedure stp_DeleteBookCategory
DELIMITER $$
DROP PROCEDURE IF EXISTS `stp_DeleteBookCategory` $$
CREATE PROCEDURE `stp_DeleteBookCategory` (
    IN BookId INT,
	IN CategoryId INT)
BEGIN
	DELETE FROM _Book_Category2
	WHERE Book = BookId AND Category = CategoryId;
	SELECT ROW_COUNT();
END$$
DELIMITER ;

CALL stp_DeleteBookCategory(1,1);

-- Stored procedure stp_GetAuthorByBookId
DELIMITER $$
DROP PROCEDURE IF EXISTS `stp_GetAuthorByBookId` $$
CREATE PROCEDURE `stp_GetAuthorByBookId` (
    IN BookId INT)
BEGIN
	SELECT FirstName,LastName,Email
	FROM _Author AS a
	INNER JOIN _Author_Book AS ab ON a.Id = ab.Author
	WHERE ab.Book = BookId;
END$$
DELIMITER ;

CALL stp_GetAuthorByBookId(1);

-- Stored procedure stp_GetBookByBookId
DELIMITER $$
DROP PROCEDURE IF EXISTS `stp_GetBookByBookId` $$
CREATE PROCEDURE `stp_GetBookByBookId` (
    IN BookId INT)
BEGIN
	SELECT Title,Summary,Price,ISBN
	FROM _Book2
	WHERE Id = BookId;
END$$
DELIMITER ;

CALL stp_GetBookByBookId(1);

-- Stored procedure stp_GetInfoByBookId
DELIMITER $$
DROP PROCEDURE IF EXISTS `stp_GetInfoByBookId` $$
CREATE PROCEDURE `stp_GetInfoByBookId` (
    IN BookId INT)
BEGIN
	SELECT FirstName,LastName,Email
	FROM _Author2 AS a
	INNER JOIN _Author_Book2 AS ab ON a.Id = ab.Author
	WHERE ab.Book = BookId;
	
	SELECT Title,Summary,Price,ISBN
	FROM _Book2
	WHERE Id = BookId;
	
	SELECT CategoryName
	FROM _Category2 as c
	INNER JOIN _Book_Category2 AS bc ON c.Id = bc.Category
	WHERE bc.Book = BookId;	
END$$
DELIMITER ;

CALL stp_GetInfoByBookId(1);


-- Dummy data generation
DELIMITER $$
DROP PROCEDURE IF EXISTS `stp_GenerateData` $$
CREATE PROCEDURE `stp_GenerateData` (
    IN Num INT)
BEGIN
	DECLARE N INT;
	SET N = 0;
	WHILE N<Num DO
		INSERT IGNORE INTO _Author
			(FirstName,LastName,Email)
		VALUES
			('a','b',CONCAT('c',N));
		SET N = N + 1;
	END WHILE;
END$$
DELIMITER ;

CALL stp_GenerateData(10);

-- Dummy data generation 2
DELIMITER $$
DROP PROCEDURE IF EXISTS `stp_GenerateAuthorData` $$
CREATE PROCEDURE `stp_GenerateAuthorData` (
    IN Num INT)
BEGIN
	INSERT IGNORE _Author(id,FirstName,LastName,Email)
	with recursive series as (
	select (select coalesce(max(id),0)+1 from _Author) as id union all
	select id + 1 as id
	from series
	where id < Num+(select coalesce(max(id),0) from _Author)),
   		first_names as (
			select 'Allan' first_name union all
			select 'Bent' first_name union all
			select 'John' first_name union all
			select 'Morten' first_name union all
			select 'David' first_name union all
			select 'Rasmus' first_name union all
			select 'Tobias' first_name union all
			select 'Søren' first_name union all
			select 'Rolf' first_name union all
			select 'Hans' first_name union all
			select 'Tommy' first_name union all
			select 'Amalie' first_name union all
			select 'Birte' first_name union all
			select 'Camilla' first_name union all
			select 'Lena' first_name union all
			select 'Katrine' first_name union all
			select 'Mie' first_name union all
			select 'Rikke' first_name union all
			select 'Tanja' first_name union all
			select 'Alex' first_name union all
			select 'Sam' first_name union all
			select 'Kim' first_name	
   		),
   	 	last_names as (
			select 'Jensen' last_name union all
			select 'Christiansen' last_name union all
			select 'Skov' last_name union all
			select 'Bahnsen' last_name union all
			select 'Rieper' last_name union all
			select 'Møller' last_name union all
			select 'Damgård' last_name union all
			select 'Bach' last_name union all
			select 'Vinther' last_name union all
			select 'Juul' last_name union all
			select 'Toft' last_name union all
			select 'Ravn' last_name union all
			select 'Koch' last_name union all
			select 'Meyer' last_name	
   		),
   	 	things as (
			select 'on' thing union all
			select 'an' thing union all
			select 'de' thing union all
			select 'ge' thing union all
			select 'hu' thing union all
			select 'ji' thing union all
			select 'koe' thing union all
			select 'mi' thing union all
			select 'car' thing union all
			select 'don' thing union all
			select 'bear' thing union all
			select 'fan' thing union all
			select 'zen' thing union all
			select 'nu' thing union all
			select 'we' thing	
   		),
   	 	emailendings as (
			select '@hotmail.com' emailending union all
			select '@gmail.com' emailending union all
			select '@fanzy.com' emailending union all
			select '@fanzy.dk' emailending union all
			select '@mnl.com' emailending union all
			select '@brt.dk' emailending union all
			select '@jp.dk' emailending union all
			select '@prt.com' emailending union all
			select '@trq.com' emailending	
   		)
		select id, first_name FirstName, last_name LastName, email Email
		from
		(select id,
		(select first_name from first_names order by rand() limit 1) first_name,
		(select last_name from last_names order by rand() limit 1) last_name,
		(select concat((select thing from things order by rand() limit 1),(select thing from things order by rand() limit 1),(select emailending from emailendings order by rand() limit 1)) ) email
		from series) as t;	   
END$$
DELIMITER ;

CALL stp_GenerateAuthorData(10);


-- Dummy data generation _Book table
DELIMITER $$
DROP PROCEDURE IF EXISTS `stp_GenerateBookData` $$
CREATE PROCEDURE `stp_GenerateBookData` (
    IN Num INT)
BEGIN
	INSERT IGNORE _Book(id,Title,Summary,Price,ISBN)
	with recursive series as (
	select (select coalesce(max(id),0)+1 from _Book) as id union all
	select id + 1 as id
	from series
	where id < Num+(select coalesce(max(id),0) from _Book)),
   		titles1 as (
			select 'Aber og ' title1 union all
			select 'Træer og ' title1 union all
			select 'Huse og ' title1 union all
			select 'Papirer og ' title1 union all
			select 'Stole og ' title1 union all
			select 'Klaverer og ' title1 union all
			select 'Planter og ' title1 union all
			select 'Bøffer og ' title1 union all
			select 'Salater og ' title1 union all
			select 'Bukser og ' title1 union all
			select 'Glæde og ' title1 union all
			select 'Død og ' title1 union all
			select 'Unødvendige ' title1 union all
			select 'De små ' title1 union all
			select 'De uforanderlige ' title1 union all
			select 'Perfekte ' title1 union all
			select 'Anvendelige ' title1 union all
			select 'Gode ' title1 union all
			select 'Trætte ' title1 union all
			select 'Mystiske ' title1 union all
			select 'Forunderlige ' title1	
   		),
		titles2 as (
			select 'mennesker' title2 union all
			select 'børn' title2 union all
			select 'kvinder' title2 union all
			select 'mænd' title2 union all
			select 'skildpadder' title2 union all
			select 'borde' title2 union all
			select 'spisestuer' title2 union all
			select 'parker' title2 union all
			select 'strandpromenader' title2 union all
			select 'månelandskaber' title2 union all
			select 'hustage' title2 union all
			select 'køleskabe' title2 union all
			select 'liv' title2 union all
			select 'historier' title2 union all
			select 'tryllerier' title2 union all
			select 'diskussioner' title2 union all
			select 'kvaler' title2 union all
			select 'anvendelser' title2 union all
			select 'teorier' title2 union all
			select 'analogier' title2 union all
			select 'ødegårde' title2 union all
			select 'biler' title2	
   		),
   	 	summaries as (
			select 'En vidunderlige historie om...' summary union all
			select 'Forskere har i årevis forsøgt det, men forgæves. Indtil nu.' summary union all
			select 'Bogen handler om en usædvanlig familie.' summary union all
			select 'Titlen siger det hele!' summary union all
			select 'Efter mange års ventetid, er dette mesterværk endelig tilgængeligt.' summary union all
			select 'Det startede en sommer.' summary union all
			select 'Denne bog behandler et kontroversielt emne på en nænsom og kærlig måde.' summary union all
			select 'Ét er sikkert - denne bog vil vække debat.' summary union all
			select 'Læs den før din nabo.' summary union all
			select 'Efter årelange magtkampe, skete der et gennembrud.' summary union all
			select 'Sådan skete det.' summary union all
			select 'Denne bog er en yderst vigtig stemme i debatten.' summary union all
			select 'På en ubesværet måde, beskriver denne bog hvad andre igennem længere tid har forsøgt på.' summary union all
			select 'Det skete under stor mystik.' summary	
   		)
		select id, title Title, summary Summary, price Price, isbn ISBN
		from
		(select id,
		(select concat((select title1 from titles1 order by rand() limit 1), (select title2 from titles2 order by rand() limit 1))) title,
		(select summary from summaries order by rand() limit 1) summary,
		(select floor(rand()*1000)*1.25) price,
		(select floor(rand()*10000000000000)) isbn
		from series) as t;
	   
END$$
DELIMITER ;

CALL stp_GenerateBookData(20);


-- Make author-book connections
DELIMITER $$
DROP PROCEDURE IF EXISTS `stp_GenerateAuthorBookConnections` $$
CREATE PROCEDURE `stp_GenerateAuthorBookConnections` ()
BEGIN
	DECLARE n INT DEFAULT 0;
	DECLARE i INT DEFAULT 0;
	SELECT COUNT(*) FROM _Book INTO n;
	SET i=0;
	WHILE i<n DO
	  INSERT INTO _Author_Book(Author,Book) SELECT (select Id from _Author order by rand() limit 1),Id FROM _Book LIMIT i,1;
	  IF rand()<0.3
	  THEN INSERT INTO _Author_Book(Author,Book) SELECT (select Id from _Author order by rand() limit 1),Id FROM _Book LIMIT i,1;
	  END IF;
	  SET i = i + 1;
	END WHILE;
END$$
DELIMITER ;

CALL stp_GenerateAuthorBookConnections();


-- Make author-book connections
DELIMITER $$
DROP PROCEDURE IF EXISTS `stp_GenerateBookCategoryConnections` $$
CREATE PROCEDURE `stp_GenerateBookCategoryConnections` ()
BEGIN
	DECLARE n INT DEFAULT 0;
	DECLARE i INT DEFAULT 0;
	SELECT COUNT(*) FROM _Book INTO n;
	SET i=0;
	WHILE i<n DO
	  INSERT IGNORE INTO _Book_Category(Book,Category) SELECT Id,(select Id from _Category order by rand() limit 1) FROM _Book LIMIT i,1;
	  IF rand()<0.5
	  THEN INSERT IGNORE INTO _Book_Category(Book,Category) SELECT Id,(select Id from _Category order by rand() limit 1) FROM _Book LIMIT i,1;
	  END IF;
	  SET i = i + 1;
	END WHILE;
END$$
DELIMITER ;

CALL stp_GenerateBookCategoryConnections();




				
				
				
-- STP to get data from the books on the current page
DELIMITER $$
DROP PROCEDURE IF EXISTS `stp_GetDataFromBooksOnCurrentPage` $$
CREATE PROCEDURE `stp_GetDataFromBooksOnCurrentPage` (
	IN limit1 INT,
	IN limit2 INT)
BEGIN
	select a.Id,Firstname,Lastname,Email from _Author as a inner join _Author_Book as ab on a.Id = ab.Author where ab.Book in (select * from (select Id from _Book as b order by title limit limit1,limit2) as t);
	select ab.Author,ab.Book from _Author_Book as ab where ab.Book in (select * from (select Id from _Book as b order by title limit limit1,limit2) as t);
	select distinct Id,Title,Summary,Price,Isbn from _Book order by Title limit limit1,limit2;
	select bc.Book,bc.Category from _Book_Category as bc where bc.Book in (select * from (select Id from _Book as b order by Title limit limit1,limit2) as t);
	select c.Id,c.Categoryname from _Category as c inner join _Book_Category as bc on c.Id = bc.Category where bc.Book in (select * from (select Id from _Book as b order by title limit limit1,limit2) as t);
END$$
DELIMITER ;

SET FOREIGN_KEY_CHECKS=0;
CALL stp_GetDataFromBooksOnCurrentPage(0,5);
SET FOREIGN_KEY_CHECKS=1;
