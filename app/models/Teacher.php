<?php

class Teacher{

    //get all students for teacher
    public static function get_all_students(){
        $query = DB::$conn->prepare('SELECT students.id, students.first_name, students.last_name FROM class JOIN students ON class.id = students.class_id WHERE class.users_id = ?;');
        $query->execute([$_COOKIE['id']]);
        $students = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach($students as $student) {
            $student_id[$student['id']] = $student;
        }
        return $student_id;
    }

    //get all subjects for teacher
    public static function get_all_subjects(){
        $query = DB::$conn->prepare('SELECT * FROM subjects WHERE high_low = 0');
        $query->execute();
        $subjects = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach($subjects as $subject) {
            $subjects_id[$subject['id']] = $subject;
        }

        return $subjects_id;
    }

    //get students for parents
    public static function get_students_id($id){
        $query = DB::$conn->prepare('SELECT students.id, students.first_name, students.last_name FROM students WHERE students.id = ?;');
        $query->execute([$id]);
        $students_id = $query->fetch(PDO::FETCH_ASSOC);
        return $students_id;
    }

    //get all id subjects
    public static function get_subjects_id($id){
        $query = DB::$conn->prepare('SELECT subjects.id, subjects.name FROM subjects WHERE subjects.id = ?');
        $query->execute([$id]);
        $subjects_id = $query->fetch(PDO::FETCH_ASSOC);
        return $subjects_id;
    }

    //get all parents for students
    public static function get_all_parents(){
        $query = DB::$conn->prepare('SELECT users.id, users.first_name, users.last_name, students.first_name AS students_first_name,students.last_name AS students_last_name FROM users JOIN students ON users.id = students.users_id join class on class.id = students.class_id where class.users_id=?');
        $query->execute([$_COOKIE['id']]);
        $parents = $query->fetchAll(PDO::FETCH_ASSOC);
        return $parents;
    }

    //get name students
    public static function get_name_child(){
        $query = DB::$conn->prepare('SELECT users.id, users.first_name, users.last_name, students.id, students.first_name AS name_students, students.last_name as last_name_students, students.users_id FROM users JOIN students ON users.id = students.users_id');
        $query->execute();
        $child = $query->fetchAll(PDO::FETCH_ASSOC);
        return $child;
    }

    //get all class
    public static function get_class(){
        $query = DB::$conn->prepare('SELECT class.id, class.name, users_id FROM class WHERE users_id = ?;');
        $query->execute([$_COOKIE['id']]);
        $class = $query->fetchAll(PDO::FETCH_ASSOC);
        return $class;
    }

    //get id subjects and grade
    public static function get_id_subjects_grade($subjects_id, $grades){
        $query = DB::$conn->prepare('SELECT id FROM subjects_has_grades WHERE subjects_id = ? AND grades = ? LIMIT 1');
        $query->execute([$subjects_id, $grades]);
        $subject = $query->fetch(PDO::FETCH_ASSOC);
        return $subject['id'];
    }

    //add new grade
    public static function add_new_grade($id, $subjects_has_grades_id){
        $query = DB::$conn->prepare('INSERT INTO subjects_has_grades_has_students (students_id, subjects_has_grades_id) VALUES (?, ?)');
        $new_grade = $query->execute([$id, $subjects_has_grades_id]);
        return $new_grade;
    }

    //delete grade
    public static function delete($id, $subjects_has_grades_id){
        $query = DB::$conn->prepare('DELETE FROM subjects_has_grades_has_students where students_id = ? AND subjects_has_grades_id = ? LIMIT 1');
        $delete_grade = $query->execute([$id, $subjects_has_grades_id]);
        return $delete_grade;
    }

    //show all grade
    public static function grade_listing($class_id){
        $query = DB::$conn->prepare('SELECT students.id, students.first_name, subjects_has_grades.grades, subjects_has_grades_has_students.students_id, subjects_has_grades.subjects_id FROM students LEFT JOIN subjects_has_grades_has_students ON students.id = subjects_has_grades_has_students.students_id LEFT JOIN subjects_has_grades ON subjects_has_grades_has_students.subjects_has_grades_id = subjects_has_grades.id WHERE students.class_id = ? ORDER BY students.id');
        $list_grade = $query->execute($class_id);
        $grade = [];
        while($grade_list = $query->fetch(PDO::FETCH_ASSOC)){
        $grade[$grade_list['id']][$grade_list['subjects_id']][] = $grade_list['grades'];
        }
        return $grade;
    }

    //show final grade
    public static function final_grade($student_id, $subjects_id, $subjects_grade){
        $query = DB::$conn->prepare('SELECT final_grade.id FROM final_grade JOIN subjects_has_grades ON subjects_has_grades.id = final_grade.subject_grade  WHERE final_grade.student_id = ? AND subjects_has_grades.subjects_id = ?');
        $query->execute([$student_id, $subjects_id]);
        $get_final = $query->fetch(PDO::FETCH_ASSOC);
        $final_id = $get_final['id'];

        if($final_id){
            $query = DB::$conn->prepare('UPDATE final_grade SET student_id = ?, subject_grade = ? WHERE id = ? LIMIT 1');
            $grade = $query->execute([$student_id, $subjects_grade, $final_id]); 
            return $grade;
        }
        else{ 
            $query = DB::$conn->prepare('INSERT INTO final_grade (id, student_id, subject_grade) VALUES (NULL, ?, ?)');
            $grade = $query->execute([$student_id, $subjects_grade]); 
            return $grade;
        }
    }

    //show all final grade 
    public static function show_final_grade($class_id){
        $query = DB::$conn->prepare('SELECT subjects_has_grades.grades, final_grade.student_id, subjects_has_grades.subjects_id FROM final_grade JOIN subjects_has_grades ON final_grade.subject_grade = subjects_has_grades.id JOIN students ON students.id = final_grade.student_id WHERE students.class_id = ?');
        $grades = $query->execute($class_id);
        $grade_final = [];
        while($final_grade = $query->fetch(PDO::FETCH_ASSOC)){
        $grade_final[$final_grade['student_id']][$final_grade['subjects_id']][] = $final_grade['grades'];
        }
        return $grade_final;
    }
}
?>