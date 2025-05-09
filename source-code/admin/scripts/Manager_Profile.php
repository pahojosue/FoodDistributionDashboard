<?php
class Manager_Profile {

    private $conn;
    private $managerTable = 'managers';

    public function __construct($conn) {
        $this->conn = $conn;
        
    }

  
    public function getManager_Profile() {

        $data = [];
        $email = $_SESSION['email'];
        
        $query = "SELECT id, firstName, lastName, region, emailAddress, mobileNumber, profileImage FROM ";
        $query .= $this->managerTable;
        $query .= " WHERE emailAddress = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $email);
       
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();
        } 
    
        return $data;
    }

    
}



?>
