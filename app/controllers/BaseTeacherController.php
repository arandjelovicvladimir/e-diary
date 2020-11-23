<?php 

class BaseTeacherController{

    public function __construct($demand){
		$this->demand = $demand;
    }
    
    //show home page
	public function index(){
        $view = new View();
        $all_class = Teacher::get_class();
        $view->data['class'] = $all_class;
		$all_students = Teacher::get_all_students();
        $view->data['students'] = $all_students;
        $view->load_view('teacher', 'pages', 'home');
    }

    //show all grades 
    public function grade(){
        $view = new View();
        $all_class = Teacher::get_class();
        $view->data['class'] = $all_class;
        $id_class = [];
        foreach ($all_class as $class ) {
            $id_class [] = $class['id'];
        }
        $get_students =  Teacher::get_all_students();
        $view->data['students'] = $get_students;
        $all_subjects = Teacher::get_all_subjects();
        $view->data['subjects'] = $all_subjects;
        $list_gradee = Teacher::grade_listing($id_class);
        $view->data['listings'] = $list_gradee;
        $final = Teacher::show_final_grade($id_class);
        $view->data['show_final_grade'] = $final;
        $view->load_view('teacher', 'pages', 'grade');
    }

    //display messages view
    public function messages(){ 
        $view = new View();
        $all_class = Teacher::get_class();
        $view->data['class'] = $all_class;
        //show new message
		$messages = Messages::get_new_messages();
		$parents = Teacher::get_all_parents();
		$view->data['all_messages'] = $messages; 
		$view->data['parents'] = $parents; 
		$view->load_view('teacher', 'pages', 'messages'); 
    }

    //ajax response for new messages
	public function ajax(){
		$messages = Messages::get_new_messages(); 
        echo JSON_encode($messages); 
    } 
 
    //change message status
	public function ajax_is_read(){
		$id = $_GET['id']; 
        $messages = Messages::ajax_is_read($id);
        if($messages){
            $response = ['response'=>true];
        }else{
            $response = ['response'=>false];
        }
        echo JSON_encode($response);
    }

    //show all messages from one parent
	public function ajax_chat(){
        $id = $_GET['id']; 
        $messages = Messages::ajax_chat($id); 
        echo JSON_encode($messages); 
    } 

    //send message
	public function ajax_send_message(){ 
		$message = htmlspecialchars(strip_tags($_GET['message'])); 
		$id = $_GET['id']; 
		Messages::ajax_send_message($message,$id); 
		$response = ['response' => 'da']; 
        echo JSON_encode($response);
    }

    //method from show subjects
    public function objects(){
        $view = new View();
        $all_class = Teacher::get_class();
        $view->data['class'] = $all_class;
        $all_subjects = Teacher::get_all_subjects();
        $view->data['subjects'] = $all_subjects;
        $view->load_view('teacher', 'pages', 'objects');
    }

    //show new grade
    public function new_grade(){
        $view = new View();
        $all_class = Teacher::get_class();
        $view->data['class'] = $all_class;
        $all_subjects = Teacher::get_all_subjects();
        $view->data['subjects'] = $all_subjects;
        $id_students = $this->demand->parts_of_url[5];
        $students_id = Teacher::get_students_id($id_students);
        $view->data['id_students'] = $students_id;
        $view->load_view('teacher', 'pages', 'new_grade');
    }

    //save the new grade
    public function save_new_grade(){
        $id_students = $this->demand->parts_of_url[5];
        $name_students = $_POST['first_name'];
        $last_name_students = $_POST['last_name'];
        $subjects_id = $_POST['id_subjects'];
        $grades = $_POST['grade'];
        $subjects_and_grades = Teacher::get_id_subjects_grade($subjects_id, $grades);
        $subject_grades['subjects_id'] =  $subjects_and_grades;	

        $add_grade = Teacher::add_new_grade($id_students, $subjects_and_grades);

        if($add_grade){
            header('Location: http://localhost/ednevnik/app/teacher/grade?success=Uspesno  uneta ocena!');
            
        }else{
            header('Location:http://localhost/ednevnik/app/teacher/new_grade?err=Ocena nije uneta!');
        }
    }

