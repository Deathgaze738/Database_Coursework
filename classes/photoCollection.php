<?php
	include_once 'photo.php';
	class photoCollection{
		private $id;
		private $name;
		private $owner;
		private $description;
		private $privacyLevel;
		private $numberOfPhotos;
		private $photos = array();
		
		function __construct($pdo, $collectionId){
			#echo "Build photo collection: ".$collectionId;
			$this->id = $collectionId;
			try{
				$stmt = $pdo->prepare("SELECT user_owner, privacy_setting, name, description, (SELECT count(*) FROM photo WHERE album_id = :id) AS noOfPhotos
										FROM photo_album
										WHERE id = :id2");
				$stmt->execute(['id'=>$collectionId, 'id2'=>$collectionId]);
			}
			catch(PDOException $e){
				echo $e->getCode();
			}
			if($stmt->rowCount() == 0){
				return NULL;
			}
			$row = $stmt->fetch();
			$this->name = $row['name'];
			$this->description = $row['description'];
			$this->privacyLevel = $row['privacy_setting'];
			$this->numberOfPhotos = $row['noOfPhotos'];
			$this->owner = $row['user_owner'];
			try{
				$stmt = $pdo->prepare("SELECT id, timestamp, description, path
										FROM photo
										WHERE album_id = :id
										ORDER BY timestamp DESC");
				$stmt->execute(['id'=>$collectionId]);
			}
			catch(PDOException $e){
				echo $e->getCode();
			}
			foreach($stmt as $row){
				array_push($this->photos, new photo($pdo, $row['id'], $row['timestamp'], $row['description'], $row['path']));
			}
		}
		
		function getOwner(){
			return $this->owner;
		}
		
		function getName(){
			return $this->name;
		}
		
		function getDescription(){
			return $this->description;
		}
		
		function getPrivacyLevel(){
			return $this->privacyLevel;
		}
		
		function getNumberOfPhotos(){
			return $this->numberOfPhotos;
		}
		
		function getPhotos(){
			return $this->photos;
		}
	}
?>