<?php
	class circle_content{		
		private $circle;
		private $members;
		private $messages;
		
		function __construct($pdo, $circle_id){
			$this->circle = $circle_id;
			$stmt = $pdo->prepare('SELECT id, first_name, last_name, email_address
									FROM user AS U
									LEFT JOIN circle_membership AS M
									ON U.id = M.user_id
									WHERE M.circle_id = :id');
			$stmt->execute(['id'=>$circle_id]);
			$this->members = $stmt->fetchAll();
			
			$stmt = $pdo->prepare('SELECT sender_user_id, first_name, last_name, content, timestamp 
									FROM circle_message AS M
									JOIN user AS U
									ON U.id = M.sender_user_id
									WHERE M.receiver_circle_id = :id
									ORDER BY timestamp DESC');
			$stmt->execute(['id'=>$circle_id]);
			$this->messages = $stmt->fetchAll();
		}
		
		function getMembers(){
			return $this->members;
		}
		
		function getMessages(){
			return $this->messages;
		}
	}
?>