<?php
      
        if($_COOKIE["fname"]){
               echo" <h2>Welcome {$_COOKIE["fname"]}</h2>" ;
               echo "<br>";
        }
         else{
             header("location:login.php");
         }
        
       


$studentinfo = json_decode($_GET['data']);
     
 foreach($studentinfo as  $key => $value){
     echo  $key.' '.$value."<br>"  ;
 }
 
  
 
 
?>