<?php
	function verifySession($pdo){
		session_start();
		define("LOGINREDIR", "http://localhost/index.php");
		if(isset($_SESSION['key']) && isset($_SESSION['id'])){
			$stmt = $pdo->prepare('SELECT session_key, 								
									CASE 
									WHEN timeout > CURRENT_TIMESTAMP
									THEN 1
									ELSE 0
									END
									AS valid 
									FROM session 
									WHERE user_id = :id');
			$stmt->execute(['id'=>$_SESSION['id']]);
			$rowCount = $stmt->rowCount();
			$row = $stmt->fetch();
			if($rowCount == 0 || $row['valid'] == 0){
				echo "Key missing or out of date";
				redirect(LOGINREDIR);
			}
			if($_SESSION['key'] != $row['session_key']){
				echo "Keys don't match";
				redirect(LOGINREDIR); 
			}
			$stmt = $pdo->prepare('UPDATE session 
								SET session_key = :sessionKey,
								timeout = CURRENT_TIMESTAMP + INTERVAL "1" DAY
								WHERE user_id = :id');
			$stmt->execute(['sessionKey'=>$_SESSION['key'], 'id'=>$_SESSION['id']]);
		}
		else{
			echo "Session not set";
			redirect(LOGINREDIR);
		}
		echo "Valid Session <br>";
	}
	verifySession($pdo);
?>