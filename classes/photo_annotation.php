<?php
	class photoAnnotation{
		private $id;
		private $user_id;
		private $username;
		private $timestamp;
		
		function __construct($id, $user_id, $timestamp, $username){
			#echo "Build photo annotation: ".$id;
			$this->id = $id;
			$this->user_id = $user_id;
			$this->timestamp = $timestamp;
			$this->username = $username;
		}
		
		function getId(){
			return $this->id;
		}
		
		function getTimestamp(){
			return $this->timestamp;
		}
		
		function getUser(){
			return $this->user_id;
		}
		
		function getUsername(){
			return $this->username;
		}
	}
?>