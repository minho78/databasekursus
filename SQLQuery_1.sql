USE AdventureWorks2019;
GO
--SELECT p.FirstName,p.MiddleName,p.LastName,soh.SalesOrderID,sp.SalesQuota,sp.Bonus
--FROM [Sales].[SalesPerson] as sp
--INNER JOIN [Sales].[SalesOrderHeader] as soh ON sp.BusinessEntityID = soh.SalesPersonID
--INNER JOIN [Person].[Person] as p ON p.BusinessEntityID = sp.BusinessEntityID;
SELECT DISTINCT M1.City,M1.PostalCode,M2.City,M2.PostalCode
FROM [Person].[Address] as M1 INNER JOIN
[Person].[Address] as M2 ON M1.City = M2.City AND M1.PostalCode != M2.PostalCode
ORDER BY M1.City;


SELECT DISTINCT City,PostalCode
FROM Person.Address
WHERE EXISTS (SELECT * FROM )
--WHERE City='Albany'


SELECT FirstName,MiddleName,LastName
FROM Person.Person
ORDER BY LastName;

SELECT DISTINCT City,PostalCode
FROM Person.Address;

SELECT p.ProductID
FROM Production.Product AS p
LEFT OUTER JOIN Sales.SalesOrderDetail AS sod ON sod.ProductID = p.ProductID
WHERE sod.SalesOrderID IS NULL;

SELECT soh.SalesPersonID,sp.SalesYTD,soh.SalesOrderID,p.FirstName,p.MiddleName,p.LastName
FROM Sales.SalesPerson as sp
LEFT OUTER JOIN Sales.SalesOrderHeader as soh ON sp.BusinessEntityID = soh.SalesPersonID
INNER JOIN Person.Person AS p ON sp.BusinessEntityID = p.BusinessEntityID;

SELECT soh.CurrencyRateID,AverageRate,SalesOrderID,ShipBase
FROM Sales.SalesOrderHeader as soh
LEFT OUTER JOIN Sales.CurrencyRate as cr ON soh.CurrencyRateID = cr.CurrencyRateID
LEFT OUTER JOIN Purchasing.ShipMethod as sm ON soh.ShipMethodID =sm.ShipMethodID;

SELECT p.ProductID,p.Name
FROM Production.Product AS p
WHERE p.ProductID NOT IN
(SELECT ProductID FROM Sales.SalesOrderDetail);

DROP TABLE IF EXISTS Production.ProductColor;
CREATE table Production.ProductColor
    (Color nvarchar(15) NOT NULL PRIMARY KEY);
GO
--Insert most of the existing colors
INSERT INTO Production.ProductColor
SELECT DISTINCT Color
FROM Production.Product
WHERE Color IS NOT NULL and Color <> 'Silver';
--Insert some additional colors
INSERT INTO Production.ProductColor
VALUES ('Green'),('Orange'),('Purple');
--Here is the query:
SELECT c.Color AS "Color from list", p.Color, p.ProductID
FROM Production.Product AS p
FULL OUTER JOIN Production.ProductColor AS c ON p.Color = c.Color
ORDER BY p.ProductID;

SELECT *
FROM Production.ProductColor
WHERE Color NOT IN (SELECT Color FROM Production.Product WHERE Color IS NOT NULL);

SELECT DISTINCT Color
FROM Production.Product AS p
WHERE NOT EXISTS (SELECT * FROM Production.ProductColor AS pc WHERE p.Color = pc.Color);

SELECT DISTINCT ModifiedDate
FROM Person.Person
UNION
SELECT DISTINCT HireDate
FROM HumanResources.Employee;

SELECT soh.SalesOrderID,soh.OrderDate,sod.ProductID
FROM Sales.SalesOrderHeader AS Soh
INNER JOIN (SELECT SalesOrderID,ProductID FROM Sales.SalesOrderDetail) AS sod ON soh.SalesOrderID = sod.SalesOrderID;

WITH abc AS (SELECT SalesOrderID,ProductID FROM Sales.SalesOrderDetail)
SELECT abc.SalesOrderID,abc.ProductID,soh.OrderDate FROM Sales.SalesOrderHeader AS soh
INNER JOIN abc ON soh.SalesOrderID = abc.SalesOrderID;

WITH orders AS (SELECT CustomerID,SalesOrderID,OrderDate FROM Sales.SalesOrderHeader WHERE Year(OrderDate)='2011')
SELECT c.CustomerID,SalesOrderID,OrderDate FROM Sales.Customer AS c
LEFT OUTER JOIN orders ON orders.CustomerID = c.CustomerID;

--1
SELECT SalesOrderDetailID
FROM Sales.SalesOrderDetail
WHERE SalesOrderDetailID <= 59549
UNION
SELECT SalesOrderDetailID
FROM Sales.SalesOrderDetail
WHERE SalesOrderDetailID > 59549;

--2
SELECT SalesOrderDetailID
FROM Sales.SalesOrderDetail
WHERE SalesOrderDetailID <= 59549
UNION ALL
SELECT SalesOrderDetailID
FROM Sales.SalesOrderDetail
WHERE SalesOrderDetailID > 59549;
