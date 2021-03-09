USE AdventureWorks2019;
GO
SELECT SalesOrderID, OrderDate
FROM Sales.SalesOrderHeader
WHERE OrderDate < '2011-06-01';
