<?php
   class BorrowReturnBook{
   private $conn;
   public  $id;
   public $user_id;
   public $book_id;
   public $status;
   public $borrowed_day;
   public $expiration_day;
   public $returned_day;
   
   public function __construct($db){
    $this->conn=$db;
   }
   
    public function getBorrowReturnBooks(){
        $query = 
        " SELECT borrow_return_books.*, users.name as user_name,books.title as book_title
        FROM borrow_return_books
        INNER JOIN users ON borrow_return_books.user_id = users.id
        INNER JOIN books ON borrow_return_books.book_id = books.id ";

        if($this->user_id){
            $query .= " WHERE user_id=:user_id ";
        }

        $query .= " ORDER BY borrow_return_books.status ASC ";

            $stmt = $this->conn->prepare($query);
            if($this->user_id){
                $stmt->bindParam(':user_id',$this->user_id);
            }
                $stmt->execute();
            $result= $stmt;
            $num = $result->rowCount();
            if($num>0){
                $results_array= [];
                while($row= $result->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                     // Kiểm tra ngày quá hạn và cập nhật trạng thái nếu cần thiết
                    $days_borrowed = strtotime($expiration_day) - strtotime($borrowed_day);
                    if ($days_borrowed < 0) {
                        $status = expired; // Cập nhật trạng thái thành 4 (quá hạn)
                        // Thực hiện câu lệnh SQL để cập nhật trạng thái trong bảng borrow_return_books
                        $update_status_query = "UPDATE borrow_return_books SET status = :status WHERE id = :id";
                        $update_stmt = $this->conn->prepare($update_status_query);
                        $update_stmt->bindParam(':status', $status);
                        $update_stmt->bindParam(':id', $id);
                        $update_stmt->execute();
                    }
                    $item = array(
                        'id'=> $id,
                        'user_id'=>$user_id,
                        'user_name'=>$user_name,
                        'book_id'=>$book_id,
                        'book_title'=>$book_title,
                        'status'=>$status,
                        'borrowed_day'=>$borrowed_day,
                        'expiration_day'=>$expiration_day,
                        'returned_day'=>$returned_day
                    );
                    array_push($results_array,$item);
                }
                $this->conn = null;
                return array("message"=>"Successfully",'data'=>$results_array);
            }
            else{
                http_response_code(404);
                return (array('message:'=>"không tìm thấy phiếu mượn nào "));    
            }
    
    }
    public function createBorrowBook(){
        $query ="INSERT INTO borrow_return_books( user_id, book_id, expiration_day) VALUES(:user_id, :book_id, :expiration_day)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':book_id', $this->book_id);
        $stmt->bindParam(':expiration_day', $this->expiration_day);

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
        INNER JOIN books ON borrow_return_books.book_id = books.id WHERE borrow_return_books.user_id=:user_id  ORDER BY borrowed_day DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id',$this->user_id);

            $stmt->execute();
            $result= $stmt;
            $num = $result->rowCount();
            if($num>0){
                $results_array= [];
                while($row= $result->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    // Kiểm tra ngày quá hạn và cập nhật trạng thái nếu cần thiết
                    $days_borrowed = strtotime($expiration_day) - strtotime($borrowed_day);
                    if ($days_borrowed < 0) {
                        $status = expired; // Cập nhật trạng thái thành 4 (quá hạn)
                        // Thực hiện câu lệnh SQL để cập nhật trạng thái trong bảng borrow_return_books
                        $update_status_query = "UPDATE borrow_return_books SET status = :status WHERE id = :id";
                        $update_stmt = $this->conn->prepare($update_status_query);
                        $update_stmt->bindParam(':status', $status);
                        $update_stmt->bindParam(':id', $id);
                        $update_stmt->execute();
                    }
                    $item = array(
                        'id'=> $id,
                        'user_id'=>$user_id,
                        'user_name'=>$user_name,
                        'book_id'=>$book_id,
                        'book_title'=>$book_title,
                        'status'=>$status,
                        'borrowed_day'=>$borrowed_day,
                        'expiration_day'=>$expiration_day,
                        'returned_day'=>$returned_day
                    );
                    array_push($results_array,$item);
                }
                $this->conn = null;
                return array("message"=>"Successfully",'data'=>$results_array);
            }
            else{
                http_response_code(404);
                return (array('message:'=>"không tìm thấy phiếu mượn nào "));    
            }

    }

}
?>