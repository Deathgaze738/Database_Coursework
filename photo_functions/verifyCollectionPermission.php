<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/classes/user.php';
	function verifyCollectionPermission($pdo, $user_id, $collection_id){
		try{
			$stmt = $pdo->prepare("SELECT privacy_setting, user_owner 
									FROM photo_album
									WHERE id = :id");
			$stmt->execute(['id'=>$collection_id]);
			if($stmt->rowCount() == 0){
				return false;
			}
		}
		catch(PDOException $e){
			exit($e->getCode());
		}
		$row = $stmt->fetch();
		if($row['user_owner'] == $user_id){
			return true;
		}
		$collectionPrivacy = $row['privacy_setting'];
		$collection_owner = new user($row['user_owner'], $pdo);
		if($collectionPrivacy == 0){
			echo "Collection is public";
			return true;
		}
		if($collectionPrivacy == 1){
			$friendsList = array_merge($collection_owner->getFriends(),$collection_owner->getFriendsOfFriends());
			foreach($friendsList as $friend){
				if($user_id == $friend['id']){
					echo "Access Granted, FOF";
					return true;
				}
			}
			return false;
		}
		if($collectionPrivacy == 2){
			$friendsList = $collection_owner->getFriends();
			foreach($friendsList as $friend){
				if($user_id == $friend['id']){
					echo "Access Granted, F";
					return true;
				}
			}
			return false;
		}
		return false;
	}
?>