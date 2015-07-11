<?php
	
	require_once("includes/requirebundle.php");
	$base = BASE_URL;
	
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
	
	
	$results = Array(); // To hold the results from the DB
	
	try{
		//Connect to DB and prepare procedure call
		$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
		$con = new PDO($conStr, DB_USER, DB_PWD);
		$stmt = $con->prepare('CALL Search_GetResults(?,?,?,?,?,?,?,?,?,?)');
		
		// Binding search input filter values to the procedure input parameters - if null, we pass null to DB
		is_null($wineName) ? $stmt->bindValue(1, null, PDO::PARAM_INT) : $stmt->bindValue(1, $wineName, PDO::PARAM_STR);
		is_null($region) ? $stmt->bindValue(2, null, PDO::PARAM_INT) : $stmt->bindValue(2, $region, PDO::PARAM_STR);
		is_null($wineryName) ? $stmt->bindValue(3, null, PDO::PARAM_INT) : $stmt->bindValue(3, $wineryName, PDO::PARAM_STR);
		is_null($grapeVar) ? $stmt->bindValue(4, null, PDO::PARAM_INT) : $stmt->bindValue(4, $grapeVar, PDO::PARAM_STR);
		is_null($minYear) ? $stmt->bindValue(5, null, PDO::PARAM_INT) : $stmt->bindValue(5, $minYear, PDO::PARAM_INT);
		is_null($maxYear) ? $stmt->bindValue(6, null, PDO::PARAM_INT) : $stmt->bindValue(6, $maxYear, PDO::PARAM_INT);
		is_null($minStock) ? $stmt->bindValue(7, null, PDO::PARAM_INT) : $stmt->bindValue(7, $minStock, PDO::PARAM_INT);
		is_null($minOrdered) ? $stmt->bindValue(8, null, PDO::PARAM_INT) : $stmt->bindValue(8, $minOrdered, PDO::PARAM_INT);
		is_null($minCost) ? $stmt->bindValue(9, null, PDO::PARAM_INT) : $stmt->bindValue(9, strval($minCost), PDO::PARAM_STR);
		is_null($maxCost) ? $stmt->bindValue(10, null, PDO::PARAM_INT) : $stmt->bindValue(10, strval($maxCost), PDO::PARAM_STR);
	
		$stmt->execute();
		
		$recordCounter = 0;
	
		while($record = $stmt->fetch(PDO::FETCH_ASSOC)){
		
			$listItem = new ResultStruct();
			
			$listItem->wineId = $record['Id'];
			$listItem->wineName = $record['WineName']; 
			$listItem->wineryName = $record['WineryName']; 
			$listItem->regionName = $record['RegionName'];
			$listItem->wineYear = $record['WineYear'];
			$listItem->grapeVariety = $record['GrapeVariety'];
			$listItem->inventoryCost = $record['InventoryCost']; 
			$listItem->onHandQty = $record['OnHandCount'];
			$listItem->soldQty = $record['QtySold'];
			$listItem->salesRevenue = $record['SalesRevenue'];
			
			$results[$recordCounter] = $listItem;
			$recordCounter++;
		}
	}
	catch(PDOException $ex){
	  
		header("Location: http://$base/error.php");
  	}
	
	// Dealing with duplicate 'Grape Variety' Records
	$resCount = count($results);
	$newCount = 0;
	$newArr = Array();
	
	// Loop though results looking for id dups
	for($i = 0; $i < $resCount; $i++){
		// Exit Condition - if at the end of $results
		if(($i + 1) >= $resCount){
			$newArr[$newCount] = $results[$i];
			break;
		}
		
		if($results[$i]->wineId == $results[$i + 1]->wineId){
			$dupCount = 0;
			$grapeVarStr = $results[$i]->grapeVariety;
			$dup = true;
			while($dup){
				$dupCount++;
				$grapeVarStr .= ", " . $results[$i + $dupCount]->grapeVariety;
				if($results[$i + $dupCount]->wineId == $results[$i + $dupCount + 1]->wineId){
					continue;
				}
				else{
					$results[$i + $dupCount]->grapeVariety = $grapeVarStr;
					$newArr[$newCount] = $results[$i + $dupCount];
					$newCount++;
					$dup = false;
					$i = $i + $dupCount;
				}
			}
		}
		else{
			$newArr[$newCount] = $results[$i];
			$newCount++;
		}
	}
	
	// Store Results in session
	$_SESSION['SearchResults'] = $newArr;

	//Navigate to results page
	header("Location: http://$base/results.php");
	

?>
