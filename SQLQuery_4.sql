SELECT ProductID,[Name],Style,[Size],Color
FROM Production.Product
WHERE Color IS NOT NULL OR Size IS NOT NULL;