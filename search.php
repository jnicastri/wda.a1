<?php

	require_once("includes/requirebundle.php");
	
	function loadAndBindSearch(){
	
		$temp = new MiniTemplator;
		$load = $temp->readTemplateFromFile("html/search-template.html");

		if(!$load)
			die ("Loading HTML template has failed!");
		
		$temp->setVariable("baseurl", BASE_URL);
		$minYear = $maxYear = "";
		
		$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
		
		// Getting Data
		try{
			$con = new PDO($conStr, DB_USER, DB_PWD);
			$records = $con->query('CALL Search_BindData(@min,@max)');
			$setCount = 1;
			
			// Looping through resut sets
			do{	
				$set = $records->fetchAll(PDO::FETCH_ASSOC);
				
				if($setCount == 1){
					//Bind Region List
					foreach($set as $row){
						$temp->setVariable("regionName", $row["region_name"]);
						$temp->addBlock("regionSelect");
					}
				}
				else if($setCount == 2){
					//Adding an 'All' selection for grape list
					$temp->setVariable("grapeName", "All");
					$temp->addBlock("grapeSelect");
					//Bind Grape Variety List to DB Values
					foreach($set as $row){
						$temp->setVariable("grapeName", $row["variety"]);
						$temp->addBlock("grapeSelect");
					}
				}
				$setCount++;
				
			}while($records->nextRowset());

		
			$minYear = $con->query("SELECT @min")->fetchAll(PDO::FETCH_ASSOC);
			$maxYear = $con->query("SELECT @max")->fetchAll(PDO::FETCH_ASSOC);
			
		}
		catch(PDOException $ex){
			// Redirect to Error here!
		}
		
		// Adding 'All' options for year Ddl's
		$temp->setVariable("minYearName", "All");
		$temp->addBlock("yearMinSelect");
		$temp->setVariable("maxYearName", "All");
		$temp->addBlock("yearMaxSelect");
		
		//Populating year min & max from DB
		$min = (int)$minYear[0]["@min"];
		$max = (int)$maxYear[0]["@max"];
		
		for($i = $min; $i < ($max + 1); $i++){
			$temp->setVariable("minYearName", $i);
			$temp->addBlock("yearMinSelect");
			$temp->setVariable("maxYearName", $i);
			$temp->addBlock("yearMaxSelect");
		}

		$temp->generateOutput();
	}

	loadAndBindSearch();

?>
