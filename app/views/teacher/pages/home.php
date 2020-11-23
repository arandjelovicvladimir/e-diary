<div id="teachers">
		<div class="wrapper">
        	<div id="teacher">
				<?php foreach($this->data['class'] as $class): ?>
					<h1 class="text-center mb-4 font-weight-bold text"><?php echo $class['name'] ?> </h1>
				<?php endforeach; ?>
                	
				<table class="table table-hover table-dark table-striped  mx-auto table-home">
					<tr>
						<th class="text-warning text-center h4 font-weight-bold"><i class="fa fa-user"></i> Ime</th>
						<th class="text-warning text-center h4 font-weight-bold"><i class="fas fa-file-alt"></i> Svedocanstvo</th>
					</tr>
					
				<?php foreach($this->data['students'] as $student): ?>
					<tr>
						<td class="name_student" class="text-center font-weight-bold"><span><?php echo $student['first_name']; ?></span> <span><?php echo $student['last_name']; ?></span></td>
						<td class="text-center"><a id="testimony" class="btn btn-outline-light font-weight-bold" href="<?php echo URLROOT.'/teacher/success/' . $student['id'].''?>" >Svedocanstvo</a>
					
						<?php if(isset($_GET['err']) && $_GET['id'] == $student['id']){
							echo '<br><span style="color:red;font-size:20px;">' . $_GET['err'] . '</span>'; }?>
						</td>
					</tr>
					
					<?php endforeach; ?>
			</table>