    //delete grade for za student
    public function delete_grade(){
        $view = new View();
        $all_class = Teacher::get_class();
        $view->data['class'] = $all_class;
        $all_subjects = Teacher::get_all_subjects();
        $view->data['subjects'] = $all_subjects;
        $id_students = $this->demand->parts_of_url[5];
        $students_id = Teacher::get_students_id($id_students);
        $view->data['id_students'] = $students_id;
        $view->load_view('teacher', 'pages', 'delete_grade');
    }

    //save the deleted rating
    public function save_delete_grade(){
        $id_students = $this->demand->parts_of_url[5];
        $name_students = $_POST['first_name'];
        $last_name_students = $_POST['last_name'];
        $subjects_id = $_POST['id_subjects'];
        $grades = $_POST['grade'];
        $subjects_and_grades = Teacher::get_id_subjects_grade($subjects_id, $grades);
        $subject_grades['subjects_id'] =  $subjects_and_grades;

        $grede_delete = Teacher::delete($id_students, $subjects_and_grades);

        if($grede_delete){
            header('Location: http://localhost/ednevnik/app/teacher/grade?success=Uspesno obrisana ocena!');
        }else{
            header('Location:http://localhost/ednevnik/app/teacher/new_grade?err=Ocena nije obrisana! ');
        }
    }

    //sohow the final grade
    public function final_grade(){
        $view = new View();
        $all_class = Teacher::get_class();
        $view->data['class'] = $all_class;
        $all_subjects = Teacher::get_all_subjects();
        $view->data['subjects'] = $all_subjects;
        $id_students = $this->demand->parts_of_url[5];
        $students_id = Teacher::get_students_id($id_students);
        $view->data['id_students'] = $students_id;
        $view->load_view('teacher', 'pages', 'final_grade');
    }

    //save the final grade
    public function save_final_grade(){
        $id_students = $this->demand->parts_of_url[5];
        $name_students = $_POST['first_name'];
        $last_name_students = $_POST['last_name'];
        $subjects_id = $_POST['id_subjects'];
        $grades = $_POST['grade'];
        $subjects_and_grades = Teacher::get_id_subjects_grade($subjects_id, $grades);
        $subject_grades['subjects_id'] =  $subjects_and_grades;	

        $add_final_grade = Teacher::final_grade($id_students, $subjects_id, $subjects_and_grades );

        if($add_final_grade){
            header('Location: http://localhost/ednevnik/app/teacher/grade?success=Uspesno  zakljucena ocena!');
            
        }else{
            header('Location:http://localhost/ednevnik/app/teacher/new_grade?err=Ocena nije zakljucena!');
        }  
    }

    //show teacher schedule
    public function schedule(){ 
        $view = new View(); 
        $all_class = Teacher::get_class(); 
        $view->data['class'] = $all_class; 
		$schedule = Schedule::get_schedule_for_teacher(); 
        $view->data['schedule'] = $schedule; 
        $view->load_view('teacher', 'pages', 'schedule');
    }

    //display open door page 
    public function open(){ 
        $view = new View(); 
        $all_class = Teacher::get_class(); 
        $time=OpenDoor::open_professors_time();
		$view->data['time'] = $time;
        $view->data['class'] = $all_class; 
		$open_doors = OpenDoor::open(); 
		$view->data['open'] = $open_doors; 
        $view->load_view('teacher', 'pages', 'open');
    }

    //confirm appointment
	public function open_yes(){ 
		$id = $this->demand->parts_of_url[5]; 
		$open_doors = OpenDoor::open_yes($id); 
		if(isset($_SERVER["HTTP_REFERER"])){
			header ("Location:" . $_SERVER["HTTP_REFERER"]);
        }
    }

    //reject appointment
	public function open_no(){ 
		$id = $this->demand->parts_of_url[5]; 
        $open_doors = OpenDoor::open_no($id);
		if (isset($_SERVER["HTTP_REFERER"])){ 
			header("Location: " . $_SERVER["HTTP_REFERER"]); 
        }
    }

