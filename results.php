<?php

	require_once("includes/requirebundle.php");
	
	$temp = new MiniTemplator;
	$load = $temp->readTemplateFromFile("html/results-template.html");

	if(!$load)
		die ("Loading HTML template has failed!");
	
	$temp->setVariable("baseurl", BASE_URL);
	
	if(count($_SESSION['SearchResults']) > 0){

		$temp->addBlock("tableHead");
		$resultArray = $_SESSION['SearchResults'];
		
		foreach($resultArray as $listItem){
			
			$temp->setVariable("WineName", strval($listItem->wineName));
			$temp->setVariable("WineryName", strval($listItem->wineryName));
			$temp->setVariable("RegionName", strval($listItem->regionName));
			$temp->setVariable("WineYear", strval($listItem->wineYear));
			$temp->setVariable("GrapeVar", strval($listItem->grapeVariety));
			$temp->setVariable("InvCost", "$" . strval($listItem->inventoryCost));
			$temp->setVariable("OHQty", strval($listItem->onHandQty));
			$temp->setVariable("SoldQty", strval($listItem->soldQty));
			
			$temp->addBlock("resultRecord");
		}
		
		$temp->addBlock("tableFooter");
	}
	else{
		$temp->addBlock("noResults");
	}
	
	$temp->generateOutput();
	
?>
