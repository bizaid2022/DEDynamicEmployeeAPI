<?php
class Employee
{

    // Connection
    private $conn;
    // Table
    private $db_table = "employeetable";
    // Columns
    public $EnrollNum;
    public $Name;
    public $Gender;
    public $DateofBirth;
    public $Age;
    public $NRCNo;
    public $FatherName;
    public $Race;
    public $Religion;
    public $MaritalStatus;
    public $Qualification;
    public $ParmenentAddressId;
    public $CurrentAddressId;
    public $EmpId;
    public $PhoneNo;
    public $PNoAndStreet;
    public $PTspId;
    public $PCityId;
    public $CNoAndStreet;
    public $CTspId;
    public $CCityId;
    public $JoinedDate;
    public $EmploymentContractId;
    public $CompanyName;
    public $TaskArray;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // (I) Employee Id
    public function autoincemp()
    {
        $emplyeeTable = "employeetable";

        global $value2;
        $query = "SELECT * from " . $emplyeeTable . " order by EnrollNum desc LIMIT 1";
        $stmt1 = $this->conn->prepare($query);
        $stmt1->execute();

        if ($stmt1->rowCount() > 0) {
            $row = $stmt1->fetch(PDO::FETCH_ASSOC);
            $value2 = $row['EnrollNum'];
            $value2 = substr($value2, 5, 8);
            $value2 = (int) $value2 + 1;
            $p = "EMP" . date("y");
            $value2 = $p . sprintf('%03s', $value2);
            $value = $value2;
            return $value;
        } else {
            $value2 = "EMP" . date("y") . "001";
            $value = $value2;
            return $value;
        }
    }

    // Login
    public function logIn($userName, $password)
    {
        $loginTable = "logintable";

        $sqlQuery = "SELECT * FROM " . $loginTable . " where Username = :userName AND Password = :password";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":userName", $userName);
        $stmt->bindParam(":password", $password);
        $stmt->execute();

