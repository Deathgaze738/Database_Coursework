<?php
	function verifyCirclePermission($user_id, $circle_id, $pdo){
		$stmt = $pdo->prepare('SELECT user_id FROM circle_membership WHERE user_id = :id AND circle_id = :c_id');
		$stmt->execute(['id'=>$user_id, 'c_id'=>$circle_id]);
		if(!$stmt->rowCount()){
			echo "No Page Exists";
			return false;
		}
		return true;
	}
?>