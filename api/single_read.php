<?php

//localhost:8080/api/single_read.php/?EnrollNum=2
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	
    include $_SERVER['DOCUMENT_ROOT'] .'/DEDynamicEmployeeAPI/config/database.php';
    include $_SERVER['DOCUMENT_ROOT'] .'/DEDynamicEmployeeAPI/class/employees.php';
    $database = new Database();
	
    $db = $database->getConnection();
    $item = new Employee($db);
    $item->EnrollNum = isset($_GET['EnrollNum']) ? $_GET['EnrollNum'] : die();
	
    $item->getSingleEmployee();
	
    if($item->Name != null){
        // create array
        $emp_arr = array(
	
			   "EnrollNum" => $item->EnrollNum,
                "Name" => $item->Name,
                "Gender" =>$item->Gender,
                "DateofBirth" => $item->DateofBirth,
                "Age" => $item->Age,
                "NRCNo" => $item->NRCNo,
				"FatherName" => $item->FatherName,
                "Race" => $item->Race,
                "Religion" => $item->Religion,
                "MaritalStatus" => $item->MaritalStatus,
				"Qualification" => $item->Qualification,
                "ParmenentAddressId" => $item->ParmenentAddressId,
                "CurrentAddressId" => $item->CurrentAddressId,
                "EmpId" => $item->EmpId,
                "PhoneNo" => $item->PhoneNo
        );
      
        http_response_code(200);
        echo json_encode($emp_arr);
    }
      
    else{
        http_response_code(404);
        echo json_encode("Employee not found.");
    }
?>