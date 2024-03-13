<?php
 header('Access-Control-Allow-Origin:*');
 header('Content-Type: application/json');
  
function createBorrowReturnBookValidator(){
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
        echo json_encode(array("errors" => "sách không tồn tại hoặc đã sách hết trong kho)"));
        $conn = null;
        return false;
    }

    return true;

}

function acceptValidator(){
    if (!isset($_POST['borrow_id']) || !is_numeric($_POST['borrow_id'])) {
        http_response_code(422);
        echo json_encode(array("errors:"=> "borrow_id phải là 1 số và bắt buộc truyền lên")) ;
        return false;
    }
    $db = new Database();
    $conn = $db -> connect();

    $query = "SELECT * FROM borrow_return_books WHERE id = :id AND status=0";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id',$_POST['borrow_id']);
    $stmt->execute();
    $isExist = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$isExist){
        http_response_code(404);
        echo json_encode(array("errors" => "đã được admin khác verify rồi)"));
        $conn = null;
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