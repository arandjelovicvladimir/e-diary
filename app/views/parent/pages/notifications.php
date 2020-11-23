<!-- <br>

<div class="container"> -->
<?php
// foreach(array_reverse($this->data['news']) as $news){
// echo "<div class='col-md-6 bg-info m-2 p-2 font-weight-bold text-center mx-auto notification'>".$news['notifications']."</div>";
// }
?>
<!-- </div> -->



<br>

<div class="container">
<?php
 foreach(array_reverse($this->data['news']) as $news){
 $notification = <<<DELIMITER

 <div class='col-md-8 bg-dark text-center notification m-2 p-2 mx-auto rounded'>
<h4 class='font-weight-bold text-warning'>{$news['notifications']}</h4>
</div>

DELIMITER;

 echo $notification;
 }
?>


</div> 