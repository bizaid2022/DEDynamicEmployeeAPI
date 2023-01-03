<?php

//http://localhost:8080/api/update.php (POST)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../class/employees.php';

$database = new Database();
$db = $database->getConnection();

$item = new Employee($db);

$data = json_decode(file_get_contents("php://input"));

$item->EnrollNum = $data->EnrollNum;
$item->Name = $data->Name;
$item->Gender = $data->Gender;
$item->DateofBirth = $data->DateofBirth;
$item->NRCNo = $data->NRCNo;
$item->FatherName = $data->FatherName;
$item->Race = $data->Race;
$item->Religion = $data->Religion;
$item->MaritalStatus = $data->MaritalStatus;
$item->PhoneNo = $data->PhoneNo;

$item->JoinedDate = $data->JoinedDate;
$item->EmploymentContractId = $data->EmploymentContractId;
$item->CompanyName = $data->CompanyName;

$item->DepartmentId = $data->DepartmentId;
$item->PositionId = $data->PositionId;
$item->LocationId = $data->LocationId;
$item->Salary = $data->Salary;
$item->FromDate = $data->FromDate;
$item->ToDate = $data->ToDate;

$item->ParmenentAddressId = $data->ParmenentAddressId;
$item->PNoAndStreet = $data->PNoAndStreet;
$item->PTspId = $data->PTspId;
$item->PCityId = $data->PCityId;

$item->CurrentAddressId = $data->CurrentAddressId;
$item->CNoAndStreet = $data->CNoAndStreet;
$item->CTspId = $data->CTspId;
$item->CCityId = $data->CCityId;

$item->ExperienceArray = $data->ExperienceArray;
$item->EducationArray = $data->EducationArray;
$item->TaskArray = $data->TaskArray;

if ($item->updateEmployee()) {
    echo json_encode("Employee data updated.");
} else {
    echo json_encode("Data could not be updated");
}
