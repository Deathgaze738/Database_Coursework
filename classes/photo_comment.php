<?php
	class photoComment{
		private $id;
		private $content;
		private $timestamp;
		private $user_id;
		private $username;
		
		function __construct($id, $content, $timestamp, $user_id, $username){
			#echo "Build photo comment: ".$id;
			$this->id = $id;
			$this->content = $content;
			$this->timestamp = $timestamp;
			$this->user_id = $user_id;
			$this->username = $username;
		}
		
		function getId(){
			return $this->id;
		}
		
		function getContent(){
			return $this->content;
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