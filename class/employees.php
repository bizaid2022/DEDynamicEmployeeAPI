<?php
    class Employee{
		
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
        public function __construct($db){
            $this->conn = $db;
        }


        // Login
        public function logIn($userName,$password){
            $loginTable="logintable";

            $sqlQuery = "SELECT * FROM " . $loginTable . " where Username = :userName AND Password = :password";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bindParam(":userName", $userName);
            $stmt->bindParam(":password", $password);
            $stmt->execute();

            return $stmt;
        }
		
        // GET ALL
        public function getEmployees(){
            $sqlQuery = "SELECT EnrollNum,Name,Gender,DateofBirth,Age,NRCNo,FatherName,Race,Religion,MaritalStatus,Qualification,ParmenentAddressId,CurrentAddressId,EmpId,PhoneNo FROM " . $this->db_table . "";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
          
            return $stmt;
        }

        // GET Tasks By EnrollNum
        public function getTasksByEnrollNum($enrollNum){
            $emptsk="empandtask";
            $tsk="task";
    
            $sqlQuery = "SELECT $tsk.* FROM " . $tsk . "," . $emptsk . "  WHERE $tsk.TaskId=$emptsk.TasksId AND $emptsk.EnrollNum = :id ";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bindParam(":id", $enrollNum);
            $stmt->execute();
            return $stmt;
        } 

        // GET Tasks By EnrollNum
        public function getExperiencesByEnrollNum($enrollNum){
            //table name
            $empandexp="empandexp";
            $exp="experiencetable";
            $address="address";
            $city="city";
            $township="tsptable";
            
            $sqlQuery = "SELECT $exp.Rank,$exp.CompanyInformaiton,$address.NoAndStreet,$city.CityName,$township.TspName,$exp.FromDate,$exp.ToDate,$exp.ReasonofResign FROM " . $empandexp . "," . $exp . "," . $address . "," . $city . "," . $township . "  WHERE $exp.CompanyAddressId=$address.Id AND $address.CityId=$city.CityId AND $address.TspId=$township.Id AND $exp.ExperienceId=$empandexp.experienceId AND $empandexp.EnrollNum = :id ";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bindParam(":id", $enrollNum);
            $stmt->execute();
            return $stmt;
        }
        
        //Get Education
        public function getEducationByEnrollNum($enrollNum){
            //table name
            $eduandemp="eduandemployee";
            $edu="educationtable";
            $city="city";
 
            $sqlQuery = "SELECT $edu.EdName,$edu.NameofUni,$edu.FromDate,$edu.ToDate,$city.CityName  FROM " . $eduandemp . "," . $edu . "," . $city . "  WHERE $edu.CityId=$city.CityId AND  $edu.EdId=$eduandemp.EdId AND $eduandemp.EnrollNum = :id ";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bindParam(":id", $enrollNum);
            $stmt->execute();
            return $stmt;
        }

        //Get Employee Info
        public function getEmployeeInfoByEnrollNum($enrollNum){
            //table name
            $empInfo="empinfotable";
            $empAndInfo="emandinfo";
            $dep="departmenttable";
            $position="positiontable";
            $location="locationtable";
            $address="address";
            $city="city";
            $township="tsptable";
            
            $sqlQuery = "SELECT $position.PositionName,$position.Level,$empInfo.Salary,$dep.DepartmentName,$location.LocationName,$address.NoAndStreet,$city.CityName,$township.TspName,$empInfo.FromDate,$empInfo.ToDate  FROM " . $empInfo . "," . $empAndInfo . "," . $dep . "," . $position . "," . $location . "," . $address . "," . $city . " ," . $township . "   WHERE $empAndInfo.EmployeeInfoId=$empInfo.EmpInfoId AND $empInfo.LocationId=$location.LocationId AND $location.AddressId=$address.Id AND $address.CityId=$city.CityId AND $address.TspId=$township.Id AND $empInfo.departmentId=$dep.DepartmentId AND $empInfo.PositionId=$position.PositionId AND $empAndInfo.EnrollNumId = :id ";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bindParam(":id", $enrollNum);
            $stmt->execute();
            return $stmt;
        }

        public function getAddressDetails($addressId){
            //tableName
            $address="address";
            $city="city";
            $township="tsptable";

            $sqlQuery = "SELECT $address.NoAndStreet,$city.CityName,$township.TspName  FROM " . $address . "," . $city . " ," . $township . "   WHERE $address.CityId=$city.CityId AND $address.TspId=$township.Id AND $address.Id = :id ";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bindParam(":id", $addressId);
            $stmt->execute();
            return $stmt;
        }

        // CREATE
        public function createEmployee(){
			
			$addresstbl= "address";
			$emptbl="employmenttable";
			$emptsk="empandtask";
			
			// Permanent Address
            $sqlQueryPadd= "
						INSERT INTO ". $addresstbl ."
                    SET
                        NoAndStreet = :PNoAndStreet, 
                        TspId = :PTspId, 
                        CityId = :PCityId";
			$stmtPadd = $this->conn->prepare($sqlQueryPadd);
			
			// sanitize - Permanent Address
            $this->PNoAndStreet=htmlspecialchars(strip_tags($this->PNoAndStreet));
            $this->PTspId=htmlspecialchars(strip_tags($this->PTspId));
            $this->PCityId=htmlspecialchars(strip_tags($this->PCityId));
			
			// bind data Permanent Address
            $stmtPadd->bindParam(":PNoAndStreet", $this->PNoAndStreet);
            $stmtPadd->bindParam(":PTspId", $this->PTspId);
            $stmtPadd->bindParam(":PCityId", $this->PCityId);
		
			$stmtPadd->execute();
			$PaddId=$this->conn->lastInsertId();
				

			// Current Address 		
			$sqlQueryCadd="INSERT INTO
                        ". $addresstbl ."
                    SET
                        NoAndStreet = :CNoAndStreet, 
                        TspId = :CTspId, 
                        CityId = :CCityId";
			$stmtCadd = $this->conn->prepare($sqlQueryCadd);
			
			// sanitize - Current  Address
            $this->CNoAndStreet=htmlspecialchars(strip_tags($this->CNoAndStreet));
            $this->CTspId=htmlspecialchars(strip_tags($this->CTspId));
            $this->CCityId=htmlspecialchars(strip_tags($this->CCityId));

			//bind data Current  Address
			$stmtCadd->bindParam(":CNoAndStreet", $this->CNoAndStreet);
            $stmtCadd->bindParam(":CTspId", $this->CTspId);
            $stmtCadd->bindParam(":CCityId", $this->CCityId);

			$stmtCadd->execute();
			$CaddId=$this->conn->lastInsertId();	
						
			$sqlQueryEmpInfo="INSERT INTO
                        ". $emptbl ."
                    SET
                        JoinedDate = :JoinedDate, 
                        EmploymentContractId = :EmploymentContractId, 
                        CompanyName = :CompanyName";
               		   
			$stmtEmp = $this->conn->prepare($sqlQueryEmpInfo);
				
			// sanitize - Employment Data
            $this->JoinedDate=htmlspecialchars(strip_tags($this->JoinedDate));
            $this->EmploymentContractId=htmlspecialchars(strip_tags($this->EmploymentContractId));
            $this->CompanyName=htmlspecialchars(strip_tags($this->CompanyName));
			
			//bind data Employment Data
			$stmtEmp->bindParam(":JoinedDate", $this->JoinedDate);
            $stmtEmp->bindParam(":EmploymentContractId", $this->EmploymentContractId);
            $stmtEmp->bindParam(":CompanyName", $this->CompanyName);
           
			$stmtEmp->execute();
			$EmploymentId=$this->conn->lastInsertId();	   
					   
					   
			 $sqlQueryMain = "INSERT INTO
                        ". $this->db_table ."
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
            $this->EnrollNum=htmlspecialchars(strip_tags($this->EnrollNum));
            $this->Name=htmlspecialchars(strip_tags($this->Name));
            $this->Gender=htmlspecialchars(strip_tags($this->Gender));
            $this->DateofBirth=htmlspecialchars(strip_tags($this->DateofBirth));
            $this->Age=htmlspecialchars(strip_tags($this->Age));
			$this->NRCNo=htmlspecialchars(strip_tags($this->NRCNo));
            $this->FatherName=htmlspecialchars(strip_tags($this->FatherName));
            $this->Race=htmlspecialchars(strip_tags($this->Race));
            $this->Religion=htmlspecialchars(strip_tags($this->Religion));
            $this->MaritalStatus=htmlspecialchars(strip_tags($this->MaritalStatus));
			$this->Qualification=htmlspecialchars(strip_tags($this->Qualification));
            $this->PhoneNo=htmlspecialchars(strip_tags($this->PhoneNo));
        
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
			
		
		/*	$stmtTaskEmp = $this->conn->prepare("INSERT INTO `empandtask` (`Id`, `EnrollNum`, `TasksId`) VALUES (NULL, :EnrollNum,:TaskId)");	
			foreach($data as $row)
			{
				$stmtTask->execute(
					"EnrollNum" => $this->EnrollNum,array(
					"TaskId" => $row['TaskId']
				));
			}*/
			//$data = json_decode($this->TaskArray);
			
	        foreach($this->TaskArray as $row){
			$stmtTaskEmp=$this->conn->prepare("INSERT INTO ".$emptsk."  SET
						EnrollNum = :EnrollNum, 
                        TasksId = :TasksId");
			  $stmtTaskEmp->bindValue(":EnrollNum", $this->EnrollNum);
			  $stmtTaskEmp->bindValue(":TasksId", $row->TasksId);
			  $stmtTaskEmp->execute();		
			
			
            //return true;
            
         //  return false;
			}
		}
		
        // READ single
        public function getSingleEmployee(){
            echo($this->EnrollNum);
            $sqlQuery = "SELECT EnrollNum, Name, Gender, DateofBirth, Age, NRCNo, FatherName, Race, Religion, MaritalStatus, Qualification, ParmenentAddressId, CurrentAddressId, EmpId, PhoneNo FROM  ". $this->db_table ." WHERE EnrollNum = $this->EnrollNum ";	
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
		
        // UPDATE
        public function updateEmployee(){
            $sqlQuery = "UPDATE
                        ". $this->db_table ."
                    SET
                        name = :name, 
                        email = :email, 
                        age = :age, 
                        designation = :designation, 
                        created = :created
                    WHERE 
                        id = :id";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->email=htmlspecialchars(strip_tags($this->email));
            $this->age=htmlspecialchars(strip_tags($this->age));
            $this->designation=htmlspecialchars(strip_tags($this->designation));
            $this->created=htmlspecialchars(strip_tags($this->created));
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            // bind data
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":age", $this->age);
            $stmt->bindParam(":designation", $this->designation);
            $stmt->bindParam(":created", $this->created);
            $stmt->bindParam(":id", $this->id);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }
		
        // DELETE
        function deleteEmployee(){
            $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE EnrollNum = ?";
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->EnrollNum=htmlspecialchars(strip_tags($this->EnrollNum));
        
            $stmt->bindParam(1, $this->EnrollNum);
        
            if($stmt->execute()){
                return true;
            }
            return false;
        }
    }
?>