<?php   
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    require '../../constants.php';
    require "../../utils/crypto.php";
    require "../../utils/jwt.php";

    require "../../db.php";
    require "../../models/borrow_return_books.model.php";

    function getBorrowReturnBooksController(){
        $db = new Database();

        $connect = $db->connect();
        $borrowReturnBooks = new BorrowReturnBook( $connect);
        if(isset($_GET['user_id'])){ $borrowReturnBooks->user_id= $_GET['user_id'];} 
        $result =  $borrowReturnBooks->getBorrowReturnBooks();
        echo json_encode($result);
    }

    function createBorrowBookController(){
        $db = new Database();
        $connect = $db->connect();
        $borrowReturnBook = new BorrowReturnBook( $connect);
        $borrowReturnBook->user_id =   $_REQUEST['decode_authorization']->id;
        $borrowReturnBook->book_id = $_POST['book_id'];
        $borrowReturnBook->expiration_day =  date('Y-m-d H:i:s', $_POST['expiration_day']);
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

    function createReturnBookController(){
        $db = new Database();
        $connect = $db->connect();
        $borrowReturnBook = new BorrowReturnBook( $connect);
        $borrowReturnBook->id = $_POST['borrow_id'];
        $borrowReturnBook->user_id = $_REQUEST['decode_authorization']->id;
        $result =  $borrowReturnBook->createReturnBook();
        echo json_encode($result);
    }
    function getMyBorrowReturnBooksController(){
        $db = new Database();
        $connect = $db->connect();
        $borrowReturnBook = new BorrowReturnBook( $connect);
        $borrowReturnBook->user_id = $_REQUEST['decode_authorization']->id;
        $result =  $borrowReturnBook->getMyBorrowReturnBooks();
        echo json_encode($result);

    }
?>