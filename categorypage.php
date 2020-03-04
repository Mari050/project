<?php
    session_start();
	if (! isset($_SESSION["login"])) {
		$url = 'choice.php';
		header("Location: " . $url);
	}

	require "db.php";
    require "functions/foreverypage.php";
    
	head($_SESSION['category']);
	nav();
	
	print "<table>
				<tr>
					<th>Name</th>
					<th>Description</th>
				</tr>";
	
?>