<?php 
    
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    require "../../db.php";
    require "../../models/books.model.php";


    function getBooksController(){
            $db = new Database();
    
            $connect = $db->connect();
            $book = new Book( $connect);
            if(isset($_GET['id'])){ $book->id= $_GET['id'];} 
            if(isset($_GET['title'])){ $book->title= $_GET['title'];} 
            if(isset($_GET['available'])){ $book->available= $_GET['available'];} 
            if(isset($_GET['description'])){ $book->description= $_GET['description'];} 
            if(isset($_GET['category_code'])){ $book->category_code= $_GET['category_code'];} 
            if(isset($_GET['author'])){ $book->author= $_GET['author'];} 
            
            $result = $book->getBooks();
            echo json_encode($result);
   
    }
    function getBookByIdController(){
        $db = new Database();

        $connect = $db->connect();
        $book = new Book( $connect);
        if(isset($_GET['id'])){ $book->id= $_GET['id'];} 
        $result = $book->getBookById();
        echo json_encode($result);

}

    function getAllCategoriesController(){
        $db = new Database();
    
        $connect = $db->connect();

        $query = "SELECT * FROM categories";
        $stmt = $connect->prepare($query);
        $stmt->execute();
        $result= $stmt;
        $connect = null;
        $num = $result->rowCount();
        if($num>0){
            $results_array= [];
            while($row= $result->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $item = array(
                    'code'=> $code,
                    'value'=>$value
                );
                array_push($results_array,$item);
            }
            echo json_encode(array("message"=>"Successfully",'categories'=>$results_array));
        }
        else{
            echo json_encode(array('message:'=>"không tìm thấy categories"));    
        }

    }
    
    function createBookController(){
        $db = new Database();

        $connect = $db->connect();
        $book = new Book( $connect);
        if(isset($_POST['title'])){ $book->title= $_POST['title'];} 
        if(isset($_POST['available'])){ $book->available= $_POST['available'];} 
        if(isset($_POST['description'])){ $book->description= $_POST['description'];} 
        if(isset($_POST['author'])){ $book->author= $_POST['author'];} 
        if(isset($_POST['image'])){ $book->image= $_POST['image'];} 
        if(isset($_POST['category_code'])){ $book->category_code= $_POST['category_code'];}
        $result = $book->createBook();
    

        echo json_encode($result);
    }

    function deleteBookController(){
        $db = new Database();

        $connect = $db->connect();
        $book = new Book( $connect);
        $id = $_GET['id'];
        $book->id = $id;
        $result = $book->deleteBook();
        echo json_encode($result);
    }
    
    function updateBookController(){
        $db = new Database();

        $connect = $db->connect();
        $book = new Book( $connect);

        if(isset($_POST['title'])){ $book->title= $_POST['title'];} 
        if(isset($_POST['available'])){ $book->available= $_POST['available'];} 
        if(isset($_POST['description'])){ $book->description= $_POST['description'];} 
        if(isset($_POST['author'])){ $book->author= $_POST['author'];} 
        if(isset($_POST['image'])){ $book->image= $_POST['image'];} 
        if(isset($_POST['category_code'])){ $book->category_code= $_POST['category_code'];}
        if(isset($_GET['id'])){
             $book->id= $_GET['id'];
        }

        $result = $book->updateBook();
        
        echo json_encode($result);

    }
    
?>