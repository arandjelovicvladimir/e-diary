<?php 

class Schedule
{

    //get schedule for professor
    public static function get_schedule_for_professor()
    {
        $query = DB::$conn->prepare('select schedule.day_in_week,schedule.lesson_no,class.name from schedule join subjects on subjects.id=schedule.subjects_id join users on users.id=subjects.users_id join class on class.id=schedule.class_id where users.id=?');
        $query->execute([$_COOKIE['id']]);
        $schedule = $query->fetchAll(PDO::FETCH_ASSOC);
        return $schedule;

    }

    //get schedule for teacher
    public static function get_schedule_for_teacher()
    {
        $query = DB::$conn->prepare('SELECT schedule.day_in_week,schedule.lesson_no,subjects.name FROM schedule JOIN subjects ON subjects.id=schedule.subjects_id  JOIN class ON class.id=schedule.class_id WHERE class.users_id=?');
        $query->execute([$_COOKIE['id']]);
        $schedule = $query->fetchAll(PDO::FETCH_ASSOC);
        return $schedule;
    }

    public static function schedule_by_class($class_id, $day, $lesson_no)
    {
        $query = DB::$conn->prepare('select schedule.day_in_week, schedule.lesson_no, subjects.name as subject, class.name as class from schedule join subjects on schedule.subjects_id = subjects.id join class on schedule.class_id = class.id where schedule.class_id = ?and schedule.day_in_week = ? and schedule.lesson_no = ?');
        $query->execute([$class_id, $day, $lesson_no]);
        $schedule = $query->fetch(PDO::FETCH_ASSOC);
        return $schedule;
    }

    public static function make_schedule($day, $lesson_no, $subject_id, $class_id)
    {
        $query = "insert into schedule (day_in_week, lesson_no, subjects_id, class_id) values (?,?,?,?)";
        $query= DB::$conn->prepare($query);
        $res = $query->execute([$day, $lesson_no, $subject_id, $class_id]);
        return $res;
    }

    public static function schedule_exists($class_id)
    {
        $query = 'select class_id from schedule where class_id = ?';
        $query= DB::$conn->prepare($query);
        $query->execute([$class_id]); 
        $class_exist = count($query->fetchAll(PDO::FETCH_ASSOC));
        if ($class_exist == 0) {
            return false;
        } else {
            return true;
        }
    
    }

    public static function is_subject_occupied($day, $lesson_num)
    {
        $query = DB::$conn->prepare('select subjects_id from schedule where day_in_week = ? and lesson_no = ?');
        $query->execute([$day, $lesson_num]);
        $occupy_classes = $query->fetchAll(PDO::FETCH_ASSOC);
        return $occupy_classes;
    }

    public static function get_sch_by_class($class_id)
    {
        $query = 'select sch.id as spec_row, sch.day_in_week, sch.lesson_no, sch.subjects_id, sch.class_id, sub.name as selected_sub, sub.high_low from schedule as sch join subjects as sub on sch.subjects_id = sub.id where class_id = ?';
        $query= DB::$conn->prepare($query);
        $res = $query->execute([$class_id]);
        $class_sch = $query->fetchAll(PDO::FETCH_ASSOC);
        return $class_sch; 
    }

    //method for updating existing schedule of spec. grade
    public static function edit_sch($day, $lesson, $subject, $class, $id)
    {
        $query = 'update schedule set day_in_week = ?, lesson_no = ?, subjects_id = ?, class_id = ? where id = ?';
        $res=  DB::$conn->prepare($query);
        return $res->execute([$day, $lesson, $subject, $class, $id]);
    }
    
    //method for deleting schedule of specific grade from db
    public static function delete($class_id)
    {
        $query = 'delete from schedule where class_id=?';
        $res=  DB::$conn->prepare($query);
        $res->execute([$class_id]);
        if ($res->rowCount()){
            return true;
        } else{
            return false;
        }
    }
}