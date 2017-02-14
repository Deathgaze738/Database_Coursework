<?php
	class user{
		private $id;
		private $firstName;
		private $lastName;
		private $privacyLevel;
		private $friendsList;
		private $friendsOfFriendsList;
		private $circleList = array();
		private $pendingFriends;
		
		private function populateFriendsList($id, $pdo){
			#Get Friends List
			$stmt = $pdo->prepare('SELECT id, first_name, last_name, email_address
									FROM user AS U
									LEFT JOIN friendship AS F
									ON U.id = F.user_2
									WHERE F.user_1 = :id AND status = 1
									UNION
									SELECT id, first_name, last_name, email_address
									FROM user AS U
									LEFT JOIN friendship AS f
									ON U.id = F.user_1
									WHERE F.user_2 = :id2 AND status = 1');
			$stmt->execute(['id'=>$id,'id2'=>$id]);
			$this->friendsList = $stmt->fetchAll();
		}
		
		private function populateFriendsOfFriendsList($id, $pdo){
			#Get Friends of Friends
			$stmt = $pdo->prepare('SELECT DISTINCT id, first_name, last_name, email_address
									FROM user AS U
									LEFT JOIN friendship AS F
									ON U.id = F.user_1 AND F.user_2
									LEFT JOIN friendship AS F2
									ON U.id = F2.user_2
									WHERE (F.user_2 IN (
										SELECT DISTINCT id
										FROM user AS U
										LEFT JOIN friendship AS F
										ON U.id = F.user_1
										LEFT JOIN friendship AS F2
										ON U.id = F2.user_2
										WHERE (F.user_2 = :id OR F2.user_1 = :id2) 
										AND (F.status = 1 AND F2.status = 1)
									)
									OR F2.user_1 IN (
										SELECT DISTINCT id
										FROM user AS U
										LEFT JOIN friendship AS F
										ON U.id = F.user_1
										LEFT JOIN friendship AS F2
										ON U.id = F2.user_2
										WHERE (F.user_2 = :id3 OR F2.user_1 = :id4) 
										AND (F.status = 1 AND F2.status = 1)
									))
									AND U.id != :id5');
			$stmt->execute(['id'=>$id,'id2'=>$id,'id3'=>$id,'id4'=>$id,'id5'=>$id]);
			$this->friendsOfFriendsList = $stmt->fetchAll();
		}
		
		private function populateCircleList($id, $pdo){
			$stmt = $pdo->prepare('SELECT id, creator, name, description
									FROM circle AS C
									LEFT JOIN circle_membership AS M
									ON C.id = M.circle_id
									WHERE user_id = :id');
			$stmt->execute(['id'=>$id]);
			include_once 'circle.php';
			foreach($stmt as $row){
				array_push($this->circleList, new circle($row['id'], $row['creator'], $row['name'], $row['description']));
			}
		}
		
		private function populatePendingFriends($id, $pdo){
			#Get Friends List
			$stmt = $pdo->prepare('SELECT DISTINCT id, first_name, last_name
									FROM user AS U
									LEFT JOIN friendship AS F 
									ON U.id = F.user_1
									WHERE
									(F.user_2 = :id)
									AND (F.status = 0)');
			$stmt->execute(['id'=>$id]);
			$this->pendingFriends = $stmt->fetchAll();
		}
		
		function __construct($id, $pdo){
			$stmt = $pdo->prepare('SELECT id, first_name, last_name, privacy_setting_fk
									FROM user
									WHERE id = :id');
			$stmt->execute(['id'=>$id]);
			$row = $stmt->fetch();
			$this->id = $row['id'];
			$this->firstName = $row['first_name'];
			$this->lastName = $row['last_name'];
			$this->privacyLevel = $row['privacy_setting_fk'];
			$this->populateFriendsList($id, $pdo);
			$this->populateFriendsOfFriendsList($id, $pdo);
			$this->populateCircleList($id, $pdo);
			$this->populatePendingFriends($id, $pdo);
		}
		
		function getFriends(){
			return $this->friendsList;
		}
		
		function getFriendsOfFriends(){
			return $this->friendsOfFriendsList;
		}
		
		function getPrivacy(){
			return $this->privacyLevel;
		}
		
		function getFirstName(){
			return $this->firstName;
		}
		
		function getCircles(){
			return $this->circleList;
		}
		
		function getPending(){
			return $this->pendingFriends;
		}
		
		function getId(){
			return $this->id;
		}
	}
?>