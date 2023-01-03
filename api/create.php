<?php
//   http://localhost:8080/api/create.php (POST)

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include $_SERVER['DOCUMENT_ROOT'] . '/DEDynamicEmployeeAPI/config/database.php';
include $_SERVER['DOCUMENT_ROOT'] . '/DEDynamicEmployeeAPI/class/employees.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $item = new Employee($db);
    $data = json_decode(file_get_contents('php://input'));

    //$data = file_get_contents('php://input');
    echo '<pre>';
    print_r($data);
    echo '</pre>';

/* $content = file_get_contents("php://input");
$data = json_decode($content, true); */

    $item->EnrollNum = $data->EnrollNum;
    $item->Name = $data->Name;
    $item->Gender = $data->Gender;
    $item->DateofBirth = $data->DateofBirth;
    $item->Age = $data->Age;
    $item->NRCNo = $data->NRCNo;
    $item->FatherName = $data->FatherName;
    $item->Race = $data->Race;
    $item->Religion = $data->Religion;
    $item->MaritalStatus = $data->MaritalStatus;
    $item->Qualification = $data->Qualification;

    $item->PNoAndStreet = $data->PNoAndStreet;
    $item->PTspId = $data->PTspId;
    $item->PCityId = $data->PCityId;

    $item->CNoAndStreet = $data->CNoAndStreet;
    $item->CTspId = $data->CTspId;
    $item->CCityId = $data->CCityId;

    $item->JoinedDate = $data->JoinedDate;
    $item->EmploymentContractId = $data->EmploymentContractId;
    $item->CompanyName = $data->CompanyName;

    $item->DepartmentId = $data->DepartmentId;
    $item->PositionId = $data->PositionId;
    $item->LocationId = $data->LocationId;
    $item->Salary = $data->Salary;
    $item->FromDate = $data->FromDate;
    $item->ToDate = $data->ToDate;

    $item->PhoneNo = $data->PhoneNo;
    $item->TaskArray = $data->TaskArray;
    $item->ExperienceArray = $data->ExperienceArray;
    $item->EducationArray = $data->EducationArray;

// $item->created = date('Y-m-d H:i:s');

    if ($item->createEmployee()) {
        echo 'Employee created successfully.';
    } else {
        echo 'Employee could not be created.';
    }
} else {
    echo $_SERVER['REQUEST_METHOD'] . ' Method not supported!';
}