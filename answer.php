<?php
	
	require_once("includes/requirebundle.php");
	
	// Get GET variables
	$wineName = trim($_GET["wineNameTb"]);
	$wineryName = trim($_GET["wineryNameTb"]);
	$region = trim($_GET["regionSelect"]);
	$grapeVar = trim($_GET["grapeSelect"]);
	$minYear = trim($_GET["yearMinSelect"]);
	$maxYear = trim($_GET["yearMaxSelect"]);
	$minStock = trim($_GET["minStockUnitsTb"]);
	$minOrdered = trim($_GET["minOrderTb"]);
	$minCost = trim($_GET["minCostTb"]);
	$maxCost = trim($_GET["maxCostTb"]);

	// Check variables - if empty set to NULL (for use in DB sproc)
	if($wineName == "")
		$wineName = null;
	if($wineryName == "")
		$wineryName = null;
	
	// Selects	
	if($region == "" || $region == "All")
		$region = null;
	if($grapeVar == "" || $grapeVar == "All")
		$grapeVar = null;
	
	
	// data that requires casting	
	if($minYear == "" || $minYear == "All")
		$minYear = null;
	else{
		$minYear = tryParseToNull($minYear, "int");
	}
	
	if($maxYear == "" || $maxYear == "All")
		$maxYear = null;
	else{
		$maxYear = tryParseToNull($maxYear, "int");
	}
	
	if($minStock == "")
		$minStock = null;
	else{
		$minStock = tryParseToNull($minStock, "int");
	}
	if($minOrdered == "")
		$minOrdered = null;
	else{
		$minOrdered = tryParseToNull($minOrdered, "int");
	}
	
	if($minCost == "")
		$minCost = null;
	else{
		$minCost = tryParseToNull($minCost, "float");
	}
	if($maxCost == "")
		$maxCost = null;
	else{
		$maxCost = tryParseToNull($maxCost, "float");
	}	
	
	
	var_dump($wineName);
	var_dump($wineryName);
	var_dump($region);
	var_dump($grapeVar);
	var_dump($minYear);
	var_dump($maxYear);
	var_dump($minStock);
	var_dump($minOrdered);
	var_dump($minCost);
	var_dump($maxCost);
	

?>
