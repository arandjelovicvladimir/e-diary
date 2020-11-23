


<div id='wrapper_home_parent'>


<?php

$sub="";
$name="";

foreach($this->data['grades'] as $subject){
   
    if($name!==$subject['first_name'])
        echo '<br><br><h1>'.ucfirst($subject['first_name']).' '.ucfirst($subject['last_name']).'</h1>';
    
    if( $sub===$subject['name'] && $name===$subject['first_name']){
        echo "<div class='grades_home_parent'> ".$subject['grades']."</div>";

    }
    else
        echo "<br><div class='subject_home_parent'>".ucfirst($subject['name'])."</div><div class='grades_home_parent'> ".$subject['grades']."</div>";

    
    $sub=$subject['name'];
    $name=$subject['first_name'];
   
}

?>
</div>

