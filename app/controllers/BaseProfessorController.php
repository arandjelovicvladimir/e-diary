<?php 

class BaseProfessorController 
{
private $grades=[1,2,3,4,5];

    public function __construct($demand)
    {
		$this->demand = $demand;
		
	}

	//home page for professor
	public function index()
	{
		$view = new View();
		//method for class name
		$class = Classes::get_my_class();
		$view->data['class'] = $class;
		//get all students from class
		$students = Classes::get_diary_of_my_class();
		$view->data['students'] = $students;
		$view->load_view('professor', 'pages', 'home');

	}

    //logout
	public function logout()
	{
		$access_destroy = BaseAccessController::logout($_COOKIE['id'], $_COOKIE['loginhash']);
		header('Location: '.URLROOT.'/');
		die();
	}

    //get all diaries 
	public function diary()
	{
		$view = new View();
		// get all classes for professor
		$all_classes = Classes::class_info();
		$view->data['classes'] = $all_classes;
		$class = Classes::get_my_class();
		$view->data['class'] = $class;
		$view->load_view('professor', 'pages', 'diary');
	
	}

	
	//get selected diary
	public function diaryof(){
		$view = new View();
		$class_id = $this->demand->parts_of_url[5];
		$diary=Grades::get_diary($class_id);
		$view->data['diaries'] = $diary;
		$view->data['subject_id']=Subjects::get_subject_id();
		$subject_id=$view->data['subject_id'];
		//show final grades
		$view->data['final']=Grades::final_grades_show($subject_id,$class_id);
		$view->load_view('professor', 'pages', 'diaryof');
	}

	//delete grade 
	public function delete(){
		$id = $this->demand->parts_of_url[5];
		$deleted=Grades::delete($id);
		if (isset($_SERVER["HTTP_REFERER"])) {
			header("Location: " . $_SERVER["HTTP_REFERER"]);
		}
		return $deleted;
	}
    //change grade 
	public function edit(){
		$id = $this->demand->parts_of_url[5];
		$new_grade = $this->demand->parts_of_url[7];
		$subject_id=$this->demand->parts_of_url[6];
		if(!in_array($new_grade,$this->grades)){
			header("Location: ".$_SERVER["HTTP_REFERER"]."?err=Ocena nije validna");
			die;
		}
		$edited=Grades::edit($id,$subject_id,$new_grade);
		if (isset($_SERVER["HTTP_REFERER"])) {
			header("Location: " . $_SERVER["HTTP_REFERER"]);
		}
		return $edited;
	}

	//add new grade
	public function new_grade(){
		$id = $this->demand->parts_of_url[5];
		$new_grade = $this->demand->parts_of_url[7];
		$subject_id=$this->demand->parts_of_url[6];
		if(!in_array($new_grade,$this->grades)){
			header("Location: " . $_SERVER["HTTP_REFERER"]."?err=ocena nije validna");
			die;
		}
		$grade=Grades::new_grade($id,$subject_id,$new_grade);
		if (isset($_SERVER["HTTP_REFERER"])) {
			header("Location: " . $_SERVER["HTTP_REFERER"]);
		}
		return $grade;
	}

	//add final grade
	public function final_grade(){
		$id = $this->demand->parts_of_url[5];
		$final_grade = $this->demand->parts_of_url[7];
		$subject_id=$this->demand->parts_of_url[6];
		if(!in_array($final_grade,$this->grades)){
			header("Location: ".$_SERVER["HTTP_REFERER"]."?err=ocena nije validna");
			die;
		}
		$grade=Grades::final_grade($id,$subject_id,$final_grade);
		if (isset($_SERVER["HTTP_REFERER"])) {
			header("Location: " . $_SERVER["HTTP_REFERER"]);
		}
		
		return $grade;
	}

	//display open door page 
	public function open(){
		$view = new View();
		$open_doors=OpenDoor::open();
		$time=OpenDoor::open_professors_time();
		$view->data['open'] = $open_doors;
		$view->data['time'] = $time;
		$view->load_view('professor', 'pages', 'open');

	}

