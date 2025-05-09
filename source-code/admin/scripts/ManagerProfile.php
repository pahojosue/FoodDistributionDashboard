<?php
class ManagerProfile {

    private $conn;
    private $ManagerTable = 'managers';
    

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function validate($firstName, $lastName, $region, $emailAddress, $mobileNumber, $password = 123  ) {

        $error = false;
        $errMsg = null;
        $firstNameErr = '';
        $lastNameErr = '';
        $regionErr = '';
        $emailAddressErr = '';
        $mobileNumberErr = '';
        $passwordErr = '';
       
        if(empty($firstName)) {
            $firstNameErr = "First Name is required";
            $error = true;
        } 
        if(empty($lastName)) {
            $lastNameErr = "Last Name is required";
            $error = true;
        } 
        if(empty($region)) {
            $regionErr = "Region is required";
            $error = true;
        } 
        if(empty(trim($emailAddress))) {
            $emailAddressErr = "Email Address is required";
            $error = true;
        } 
        if(empty(trim($mobileNumber))) {
            $mobileNumberErr = "Mobile Number is required";
            $error = true;
        } 

        if(empty($password)) {
            $passwordErr = "Password is required";
            $error = true;
        } 

        $errorInfo = [
            "error" => $error,
            "errMsg" => [
                "firstName" => $firstNameErr,
                "lastName" => $lastNameErr,
                "region" => $regionErr,
                "emailAddress" => $emailAddressErr,
                "mobileNumber" => $mobileNumberErr,
                "password" => $passwordErr

            ]
        ];
        
        return $errorInfo;
    }

    public function uploadProfileImage($id= null) {
  
            $error = false;
            $thumbnailErr ='';
            $profileImageErr = '';
            $uploadTo = "public/images/admin-profile/"; 
            $allowFileType = array('jpg','png','jpeg');
            $fileName = $_FILES['profileImage']['name'];

            if(empty($fileName) && null !== $id) {

                $get = $this->getById($id);
                if(isset($get['profileImage'])) {
                    $fileName = $get['profileImage'];
                }
           
            } else {
            
            $tempPath = $_FILES["profileImage"]["tmp_name"];
        
            $basename = basename($fileName);
            $originalPath = $uploadTo.$basename; 
            $fileType = pathinfo($originalPath, PATHINFO_EXTENSION); 
         
            if(!empty($fileName)){ 
               if(in_array($fileType, $allowFileType)){ 

                 if(!move_uploaded_file($tempPath, $originalPath)){ 
                    $thumbnailErr = 'Profile Not uploaded ! try again';
                    $error = true;
                }
             }else{  
                $thumbnailErr = 'Profile type is not allowed'; 
                $error = true;
             }
           } else {
                 $thumbnailErr = 'Profile is required'; 
                $error = true;
           }  
         }
        $thumbnailInfo = [
            "error" => $error, 
            "profileImageErr" => $thumbnailErr, 
            "profileImage" => $fileName
        ];

        return  $thumbnailInfo;
    }
    public function create($firstName, $lastName, $region, $emailAddress, $mobileNumber, $password) {
        $validate = $this->validate($firstName, $lastName, $region, $emailAddress, $mobileNumber, $password);
        $success = false;
    
        if (!$validate['error']) {
            $uploadProfileImage = $this->uploadProfileImage();

            if (!$uploadProfileImage['error']) {
                //  table name for manager profiles
                $query = "INSERT INTO " . $this->ManagerTable . " (firstName, lastName, region, emailAddress, mobileNumber, password, profileImage) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($query);
    
                $stmt->bind_param("sssssss", $firstName, $lastName, $region, $emailAddress, $mobileNumber, $password, $uploadProfileImage['profileImage']);
    
                if ($stmt->execute()) {
                    $success = true;
                    $stmt->close();
                }
            }
        }
    
        $data = [
            'errMsg' => $validate['errMsg'],
            'uploadProfileImage' => $uploadProfileImage['profileImageErr'] ?? 'Unable to upload profile due to other fields facing errors',
            'success' => $success
        ];
    
        return $data;
    }
    

        public function get() {

        $data = [];
    
        $query = "SELECT id, firstName, lastName, region, emailAddress, mobileNumber, profileImage FROM ";
        $query .= $this->ManagerTable;

        $result = $this->conn->query($query);
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $result->free();
        }
    
        return $data;
    }

    public function getById($id) {

        $data = [];
    
        $query = "SELECT id, firstName, lastName, region, emailAddress, mobileNumber, profileImage FROM ";
        $query .= $this->ManagerTable;
        $query .= " WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
       
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();
        } 

        return $data;
    }

    public function updateById($id, $firstName, $lastName, $region, $emailAddress, $mobileNumber) {
        $validate = $this->validate($firstName, $lastName, $region, $emailAddress, $mobileNumber);
        $success = false;
        
        
        if (!$validate['error']) {
            $uploadProfileImage = $this->uploadProfileImage($id);
           
            if (!$uploadProfileImage['error']) {
            // Replace 'content' with the correct table name for manager profiles
                $query = "UPDATE " . $this->ManagerTable . " SET firstName = ?, lastName = ?, region = ?, emailAddress = ?, mobileNumber = ?, profileImage = ? WHERE id = ?";
                $stmt = $this->conn->prepare($query);
            
                $stmt->bind_param("ssssssi", $firstName, $lastName, $region, $emailAddress, $mobileNumber, $uploadProfileImage['profileImage'], $id);
            
                if ($stmt->execute()) {
                    $success = true;
                    
                } 
           }
        }
        
        $data = [
            'success' => $success,
            'errMsg' => $validate['errMsg'],
            'uploadProfileImage' => $uploadProfileImage['profileImageErr'] ?? 'Unable to upload profile due to other fields facing errors',
        ];

        
        return $data;
    }
    
    
    public function deleteById($id) {

        $query = "DELETE FROM ";
        $query .= $this->ManagerTable;
        $query .= " WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
       
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }
    
}



?>
