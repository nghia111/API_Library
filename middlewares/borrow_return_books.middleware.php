<?php
 header('Access-Control-Allow-Origin:*');
 header('Content-Type: application/json');
  
// function getBorrowReturnBookValidator(){
//     $errors = [];
   
//     // Kiểm tra $user_id
//     if (isset($_GET['user_id']) && !is_numeric($_GET['user_id'])) {
//         $errors[] = "User id phải là một số.";
//     }
//     // Kiểm tra $book_id
//     if (isset($_GET['book_id']) && !is_numeric($_GET['book_id'])) {
//         $errors[] = "Book id phải là một số.";
//     }
  
//     }
//     if (!empty($errors)) {
//             http_response_code(422);
//             echo json_encode(array("error:"=> $errors)) ;
        
//         return false;
//     }
//     return true;

// function createBorrowReturnBookValidator(){
//     $error=[];
//     if(!isset($_POST['user_id']) || empty($_POST['user_id'])|| !isset($_POST['book_id'])|| empty($_POST['book_id'])){
//         http_response_code(422);
//         echo json_encode(array("error:"=> "vui lòng điền đầy đủ thông tin: user_id, book_id"));
//     }
//     if (isset($_POST['user_id']) && !is_numeric($_POST['user_id'])) {
//             $errors[] = "User id phải là một số.";
//         }
//     if (isset($_POST['book_id']) && !is_numeric($_POST['book_id'])) {
//             $errors[] = "Book id phải là một số.";
//         }
//     if (!empty($errors)) {
//             // lỗi validate 
//         http_response_code(422);
//         echo json_encode(array("error:"=> $errors)) ;
//         return false;
//         }
//     return true;

// }


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