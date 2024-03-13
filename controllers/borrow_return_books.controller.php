<?php   
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    require "../../db.php";
    require "../../models/borrow_return_books.model.php";

    function getBorrowReturnBooksController(){
        $db = new Database();

        $connect = $db->connect();
        $borrowReturnBooks = new BorrowReturnBooks( $connect);
       
        $result =  $borrowReturnBooks->getBorrowReturnBooks();
        echo json_encode($result);
    }
    function createBorrowReturnBooksController(){
        $db = new Database();
        $connect = $db->connect();
        $borrowReturnBooks = new BorrowReturnBooks( $connect);
        if(isset($_POST['user_id'])) { $borrowReturnBooks -> user_id= $_POST['user_id'];}
        if(isset($_POST['book_id'])) { $borrowReturnBooks -> book_id= $_POST['user_id'];}
        $result =  $borrowReturnBooks->createBorrowReturnBooks();
        echo json_encode($result);
    }
    function updateBorrowReturnBooksController(){
        $db = new Database();
        $connect = $db->connect();
        $borrowReturnBooks = new BorrowReturnBooks( $connect);
        if(isset($_GET['id'])) { $borrowReturnBooks -> user_id= $_POST['user_id'];}
        $result =  $borrowReturnBooks->updateBorrowReturnBooks();
        echo json_encode($result);
    }
    function deleteBorrowReturnBooksController(){
        $db = new Database();
        $connect = $db->connect();
        $borrowReturnBooks = new BorrowReturnBooks( $connect);
        if(isset($_GET['id'])) { $borrowReturnBooks -> user_id= $_POST['user_id'];}
        $result =  $borrowReturnBooks->deleteBorrowReturnBooks();
        echo json_encode($result);
    }
?>