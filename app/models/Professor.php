<?php

class Professor
{
    
    
    //get all professors
    public static function all_professors()
    {
        $query = DB::$conn->prepare('select * from users where roles_id=?');
        $query->execute([1]); 
        $professors = $query->fetchAll(PDO::FETCH_ASSOC);
        return $professors;
    }

    
   

  
   

   

   


   
   

   
   
  
  
  





}