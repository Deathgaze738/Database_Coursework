<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/classes/user.php';
	function checkBlogPermission($user_id, $blog_owner_id, $pdo){
		$stmt = $pdo->prepare('SELECT id FROM user WHERE id = :id');
		$stmt->execute(['id'=>$blog_owner_id]);
		if(!$stmt->rowCount()){
			echo "No Page Exists";
			return false;
		}
		$blog_owner = new user($blog_owner_id, $pdo);
		$privacyLevel = $blog_owner->getPrivacy();
		if($privacyLevel == 0){
			echo "Blog is public";
			return true;
		}
		if($privacyLevel == 1){
			$friendsList = array_merge($blog_owner->getFriends(),$blog_owner->getFriendsOfFriends());
			foreach($friendsList as $friend){
				if($user_id == $friend['id']){
					echo "You are a friend of friends";
					return true;
				}
			}
			return false;
		}
		if($privacyLevel == 2){
			$friendsList = $blog_owner->getFriends();
			foreach($friendsList as $friend){
				if($user_id == $friend['id']){
					echo "You are a friend";
					return true;
				}
			}
			return false;
		}
		return false;
	}
?>