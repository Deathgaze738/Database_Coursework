<?php
	#Returns 0 for no friendship, 1 for friendship, and 2 for friendship unconfirmed

	function checkFriendship($userid, $friendid, $pdo){
		$stmt = $pdo->prepare("SELECT status 
								FROM friendship
								WHERE ((user_1 = :user
								AND user_2 = :friend)
								OR (user_1 = :friend2 
								AND user_2 = :user2))");
		$stmt->execute(['user'=>$userid, 
						'friend'=>$friendid, 
						'friend2'=>$friendid, 
						'user2'=>$userid]);
		if($stmt->rowCount() == 0){
			return 0;
		}
		$row = $stmt->fetch();
		if($row['status'] == 0){
			return 2;
		}
		return 1;
	}
?>