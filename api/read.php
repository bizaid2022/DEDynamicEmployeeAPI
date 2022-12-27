<?php

//	http://localhost:8080/api/read.php (GET)
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
   
    include $_SERVER['DOCUMENT_ROOT'] .'/DEDynamicEmployeeAPI/config/database.php';
    include $_SERVER['DOCUMENT_ROOT'] .'/DEDynamicEmployeeAPI/class/employees.php';
    $database = new Database();
    $db = $database->getConnection();
    $items = new Employee($db);
    $stmt = $items->getEmployees();
    $itemCount = $stmt->rowCount();
   
    if($itemCount > 0){
        $employeeArr = array();
        $employeeArr["itemCount"] = $itemCount;
        $employeeArr["body"] = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            //get tasks list
            $taskList = $items->getTasksByEnrollNum($EnrollNum);
            $tasksArr=array();
            while ($taskRow = $taskList->fetch(PDO::FETCH_ASSOC)){
                extract($taskRow);
                $task=array(
                    "taskId" =>$TaskId,
                    "name" =>$TaskName,
                    "platform" => $MobileORWeb
                );
                array_push($tasksArr, $task);
            }

            //get Experiences
            $experienceList = $items->getExperiencesByEnrollNum($EnrollNum);
            $experienceArr=array();
            while ($expRow = $experienceList->fetch(PDO::FETCH_ASSOC)){
                extract($expRow);
                $experience=array(
                    "rank" =>$Rank,
                    "company" =>$CompanyInformaiton,
                    "address" => $NoAndStreet,
                    "city" => $CityName,
                    "township" => $TspName,
                    "startDate" => $FromDate,
                    "endDate" => $ToDate,
                    "reasonOfResign" => $ReasonofResign,
                );
                array_push($experienceArr, $experience);
            }

            //get Education
            $educationList = $items->getEducationByEnrollNum($EnrollNum);
            $educationArr=array();
            while ($eduRow = $educationList->fetch(PDO::FETCH_ASSOC)){
                extract($eduRow);
                $education=array(
                    "name" =>$EdName,
                    "university" =>$NameofUni,
                    "startDate" => $FromDate,
                    "endDate" => $ToDate,
                    "city" => $CityName,
                );
                array_push($educationArr, $education);
            }

            //get employee info
            $infoList = $items->getEmployeeInfoByEnrollNum($EnrollNum);
            $infoArr=array();
            while ($infoRow = $infoList->fetch(PDO::FETCH_ASSOC)){
                extract($infoRow);
                $locationArr=array(
                    "location"=>$LocationName,
                    "address" =>$NoAndStreet,
                    "city" =>$CityName,
                    "township" =>$TspName
                );
                $Employeeinformation=array(
                    "position" =>$PositionName,
                    "level" =>$Level,
                    "salary" => $Salary,
                    "department" => $DepartmentName,
                    "location" => $locationArr,
                    "startDate" => $FromDate,
                    "endDate" => $ToDate
                );
                array_push($infoArr, $Employeeinformation);
            }

            //get parmenent address
            $parmenentAdressDetails = $items->getAddressDetails($ParmenentAddressId);
            $parmenetAdressArr=array();
            while ($pAdressRow = $parmenentAdressDetails->fetch(PDO::FETCH_ASSOC)){
                extract($pAdressRow);
                $parmenetAdressArr=array(
                    "adress" =>$NoAndStreet,
                    "city" =>$CityName,
                    "township" => $TspName,
                );
            }

            //get current address
            $currentAdressDetails = $items->getAddressDetails($CurrentAddressId);
            $currentAdressArr=array();
            while ($cAdressRow = $currentAdressDetails->fetch(PDO::FETCH_ASSOC)){
                extract($cAdressRow);
                $currentAdressArr=array(
                    "adress" =>$NoAndStreet,
                    "city" =>$CityName,
                    "township" => $TspName,
                );
            }

            $e = array(
                "EnrollNum" => $EnrollNum,
                "Name" => $Name,
                "Gender" => $Gender,
                "DateofBirth" => $DateofBirth,
                "Age" => $Age,
                "NRCNo" => $NRCNo,
				"FatherName" => $FatherName,
                "Race" => $Race,
                "Religion" => $Religion,
                "MaritalStatus" => $MaritalStatus,
				"Qualification" => $Qualification,
                "ParmenentAddress" => $parmenetAdressArr,
                "CurrentAddress" => $currentAdressArr,
                "EmpId" => $EmpId,
                "PhoneNo" => $PhoneNo,
                "tasks" => $tasksArr,
                "experiences" => $experienceArr,
                "education" => $educationArr,
                "info"=> $infoArr,
            );
            array_push($employeeArr["body"], $e);
        }
        echo json_encode($employeeArr);
    }
    else{
        http_response_code(404);
        echo json_encode(
            array("message" => "No record found.")
        );
    }
?>