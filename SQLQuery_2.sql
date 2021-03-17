USE WideWorldImporters;
GO
SELECT c.CountryName,c.SubRegion,sp.StateProvinceName
FROM [Application].[Countries] as c
INNER JOIN [Application].[StateProvinces] as sp ON c.CountryID = sp.CountryID;

SELECT count(*)
FROM sys.sysobjects AS s1
CROSS JOIN sys.sysobjects AS s2;

SELECT count(*)
FROM Sales.Customers as c1
INNER JOIN Sales.Customers as c2 ON c1.BillToCustomerID = c2.CustomerID
WHERE c1.BillToCustomerID = 1;

SELECT count(CityName)
FROM [Application].Cities
WHERE StateProvinceID IN (SELECT StateProvinceID FROM [Application].StateProvinces WHERE StateProvinceName='Texas');


SELECT StockItemName
FROM Warehouse.StockItems AS si
WHERE NOT EXISTS (SELECT * FROM Sales.OrderLines AS ol WHERE si.StockItemID = ol.StockItemID);

SELECT CityName
FROM [Application].[Cities] AS c
INNER JOIN [Application].[StateProvinces] AS sp ON c.StateProvinceID = sp.StateProvinceID
WHERE sp.StateProvinceName = 'Utah'
INTERSECT
SELECT CityName
FROM [Application].[Cities] AS c
INNER JOIN [Application].[StateProvinces] AS sp ON c.StateProvinceID = sp.StateProvinceID
WHERE sp.StateProvinceName = 'Wyoming';

SELECT CustomerID,CustomerName
FROM (SELECT CustomerID,CustomerName FROM Sales.Customers);

