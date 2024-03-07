<?php
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');

    function getBooksValidator() {
        $errors = [];
        if(isset($_GET['limit'])){
            $limit = $_GET['limit'];
        }else{
            $limit = null;
        }     
        if(isset($_GET['page'])){
            $page = $_GET['page'];
        }else{
            $page = null;
        }
        // Kiểm tra $id
        if (isset($_GET['id']) && !is_numeric($_GET['id'])) {
            $errors[] = "ID phải là một số.";
        }
    
        // Kiểm tra $title
        if (isset($_GET['title'])) {
            if (!is_string($_GET['title']) || empty($_GET['title'])) {
                $errors[] = "title phải là một chuỗi và không được để trống.";
            }
        }
        
        // Kiểm tra $available
        if (isset($_GET['available']) && !is_numeric($_GET['available'])) {
            $errors[] = "available phải là một số.";
        }
    
        // Kiểm tra $description
        if (isset($_GET['description'])) {
            if (!is_string($_GET['description']) || empty($_GET['description'])) {
                $errors[] = "description phải là một chuỗi và không được để trống.";
            }
        }
        // Kiểm tra $category
        if (isset($_GET['category_code'])) {
             if (!is_string($_GET['category_code']) || empty($_GET['category_code'])) {
                  $errors[] = "category_code phải là một chuỗi và không được để trống.";
              }
           }
        
        
        if ($limit ==null) {
            $errors[] = "Tham số 'limit' là bắt buộc.";
        }else{
            if(intval($limit)<=0 || intval($limit)>99){
                $errors[] = "0 < 'limit' < 100.";
            }
        }
        if ($page == null) {
            $errors[] = "Tham số 'page' là bắt buộc.";
        }else{
            if(intval($page)<=0){
                $errors[] = "'page' > 0 .";
            }
        }
        if (!empty($errors)) {
                http_response_code(422);
                echo json_encode(array("error:"=> $errors)) ;
            
            return false;
        }
        return true;
}

?>