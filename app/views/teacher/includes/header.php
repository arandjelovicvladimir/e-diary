<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>E-Dnevnik/Ucitelj</title>
<link href="http://localhost/ednevnik/app/assets/teacher/css/style.css" type="text/css" rel="stylesheet">
<!-- <link href="http://localhost/eDiary/task1/assets/teacher/css/https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,700,700i&amp;subset=latin-ext" rel="stylesheet"> -->
<!-- <link href="http://localhost/eDiary/task1/assets/teacher/css/fa/css/font-awesome.min.css" type="text/css" rel="stylesheet">
<link href="http://localhost/eDiary/task1/assets/teacher/css/web-fonts-with-css/css/fontawesome-all.min.css" type="text/css" rel="stylesheet"> -->

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/professor/css/main.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-0c38nfCMzF8w8DBI+9nTWzApOpr1z0WuyswL4y6x/2ZTtmj/Ki5TedKeUcFusC/k" crossorigin="anonymous">

</head>
<body>
	<div class="wrapper">
		<header class="fixed-top">
			<ul class="left_nav">
				<li><a class="font-weight-bold menu-link navProf" data-a='0' href="http://localhost/ednevnik/app/teacher"><span><i class="fas fa-user-graduate"></i></span> Ucenici</a></li>
				<li><a class="font-weight-bold menu-link navProf" data-a='0' href="http://localhost/ednevnik/app/teacher/objects"><span><i class="fas fa-list"></i></span> Predmeti</a></li>
				<li><a class="font-weight-bold menu-link navProf" data-a='0' href="http://localhost/ednevnik/app/teacher/grade/"><span><i class="fas fa-book"></i></span> Dnevnik</a></li>
				<li><a class="font-weight-bold menu-link navProf" data-a='0' href="http://localhost/ednevnik/app/teacher/messages"><span><i class="fa fa-envelope"></i></span> Poruke</a></li>
				<li><a class="font-weight-bold menu-link navProf" data-a='0' href="http://localhost/ednevnik/app/teacher/open"><span><i class="fas fa-handshake"></i></span> Otvorena vrata</a></li>
				<li><a class="font-weight-bold menu-link navProf" data-a='0' href="http://localhost/ednevnik/app/teacher/schedule"><span><i class="fas fa-clipboard-list"></i></span> Raspored casova</a></li>
				<li><a class="font-weight-bold menu-link navProf" data-a='0' href="http://localhost/ednevnik/app/teacher/excuse"><span><i class="fas fa-clipboard-list"></i></span> Opravdanja</a></li>
			</ul>
				<ul class="right_nav">
				<li><a id='logout' class="font-weight-bold menu-link navProf" href="<?php echo URLROOT; ?>/teacher/logout">Izloguj se <span><i class="fas fa-sign-out-alt"></i></span></a></li>
			</ul>
		</header>
		</div><!-- end .wrapper -->
	
	