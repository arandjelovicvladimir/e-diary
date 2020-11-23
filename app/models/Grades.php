<?php

class Grades{

    //get diary of selected class    
    public static function get_diary($class_id)
        {
            $query = DB::$conn->prepare(' Select class.id as class_id,students.id,students.first_name,students.last_name,subjects_has_grades.grades,subjects_has_grades_has_students.id as mark from students join subjects_has_grades_has_students on students.id=subjects_has_grades_has_students.students_id join subjects_has_grades on 
            subjects_has_grades.id=subjects_has_grades_has_students.subjects_has_grades_id join subjects on subjects.id=subjects_has_grades.subjects_id join class on class.id=students.class_id where  subjects.users_id=? and students.class_id=?
            union
            Select class.id as class_id,students.id,students.first_name,students.last_name,subjects_has_grades.grades,subjects_has_grades_has_students.id as mark from students left join subjects_has_grades_has_students on students.id=subjects_has_grades_has_students.students_id and subjects_has_grades_has_students.id not in(select subjects_has_grades_has_students.id from subjects_has_grades_has_students join subjects_has_grades on subjects_has_grades_has_students.subjects_has_grades_id=subjects_has_grades.id join subjects on subjects.id=subjects_has_grades.subjects_id where subjects.users_id !=?) left join subjects_has_grades on 
            subjects_has_grades.id=subjects_has_grades_has_students.subjects_has_grades_id left join subjects on subjects.id=subjects_has_grades.subjects_id left join class on class.id=students.class_id where  students.id not in(Select students.id from students join subjects_has_grades_has_students on students.id=subjects_has_grades_has_students.students_id join subjects_has_grades on 

            subjects_has_grades.id=subjects_has_grades_has_students.subjects_has_grades_id join subjects on subjects.id=subjects_has_grades.subjects_id join class on class.id=students.class_id where  subjects.users_id=? and students.class_id=?) and  students.class_id=? order by last_name,first_name,id');
   
           $query->execute([$_COOKIE['id'],$class_id,$_COOKIE['id'],$_COOKIE['id'],$class_id,$class_id]); 

            $class = $query->fetchAll(PDO::FETCH_ASSOC);
            return $class;

    }

    //delete grade
    public static function delete($id)
    {
        $query = DB::$conn->prepare('delete from subjects_has_grades_has_students where id=? limit 1');
        $deleted=$query->execute([$id]); 

         // If grade is deleted, delete cache file on the school level..
        $schoolCacheFile = sprintf("views/director/pages/cache/avgschool_cache%s.php", date("Ymd"));
        unlink($schoolCacheFile);

        // If grade is deleted, delete cache file for classes..
        $classCacheFile = sprintf("views/director/pages/cache/avgclass_cache%s-%s.php", 
            $class, date("Ymd"));
        foreach (glob("views/director/pages/cache/avgclass_cache*") as $cache) {
            if($cache != $classCacheFile) {
                unlink($cache);
            }
        }

        return $deleted;
    }

    //change grade
    public static function edit($id,$subject_id,$grade)
    {
        $query = DB::$conn->prepare('select id from subjects_has_grades where subjects_id=? and grades=? limit 1');
        $query->execute([$subject_id,$grade]);
        $subject = $query->fetch(PDO::FETCH_ASSOC);
        $subject_grade_id=$subject['id'];
        
        $query = DB::$conn->prepare('update  subjects_has_grades_has_students set subjects_has_grades_id=?  where id=? limit 1');
        $deleted=$query->execute([$subject_grade_id,$id]); 

         // If new grade is inserted, delete cache file on the school level..
        $schoolCacheFile = sprintf("views/director/pages/cache/avgschool_cache%s.php", date("Ymd"));
        unlink($schoolCacheFile);

        // If new grade is inserted, delete cache file for classes..
        $classCacheFile = sprintf("views/director/pages/cache/avgclass_cache%s-%s.php", 
            $class, date("Ymd"));
        foreach (glob("views/director/pages/cache/avgclass_cache*") as $cache) {
            if($cache != $classCacheFile) {
                unlink($cache);
            }
        }

        return $deleted;
    }

    //add new grade
    public static function new_grade($id, $subject_id, $grade)
    {
        $query = DB::$conn->prepare('SELECT id from subjects_has_grades where subjects_id=? and grades=? limit 1');
        $query->execute([$subject_id,$grade]);
        $subject = $query->fetch(PDO::FETCH_ASSOC);
        $subject_grade_id=$subject['id'];
        $query = DB::$conn->prepare('insert into subjects_has_grades_has_students (students_id,subjects_has_grades_id) values (?,?)');
        $grade=$query->execute([$id,$subject_grade_id]); 

        // If new grade is inserted, delete cache file on the school level..
        $schoolCacheFile = sprintf("views/director/pages/cache/avgschool_cache%s.php", date("Ymd"));
        unlink($schoolCacheFile);

        // If new grade is inserted, delete cache file for classes..
        $classCacheFile = sprintf("views/director/pages/cache/avgclass_cache%s-%s.php", 
            $class, date("Ymd"));
        foreach (glob("views/director/pages/cache/avgclass_cache*") as $cache) {
            if($cache != $classCacheFile) {
                unlink($cache);
            }
        }

        return $grade;
    }

    //add final grade
    public static function final_grade($id, $subject_id, $grade)
    {
        $query = DB::$conn->prepare('SELECT id from subjects_has_grades where subjects_id=? and grades=? ');
        $query->execute([$subject_id,$grade]);
        $subject = $query->fetch(PDO::FETCH_ASSOC);
        $subject_grade_id=$subject['id'];
        $query = DB::$conn->prepare('SELECT final_grade.id from final_grade join subjects_has_grades on subjects_has_grades.id=final_grade.subject_grade  where final_grade.student_id=? and subjects_has_grades.subjects_id=?');
        $query->execute([$id, $subject_id]);
        $has_final = $query->fetch(PDO::FETCH_ASSOC);
        $final= $has_final['id'];
        //if final grade exist update that row,else make new row
        if($final){
            $query = DB::$conn->prepare('update final_grade  set student_id=?,subject_grade=? where id=? limit 1');
            $grade=$query->execute([$id,$subject_grade_id,$final]); 

             // If new grade is inserted, delete cache file on the school level..
        $schoolCacheFile = sprintf("views/director/pages/cache/avgschool_cache%s.php", date("Ymd"));
        unlink($schoolCacheFile);

        // If new grade is inserted, delete cache file for classes..
        $classCacheFile = sprintf("views/director/pages/cache/avgclass_cache%s-%s.php", 
            $class, date("Ymd"));
        foreach (glob("views/director/pages/cache/avgclass_cache*") as $cache) {
            if($cache != $classCacheFile) {
                unlink($cache);
            }
        }

            return $grade;
        }
        else{ 
            $query = DB::$conn->prepare('insert into final_grade (id,student_id,subject_grade) values (null,?,?)');
            $grade=$query->execute([$id,$subject_grade_id]); 
            
             // If new grade is inserted, delete cache file on the school level..
        $schoolCacheFile = sprintf("views/director/pages/cache/avgschool_cache%s.php", date("Ymd"));
        unlink($schoolCacheFile);

        // If new grade is inserted, delete cache file for classes..
        $classCacheFile = sprintf("views/director/pages/cache/avgclass_cache%s-%s.php", 
            $class, date("Ymd"));
        foreach (glob("views/director/pages/cache/avgclass_cache*") as $cache) {
            if($cache != $classCacheFile) {
                unlink($cache);
            }
        }

            return $grade;
        }
    }

    //get final grade for selected subject 
    public static function final_grades_show($subject_id,$class_id)
    {
        $query = DB::$conn->prepare('SELECT final_grade.student_id,subjects_has_grades.grades from final_grade join subjects_has_grades on subjects_has_grades.id=final_grade.subject_grade join students on students.id=final_grade.student_id  where subjects_has_grades.subjects_id=?  and students.class_id=?');
        $query->execute([$subject_id,$class_id]);
        $final_grades = $query->fetchAll(PDO::FETCH_ASSOC);
        return $final_grades;

    }


        // Average grades for all subjects on the school level..
    public static function average_school_grades()
    {
        $query = DB::$conn->prepare("SELECT FORMAT(SUM(shg.grades) / COUNT(shghs.students_id), 1) AS prosecna_ocena, subjects.name AS predmet 
            FROM subjects_has_grades shg
            JOIN subjects ON shg.subjects_id = subjects.id 
            JOIN subjects_has_grades_has_students shghs ON shg.id = shghs.subjects_has_grades_id 
            JOIN students ON shghs.students_id = students.id 
            JOIN class ON students.class_id = class.id 
            GROUP BY subjects.name");
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    $json = json_encode($result);
    return $json;
    }



    // Average grades for selected class..
    public static function average_class_grades($class, $high_low)
    {
        $query = DB::$conn->prepare("SELECT FORMAT(SUM(shg.grades) / COUNT(shghs.students_id), 1) AS prosecna_ocena, subjects.name AS predmet 
            FROM subjects_has_grades shg
            JOIN subjects ON shg.subjects_id = subjects.id 
            JOIN subjects_has_grades_has_students shghs ON shg.id = shghs.subjects_has_grades_id 
            JOIN students ON shghs.students_id = students.id 
            JOIN class ON students.class_id = class.id 
            WHERE class.name = ? AND class.high_low = ? GROUP BY subjects.name");
        $query->execute([$class, $high_low]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $json = json_encode($result);
        return $json;
    }


    public static function exportSchool()
    {
        $query = DB::$conn->prepare('SELECT FORMAT(SUM(shg.grades) / COUNT(shghs.students_id), 1) AS ocena, subjects.name AS predmet 
            FROM subjects_has_grades shg
            JOIN subjects ON shg.subjects_id = subjects.id 
            JOIN subjects_has_grades_has_students shghs ON shg.id = shghs.subjects_has_grades_id 
            JOIN students ON shghs.students_id = students.id 
            JOIN class ON students.class_id = class.id 
            GROUP BY subjects.name');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    public static function exportClass($class, $high_low)
    {
        $query = DB::$conn->prepare('SELECT FORMAT(SUM(shg.grades) / COUNT(shghs.students_id), 1) AS ocena, subjects.name AS predmet 
            FROM subjects_has_grades shg
            JOIN subjects ON shg.subjects_id = subjects.id 
            JOIN subjects_has_grades_has_students shghs ON shg.id = shghs.subjects_has_grades_id 
            JOIN students ON shghs.students_id = students.id 
            JOIN class ON students.class_id = class.id 
            WHERE class.name = ? AND class.high_low = ? GROUP BY subjects.name');
        $query->execute([$class, $high_low]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }




}
