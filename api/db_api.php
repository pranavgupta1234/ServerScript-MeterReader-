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

    public function storeReading($flat_id,$newReading) {

        $var1 = 'a';
        $var2 = 'a';
        $var3 = 'a';
        $var4 = 'a';
        $stmt = $this -> conn -> prepare("INSERT INTO test(col1,col2,col3,col4) VALUES (2,2,2,2)");
        $stmt -> bind_param("sssss",$var1,$var2,$var3,$var4);
        $result = $stmt -> execute();
        $stmt -> close();

    }

    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name , $password) {

        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
        $role = "field_user";

        //escape string
        $name = mysqli_real_escape_string($this -> conn,$name);
        $encrypted_password = mysqli_real_escape_string($this->conn,$encrypted_password);
        $salt = mysqli_real_escape_string($this -> conn,$salt);
        $role = mysqli_real_escape_string($this -> conn,$role);

        $stmt = $this -> conn -> prepare("INSERT INTO Users(Username,Name,Password,Salt,Designation,Role,UpdatedBy) VALUES('$name','$name','$encrypted_password','$salt','$role',1,'$name')");
        $stmt->bind_param("sssss", $name, $name,$encrypted_password,$salt,$role);
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
            $salt = $user['salt'];
            $encrypted_password = $user['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
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


}



?>




