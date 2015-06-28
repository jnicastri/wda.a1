<?php

	require_once("includes/requirebundle.php");
	
	function loadAndBindSearch(){
	
		$temp = new MiniTemplator;
		$load = $temp->readTemplateFromFile("html/search-template.html");

		if(!$load)
			die ("Loading HTML template has failed!");
		
		$temp->setVariable("baseurl", BASE_URL);
		
		$temp->generateOutput();
	}

	loadAndBindSearch();

?>
