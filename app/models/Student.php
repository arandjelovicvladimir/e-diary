<?php 

class Student
{
    //method for getting students by class
    public static function get_student_by_id($student_id)
    {
        $query = DB::$conn->prepare('select st.id, st.first_name as student_name, st.last_name as student_surname, st.class_id, st.users_id, users.first_name as parent_name, users.last_name as parent_surname, class.name as class_name from students as st join users on st.users_id = users.id join class on st.class_id = class.id where st.id = ?');
        $query->execute([$student_id]);
        $student = $query->fetch(PDO::FETCH_ASSOC);
        return $student;
    }

    //method for getting students by class
    public static function get_students_by_class($class_id)
    {
        $query = DB::$conn->prepare('select st.id, st.first_name as student_name, st.last_name as student_surname, st.class_id, st.users_id, users.first_name as parent_name, users.last_name as parent_surname, class.name as class_name from students as st join users on st.users_id = users.id join class on st.class_id = class.id where class_id = ?');
        $query->execute([$class_id]);
        $students = $query->fetchAll(PDO::FETCH_ASSOC);
        return $students;
    }

   //get final grades
   public static function success($student_id){
        $query = DB::$conn->prepare('SELECT students.id,students.last_name,students.first_name,subjects.name,subjects_has_grades.grades  from students join final_grade on final_grade.student_id=students.id join subjects_has_grades on subjects_has_grades.id=final_grade.subject_grade  join subjects on subjects.id=subjects_has_grades.subjects_id  where students.id=?');
        $query->execute([$student_id]);
        $grades = $query->fetchAll(PDO::FETCH_ASSOC);
        return $grades;

    }

   
   public static function get_student(){
        $query = DB::$conn->prepare('SELECT students.first_name,students.last_name,subjects.name,subjects_has_grades.grades from students join subjects_has_grades_has_students on subjects_has_grades_has_students.students_id=students.id join subjects_has_grades on subjects_has_grades.id=subjects_has_grades_has_students.subjects_has_grades_id join subjects on subjects.id=subjects_has_grades.subjects_id
        WHERE students.users_id=? order by first_name,subjects.name');
        $query->execute([$_COOKIE['id']]); 
        $subjects_and_grades = $query->fetchAll(PDO::FETCH_ASSOC);
        return $subjects_and_grades;

    }

    public static function get_child_student(){
        $query = DB::$conn->prepare('SELECT students.id,students.first_name,students.last_name from students
        WHERE students.users_id=?');
        $query->execute([$_COOKIE['id']]); 
        $childs = $query->fetchAll(PDO::FETCH_ASSOC);
        return $childs;

    }

    public static function edit_student($puple_name, $puple_surname, $class_id, $users_id, $puple_id, $parent_name, $parent_surname)
    {
        try {  
            DB::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            DB::$conn->beginTransaction();
            $query = DB::$conn->prepare("update students set first_name=?, last_name=?, class_id=? where id=?");
            $query->execute([$puple_name, $puple_surname, $class_id, $puple_id]); 
            $parent_id = $users_id;

            $query =  DB::$conn->prepare("update users set first_name=?, last_name=? where id=?");
            $query->execute([$parent_name, $parent_surname, $parent_id]);
            
            DB::$conn->commit();
        
        } catch (Exception $e) {
            DB::$conn->rollBack();
            echo "Failed: " . $e->getMessage();
            return false;
        }
        return true;
    }

    //method for adding new students to specific class in db
    public static function add_students($parent_name, $parent_surname, $parent_username, $parent_pass, $parent_role, $student_n, $student_s, $class_id)
    {
      try {  
            DB::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            DB::$conn->beginTransaction();
            $query = DB::$conn->prepare("insert into users (first_name, last_name, username, password, roles_id) values (?, ?, ?, ?, ?)");
            $query->execute([$parent_name, $parent_surname, $parent_username, $parent_pass, $parent_role]); 
            $parent_id = DB::$conn->lastInsertId(); 

            $query =  DB::$conn->prepare("insert into students (first_name, last_name, class_id, users_id) values (?, ?, ?, ?)");
            $query->execute([$student_n, $student_s, $class_id, $parent_id]); 
            DB::$conn->commit();
        
        } catch (Exception $e) {
            DB::$conn->rollBack();
            echo "Failed: " . $e->getMessage();
            return false;
        }
        return true;

    }

    //method for taing parent id of spec student
    public static function get_parent_id($child_id)
    {
        $query = DB::$conn->prepare("select users_id from students where id = ?");
        $query->execute([$child_id]);
        $parent_id = $query->fetch(PDO::FETCH_ASSOC);
        return $parent_id;

    }
    //method for deleting puple from db and their parents from users if parent hasn't more kids
    public static function delete_puple($puple_id)
    {
        $query = 'delete from students where id=? limit 1';
        $res=  DB::$conn->prepare($query);
        return $res->execute([$puple_id]);
            
    }

    public static function get_children($parent_id)
    {  
        $stmt = DB::$conn->prepare("select * from students where users_id = ?");
        $stmt->execute([$parent_id]);
        $children = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $children;
    }

    public static function delete_parent($parent_id)
    {
        $query = 'delete from users where id=? limit 1';
        $res=  DB::$conn->prepare($query);
        return $res->execute([$parent_id]);
    }
}



