<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    include $_SERVER['DOCUMENT_ROOT'] .'/DEDynamicEmployeeAPI/config/database.php';
    include $_SERVER['DOCUMENT_ROOT'] .'/DEDynamicEmployeeAPI/class/employees.php';
    $database = new Database();
    $db = $database->getConnection();
    $items = new Employee($db);

    $userName = $_POST['UserName'];
    $password = $_POST['Password'];
  
    $stmt = $items->logIn($userName,$password);
    $itemCount = $stmt->rowCount();

    $result=array();
    if($itemCount > 0){
        $result=array(
            "success"=>true,
            "message"=>"Log In success"
        );
        echo json_encode($result);
    }
    else{
        http_response_code(404);
        echo json_encode(
            array(
                "success" => false,
                "message" => "Invalid username or password")
        );
    }
?>