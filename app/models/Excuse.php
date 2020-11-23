<?php


class Excuse
{


    public static function send_excuse($student_id,$image){
        $query = DB::$conn->prepare('insert into excuses (students_id,image) values (?,?)');
        $img=$query->execute([$student_id,$image]);
        return $img;
    
       }

       public static function get_excuses(){
       $query = DB::$conn->prepare('select students.last_name,students.first_name,excuses.image from students join excuses on excuses.students_id=students.id join class on class.id=students.class_id where class.users_id=?');
       $query->execute([$_COOKIE['id']]);
       $excuses= $query->fetchAll(PDO::FETCH_ASSOC);
       return $excuses;

       }

}