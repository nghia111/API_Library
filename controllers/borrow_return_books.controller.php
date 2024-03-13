<?php   
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    require "../../db.php";
    require "../../models/borrow_return_books.model.php";

    function getBorrowReturnBooksController(){
        $db = new Database();

        $connect = $db->connect();
        $borrowReturnBooks = new BorrowReturnBook( $connect);
       
        $result =  $borrowReturnBooks->getBorrowReturnBooks();
        echo json_encode($result);
    }

    function createBorrowBookController(){
        $db = new Database();
        $connect = $db->connect();
        $borrowReturnBook = new BorrowReturnBook( $connect);
        $borrowReturnBook->user_id =   $_REQUEST['decode_authorization']->id;
        $borrowReturnBook->book_id = $_POST['book_id'];
        $result =  $borrowReturnBook->createBorrowBook();
        echo json_encode($result);
    }

    function acceptRejectBorrowController(){
        $db = new Database();
        $connect = $db->connect();
        $borrowReturnBook = new BorrowReturnBook( $connect);
        $borrowReturnBook->id = $_POST['borrow_id'];
        $borrowReturnBook->status = intval($_GET['type']);
        $result =  $borrowReturnBook->acceptRejectBorrow();
        echo json_encode($result);
    }
    function deleteBorrowReturnBooksController(){
        $db = new Database();
        $connect = $db->connect();
        $borrowReturnBooks = new BorrowReturnBook( $connect);
        if(isset($_GET['id'])) { $borrowReturnBooks -> user_id= $_POST['user_id'];}
        $result =  $borrowReturnBooks->deleteBorrowReturnBooks();
        echo json_encode($result);
    }
?>