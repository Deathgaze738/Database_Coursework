<?php
	include_once 'photo_comment.php';
	include_once 'photo_annotation.php';
	class photo{
		private $id;
		private $timestamp;
		private $description;
		private $path;
		private $comments = array();
		private $annotations = array();
		
		function __construct($pdo, $photoId, $timestamp, $description, $path){
			#echo "Build photo: ".$photoId;
			$this->id = $photoId;
			$this->timestamp = $timestamp;
			$this->description = $description;
			$this->path = $path;
			try{
				$stmt = $pdo->prepare("SELECT p.id, content, timestamp, commenter, first_name, last_name
										FROM photo_comment AS p
										JOIN user AS u
										ON p.commenter = u.id
										WHERE photo_id = :id
										ORDER BY timestamp DESC");
				$stmt->execute(['id'=>$photoId]);
			}
			catch(PDOException $e){
				echo $e->getCode();
			}
			foreach($stmt as $row){
				array_push($this->comments, new photoComment($row['id'], $row['content'], $row['timestamp'], $row['commenter'], $row['first_name']." ".$row['last_name']));
			}
			try{
				$stmt = $pdo->prepare("SELECT a.id, user_id, timestamp, first_name, last_name
										FROM annotation AS a
										JOIN user AS u
										ON a.user_id = u.id
										WHERE photo_id = :id
										ORDER BY timestamp DESC");
				$stmt->execute(['id'=>$photoId]);
			}
			catch(PDOException $e){
				echo $e->getCode();
			}
			foreach($stmt as $row){
				array_push($this->annotations, new photoAnnotation($row['id'], $row['user_id'], $row['timestamp'], $row['first_name']." ".$row['last_name']));
			}
		}
		
		function getId(){
			return $this->id;
		}
		
		function getTimestamp(){
			return $this->timestamp;
		}
		
		function getDescription(){
			return $this->description;
		}
		
		function getPath(){
			return $this->path;
		}
		
		function getComments(){
			return $this->comments;
		}
		
		function getAnnotations(){
			return $this->annotations;
		}
	}
?>