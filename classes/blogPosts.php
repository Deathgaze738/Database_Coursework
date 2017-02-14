<?php
	class blogPosts{
		private $numberOfPosts;
		private $listOfPosts;
		private $limit;
		private $numPerPage;
		
		function __construct($id, $pdo, $limit, $numPerPage){
			$stmt = $pdo->prepare('SELECT id, timestamp, content 
									FROM blog_post 
									WHERE blog_owner_id = :id
									ORDER BY timestamp DESC
									LIMIT :lim, :num');
			$stmt->execute(['id'=>$id, 'lim'=>$limit, 'num'=>$numPerPage]);
			$this->listOfPosts = $stmt->fetchAll();
			$this->limit = $limit;
			$this->numPerPage = $numPerPage;
			
			$stmt = $pdo->prepare('SELECT id, timestamp, content 
									FROM blog_post 
									WHERE blog_owner_id = :id
									ORDER BY timestamp');
			$stmt->execute(['id'=>$id]);
			$this->numberOfPosts = $stmt->rowCount();
		}
		
		function getBlogPosts(){
			return $this->listOfPosts;
		}
		
		function getLimit(){
			return $this->limit;
		}
		
		function getNumberPosts(){
			return $this->numberOfPosts;
		}
	}
?>