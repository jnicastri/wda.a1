DELIMITER $$

CREATE PROCEDURE Search_GetResults(
	IN in_wine_name varchar(50),
	IN in_wine_region varchar(100),
	IN in_winery_name varchar(100),
	IN in_grape_variety varchar(50),
	IN in_min_year int(4),
	IN in_max_year int(4),
	IN in_min_stock int(5),
	IN in_min_ordered int(3),
	IN in_min_cost decimal(5,2),
	IN in_max_cost decimal(5,2))
BEGIN

SELECT 
	W.wine_id AS Id,
	W.wine_name AS WineName,
	WY.winery_name AS WineryName,
	W.year AS WineYear,
	R.region_name AS RegionName,
	GV.variety AS GrapeVariety,
	IFNULL(I.cost,0) * IFNULL(I.on_hand,0) AS InventoryCost,
	IFNULL(SUM(I.on_hand),0) AS OnHandCount,
	IFNULL(SUM(OI.qty),0) AS QtySold,
	IFNULL(SUM(OI.price),0) AS SalesRevenue
FROM 
	wine W
	LEFT JOIN winery WY ON W.winery_id = WY.winery_id
	LEFT JOIN region R ON WY.region_id = R.region_id
	LEFT JOIN wine_variety V ON V.wine_id = W.wine_id
	LEFT JOIN grape_variety GV ON V.variety_id = GV.variety_id 
	LEFT JOIN inventory I ON I.wine_id = W.wine_id
	LEFT JOIN items OI ON OI.wine_id = W.wine_id
WHERE
	W.wine_name LIKE CONCAT('%',IFNULL(in_wine_name, ''),'%')
	AND WY.winery_name LIKE CONCAT('%',IFNULL(in_winery_name, ''),'%')
	AND R.region_name LIKE CONCAT('%',IFNULL(in_wine_region, ''),'%')
	AND GV.variety LIKE CONCAT('%',IFNULL(in_grape_variety, ''),'%')
	AND (
		CASE
			WHEN in_min_year IS NOT NULL AND in_max_year IS NOT NULL THEN W.year >= in_min_year AND W.year <= in_max_year
			WHEN in_min_year IS NOT NULL AND in_max_year IS NULL THEN W.year >= in_min_year
			WHEN in_min_year IS NULL AND in_max_year IS NOT NULL THEN W.year <= in_max_year
			ELSE W.year
		END
	)
	AND (
		CASE
			WHEN in_min_cost IS NOT NULL AND in_max_cost IS NOT NULL THEN I.cost >= in_min_cost AND I.cost <= in_max_cost
			WHEN in_min_year IS NOT NULL AND in_max_year IS NULL THEN I.cost >= in_min_cost
			WHEN in_min_year IS NULL AND in_max_year IS NOT NULL THEN I.cost <= in_max_cost
			ELSE I.cost
		END
	)
GROUP BY
	W.wine_name,
	WY.winery_name,
	W.year,
	R.region_name,
	GV.variety,
	I.cost
HAVING
	CASE
		WHEN in_min_stock IS NOT NULL THEN SUM(I.on_hand) >= in_min_stock
		ELSE SUM(I.on_hand)
	END
	AND
	CASE
		WHEN in_min_ordered IS NOT NULL THEN SUM(OI.qty) >= in_min_ordered
		ELSE SUM(OI.qty)
	END;
END$$ 
