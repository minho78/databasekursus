SELECT ProductNumber,SUBSTRING(ProductNumber,CHARINDEX('-',ProductNumber)+1,10)
FROM [Production].[Product];