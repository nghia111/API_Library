<?php
    class Book{
        private $conn;
        
        public $id;	
        public $title	;
        public $available	;
        private $image;	
        public $description;
        public $category_code;
        public $author;

        //connect db 
        public function   __construct($db){    
            $this->conn = $db;
        }

        public function getBooks(){
            $query_params= [];
            $query = "SELECT books.*, categories.code as category_code, categories.value as category_value FROM books JOIN categories ON books.category_code = categories.code where 1=1 ";
            if($this->id){
                $query = $query."AND id = ?";
                array_push($query_params,$this->id);
            }
            if($this->title){
                $query = $query."AND title LIKE ?";
                $titleValue = '%'.$this->title.'%';
                array_push($query_params,$titleValue);
            }
            if($this->available){
                $query = $query."AND available = ?";
                array_push($query_params,$this->available);
            }
            if($this->description){
                $query = $query."AND description = ?";
                array_push($query_params,$this->description);
            }
            if($this->category_code){
                $query = $query."AND category_code = ?";
                array_push($query_params,$this->category_code);
            }
            if($this->author){
                $query = $query."AND author = ?";
                array_push($query_params,$this->author);
            }
    
            $offset = ($_GET['page'] - 1) *$_GET['limit'];
    
            $query= $query."LIMIT ".$_GET['limit']."
                            OFFSET ".$offset." ";
    
            $stmt = $this->conn->prepare($query);
            for ($i = 0; $i < count($query_params); $i++) {
                $stmt->bindParam($i + 1, $query_params[$i]);
            }
            $stmt->execute();
            $this->conn = null;
            return $stmt;
        }
        
    }




?>