<?php
class  Account{
  private $con;
  private $errorArray=array();
  public function __construct($con){
    $this->con=$con;
  }

  public function updateDetails($fn,$ln,$em,$un){      //to update the details of a loggedin user
    $this->validateFirstName($fn);
    $this->validateLastName($ln);
    $this->validateNewEmail($em,$un);

    if(empty($this->errorArray)){   //no errors then update the entered details
      //update data
      $query=$this->con->prepare("UPDATE users SET firstName=:fn, lastName=:ln,email=:em WHERE username=:un");

      $query->bindValue(":fn",$fn);
      $query->bindValue(":ln",$ln);
      $query->bindValue(":em",$em);
      $query->bindValue(":un",$un);

      return $query->execute();

    }
    return false;
  }


  public function register($fn,$ln,$un,$em,$em2,$pw,$pw2){
    $this->validateFirstName($fn);
    $this->validateLastName($ln);
    $this->validateUserName($un);
    $this->validateEmails($em,$em2);
    $this->validatePasswords($pw,$pw2);

    if(empty($this->errorArray)){
      return $this->insertUserDetails($fn,$ln,$un,$em,$pw);   //returns true
    }
    return false;
    //inserting user details into the table
  }
  public function login($un,$pw){
    $pw=hash("sha512",$pw);   //this hash should match the hash in the database to login.

    $query=$this->con->prepare("SELECT * FROM users WHERE username=:un AND password=:pw");   //  * refers to all or everything


    $query->bindValue(":un",$un);
    $query->bindValue(":pw",$pw);

    $query->execute();

    if($query->rowCount()==1){
      return true;
    }
    array_push($this->errorArray,Constants::$loginFailed);
    return false;


  }
  private function insertUserDetails($fn,$ln,$un,$em,$pw){
    $pw=hash("sha512",$pw);   /*it converts the password into random combo of numbers lettrs and symbols */
    $query=$this->con->prepare("INSERT INTO users(firstName,lastName,username,email,password)
          VALUES(:fn,:ln,:un,:em,:pw)");
    $query->bindValue(":fn",$fn);
    $query->bindValue(":ln",$ln);
    $query->bindValue(":un",$un);
    $query->bindValue(":em",$em);
    $query->bindValue(":pw",$pw);

    return $query->execute();     //returns true if everything goes well
  }


  private function validateFirstName($fn){      //private makes the function to be accesesed only in that class.
    if(strlen($fn)<2||strlen($fn)>25){
      array_push($this->errorArray,Constants::$firstNameCharacters);  //the second element in array_push says access the static property from constants class anmed firstNameCharacters.
    }
  }
  private function validateLastName($ln){
    if(strlen($ln)<2||strlen($ln)>25){
      array_push($this->errorArray,Constants::$lastNameCharacters);  //the second element in array_push says access the static property from constants class anmed firstNameCharacters.
    }
  }
  private function validateUserName($un){
    if(strlen($un)<2||strlen($un)>25){
      array_push($this->errorArray,Constants::$userNameCharacters);  //the second element in array_push says access the static property from constants class anmed firstNameCharacters.
      return;  //no need to chck the other if one is wrong
    }
    //SQL QUERY to check if the username alraedy exists
    $query=$this->con->prepare("SELECT * FROM users WHERE username= :un");     //users is the name of the table
    $query->bindValue(":un",$un);     //assigning $un to :un
    $query->execute();

    if($query->rowCount()!=0){
      array_push($this->errorArray,Constants::$usernameTaken);
    }
  }
  private  function validateEmails($em,$em2){
    if($em!=$em2){
      array_push($this->errorArray,Constants::$emailsDontMatch);
      return;
    }
    if(!filter_var($em,FILTER_VALIDATE_EMAIL)){
      array_push($this->errorArray,Constants::$emailInvalid);
      return;
    }
    $query=$this->con->prepare("SELECT * FROM users WHERE email= :em");     //users is the name of the table
    $query->bindValue(":em",$em);     //assigning $un to :un
    $query->execute();

    if($query->rowCount()!=0){
      array_push($this->errorArray,Constants::$emailTaken);
    }

  }

  private  function validateNewEmail($em,$un){

    if(!filter_var($em,FILTER_VALIDATE_EMAIL)){
      array_push($this->errorArray,Constants::$emailInvalid);
      return;
    }
    $query=$this->con->prepare("SELECT * FROM users WHERE email= :em AND username!=:un");     //users is the name of the table
    $query->bindValue(":em",$em);
    $query->bindValue(":un",$un);     //assigning $un to :un
    $query->execute();

    if($query->rowCount()!=0){
      array_push($this->errorArray,Constants::$emailTaken);
    }

  }

  private function validatePasswords($pw,$pw2){
    if($pw!=$pw2){
      array_push($this->errorArray,Constants::$passwordsDontMatch);
      return;
    }
    if(strlen($pw)<5||strlen($pn)>25){
      array_push($this->errorArray,Constants::$passwordLength);
  }
}

  public function getError($error){
    if(in_array($error,$this->errorArray)){       //in_array is an inbuilt fn to check if an element is present in the array.
      return "<span class='errorMessage'>$error</span>";
    }
  }

  public function getFirstError(){
    if(!empty($this->errorArray)){
      return $this->errorArray[0];
    }
  }
  public function updatePassword($oldPw,$pw,$pw2,$un){
    $this->validateOldPassword($oldPw,$un);
    $this->validatePasswords($pw,$pw2);

    if(empty($this->errorArray)){   //no errors then update the entered details
      //update data
      $query=$this->con->prepare("UPDATE users SET password=:pw WHERE username=:un");
      $pw=hash("sha512",$pw);
      $query->bindValue(":pw",$pw);
      $query->bindValue(":un",$un);

      return $query->execute();

    }
    return false;
  }
  public function validateOldPassword($oldPw,$un){
    $pw=hash("sha512",$oldPw);   //this hash should match the hash in the database to login.

    $query=$this->con->prepare("SELECT * FROM users WHERE username=:un AND password=:pw");   //  * refers to all or everything


    $query->bindValue(":un",$un);
    $query->bindValue(":pw",$pw);

    $query->execute();

    if($query->rowCount()==0){
      array_push($this->errorArray, Constants::$passwordIncorrect);    }
  }
} ?>
