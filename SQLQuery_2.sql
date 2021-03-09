SELECT CustomerName,SUBSTRING(CustomerName,CHARINDEX('(',CustomerName),CHARINDEX(')',CustomerName)),CHARINDEX('(',CustomerName),CHARINDEX(')',CustomerName)
FROM [Sales].Customers;