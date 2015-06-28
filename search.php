<?php

	require_once("includes/requirebundle.php");
	
	function loadAndBindSearch(){
	
		$temp = new MiniTemplator;
		$load = $temp->readTemplateFromFile("html/search-template.html");

		if(!$load)
			die ("Loading HTML template has failed!");
			
		var_dump($load);
		
		$temp->setVariable("baseurl", BASE_URL);
		$minYear = $maxYear = 0;
		
		
		var_dump(DB_HOST);
		var_dump(DB_NAME);
		var_dump(DB_USER);
		var_dump(DB_PWD);
		
		$conStr = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
		
		var_dump($conStr);
		
		// Getting Data
		try{
			$con = new PDO($conStr, DB_USER, DB_PWD);
			$sql = 'CALL Search_BindData(@min,@max)';
			$stmt = $con->query($sql);	
			//$stmt = $con->execute();
			//$stmt->closeCursor();
			
			$out = $con->query("SELECT @min, @max")->fetch(PDO::FETCH_ASSOC);
			
			if($out){
				$minYear = $out['@min'];
				$maxYear = $out['@max'];	
			}
			
			//$stmt = $con->query('SELECT MAX(year) AS year FROM wine');
			
			//while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			//	$maxYear = $row['year'];
			//}
			
			var_dump($minYear);
			var_dump($maxYear);
		}
		catch(Exception $ex){
		//	die("An Error has occured whist attempting to access the DB", $ex->getMessage());
			var_dump($ex);
		}
		
		
		
		
		$temp->generateOutput();
	}

	loadAndBindSearch();

?>
