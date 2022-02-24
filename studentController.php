<?php

//////////connection
try{
$connection = new pdo("mysql:host=localhost;dbname=phpQena", "root","");
}catch(PDOException $e){
echo $e;
}


////////////////addstudent

if(isset($_POST['addstudent'])){

try{
 
 $fname=validation($_POST['fname']);
 $lname=validation($_POST['lname']);
 $email=validation($_POST['email']);
 $address=validation($_POST['address']);
 $Password=validation($_POST['Password']);
 $imgName=validation($_FILES["name"]);

  $error=[];


 
 if(strlen($fname)<3){
       $error["fname"] = "first name must be more than 3 char";
 }

 if(strlen($lname)<3){
  $error["lname"] = "last name must be more than 3 char";
 }
 
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
     $error["email"] = "pls enter valid email";
}
 
if(count($error)>0){
       $errorArray = json_encode($error);
      header("location:addStudent.php?error=$errorArray");
}

 $imgExtension= pathinfo($_FILES["img"]["name"],PATHINFO_EXTENSION);
 
 $allowedExtension=["png","jpg","txt"];

 if(!in_array($imgExtension,$allowedExtension)){
   $error["imgExtension"]="not in allow imgExtension";

     header("location:addStudent.php?error=$imgExtension");
 }
 
  if(!move_uploaded_file(
    $_FILES["img"]["tmp_name"],
    $_FILES["img"]["name"])){
      $error["img"]="img is not exists";
    }

 else {
      $stm = $connection->prepare("
   
      insert into  student set
       fname = ?,
        lname=?, 
        email=?,
         address=?,
         Password=?,
         imgName=?
");
      $stm->execute([$fname, $lname, $email, $address,$Password,$imgName]);
       header("location:list.php");
    }
}catch(PDOException $e){
echo $e;
}

$connection = null;
 

}


////////////delete

else if(isset($_GET['delete'])){
 $id=$_GET['id'];
 
try{

$connection->query(" delete  from  student where id=$id");
  header("location:list.php");

}catch(PDOException $e){
echo $e;
}
$connection = null;
}

/////////////////show

else if(isset($_GET['show'])){
$id=$_GET['id'];
try {  
 $sudentData=$connection->query("select * from  student where id=$id");
  $studentinfo=$sudentData->fetch(PDO::FETCH_ASSOC);
   $data=json_encode($studentinfo);
   header("location:show.php?data=$data");
}catch(PDOException $e){
 
  }
  $connection = null;
}

/////////////////edit

else if(isset($_GET['edit'])){
$id=$_GET['id'];
try {  
 $sudentData=$connection->query("select * from  student where id=$id");
  $studentinfo=$sudentData->fetch(PDO::FETCH_ASSOC);
   $data=json_encode($studentinfo);
   header("location:edit.php?data=$data");
}catch(PDOException $e){
 
  }
  $connection = null;
}


//////////////update

else if (isset($_GET['update'])){
try{
  $id=validation($_GET['id']);
 $fname=validation($_GET['fname']);
 $lname=validation($_GET['lname']);
 $email=validation($_GET['email']);
 $address=validation($_GET['address']);

 if(strlen($fname)<3){
       $error["fname"] = "first name must be more than 3 char";
 }

 if(strlen($lname)<3){
  $error["lname"] = "last name must be more than 3 char";
 }
 
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
     $error["email"] = "pls enter valid email";
}
 
if(count($error)>0){
       $errorArray = json_encode($error);
            if(isset($error["fname"])){
           echo $error["fname"]."<br>";
         } 
            if(isset($error["lname"])){
           echo $error["lname"]."<br>";
         }

              if(isset($error["email"])){
           echo $error["email"]."<br>";
         }  
}
else{
$sudentData=$connection->query("update student
set fname='$fname',
     lname='$lname',
     email='$email',
     address='$address'
where id= $id;
");
 // var_dump($sudentData);

     header("location:list.php");
}
}catch(PDOException $e){
echo $e;
}
}

////////////////////login
if(isset($_POST['login'])){
   $sudentData=$connection->query("select * from  student where email='{$_POST['email']}' and password='{$_POST['Password']}'");
 $studentinfo=$sudentData->fetch(PDO::FETCH_ASSOC);
   $email=$studentinfo["email"];
  $password=$studentinfo["password"];
  //////////////// $ sudentData check not work with me
    if($password!="" && $email!=""){
     setcookie("fname",$studentinfo["fname"]);
    header("location:list.php");
  }else{
    header("location:login.php");
  }
  
  
   
}



///////////////////validation
function validation($data){
  //// delete  html code - // - space 
  $data = htmlspecialchars(stripcslashes(trim($data)));
  return $data;
}
 ?>