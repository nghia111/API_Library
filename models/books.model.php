<?php
    class Book{
        private $conn;
        
        public $id;	
        public $title	;
        public $available	;
        public $image;	
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
                $query = $query."AND description LIKE ?";
                $descriptionValue = '%'.$this->description.'%';
                array_push($query_params,$descriptionValue);

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














            $num = $stmt->rowCount();
            $query2 =  "SELECT COUNT(*) as total_rows FROM BOOKS";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->execute();
            $total_rows_result = $stmt2->fetch(PDO::FETCH_ASSOC);
            $total_rows = $total_rows_result['total_rows'];
            $total_pages = ceil($total_rows / $_GET['limit']);
            $this-> conn = null;
            if($num>0){
                $results_array= [];
                while($row= $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $item = array(
                        'id'=> $id,
                        'title'=>$title,
                        'available'=>$available,
                        'image'=>$image,
                        'description'=>$description,
                        'category_code'=>$category_code,
                        'author'=>$author,
                        'category_code' => $category_code,
                        'category_value' =>$category_value,
                    );
                    array_push($results_array,$item);
                }
                return (array("message"=>"Successfully",'data'=>$results_array, 'total_page' => $total_pages));
            }
            else{
                http_response_code(404);
               return (array('message:'=>"không tìm thấy sách"));    
            }







            return $stmt;
        }
        
        public function createBook(){
            $query = "INSERT INTO books (title,available,description,image,category_code,author) values (:title,:available,:description,:image,:category_code,:author)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':available', $this->available);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':image', $this->image );
            $stmt->bindParam(':category_code', $this->category_code );
            $stmt->bindParam(':author', $this->author );

            $stmt->execute();
    
            $this->conn = null;
            return array("message"=>"tạo sách thành công thành công.");
        }

        public function deleteBook(){
            $query = "DELETE FROM books  WHERE  id= :id  ";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();
            // lấy số row được cập nhật
            $affectedRows = $stmt->rowCount();
            $this->conn = null;
            if($affectedRows >0){
                return array("message"=>"xóa sách thành công.");
            }else{
                http_response_code(404);
                return array("errors"=>"xóa sách thất bại, book not found");
    
            }
        }
        
        public function updateBook(){
            $query_params= [];
            $query = "UPDATE books SET updatedAt = CURRENT_TIMESTAMP()";
            
            if($this->title){
                $query = $query.", title = ?";
                $titleValue = $this->title;
                array_push($query_params,$titleValue);
            }
            if($this->available){
                $query = $query.",available = ?";
                array_push($query_params,$this->available);
            }
            if($this->description){
                $query = $query.",description = ?";
                $descriptionValue = $this->description;
                array_push($query_params,$descriptionValue);

            }
            if($this->category_code){
                $query = $query.",category_code = ?";
                array_push($query_params,$this->category_code);
            }
            if($this->author){
                $query = $query.",author = ?";
                array_push($query_params,$this->author);
            }
            if($this->image){
                $query = $query.",image = ?";
                array_push($query_params,$this->image);
            }
            $query = $query. " WHERE id= ?";
            array_push($query_params,$this->id);

            $stmt = $this->conn->prepare($query);
            for ($i = 0; $i < count($query_params); $i++) {
                $stmt->bindParam($i + 1,  $query_params[$i]);
            }
            $stmt->execute();
            $this->conn = null;
            // lấy số row được cập nhật
            $affectedRows = $stmt->rowCount();
            $this->conn = null;
            if($affectedRows >0){
                return array("message"=>"cập nhật sách thành công");
            }else{
                http_response_code(404);
                return array("errors"=>"cập nhật sách thất bại, book not found");
    
            }
    
        }

    }




?>