    //create appointment
	public function open_create(){
		$time = str_replace('T',' ',$_GET['date']);
		$time .= ":00";
		$open_create = OpenDoor::open_create($time);
		if(isset($_SERVER["HTTP_REFERER"])){
			header ("Location: " . $_SERVER["HTTP_REFERER"]);
		}
    }

//get pdf of student final success R&OS library 
public function success(){
    include_once 'Creport.php';
    $id = $this->demand->parts_of_url[5];
    $view = new View();
    //get all final grades 
    $grades = Student::success($id);
    if($grades == null){ 
        header("Location: http://localhost/ednevnik/app/teacher/index?err=Ucenik nije ocenjen! & id= " . $id);
        exit(); 
    } 

    //$pdf = new Cezpdf();
    $pdf = new Creport('a4', 'portrait');
    $mainFont ='FreeSerif';
    $family = array(
        'b'=> $mainFont . 'Bold'
    );
    $pdf->setFontFamily($mainFont,$family);
    // select a font and use font subsetting
    $pdf->selectFont($mainFont, '', 1, true);
    $pdf->ezSetMargins(40,40,40,40);
    $pdf->setLineStyle(1,'round');
    $pdf->line(72,778,522,778);
    $pdf->line(72,780,522,780);
    $pdf->line(72,750,522,750);
    $pdf->line(144,630,450,630);
    $pdf->line(72,60,522,60);
    $pdf->line(420,90,522,90);
    
    $pdf->ezText('Republika Srbija',13,[ 'justification'=> 'center']);
    $pdf->ezSetDy(-7);
    $pdf->ezText('Osnovna škola "Svetozar Marković"',16,[ 'justification'=> 'center']);
    $pdf->ezSetDy(-10);
    
    $pdf->ezText(' <b>SVEDOČANSTVO</b>',40,[ 'justification'=> 'center']);
    $pdf->ezSetDy(-23);
    $pdf->ezText(''.ucfirst($grades[0]['first_name']).' '.ucfirst($grades[0]['last_name']).'',16,[ 'justification'=> 'center']);
    $pdf->ezSetDy(-8);
    $pdf->ezText('',15,[ 'justification'=> 'center']);
    $pdf->ezSetDy(-25);
    $sum=0;
    $count=0;
    $fall=false;
    $x=550;
    foreach($grades as $subject){
        $grade;
        $sum+=$subject['grades'];
        $count++;
        switch($subject['grades']){
            case 1:
            $grade='nedovoljan';
            $fall=true;
            break;
            case 2:
            $grade='dovoljan';
            break;
            case 3:
            $grade='dobar';
            break;
            case 4:
            $grade='vrlo dobar';
            break;
            case 5:
            $grade='odličan';
            break;
            default:
            return;
        }

    //$pdf->setColor (1,0,0,[0]);
    $pdf->ezText('         - '.ucfirst($subject['name']),13);
    $pdf->ezSetDy(+19);
    $pdf->ezText('<i>'.$grade.'('.$subject['grades'].')</i>           ',13,[ 'justification'=> 'right']);
    $pdf->line(70,$x,520,$x);
    $pdf->ezSetDy(-12);
    $x-=32;
    }
    
    $grade=round($sum/$count);
    switch($grade){
        case 1:
        $grade='nedovoljan';
        break;
        case 2:
        $grade='dovoljan';
        break;
        case 3:
        $grade='dobar';
        break;
        case 4:
        $grade='vrlo dobar';
        break;
        case 5:
        $grade='odličan';
        break;
        default:
        return;
    }
    if($fall){
        $grade='Nedovoljan';
        $pdf-> addText (100,115,14,'Uspeh:<b> '.$grade.'(1)</b>');
        $pdf->line(80,110,300,110);
    }
    else
        $pdf-> addText (100,115,14,'Uspeh:<b> '.$grade.' ('.sprintf('%0.2f',($sum/$count)).')</b>');
        $pdf->ezSetDy(-15);
        $pdf-> addText (445,75,10,'DIREKTOR');
        $pdf->ezStream();
}

public function logout(){
    $access_destroy = BaseAccessController::logout($_COOKIE['id'], $_COOKIE['loginhash']);
    header('Location: '.URLROOT.'/');
    die();	
}
 
public function excuse(){ 
    $view = new View(); 
    $excuses = Excuse::get_excuses(); 
    $view->data['excuses'] = $excuses; 
    $view->load_view('teacher', 'pages', 'excuse'); 
} 
} 