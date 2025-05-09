<?php
class CategoryManager {

    private $conn;
    private $categoryTable = "category";

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function validate($food_type) {

        $error = false;
        $errMsg = null;

        if(empty($food_type)) {
            $errMsg = "Category is required";
            $error = true;
        } 

        $errorInfo = [
            "error" => $error,
            "errMsg" => $errMsg
        ];
        
        return $errorInfo;
    }

    public function create($food_type, $quantity) {

        $validate = $this->validate($food_type);
        $success = false;

        if (!$validate['error']){

            $query = "INSERT INTO ";
            $query .= $this->categoryTable; 
            $query .= " (food_type, quantity) ";
            $query .= " VALUES ('$food_type', '$quantity')";

            $stmt = $this->conn->prepare($query);
            // $stmt->bind_param("s", $food_type, $quantity);
            
            if ($stmt->execute()) {
                $success = true;
                $stmt->close();
            }
        }
         
         $data = [
            'errMsg' => $validate['errMsg'],
            'success' => $success
         ];

         return $data;
    }

    public function get() {

        $data = [];
    
        $query = "SELECT id, food_type, quantity FROM ";
        $query .= $this->categoryTable;
        
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
    
        $query = "SELECT food_type FROM ";
        $query .= $this->categoryTable; 
        $query .= " WHERE id=?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
       
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $data= $result->fetch_assoc();
            $stmt->close();
        } 

        return $data;
    }

    public function getQuantityById($id) {
        $data = [];

        $query = "SELECT quantity FROM ";
        $query .= $this->categoryTable; 
        $query .= " WHERE id=?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
       
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $data= $result->fetch_assoc();
            $stmt->close();
        } 

        return $data;
    }

    public function updateById($id, $food_type, $quantity) {

        $validate = $this->validate($food_type);
        $success = false;

        if (!$validate['error']){

            $query = "UPDATE ";
            $query .= $this->categoryTable;
            $query .= " SET food_type = '$food_type', quantity = '$quantity' WHERE id = $id";

            $stmt = $this->conn->prepare($query);
            
            if ($stmt->execute()) {
                $success = true;
                $stmt->close();
            }
        }
         
         $data = [
            'errMsg' => $validate['errMsg'],
            'success' => $success
         ];

         return $data;
    }

    public function deleteById($id) {

        $query = "DELETE FROM ";
        $query .= $this->categoryTable; 
        $query .= " WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
    
        if ($stmt->execute()) {
            return true;
        } else {
            $stmt->close();
        }
        $stmt->close();
    }
    
}



?>
