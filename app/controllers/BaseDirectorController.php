<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory; 
use PhpOffice\PhpSpreadsheet\Helper\Sample;

class BaseDirectorController
{

    public function __construct($demand)
    {
		$this->demand = $demand;
		
	}
	
	public function index()
	{
		$view = new View();
		$view->load_view('director', 'pages', 'home');
		$users = Active::find_by_username('Nenad');
		// echo $users->last_name;
		
	}
	

		// Average school grades..
	public function avgschool() 
	{
		$view = new View();

		// Create cache dir if doesn't exists..
		if(!is_dir("views/director/pages/cache")){
			mkdir("views/director/pages/cache");
		}

		

		$cacheFile = sprintf("views/director/pages/cache/avgschool_cache%s.php", date("Ymd"));

		// Delete old cache files from cache dir..
		 foreach (glob('views/director/pages/cache/avgschool*') as $cache) {
	//	 	 echo $cache . "<br>";
			if($cache != $cacheFile) {
				unlink($cache);
			}
		}

		if(file_exists($cacheFile)) {

		$timeDiff = time() - filemtime($cacheFile);
		// echo $timeDiff;
			// Izbrisi fajl ako je stariji od 12 sati..
			if($timeDiff > (60 * 60 * 12)) {
				unlink($cacheFile);
				}
				else {
					// Ucitaj validan cache fajl ako postoji..
					$file = "cache/avgschool_cache".date("Ymd");
					$view->load_view('director', 'pages', $file);
					exit;
				}
		}
			// Ako nema validnog cache fajla, posalji upit u bazu i napravi cache fajl..
			$grades = Grades::average_school_grades();
			$view->data['grades'] = $grades;
			$view->load_view('director', 'pages', 'avgschool');
	}



		// Prosek ocena za predmete na nivou odeljenja..
	public function avgclass()
	{
		$view = new View();

		// Create cache dir if not exists..
		if(!is_dir("views/director/pages/cache")){
			mkdir("views/director/pages/cache");
		}

		// Converting class name (e.g. 7/1 to 7-1) for file creation..
		$pattern = "/\//";
		$replacement = "-";
		$class = preg_replace($pattern, $replacement, $_GET['class']);

		// The path for cache file..
		$cacheFile = sprintf("views/director/pages/cache/avgclass_cache%s-%s.php", 
			$class, date("Ymd"));

		// Delete old cache files..
		 foreach (glob("views/director/pages/cache/avgclass_cache".$class."*") as $cache) {
			if($cache != $cacheFile) {
				unlink($cache);
			}
		}

		if(file_exists($cacheFile)) {

		$timeDiff = time() - filemtime($cacheFile);
		// echo $timeDiff;
			// Delete file if older than 12 hours..
			if($timeDiff > (60 * 60 * 12)) {
				unlink($cacheFile);
				}
				else {
					// Load valid cache file if exists..
					$file = "cache/avgclass_cache".$class."-".date("Ymd");
					$view->load_view('director', 'pages', $file);
					exit;
				}
		}
		// Ako nema validnog cache fajla, posalji upit u bazu i napravi cache fajl..
		$grades = Grades::average_class_grades($_GET['class'], $_GET['high_low']);
		$view->data['grades'] = $grades;
		$view->data['class'] = $_GET['class'];
		$view->load_view('director', 'pages', 'avgclass');
	}


		// Export average school grades to .xlsx file..
		public function exportSchoolGrades()
	{
			// Create excel dir if not exists..
			if(!is_dir("views/director/pages/excel/")){
				mkdir("views/director/pages/excel/");
			}
			
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			
			$sheet->setCellValue('A1', 'Predmet');
			$sheet->setCellValue('B1', 'Ocena');

			$grades = Grades::exportSchool();

			$row = 2;

		foreach($grades as $grade){
			
			$sheet->setCellValue('A'.$row, $grade['predmet']);
        	$sheet->setCellValue('B'.$row, $grade['ocena']);
			$row++;
		}

		$filename = 'views/director/pages/excel/grades'.time().'.xlsx';

		$writer = new Xlsx($spreadsheet);
		
		$writer->save($filename);
		header('Location:'.$_SERVER['HTTP_REFERER']);
		// Redirect output to client..
		// header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		// header('Content-Disposition: attachment; filename="'.$filename.'"');
		// header('Cache-Control: max-age=0');
		// If user IE 9
		// header('Cache-Control: max-age=1');
		// $writer->save('php://output');
	}


	public function exportClassGrades()
	{
		// Create excel dir if not exists..
		if(!is_dir("excel")){
			mkdir("views/director/pages/excel");
		}

		$class = $_GET['class'];
		$high_low = $_GET['high_low'];
		$grades = Grades::exportClass($class, $high_low);

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
			
		$sheet->setCellValue('A1', 'Predmet');
		$sheet->setCellValue('B1', 'Ocena');

		$row = 2;

		foreach($grades as $grade){
				
			$sheet->setCellValue('A'.$row, $grade['predmet']);
        	$sheet->setCellValue('B'.$row, $grade['ocena']);
			$row++;
		}

		$pattern = "/\//";
		$replacement = ".";
		$className = preg_replace($pattern, $replacement, $_GET['class']);
		$filePath = "views/director/pages/excel/grades".$className.time().".xlsx";
		//$filename = $className.time().".xlsx";

		$writer = new Xlsx($spreadsheet);
		
		$writer->save($filePath);
		header('Location: '.$_SERVER['HTTP_REFERER']);
		
		// Redirect output to client..
		// header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		// header('Content-Disposition: attachment; filename="'.$filename.'"');
		// header('Cache-Control: max-age=0');
		// If user IE 9
		// header('Cache-Control: max-age=1');
		// $writer->save('php://output');
	}



	public function downloadExcel()
	{
		if(isset($_GET['filename'])) {
			header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
			header("Content-Disposition: attachment;filename={$_GET['filename']}");
		}
		else {
			return false;
		}
	}

	public function deleteExcel()
	{
		if(isset($_GET['filePath'])) {
			unlink('views/director/pages/excel/'.$_GET['filePath']);
			header('Location: '. $_SERVER['HTTP_REFERER']);
		}
		else {
			return false;
		}
	}


	public function record()
	{
		
	}



	public function logout()
	{
		$access_destroy = BaseAccessController::logout($_COOKIE['id'], $_COOKIE['loginhash']);
		header('Location: '.URLROOT.'/');
		die();
		
	}



}