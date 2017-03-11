<?php
	include_once '../dbconnect.php';
	include_once '../functions.php';
	function printSearchResults($pdo, $input){
		try{
			$stmt = $pdo->prepare('SELECT id, first_name, last_name
									FROM user 
									WHERE CONCAT(first_name, " ", last_name) LIKE :value');
								
			$stmt->execute(['value'=>"%".$input."%"]);
		}
		catch(PDOException $e){
			exit($e->getCode());
		}
		if($stmt->rowCount() == 0){
			echo "No Results Found";
		}
		else{
			echo "<h1> Search Results </h1>";
			foreach($stmt->fetchAll() as $rec){
			    echo "<a href='blog.php?blogid=".$rec['id']."'>".$rec['first_name']." ".$rec['last_name']."</a>";
				echo "<br>";
			}
		}
	}
	$vars = array('value');
	if(!verifyNull($vars)){
		exit("NEIN");
	}
	printSearchResults($pdo, $_POST['value']);
?>