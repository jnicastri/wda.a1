DELIMITER $$

CREATE PROCEDURE Search_BindData(
	OUT year_min int(4),
	OUT year_max int(4))
BEGIN
	
	-- Setting min & Max output params
	SELECT MAX(year) INTO year_max
	FROM wine;
	
	SELECT MIN(year) INTO year_min
	FROM wine;

	-- Getting wine regions
	SELECT * 
	FROM region
	ORDER BY region_name;

	-- Getting Grape variety types
	SELECT *
	FROM grape_variety
	ORDER BY variety;

END$$ 
