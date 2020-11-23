<?php

class OpenDoor
{

    //get all apointment requests 
    public static function open(){
    $query = DB::$conn->prepare('SELECT users.last_name,users.first_name,users_has_open.users_id as parent_id,users_has_open.id as user_open_id,open.id as open_id,open.time,users_has_open.accepted  FROM `users_has_open` join open on open.id=users_has_open.open_id join users on users.id=users_has_open.users_id WHERE open.users_id=? order by accepted');
    $query->execute([$_COOKIE['id']]);
    $open_doors = $query->fetchAll(PDO::FETCH_ASSOC);
    return $open_doors;
    $zamena='SELECT users.last_name,users.first_name,users_has_open.users_id as parent_id,users_has_open.id as user_open_id,open.id as open_id,open.time,users_has_open.accepted  FROM `users_has_open` join open on open.id=users_has_open.open_id join users on users.id=users_has_open.users_id WHERE open.users_id=? order by accepted';

   }

    //confirm apointment request
    public static function open_yes($id){
    $query = DB::$conn->prepare('update users_has_open set accepted=1 where id=?');
    $query->execute([$id]);
    }

    //reject apointment request
    public static function open_no($id){
    $query = DB::$conn->prepare('update users_has_open set accepted=2 where id=?');
    $query->execute([$id]);
    }

    //create apointment request
    public static function open_create($time){
    $query = DB::$conn->prepare('delete  from open where open.users_id=?');
    $has_open=$query->execute([$_COOKIE['id']]);
    $query = DB::$conn->prepare('insert into open (users_id,time) values(?,?)');
    $query->execute([$_COOKIE['id'],$time]);
    return true;
    }

    //send apointment request
    public static function open_request($open_id){
    $query = DB::$conn->prepare('insert into users_has_open (users_id,open_id) values(?,?)');
    $query->execute([$_COOKIE['id'],$open_id]);
    return true;
    }

    //get all apointment request fom parent
    public static function open_response(){
    $query = DB::$conn->prepare('select distinct users.id,open.time,users.first_name,users.last_name,subjects.name,users_has_open.accepted from open join users on open.users_id=users.id join subjects on subjects.users_id=users.id join users_has_open on users_has_open.open_id=open.id  where users_has_open.users_id=? and CURRENT_TIMESTAMP<open.time
    UNION
    select users.id,open.time,users.first_name,users.last_name,role.name,users_has_open.accepted from open join users on open.users_id=users.id join role on role.id=users.roles_id join users_has_open on users_has_open.open_id=open.id join class on class.users_id=users.id where users_has_open.users_id=? and CURRENT_TIMESTAMP<open.time');
    $query->execute([$_COOKIE['id'],$_COOKIE['id']]);
    $all=[];
   while( $open = $query->fetch(PDO::FETCH_ASSOC)){
       $all[$open['id']]=[$open['time'],$open['last_name'],$open['first_name'],$open['accepted'],$open['name']];
   }
   return $all;
   }

    //get all active apointments 
    public static function open_professors(){
        $query = DB::$conn->prepare('  SELECT distinct open.id,open.time,users.first_name,users.last_name,subjects.name as title from open join users on users.id=open.users_id  join subjects on subjects.users_id=users.id join schedule on schedule.subjects_id=subjects.id join class on class.id=schedule.class_id join students on students.class_id=class.id where students.users_id=? and open.id not in (select open.id from open join users_has_open on users_has_open.open_id=open.id where users_has_open.users_id=? ) and CURRENT_TIMESTAMP<open.time
    union 
    select open.id,open.time,users.first_name,users.last_name,role.name as title from users join open on users.id=open.users_id join class on class.users_id=users.id join students on students.class_id=class.id join role on role.id=users.roles_id where students.users_id=? and open.id not in (select open.id from open join users_has_open on users_has_open.open_id=open.id where users_has_open.users_id=?) 
    and open.id not in
    (SELECT distinct open.id from open join users on users.id=open.users_id  join subjects on subjects.users_id=users.id join schedule on schedule.subjects_id=subjects.id join class on class.id=schedule.class_id join students on students.class_id=class.id where students.users_id=? and open.id not in(select open.id from open join users_has_open on users_has_open.open_id=open.id where users_has_open.users_id=?)) and  CURRENT_TIMESTAMP<open.time');
    // $query = DB::$conn->prepare('SELECT distinct open.id,open.time,users.first_name,users.last_name,subjects.name as title from open join users on users.id=open.users_id  join users_has_class on users_has_class.users_id=users.id  join class on class.id=users_has_class.class_id join students on students.class_id=class.id join subjects on subjects.users_id=users.id where students.users_id=? and open.id not in (select open.id from open join users_has_open on users_has_open.open_id=open.id where users_has_open.users_id=? ) and CURRENT_TIMESTAMP<open.time
    // union 
    // select open.id,open.time,users.first_name,users.last_name,role.name as title from users join open on users.id=open.users_id join class on class.users_id=users.id join students on students.class_id=class.id join role on role.id=users.roles_id where students.users_id=? and open.id not in (select open.id from open join users_has_open on users_has_open.open_id=open.id where users_has_open.users_id=?) 
    // and open.id not in
    // (SELECT distinct open.id from open join users on users.id=open.users_id  join users_has_class on users_has_class.users_id=users.id  join class on class.id=users_has_class.class_id join students on students.class_id=class.id join subjects on subjects.users_id=users.id where students.users_id=? and open.id not in (select open.id from open join users_has_open on users_has_open.open_id=open.id where users_has_open.users_id=?)) and  CURRENT_TIMESTAMP<open.time');
    $query->execute([$_COOKIE['id'],$_COOKIE['id'],$_COOKIE['id'],$_COOKIE['id'],$_COOKIE['id'],$_COOKIE['id']]);
    $open = $query->fetchAll(PDO::FETCH_ASSOC);
    return $open;
    }
   

    public static function open_professors_time(){ 
        $query = DB::$conn->prepare('SELECT distinct open.time from open where users_id=?');
        $query->execute([$_COOKIE['id']]);
        $time = $query->fetch(PDO::FETCH_ASSOC);
        return $time['time'];
    }  


}