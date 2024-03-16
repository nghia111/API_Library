<?php
 header('Access-Control-Allow-Origin:*');
 header('Content-Type: application/json');
  
    function getBorrowReturnBooksValidator(){
        if (isset($_GET['user_id']) && !is_numeric($_GET['user_id'])) {
            http_response_code(422);
            echo json_encode(array("errors:"=> "user_id phải là 1 số và bắt buộc truyền lên")) ;
            return false;
        }
        return true;
    }

    function createBorrowBookValidator(){
        if (!isset($_POST['book_id']) || !is_numeric($_POST['book_id'])) {
            http_response_code(422);
            echo json_encode(array("errors:"=> "book_id phải là 1 số và bắt buộc truyền lên")) ;
            return false;
        }
        $db = new Database();
        $conn = $db -> connect();

        $query = "SELECT * FROM books WHERE id = :book_id AND available > 0";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':book_id',$_POST['book_id']);
        $stmt->execute();

        $isExist = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$isExist){
            http_response_code(404);
            echo json_encode(array("errors" => "sách không tồn tại hoặc đã sách hết trong kho"));
            return false;
        }

        $query = "SELECT * FROM borrow_return_books WHERE user_id = :user_id AND book_id = :book_id AND (status = 0 OR status = 1)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id',$_REQUEST['decode_authorization']->id);
        $stmt->bindParam(':book_id',$_POST['book_id']);
        $stmt->execute();

        $BRB = $stmt->fetch(PDO::FETCH_ASSOC);
        if($BRB){
            http_response_code(200);
            echo json_encode(array("message" => "Sách này đang được bạn mượn"));
            return false;
        }
        return true;

    }

    function createReturnBookValidator(){
        if (!isset($_POST['borrow_id']) || !is_numeric($_POST['borrow_id'])) {
            http_response_code(422);
            echo json_encode(array("errors:"=> "borrow_id phải là 1 số và bắt buộc truyền lên")) ;
            return false;
        }
        $db = new Database();
        $conn = $db -> connect();

        $query = "SELECT * FROM borrow_return_books WHERE id = :id AND status =:status";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id',$_POST['borrow_id']);
        $accepted_borrow =accepted_borrow;
        $stmt->bindParam(':status',$accepted_borrow);
        $stmt->execute();
        $conn = null;

        $isExist = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$isExist){
            http_response_code(404);
            echo json_encode(array("errors" => "không tìm thấy phiếu mượn"));
            return false;
        }

        return true;


    }

    function acceptRejectBorrowValidator(){


        if (!isset($_GET['type']) ) {
            http_response_code(422);
            echo json_encode(array("errors:"=> "phải truyền lên req query type")) ;
            return false;
        }else{
        if($_GET['type'] != accepted_borrow  && $_GET['type'] != rejected_borrow  ){
                http_response_code(422);
                echo json_encode(array("errors:"=> "type phải là 1(accept) hoặc 2(reject)")) ;
                return false;

        }
        }


        if (!isset($_POST['borrow_id']) || !is_numeric($_POST['borrow_id'])) {
            http_response_code(422);
            echo json_encode(array("errors:"=> "borrow_id phải là 1 số và bắt buộc truyền lên")) ;
            return false;
        }
        $db = new Database();
        $conn = $db -> connect();

        $query = "SELECT * FROM borrow_return_books WHERE id = :id AND status=:status";
        $stmt = $conn->prepare($query);
        $request_borrow = request_borrow;
        $stmt->bindParam(':id',$_POST['borrow_id']);
        $stmt->bindParam(':status',$request_borrow);
        $stmt->execute();
        $isExist = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;

        if(!$isExist){
            http_response_code(404);
            echo json_encode(array("errors" => "đã được admin khác verify rồi"));
            return false;
        }
        return true;


    }

// function updateBorrowReturnBookValidator(){
//  if(!isset($_GET['id']) || empty($_GET['id'])){
//     http_response_code(422);
//     echo json_encode(array("error:"=> "vui lòng điền đầy đủ thông tin id"));
//  }
// }

// function deleteBorrowReturnBookValidator(){
//     if(!isset($_GET['id'])|| empty($_GET['id'])   ){
//         http_response_code(422);
//         echo json_encode(array("error:"=> "yêu cầu truyền id lên req query ")) ;
//         return false;
//     }
//     return true;
// }
?>