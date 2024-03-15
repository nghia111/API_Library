<?php
   class BorrowReturnBook{
   private $conn;
   public  $id;
   public $user_id;
   public $book_id;
   public $status;
   public $borrowed_day;
   public $returned_day;
   
   public function __construct($db){
    $this->conn=$db;
   }
   
   //lấy sách mượn theo user id hoac book id 
    public function getBorrowReturnBooks(){
        $query = 
        " SELECT borrow_return_books.*, users.name as user_name,books.title as book_title
        FROM borrow_return_books
        INNER JOIN users ON borrow_return_books.user_id = users.id
        INNER JOIN books ON borrow_return_books.book_id = books.id ORDER BY borrow_return_books.status ASC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result= $stmt;
            $this->conn = null;
            $num = $result->rowCount();
            if($num>0){
                $results_array= [];
                while($row= $result->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $item = array(
                        'id'=> $id,
                        'user_id'=>$user_id,
                        'user_name'=>$user_name,
                        'book_id'=>$book_id,
                        'book_title'=>$book_title,
                        'status'=>$status,
                        'borrowed_day'=>$borrowed_day,
                        'returned_day'=>$returned_day
                    );
                    array_push($results_array,$item);
                }
                return array("message"=>"Successfully",'data'=>$results_array);
            }
            else{
                http_response_code(404);
                return (array('message:'=>"không tìm thấy phiếu mượn nào "));    
            }
    
    }


        //tạo sách mượn , borrrowed_day ngay thời gian tạo, returned_day là null

    public function createBorrowBook(){
        $query ="INSERT INTO borrow_return_books( user_id, book_id) VALUES(:user_id, :book_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':book_id', $this->book_id);
        $stmt->execute();
        $this->conn = null;
        return array("message"=>"tạo yêu cầu mượn công thành công. Hãy đợi admin duyệt");
    }

    public function createReturnBook(){
        $query = "UPDATE borrow_return_books
        JOIN books ON borrow_return_books.book_id = books.id
        SET borrow_return_books.status =:status, books.available = books.available +1, borrow_return_books.returned_day= CURRENT_TIMESTAMP()
        WHERE borrow_return_books.id=:id AND borrow_return_books.user_id=:user_id" ;
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':user_id',$this->user_id);
        $request_return =request_return;
        $stmt->bindParam(':status',$request_return );
        $stmt->execute();
        $this->conn = null;
        return array("message"=>"Trả sách thành công");

    }

    public function acceptRejectBorrow(){

        $query = "UPDATE borrow_return_books
        JOIN books ON borrow_return_books.book_id = books.id
        SET borrow_return_books.status =:status " ;
        if($this->status == accepted_borrow){
            $query= $query. " ,books.available = books.available- 1 ";
        }
        $query = $query. " WHERE borrow_return_books.id=:id ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        // lấy số row được cập nhật
        $affectedRows = $stmt->rowCount();
        if($affectedRows >0){
            if($this->status == accepted_borrow){                
                return array("message"=>"thành công. Accepted");
            }
            else{
                return array("message"=>"Thành công. Rejected");
            }
               
        }else{
            http_response_code(404);
            return array("errors"=>"Thất bại. ");
        }
    }

    public function getMyBorrowReturnBooks(){
        $query = 
        " SELECT borrow_return_books.*, users.name as user_name,books.title as book_title
        FROM borrow_return_books
        INNER JOIN users ON borrow_return_books.user_id = users.id
        INNER JOIN books ON borrow_return_books.book_id = books.id WHERE borrow_return_books.user_id=:user_id ";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id',$this->user_id);

            $stmt->execute();
            $result= $stmt;
            $this->conn = null;
            $num = $result->rowCount();
            if($num>0){
                $results_array= [];
                while($row= $result->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $item = array(
                        'id'=> $id,
                        'user_id'=>$user_id,
                        'user_name'=>$user_name,
                        'book_id'=>$book_id,
                        'book_title'=>$book_title,
                        'status'=>$status,
                        'borrowed_day'=>$borrowed_day,
                        'returned_day'=>$returned_day
                    );
                    array_push($results_array,$item);
                }
                return array("message"=>"Successfully",'data'=>$results_array);
            }
            else{
                http_response_code(404);
                return (array('message:'=>"không tìm thấy phiếu mượn nào "));    
            }

    }

//     //xóa sách mượn qua mã mượn
//     public function deleteBorrowReturnBooks(){
//     $query="DELETE FROM borrow_return_books where id=:id ";
//     $stmt = $this->conn->prepare($query);
//     $stmt->bindParam(':id', $this->id);
//     $stmt->execute();
//     $affectedRows = $stmt->rowCount();
//     $this->conn = null;
//     if($affectedRows >0){
//         return array("message"=>"xóa sách thành công.");
//     }else{
//         http_response_code(404);
//         return array("errors"=>"xóa sách thất bại, book not found");

//     }
//     }
// //cập nhật thời gian trả sách qua mã mượn
// public function updateBorrowReturnBooks(){
//     $query = "UPDATE borrow_return_books SET returned_day = CURRENT_TIMESTAMP() where id =:id";
//     $stmt = $this->conn->prepare($query);
//     $stmt->bindParam(':id', $this->id);
//     $stmt->execute();
//     $this->conn = null;
//     $affectedRows = $stmt->rowCount();
//     $this->conn = null;
//     if($affectedRows >0){
//         return array("message"=>"cập nhật sách mượn thành công");
//     }else{
//         http_response_code(404);
//         return array("errors"=>"cập nhật sách thất bại, book not found");

//     }
// }

}
?>