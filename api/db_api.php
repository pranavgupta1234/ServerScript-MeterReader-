<?php

class DB_API{

    private $conn;

    //constructor
    function  __construct(){

        require_once '../db_connect.php';

        //connecting to database
        $db = new DB_Connect();
        $this -> conn = $db -> connect();

    }

    //destructor
    function __destruct()
    {
        // TODO: Implement __destruct() method.
    }

    /**
     * storing reading
    */

    public function storeReading($flat_id,$takenOn,$newReading,$remarks,$name) {

        $flat_id = mysqli_real_escape_string($this -> conn,$flat_id);
        $newReading = mysqli_real_escape_string($this -> conn,$newReading);
        $remarks = mysqli_real_escape_string($this -> conn,$remarks);
        $name = mysqli_real_escape_string($this -> conn,$name);

        $stmt = $this -> conn -> prepare("INSERT INTO MeterReadings(FlatId,TakenOn,ReadingValue,Remarks,TakenBy) VALUES ('$flat_id',NOW(),'$newReading','$remarks','$name')");
        $stmt -> bind_param("sssss",$flat_id,$takenOn,$newReading,$remarks,$name);
        $result = $stmt -> execute();
        $stmt -> close();

        // check for successful store
        if ($result) {

            $stmt = $this->conn->prepare("SELECT * FROM MeterReadings WHERE Flat_Id = '$flat_id'");
            $stmt -> bind_param("s", $flat_id);
            $stmt -> execute();
            $reading = $stmt -> get_result() -> fetch_assoc();
            $stmt -> close();

            return $reading;
        } else {
            return false;
        }

    }

    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name , $password) {

        $uuid = uniqid('', true);
        $hash = $this -> hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
        $role = "field_user";

        //escape string
        $name = mysqli_real_escape_string($this -> conn,$name);
        $encrypted_password = mysqli_real_escape_string($this->conn,$encrypted_password);
        $salt = mysqli_real_escape_string($this -> conn,$salt);
        $role = mysqli_real_escape_string($this -> conn,$role);

        $stmt = $this -> conn -> prepare("INSERT INTO Users(Username,Name,Password,Salt,Designation,Role,UpdatedBy) VALUES('$name','$name','$encrypted_password','$salt','$role',1,'$name')");
        $stmt->bind_param("sssssss", $name, $name,$encrypted_password,$salt,"Designation",$role,"UpdatedBy");
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {

            $stmt = $this->conn->prepare("SELECT * FROM Users WHERE Username = '$name'");
            $stmt -> bind_param("s", $name);
            $stmt -> execute();
            $user = $stmt -> get_result() -> fetch_assoc();
            $stmt -> close();

            return $user;
        } else {
            return false;
        }
    }

    /**
     * Get user by email and password
     */
    public function getUserByUsernameAndPassword($username , $password) {

        $username = mysqli_real_escape_string($this->conn,$username);

        $stmt = $this->conn->prepare("SELECT * FROM Users WHERE Username = '$username'");

        $stmt->bind_param("s", $username);

        if ($stmt->execute()) {

            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user password
            $salt = $user["Salt"];
            $encrypted_password = $user["Password"];
            $hash = $this -> checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $user;
            }
        } else {
            return NULL;
        }
    }

    /**
     * Check user is existed or not
     */
    public function isUserExisted($name) {

        $name = mysqli_real_escape_string($this->conn,$name);

        $stmt = $this->conn->prepare("SELECT * from Users WHERE Name = '$name'");

        $stmt->bind_param("s", $name);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt -> num_rows > 0) {
            // user existed
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }


    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     * @return array
     */
    public function hashSSHA($password) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
     * @param salt , password
     * returns hash string
     * @return string
     */
    public function checkhashSSHA($salt, $password) {

        $hash = base64_encode(sha1($password . $salt, true) . $salt);

        return $hash;
    }

    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
    }
}

?>