        return $stmt;
    }

    // GET ALL
    public function getEmployees()
    {
        $sqlQuery = "SELECT EnrollNum,Name,Gender,DateofBirth,Age,NRCNo,FatherName,Race,Religion,MaritalStatus,Qualification,ParmenentAddressId,CurrentAddressId,EmpId,PhoneNo FROM " . $this->db_table . "";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();

        return $stmt;
    }

    // GET Tasks By EnrollNum
    public function getTasksByEnrollNum($enrollNum)
    {
        $emptsk = "empandtask";
        $tsk = "task";

        $sqlQuery = "SELECT $tsk.* FROM " . $tsk . "," . $emptsk . "  WHERE $tsk.TaskId=$emptsk.TasksId AND $emptsk.EnrollNum = :id ";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":id", $enrollNum);
        $stmt->execute();
        return $stmt;
    }

    // GET Tasks By EnrollNum
    public function getExperiencesByEnrollNum($enrollNum)
    {
        //table name
        $empandexp = "empandexp";
        $exp = "experiencetable";
        $address = "address";
        $city = "city";
        $township = "tsptable";

        $sqlQuery = "SELECT $exp.Rank,$exp.CompanyInformaiton,$address.NoAndStreet,$city.CityName,$township.TspName,$exp.FromDate,$exp.ToDate,$exp.ReasonofResign FROM " . $empandexp . "," . $exp . "," . $address . "," . $city . "," . $township . "  WHERE $exp.CompanyAddressId=$address.Id AND $address.CityId=$city.CityId AND $address.TspId=$township.Id AND $exp.ExperienceId=$empandexp.experienceId AND $empandexp.EnrollNum = :id ";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":id", $enrollNum);
        $stmt->execute();
        return $stmt;
    }

    //Get Education
    public function getEducationByEnrollNum($enrollNum)
    {
        //table name
        $eduandemp = "eduandemployee";
        $edu = "educationtable";
        $city = "city";

        $sqlQuery = "SELECT $edu.EdName,$edu.NameofUni,$edu.FromDate,$edu.ToDate,$city.CityName  FROM " . $eduandemp . "," . $edu . "," . $city . "  WHERE $edu.CityId=$city.CityId AND  $edu.EdId=$eduandemp.EdId AND $eduandemp.EnrollNum = :id ";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":id", $enrollNum);
        $stmt->execute();
        return $stmt;
    }

    //Get Employee Info
    public function getEmployeeInfoByEnrollNum($enrollNum)
    {
        //table name
        $empInfo = "empinfotable";
        $empAndInfo = "emandinfo";
        $dep = "departmenttable";
        $position = "positiontable";
        $location = "locationtable";
        $address = "address";
        $city = "city";
        $township = "tsptable";

        $sqlQuery = "SELECT $position.PositionName,$position.Level,$empInfo.Salary,$dep.DepartmentName,$location.LocationName,$address.NoAndStreet,$city.CityName,$township.TspName,$empInfo.FromDate,$empInfo.ToDate  FROM " . $empInfo . "," . $empAndInfo . "," . $dep . "," . $position . "," . $location . "," . $address . "," . $city . " ," . $township . "   WHERE $empAndInfo.EmployeeInfoId=$empInfo.EmpInfoId AND $empInfo.LocationId=$location.LocationId AND $location.AddressId=$address.Id AND $address.CityId=$city.CityId AND $address.TspId=$township.Id AND $empInfo.departmentId=$dep.DepartmentId AND $empInfo.PositionId=$position.PositionId AND $empAndInfo.EnrollNumId = :id ";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":id", $enrollNum);
        $stmt->execute();
        return $stmt;
    }

    public function getAddressDetails($addressId)
    {
        //tableName
        $address = "address";
        $city = "city";
        $township = "tsptable";

        $sqlQuery = "SELECT $address.NoAndStreet,$city.CityName,$township.TspName  FROM " . $address . "," . $city . " ," . $township . "   WHERE $address.CityId=$city.CityId AND $address.TspId=$township.Id AND $address.Id = :id ";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":id", $addressId);
        $stmt->execute();
        return $stmt;
    }

    // CREATE
    public function createEmployee()
    {
        $addresstbl = "address";
        $emptbl = "employmenttable";
        $emptsk = "empandtask";
        $taskTbl = "task";

        //experience
        $empExp = 'experiencetable';
        $empAndExp = 'empandexp';

        // education
        $empEdu = 'educationtable';
        $empAndEdu = 'eduandemployee';

        //empInfo
        $empAndInfo = 'emandinfo';
        $empInfo = 'empinfotable';

        $departmenttbl = 'departmenttable';
        $depAndPos = 'depandpos';
        $positiontbl = 'positiontable';
        $locationtbl = 'locationtable';

        //loginInfo table
        $loginInfo = 'logintable';

        // Permanent Address
        $sqlQueryPadd = "
                                INSERT INTO " . $addresstbl . "
                            SET
                                NoAndStreet = :PNoAndStreet,
                                TspId = :PTspId,
                                CityId = :PCityId";
        $stmtPadd = $this->conn->prepare($sqlQueryPadd);

        // sanitize - Permanent Address
        $this->PNoAndStreet = htmlspecialchars(strip_tags($this->PNoAndStreet));
        $this->PTspId = htmlspecialchars(strip_tags($this->PTspId));
        $this->PCityId = htmlspecialchars(strip_tags($this->PCityId));

        // bind data Permanent Address
        $stmtPadd->bindParam(":PNoAndStreet", $this->PNoAndStreet);
        $stmtPadd->bindParam(":PTspId", $this->PTspId);
        $stmtPadd->bindParam(":PCityId", $this->PCityId);

        $stmtPadd->execute();
        $PaddId = $this->conn->lastInsertId();

        // Current Address
        $sqlQueryCadd = "INSERT INTO
                                " . $addresstbl . "
                            SET
                                NoAndStreet = :CNoAndStreet,
                                TspId = :CTspId,
                                CityId = :CCityId";
        $stmtCadd = $this->conn->prepare($sqlQueryCadd);

        // sanitize - Current  Address
        $this->CNoAndStreet = htmlspecialchars(strip_tags($this->CNoAndStreet));
        $this->CTspId = htmlspecialchars(strip_tags($this->CTspId));
        $this->CCityId = htmlspecialchars(strip_tags($this->CCityId));

        //bind data Current  Address
        $stmtCadd->bindParam(":CNoAndStreet", $this->CNoAndStreet);
        $stmtCadd->bindParam(":CTspId", $this->CTspId);
        $stmtCadd->bindParam(":CCityId", $this->CCityId);

        $stmtCadd->execute();
        $CaddId = $this->conn->lastInsertId();

        $sqlQueryEmpInfo = "INSERT INTO
                                " . $emptbl . "
                            SET
                                JoinedDate = :JoinedDate,
                                EmploymentContractId = :EmploymentContractId,
                                CompanyName = :CompanyName";

        $stmtEmp = $this->conn->prepare($sqlQueryEmpInfo);

        // sanitize - Employment Data
        $this->JoinedDate = htmlspecialchars(strip_tags($this->JoinedDate));
        $this->EmploymentContractId = htmlspecialchars(strip_tags($this->EmploymentContractId));
        $this->CompanyName = htmlspecialchars(strip_tags($this->CompanyName));

        //bind data Employment Data
        $stmtEmp->bindParam(":JoinedDate", $this->JoinedDate);
        $stmtEmp->bindParam(":EmploymentContractId", $this->EmploymentContractId);
        $stmtEmp->bindParam(":CompanyName", $this->CompanyName);

        $stmtEmp->execute();
        $EmploymentId = $this->conn->lastInsertId();

        $sqlQueryMain = "INSERT INTO
                                " . $this->db_table . "
                            SET
                                EnrollNum = :EnrollNum,
                                Name = :Name,
                                Gender = :Gender,
                                DateofBirth = :DateofBirth,
                                Age = :Age,
                                NRCNo = :NRCNo,
                                FatherName = :FatherName,
                                Race = :Race,
                                Religion = :Religion,
                                MaritalStatus = :MaritalStatus,
                                Qualification = :Qualification,
                                ParmenentAddressId = :PaddId ,
                                CurrentAddressId = :CaddId ,
                                EmpId = :EmploymentId ,
                                PhoneNo = :PhoneNo";

        $stmt = $this->conn->prepare($sqlQueryMain);

        // sanitize - employee
        $this->EnrollNum = htmlspecialchars(strip_tags($this->EnrollNum));
        $this->Name = htmlspecialchars(strip_tags($this->Name));
        $this->Gender = htmlspecialchars(strip_tags($this->Gender));
        $this->DateofBirth = htmlspecialchars(strip_tags($this->DateofBirth));
        $this->Age = htmlspecialchars(strip_tags($this->Age));
        $this->NRCNo = htmlspecialchars(strip_tags($this->NRCNo));
        $this->FatherName = htmlspecialchars(strip_tags($this->FatherName));
        $this->Race = htmlspecialchars(strip_tags($this->Race));
        $this->Religion = htmlspecialchars(strip_tags($this->Religion));
        $this->MaritalStatus = htmlspecialchars(strip_tags($this->MaritalStatus));
        $this->Qualification = htmlspecialchars(strip_tags($this->Qualification));
        $this->PhoneNo = htmlspecialchars(strip_tags($this->PhoneNo));

        // bind data employee
        $stmt->bindParam(":EnrollNum", $this->EnrollNum);
        $stmt->bindParam(":Name", $this->Name);
        $stmt->bindParam(":Gender", $this->Gender);
        $stmt->bindParam(":DateofBirth", $this->DateofBirth);
        $stmt->bindParam(":Age", $this->Age);
        $stmt->bindParam(":NRCNo", $this->NRCNo);
        $stmt->bindParam(":FatherName", $this->FatherName);
        $stmt->bindParam(":Race", $this->Race);
        $stmt->bindParam(":Religion", $this->Religion);
        $stmt->bindParam(":MaritalStatus", $this->MaritalStatus);
        $stmt->bindParam(":Qualification", $this->Qualification);

        //bind data Employment Data
        $stmt->bindParam(":PaddId", $PaddId);
        $stmt->bindParam(":CaddId", $CaddId);
        $stmt->bindParam(":EmploymentId", $EmploymentId);
        $stmt->bindParam(":PhoneNo", $this->PhoneNo);

        $stmt->execute();

        //// employee information creation

        $this->LocationId = htmlspecialchars(strip_tags($this->LocationId));
        $this->DepartmentId = htmlspecialchars(strip_tags($this->DepartmentId));
        $this->PositionId = htmlspecialchars(strip_tags($this->PositionId));
        $this->Salary = htmlspecialchars(strip_tags($this->Salary));
        $this->FromDate = htmlspecialchars(strip_tags($this->FromDate));
        $this->ToDate = htmlspecialchars(strip_tags($this->ToDate));

        $stmtEmpInfo = $this->conn->prepare("INSERT INTO " . $empInfo . "
                (LocationId, departmentId, PositionId, Salary,FromDate, ToDate)
                VALUES (:LocationId, :departmentId, :PositionId, :Salary, :FromDate, :ToDate)");
        $stmtEmpInfo->bindParam(':LocationId', $this->LocationId);
        $stmtEmpInfo->bindParam(':departmentId', $this->DepartmentId);
        $stmtEmpInfo->bindParam(':PositionId', $this->PositionId);
        $stmtEmpInfo->bindParam(':Salary', $this->Salary);
        $stmtEmpInfo->bindParam(':FromDate', $this->FromDate);
        $stmtEmpInfo->bindParam(':ToDate', $this->ToDate);

        $stmtEmpInfo->execute();
        $empInfoId = $this->conn->lastInsertId();

        //// employee and information linked table creation
        $stmtEmpAndInfo = $this->conn->prepare("INSERT INTO " . $empAndInfo . " (EnrollNumId, EmployeeInfoId)
                        VALUES (:EnrollNumId, :EmployeeInfoId)");
        $stmtEmpAndInfo->bindParam(':EnrollNumId', $this->EnrollNum);
        $stmtEmpAndInfo->bindParam(':EmployeeInfoId', $empInfoId);

        $stmtEmpAndInfo->execute();

        /*    $stmtTaskEmp = $this->conn->prepare("INSERT INTO `empandtask` (`Id`, `EnrollNum`, `TasksId`) VALUES (NULL, :EnrollNum,:TaskId)");
        foreach($data as $row)
        {
        $stmtTask->execute(
        "EnrollNum" => $this->EnrollNum,array(
        "TaskId" => $row['TaskId']
        ));
        }*/
        //$data = json_decode($this->TaskArray);

        // foreach((array)$this->TaskArray as $row){
        // $stmtTaskEmp=$this->conn->prepare("INSERT INTO ".$emptsk."  SET
        //             EnrollNum = :EnrollNum,
        //             TasksId = :TaskId");
        //   $stmtTaskEmp->bindValue(":EnrollNum", $this->EnrollNum);
        //   $stmtTaskEmp->bindValue(":TaskId",var_dump($row->TaskId));
        //   $stmtTaskEmp->execute();

        //// Task Creation////
        foreach ((array) $this->TaskArray as $row) {
            $taskSql = "INSERT INTO
                        " . $taskTbl . "
                    SET
                         TaskName= :TaskName,
                        MobileORWeb = :MobileORWeb";
            $stmtTask = $this->conn->prepare($taskSql);

            // sanitize - Task Table
            $row->TaskName = htmlspecialchars(strip_tags($row->TaskName));
            $row->MobileORWeb = htmlspecialchars(strip_tags($row->MobileORWeb));

            //bind data Task  Table
            $stmtTask->bindParam(":TaskName", $row->TaskName);
            $stmtTask->bindParam(":MobileORWeb", $row->MobileORWeb);

            $stmtTask->execute();

            $taskId = $this->conn->lastInsertId();

            $stmtTaskEmp = $this->conn->prepare("INSERT INTO " . $emptsk . "  (EnrollNum, TasksId)
                        VALUES (:EnrollNum, :TaskId)");
            $stmtTaskEmp->bindParam(':EnrollNum', $this->EnrollNum);
            $stmtTaskEmp->bindParam(':TaskId', $taskId);
            $stmtTaskEmp->execute();

            //return true;

            //  return false;
        }

        //// Expericence Creation ////
        foreach ((array) $this->ExperienceArray as $row) {
            // Company Address
            $sqlQueryCmpyadd = "INSERT INTO
                        " . $addresstbl . "
                    SET
                        NoAndStreet = :CmpyNoAndStreet,
                        TspId = :CmpyTspId,
                        CityId = :CmpyCityId";
            $stmtCmpyadd = $this->conn->prepare($sqlQueryCmpyadd);

            // sanitize - Company  Address
            $row->CmpyNoAndStreet = htmlspecialchars(strip_tags($row->CmpyNoAndStreet));
            $row->CmpyTspId = htmlspecialchars(strip_tags($row->CmpyTspId));
            $row->CmpyCityId = htmlspecialchars(strip_tags($row->CmpyCityId));

            //bind data Company  Address
            $stmtCmpyadd->bindParam(":CmpyNoAndStreet", $row->CmpyNoAndStreet);
            $stmtCmpyadd->bindParam(":CmpyTspId", $row->CmpyTspId);
            $stmtCmpyadd->bindParam(":CmpyCityId", $row->CmpyCityId);

            $stmtCmpyadd->execute();

            $CmpyaddId = $this->conn->lastInsertId();

            //create entries for experiencetable
            $stmtExp = $this->conn->prepare("INSERT INTO " . $empExp . "
                            (Rank, CompanyInformaiton,CompanyAddressId, FromDate, ToDate, ReasonofResign)
                            VALUES (:Rank, :CompanyInformaiton,:CompanyAddressId, :FromDate, :ToDate, :ReasonofResign)");

            $stmtExp->bindParam(':Rank', $row->Rank);
            $stmtExp->bindParam(':CompanyInformaiton', $row->CompanyInformaiton);
            $stmtExp->bindParam(':CompanyAddressId', $CmpyaddId);
            $stmtExp->bindParam(':FromDate', $row->FromDate);
            $stmtExp->bindParam(':ToDate', $row->ToDate);
            $stmtExp->bindParam(':ReasonofResign', $row->ReasonofResign);

            $stmtExp->execute();

            $expId = $this->conn->lastInsertId();

            //create entries for employee and experience table
            $stmtEmpExp = $this->conn->prepare("INSERT INTO " . $empAndExp . "  (EnrollNum, experienceId)
                        VALUES (:EnrollNum, :experienceId)");
            $stmtEmpExp->bindParam(':EnrollNum', $this->EnrollNum);
            $stmtEmpExp->bindParam(':experienceId', $expId);
            $stmtEmpExp->execute();

            // return true;
        }

        ///// Education Creations ////
        foreach ((array) $this->EducationArray as $row) {

            //create entries for educationtable
            $stmtEdu = $this->conn->prepare("INSERT INTO " . $empEdu . "
                            (EdName, NameofUni, FromDate, ToDate, CityId)
                            VALUES (:EdName, :NameofUni, :FromDate, :ToDate, :CityId)");

            $stmtEdu->bindParam(':EdName', $row->EdName);
            $stmtEdu->bindParam(':NameofUni', $row->NameofUni);
            $stmtEdu->bindParam(':FromDate', $row->FromDate);
            $stmtEdu->bindParam(':ToDate', $row->ToDate);
            $stmtEdu->bindParam(':CityId', $row->CityId);

            $stmtEdu->execute();

            $eduId = $this->conn->lastInsertId();

            //create entries for employee and education linked table
            $stmtEmpEdu = $this->conn->prepare("INSERT INTO " . $empAndEdu . "  (EdId, EnrollNum)
                        VALUES (:EdId, :EnrollNum)");

            $stmtEmpEdu->bindParam(':EdId', $eduId);
            $stmtEmpEdu->bindParam(':EnrollNum', $this->EnrollNum);
            $stmtEmpEdu->execute();
            // return true;
        }

        /// insert employee into logintable
        $stmtLoginInfo = $this->conn->prepare("INSERT INTO " . $loginInfo . "
                    (Username, Password, EnrollNum, PhoneNo, Date)
                    VALUES (:Username, :Password, :EnrollNum, :PhoneNo, :Date)");

        $pwd = 'password';
        // $pwd = password_hash('password', PASSWORD_DEFAULT);
        $date = date('Y-m-d H:i:s');

        $stmtLoginInfo->bindParam(':Username', $this->PhoneNo);
        $stmtLoginInfo->bindParam(':Password', $pwd);
        $stmtLoginInfo->bindParam(':EnrollNum', $this->EnrollNum);
        $stmtLoginInfo->bindParam(':PhoneNo', $this->PhoneNo);
        $stmtLoginInfo->bindParam(':Date', $date);
        $stmtLoginInfo->execute();
    }

    // READ single
    public function getSingleEmployee()
    {
        echo ($this->EnrollNum);
        $sqlQuery = "SELECT EnrollNum, Name, Gender, DateofBirth, Age, NRCNo, FatherName, Race, Religion, MaritalStatus, Qualification, ParmenentAddressId, CurrentAddressId, EmpId, PhoneNo FROM  " . $this->db_table . " WHERE EnrollNum = $this->EnrollNum ";
        $stmt = $this->conn->prepare($sqlQuery);

        //   $stmt->bindParam(1,$this->EnrollNum);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->EnrollNum = $dataRow['EnrollNum'];
        $this->Name = $dataRow['Name'];
        $this->Gender = $dataRow['Gender'];
        $this->DateofBirth = $dataRow['DateofBirth'];
        $this->Age = $dataRow['Age'];
        $this->NRCNo = $dataRow['NRCNo'];
        $this->FatherName = $dataRow['FatherName'];
        $this->Race = $dataRow['Race'];
        $this->Religion = $dataRow['Religion'];
        $this->MaritalStatus = $dataRow['MaritalStatus'];
        $this->Qualification = $dataRow['Qualification'];
        $this->ParmenentAddressId = $dataRow['ParmenentAddressId'];
        $this->CurrentAddressId = $dataRow['CurrentAddressId'];
        $this->EmpId = $dataRow['EmpId'];
        $this->PhoneNo = $dataRow['PhoneNo'];

    }

    public function updateEmployee()
    {
        $sqlQuery = "UPDATE
                            " . $this->db_table . "
                        SET
                            Name = :Name,
                            Gender = :Gender,
                            DateofBirth = :DateofBirth,
                            NRCNo = :NRCNo,
                            FatherName = :FatherName,
                            Race = :Race,
                            Religion = :Religion,
                            MaritalStatus = :MaritalStatus,
                            PhoneNo = :PhoneNo,
                            EnrollNum  = :EnrollNum
                        WHERE
                        EnrollNum  = :EnrollNum";

        $stmt = $this->conn->prepare($sqlQuery);

        $this->Name = htmlspecialchars(strip_tags($this->Name));
        $this->Gender = htmlspecialchars(strip_tags($this->Gender));
        $this->DateofBirth = htmlspecialchars(strip_tags($this->DateofBirth));
        $this->NRCNo = htmlspecialchars(strip_tags($this->NRCNo));
        $this->FatherName = htmlspecialchars(strip_tags($this->FatherName));
        $this->Race = htmlspecialchars(strip_tags($this->Race));
        $this->Religion = htmlspecialchars(strip_tags($this->Religion));
        $this->MaritalStatus = htmlspecialchars(strip_tags($this->MaritalStatus));
        $this->PhoneNo = htmlspecialchars(strip_tags($this->PhoneNo));
        $this->EnrollNum = htmlspecialchars(strip_tags($this->EnrollNum));

        // bind data
        $stmt->bindParam(":Name", $this->Name);
        $stmt->bindParam(":Gender", $this->Gender);
        $stmt->bindParam(":DateofBirth", $this->DateofBirth);
        $stmt->bindParam(":NRCNo", $this->NRCNo);
        $stmt->bindParam(":FatherName", $this->FatherName);
        $stmt->bindParam(":Race", $this->Race);
        $stmt->bindParam(":Religion", $this->Religion);
        $stmt->bindParam(":MaritalStatus", $this->MaritalStatus);
        $stmt->bindParam(":PhoneNo", $this->PhoneNo);
        $stmt->bindParam(":EnrollNum", $this->EnrollNum);

        $stmt->execute();

        //// employment table update
        $employmentTbl = 'employmenttable';
        $empId = 0;

        $sql = "SELECT EmpId FROM " . $this->db_table . " WHERE EnrollNum =:EnrollNum";
        $empStmt = $this->conn->prepare($sql);
        $empStmt->bindParam(":EnrollNum", $this->EnrollNum);
        $empStmt->execute();

        if ($empStmt->rowCount() > 0) {
            $row = $empStmt->fetch(PDO::FETCH_ASSOC);
            $empId = $row['EmpId'];
        } else {
            echo "Result not found";
        }

        if ($empId) {
            $sqlQuery = "UPDATE
                            " . $employmentTbl . "
                        SET
                            CompanyName = :CompanyName,
                            JoinedDate = :JoinedDate,
                            EmpId  = :EmpId
                        WHERE
                        EmpId  = :EmpId";

            $stmt = $this->conn->prepare($sqlQuery);

            $this->CompanyName = htmlspecialchars(strip_tags($this->CompanyName));
            $this->JoinedDate = htmlspecialchars(strip_tags($this->JoinedDate));

            $stmt->bindParam(":CompanyName", $this->CompanyName);
            $stmt->bindParam(":JoinedDate", $this->JoinedDate);
            $stmt->bindParam(":EmpId", $empId);

            $stmt->execute();
        }

        //// employeeinfo table update
        $empInfoTbl = 'empinfotable';
        $empAndInfoTbl = 'emandinfo';
        $empInfoId = 0;

        $infoSql = "SELECT EmployeeInfoId FROM " . $empAndInfoTbl . " WHERE EnrollNumId= :EnrollNum";
        $infoStmt = $this->conn->prepare($infoSql);
        $infoStmt->bindParam(":EnrollNum", $this->EnrollNum);
        $infoStmt->execute();

        if ($infoStmt->rowCount() > 0) {
            $row = $infoStmt->fetch(PDO::FETCH_ASSOC);
            $empInfoId = $row['EmployeeInfoId'];
        } else {
            echo "Result not found";
        }

        if ($empInfoId) {
            $sqlQuery = "UPDATE
                            " . $empInfoTbl . "
                        SET
                        LocationId = :LocationId,
                        departmentId = :DepartmentId,
                        PositionId  = :PositionId,
                        Salary  = :Salary,
                        FromDate  = :FromDate,
                        ToDate  = :ToDate,
                        EmpInfoId  = :EmpInfoId
                        WHERE
                        EmpInfoId  = :EmpInfoId";

            $stmt = $this->conn->prepare($sqlQuery);

            $this->LocationId = htmlspecialchars(strip_tags($this->LocationId));
            $this->DepartmentId = htmlspecialchars(strip_tags($this->DepartmentId));
            $this->PositionId = htmlspecialchars(strip_tags($this->PositionId));
            $this->Salary = htmlspecialchars(strip_tags($this->Salary));
            $this->FromDate = htmlspecialchars(strip_tags($this->FromDate));
            $this->ToDate = htmlspecialchars(strip_tags($this->ToDate));

            $stmt->bindParam(":LocationId", $this->LocationId);
            $stmt->bindParam(":DepartmentId", $this->DepartmentId);
            $stmt->bindParam(":PositionId", $this->PositionId);
            $stmt->bindParam(":Salary", $this->Salary);
            $stmt->bindParam(":FromDate", $this->FromDate);
            $stmt->bindParam(":ToDate", $this->ToDate);
            $stmt->bindParam(":EmpInfoId", $empInfoId);

            $stmt->execute();
        }

        //// permanent address table update
        $addressTbl = 'address';

        $pAddressQuery = "UPDATE
                            " . $addressTbl . "
                        SET
                        NoAndStreet     = :NoAndStreet    ,
                        TspId = :TspId,
                        CityId  = :CityId,
                        Id  = :Id
                        WHERE
                        Id  = :Id";

        $pAddressStmt = $this->conn->prepare($pAddressQuery);

        $this->PNoAndStreet = htmlspecialchars(strip_tags($this->PNoAndStreet));
        $this->PTspId = htmlspecialchars(strip_tags($this->PTspId));
        $this->PCityId = htmlspecialchars(strip_tags($this->PCityId));

        $pAddressStmt->bindParam(":NoAndStreet", $this->PNoAndStreet);
        $pAddressStmt->bindParam(":TspId", $this->PTspId);
        $pAddressStmt->bindParam(":CityId", $this->PCityId);
        $pAddressStmt->bindParam(":Id", $this->ParmenentAddressId);

        $pAddressStmt->execute();

        // //// current address table update
        $cAddressQuery = "UPDATE
                            " . $addressTbl . "
                        SET
                        NoAndStreet     = :NoAndStreet    ,
                        TspId = :TspId,
                        CityId  = :CityId,
                        Id  = :Id
                        WHERE
                        Id  = :Id";

        $cAddressStmt = $this->conn->prepare($cAddressQuery);

        $this->CNoAndStreet = htmlspecialchars(strip_tags($this->CNoAndStreet));
        $this->CTspId = htmlspecialchars(strip_tags($this->CTspId));
        $this->CCityId = htmlspecialchars(strip_tags($this->CCityId));

        $cAddressStmt->bindParam(":NoAndStreet", $this->CNoAndStreet);
        $cAddressStmt->bindParam(":TspId", $this->CTspId);
        $cAddressStmt->bindParam(":CityId", $this->CCityId);
        $cAddressStmt->bindParam(":Id", $this->CurrentAddressId);

        $cAddressStmt->execute();

        //experience table  update
        $empAndExpTbl = 'empandexp';
        $empExpTbl = 'experiencetable';
        $expIdArr = array();

        $empExpSql = "SELECT experienceId FROM " . $empAndExpTbl . " WHERE EnrollNum= :EnrollNum";
        $empExpStmt = $this->conn->prepare($empExpSql);
        $empExpStmt->bindParam(":EnrollNum", $this->EnrollNum);
        $empExpStmt->execute();

        if ($empExpStmt->rowCount() > 0) {
            while ($row = $empExpStmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $e = array(
                    "Id" => $experienceId,
                );
                array_push($expIdArr, $e);
            }
        }

        if ($expIdArr) {

            if (sizeof($this->ExperienceArray) < sizeof($expIdArr)) {
                foreach ($this->ExperienceArray as $key => $value) {
                    $stmtExp = $this->conn->prepare(
                        "UPDATE " . $empExpTbl . " SET
                                Rank = :Rank,
                                CompanyInformaiton=:CompanyInformaiton,
                                FromDate = :FromDate,
                                ToDate = :ToDate,
                                ReasonofResign = :ReasonofResign,
                                ExperienceId = :ExperienceId
                                WHERE
                                ExperienceId = :ExperienceId"
                    );

                    $exp = $this->ExperienceArray[$key];

                    $stmtExp->bindParam(':Rank', $exp->Rank);
                    $stmtExp->bindParam(':CompanyInformaiton', $exp->CompanyInformaiton);
                    $stmtExp->bindParam(':FromDate', $exp->FromDate);
                    $stmtExp->bindParam(':ToDate', $exp->ToDate);
                    $stmtExp->bindParam(':ReasonofResign', $exp->ReasonofResign);
                    $stmtExp->bindParam(':ExperienceId', $expIdArr[$key]['Id']);

                    $stmtExp->execute();

                    $stmtDelete = $this->conn->prepare(
                        "DELETE FROM " . $empExpTbl . " WHERE ExperienceId = :ExperienceId");
                    $stmtDelete->bindParam(':ExperienceId', $expIdArr[sizeof($this->ExperienceArray) + $key]['Id']);
                    $stmtDelete->execute();

                    $stmtEmpAndExpDel = $this->conn->prepare(
                        "DELETE FROM " . $empAndExpTbl . " WHERE experienceId = :ExperienceId");
                    $stmtEmpAndExpDel->bindParam(':ExperienceId', $expIdArr[sizeof($this->ExperienceArray) + $key]['Id']);
                    $stmtEmpAndExpDel->execute();
                }

            } else {

                if (sizeof($this->ExperienceArray) > sizeof($expIdArr)) {
                    for ($i = sizeof($expIdArr); $i < sizeof($this->ExperienceArray); $i++) {

                        $stmtExp = $this->conn->prepare(
                            "INSERT INTO " . $empExpTbl . "(Rank, CompanyInformaiton, FromDate,ToDate,ReasonofResign)
                                        VALUES (:Rank, :CompanyInformaiton, :FromDate,:ToDate,:ReasonofResign)"
                        );

                        $exp = $this->ExperienceArray[$i];

                        $stmtExp->bindParam(':Rank', $exp->Rank);
                        $stmtExp->bindParam(':CompanyInformaiton', $exp->CompanyInformaiton);
                        $stmtExp->bindParam(':FromDate', $exp->FromDate);
                        $stmtExp->bindParam(':ToDate', $exp->ToDate);
                        $stmtExp->bindParam(':ReasonofResign', $exp->ReasonofResign);

                        $stmtExp->execute();
                        $Id = $this->conn->lastInsertId();

                        //create entries for employee and experience table
                        $stmtEmpExp = $this->conn->prepare("INSERT INTO " . $empAndExpTbl . "  (EnrollNum, experienceId)
                                            VALUES (:EnrollNum, :experienceId)");
                        $stmtEmpExp->bindParam(':EnrollNum', $this->EnrollNum);
                        $stmtEmpExp->bindParam(':experienceId', $Id);
                        $stmtEmpExp->execute();
                    }
                }
            }

        }

        if (!$expIdArr) {
            foreach ((array) $this->ExperienceArray as $row) {
                $stmtExp = $this->conn->prepare(
                    "INSERT INTO " . $empExpTbl . "(Rank, CompanyInformaiton, FromDate,ToDate,ReasonofResign)
                        VALUES (:Rank, :CompanyInformaiton, :FromDate,:ToDate,:ReasonofResign)"
                );

                $stmtExp->bindParam(':Rank', $row->Rank);
                $stmtExp->bindParam(':CompanyInformaiton', $row->CompanyInformaiton);
                $stmtExp->bindParam(':FromDate', $row->FromDate);
                $stmtExp->bindParam(':ToDate', $row->ToDate);
                $stmtExp->bindParam(':ReasonofResign', $row->ReasonofResign);

                $stmtExp->execute();
                $Id = $this->conn->lastInsertId();

                //create entries for employee and experience table
                $stmtEmpExp = $this->conn->prepare("INSERT INTO " . $empAndExpTbl . "  (EnrollNum, experienceId)
                            VALUES (:EnrollNum, :experienceId)");
                $stmtEmpExp->bindParam(':EnrollNum', $this->EnrollNum);
                $stmtEmpExp->bindParam(':experienceId', $Id);
                $stmtEmpExp->execute();
            }
        }

        ///////Education Array Update///////
        $eduAndEmp = 'eduandemployee';
        $eduTbl = 'educationtable';
        $edIdArr = array();

        $empEduSql = "SELECT EdId FROM " . $eduAndEmp . " WHERE EnrollNum= :EnrollNum";
        $empEduStmt = $this->conn->prepare($empEduSql);
        $empEduStmt->bindParam(":EnrollNum", $this->EnrollNum);
        $empEduStmt->execute();

        if ($empEduStmt->rowCount() > 0) {
            while ($row = $empEduStmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $e = array(
                    "Id" => $EdId,
                );
                array_push($edIdArr, $e);
            }
        }

        if ($edIdArr) {
            if (sizeof($this->EducationArray) < sizeof($edIdArr)) {
                foreach ($this->EducationArray as $key => $value) {
                    $stmtEdu = $this->conn->prepare(
                        "UPDATE " . $eduTbl . " SET
                                EdName = :EdName,
                                NameofUni=:NameofUni,
                                FromDate = :FromDate,
                                ToDate = :ToDate,
                                CityId = :CityId,
                                EdId = :EdId
                                WHERE
                                EdId = :EdId"
                    );

                    $edu = $this->EducationArray[$key];

                    $stmtEdu->bindParam(':EdName', $edu->EdName);
                    $stmtEdu->bindParam(':NameofUni', $edu->NameofUni);
                    $stmtEdu->bindParam(':FromDate', $edu->FromDate);
                    $stmtEdu->bindParam(':ToDate', $edu->ToDate);
                    $stmtEdu->bindParam(':CityId', $edu->CityId);
                    $stmtEdu->bindParam(':EdId', $edIdArr[$key]['Id']);

                    $stmtEdu->execute();

                    $stmtDel = $this->conn->prepare(
                        "DELETE FROM " . $eduTbl . " WHERE EdId = :EdId");
                    $stmtDel->bindParam(':EdId', $edIdArr[sizeof($this->EducationArray) + $key]['Id']);
                    $stmtDel->execute();

                    $stmtEmpAndEduDel = $this->conn->prepare(
                        "DELETE FROM " . $eduAndEmp . " WHERE EdId = :EdId");
                    $stmtEmpAndEduDel->bindParam(':EdId', $edIdArr[sizeof($this->EducationArray) + $key]['Id']);
                    $stmtEmpAndEduDel->execute();
                }

            } else {

                if (sizeof($this->EducationArray) > sizeof($edIdArr)) {
                    for ($i = sizeof($edIdArr); $i < sizeof($this->EducationArray); $i++) {

                        $stmtEdu = $this->conn->prepare(
                            "INSERT INTO " . $eduTbl . "(EdName, NameofUni, FromDate,ToDate,CityId)
                                        VALUES (:EdName, :NameofUni, :FromDate, :ToDate, :CityId)"
                        );

                        $edu = $this->EducationArray[$i];

                        $stmtEdu->bindParam(':EdName', $edu->EdName);
                        $stmtEdu->bindParam(':NameofUni', $edu->NameofUni);
                        $stmtEdu->bindParam(':FromDate', $edu->FromDate);
                        $stmtEdu->bindParam(':ToDate', $edu->ToDate);
                        $stmtEdu->bindParam(':CityId', $edu->CityId);

                        $stmtEdu->execute();
                        $Id = $this->conn->lastInsertId();

                        //create entries for employee and education table
                        $stmtEmpEdu = $this->conn->prepare("INSERT INTO " . $eduAndEmp . "  (EdId, EnrollNum)
                                            VALUES ( :EdId, :EnrollNum)");
                        $stmtEmpEdu->bindParam(':EdId', $Id);
                        $stmtEmpEdu->bindParam(':EnrollNum', $this->EnrollNum);
                        $stmtEmpEdu->execute();
                    }
                }
            }

        }

        if (!$edIdArr) {
            foreach ((array) $this->EducationArray as $row) {
                $stmtEdu = $this->conn->prepare(
                    "INSERT INTO " . $eduTbl . "(EdName, NameofUni, FromDate,ToDate,CityId)
                        VALUES (:EdName, :NameofUni, :FromDate,:ToDate,:CityId)"
                );

                $stmtEdu->bindParam(':EdName', $row->EdName);
                $stmtEdu->bindParam(':NameofUni', $row->NameofUni);
                $stmtEdu->bindParam(':FromDate', $row->FromDate);
                $stmtEdu->bindParam(':ToDate', $row->ToDate);
                $stmtEdu->bindParam(':CityId', $row->CityId);

                $stmtEdu->execute();
                $Id = $this->conn->lastInsertId();

                //create entries for employee and education table
                $stmtEmpEdu = $this->conn->prepare("INSERT INTO " . $eduAndEmp . "  (EdId, EnrollNum)
                            VALUES (:EdId, :EnrollNum)");
                $stmtEmpEdu->bindParam(':EdId', $Id);
                $stmtEmpEdu->bindParam(':EnrollNum', $this->EnrollNum);
                $stmtEmpEdu->execute();
            }
        }

        ///////Task Array Update///////
        $empAndTask = 'empandtask';
        $taskTbl = 'task';
        $taskIdArr = array();

        $empTaskSql = "SELECT TasksId FROM " . $empAndTask . " WHERE EnrollNum= :EnrollNum";
        $empTaskStmt = $this->conn->prepare($empTaskSql);
        $empTaskStmt->bindParam(":EnrollNum", $this->EnrollNum);
        $empTaskStmt->execute();

        if ($empTaskStmt->rowCount() > 0) {
            while ($row = $empTaskStmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $e = array(
                    "Id" => $TasksId,
                );
                array_push($taskIdArr, $e);
            }
        }

        if ($taskIdArr) {
            if (sizeof($this->TaskArray) < sizeof($taskIdArr)) {
                foreach ($this->TaskArray as $key => $value) {
                    $stmtTask = $this->conn->prepare(
                        "UPDATE " . $taskTbl . " SET
                                        TaskName = :TaskName,
                                        MobileORWeb=:MobileORWeb,
                                        TaskId = :TaskId
                                        WHERE
                                        TaskId = :TaskId"
                    );

                    $task = $this->TaskArray[$key];

                    $stmtTask->bindParam(':TaskName', $task->TaskName);
                    $stmtTask->bindParam(':MobileORWeb', $task->MobileORWeb);
                    $stmtTask->bindParam(':TaskId', $taskIdArr[$key]['Id']);

                    $stmtTask->execute();

                    $stmtDel = $this->conn->prepare(
                        "DELETE FROM " . $taskTbl . " WHERE TaskId = :TaskId");
                    $stmtDel->bindParam(':TaskId', $taskIdArr[sizeof($this->TaskArray) + $key]['Id']);
                    $stmtDel->execute();

                    $stmtEmpAndTaskDel = $this->conn->prepare(
                        "DELETE FROM " . $empAndTask . " WHERE TasksId = :TaskId");
                    $stmtEmpAndTaskDel->bindParam(':TaskId', $taskIdArr[sizeof($this->TaskArray) + $key]['Id']);
                    $stmtEmpAndTaskDel->execute();
                }

            } else {
                if (sizeof($this->TaskArray) > sizeof($taskIdArr)) {

                    for ($i = sizeof($taskIdArr); $i < sizeof($this->TaskArray); $i++) {

                        $stmtTask = $this->conn->prepare(
                            "INSERT INTO " . $taskTbl . "(TaskName, MobileORWeb )
                                                VALUES (:TaskName, :MobileORWeb)"
                        );

                        $task = $this->TaskArray[$i];

                        $stmtTask->bindParam(':TaskName', $task->TaskName);
                        $stmtTask->bindParam(':MobileORWeb', $task->MobileORWeb);

                        $stmtTask->execute();
                        $Id = $this->conn->lastInsertId();

                        //create entries for employee and task table
                        $stmtEmpTask = $this->conn->prepare("INSERT INTO " . $empAndTask . "  (TasksId, EnrollNum)
                                                    VALUES (:TaskId, :EnrollNum)");
                        $stmtEmpTask->bindParam(':TaskId', $Id);
                        $stmtEmpTask->bindParam(':EnrollNum', $this->EnrollNum);
                        $stmtEmpTask->execute();
                    }
                }
            }

        }

        if (!$taskIdArr) {
            foreach ((array) $this->TaskArray as $row) {
                $stmtTask = $this->conn->prepare(
                    "INSERT INTO " . $taskTbl . "(TaskName, MobileORWeb )
                                VALUES (:TaskName, :MobileORWeb)"
                );

                $stmtTask->bindParam(':TaskName', $row->TaskName);
                $stmtTask->bindParam(':MobileORWeb', $row->MobileORWeb);

                $stmtTask->execute();
                $Id = $this->conn->lastInsertId();

                //create entries for employee and task table
                $stmtEmpTask = $this->conn->prepare("INSERT INTO " . $empAndTask . "  (TasksId, EnrollNum)
                                    VALUES (:TaskId, :EnrollNum)");
                $stmtEmpTask->bindParam(':TaskId', $Id);
                $stmtEmpTask->bindParam(':EnrollNum', $this->EnrollNum);
                $stmtEmpTask->execute();
            }
        }

    }

    // DELETE
    public function deleteEmployee()
    {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE EnrollNum = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->EnrollNum = htmlspecialchars(strip_tags($this->EnrollNum));

        $stmt->bindParam(1, $this->EnrollNum);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
