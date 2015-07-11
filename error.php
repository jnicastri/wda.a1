<?php
	require_once("includes/requirebundle.php");
	$base = BASE_URL;
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Error</title>
	</head>
	<body>
		<h1>Oh....FAIL!</h1>
		<div>
			<p>Don't worry, is wasn't your fault. Please try your search again<br />
			<?php
				echo "<a href=\"http://$base/search.php\">Back to Search Page</a>";
			?>
			</p>
		</div>
	</body>
</html>