	//confirm appointment
	public function open_yes(){
		$id = $this->demand->parts_of_url[5];
		$open_doors=OpenDoor::open_yes($id);
		if (isset($_SERVER["HTTP_REFERER"])) {
			header("Location: " . $_SERVER["HTTP_REFERER"]);
		}

	}
    //reject appointment
	public function open_no(){
		$id = $this->demand->parts_of_url[5];
		$open_doors=OpenDoor::open_no($id);
		if (isset($_SERVER["HTTP_REFERER"])) {
			header("Location: " . $_SERVER["HTTP_REFERER"]);
		}
	}

	//create appointment
	public function open_create(){
		$time = str_replace('T',' ',$_GET['date']);
		$time.=":00";
		
		$open_create=OpenDoor::open_create($time);
		if (isset($_SERVER["HTTP_REFERER"])) {
			header("Location: " . $_SERVER["HTTP_REFERER"]);
		}
	}

	//show professor schedule
	public function schedule(){
		$view = new View();
		$schedule=Schedule::get_schedule_for_professor();
		$view->data['schedule'] = $schedule;
		$view->load_view('professor', 'pages', 'schedule');
		

	}

	//display messages view
	public function messages(){
		$view = new View();
		//show new messages
		$messages=Messages::get_new_messages();
		//show parents for chat
		$parents=Messages::parents_chat();
		$view->data['all_messages'] = $messages;
		$view->data['parents'] = $parents;
		$view->load_view('professor', 'pages', 'messages');
		
	}

    //ajax response for new messages
	public function ajax(){
		$messages=Messages::get_new_messages();
		echo JSON_encode($messages);
		
	}

	//change message status
	public function ajax_is_read(){	
		$id=$_GET['id'];
		$messages=Messages::ajax_is_read($id);
		if($messages)
		$response=['response'=>true];
		else
		$response=['response'=>false];
		echo JSON_encode($response);
		

	}

	//show all messages from one parent
	public function ajax_chat(){	
		$id=$_GET['id'];
		$messages=Messages::ajax_chat($id);
		echo JSON_encode($messages);

	}

    //send message
	public function ajax_send_message(){
		$message=htmlspecialchars(strip_tags($_GET['message']));
		$id=$_GET['id'];
		if($id===null){
			header("Location: http://localhost/ednevnik/app/professor/messages?err=Niste izabrali primaoca");
			exit();
		}
		Messages::ajax_send_message($message,$id);
		$response=['response'=>'da'];
		echo JSON_encode($response);
		
	}

	//get pdf of student final success R&OS library
	public function success(){
		include_once 'Creport.php';
		$id= $this->demand->parts_of_url[5];
		$view = new View();
		//get all final grades 
		$grades=Student::success($id);
		if($grades==null){
			header("Location: http://localhost/ednevnik/app/professor/index?err=Ucenik nije ocenjen!&id=".$id);
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
		$pdf->ezText('<b>'.ucfirst($grades[0]['first_name']).' '.ucfirst($grades[0]['last_name']).'</b>',18,[ 'justification'=> 'center']);
		$pdf->ezSetDy(-8);
		$pdf->ezText(' ',15,[ 'justification'=> 'center']);
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
	
		//$pdf->setColor (0.1,0.3,0.1,[0]);
		$pdf->ezText('         - '.ucfirst($subject['name']),13);
		$pdf->ezSetDy(+19);
		$pdf->ezText(''.$grade.'('.$subject['grades'].')           ',13,[ 'justification'=> 'right']);
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
			$pdf->addText (445,75,10,'DIREKTOR');
			$pdf->ezStream();
		
		
		
	}
    //show all excuses
	public function excuse(){
		$view = new View();
		$excuses=Excuse::get_excuses();
		$view->data['excuses'] = $excuses;
		$view->load_view('professor', 'pages', 'excuse');
		
	}

 

}