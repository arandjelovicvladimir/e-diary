<style>
td::first-letter{
    text-transform:uppercase;
    text-align:left;
}
table{
    text-align: left;
}
</style>
<?php
//print_r($this->data['schedule']);

echo "<br>"; 
$days = ["", "ponedeljak", "utorak", "sreda", "cetvrtak", "petak"];
echo "<table class='table table-bordered table-dark table-hover col-md-10 mx-auto scheduleTeacher'>";
for($i=0; $i<8; $i++){ 
echo"<tr>";
    for($y=0; $y<6; $y++){
        $has_class = false;
        foreach($this->data['schedule'] as $term){ 
            if($term['day_in_week'] == $y && $term['lesson_no'] == $i){
                $class = $term['name'];
                $has_class = true;
            } 


        }
        if($has_class == true)
        echo "<td>" . $class . "</td>";
        else 
        if($i == 0 && $y == 0)
        echo "<th> &nbsp; </th>";
        else if($i == 0 && $y != 0)
        echo "<th class='text-center h4 text-warning'>" . ucfirst(next($days)) . "</th>";
        else if($y == 0)
        echo "<td class='lessonNO text-info'> $i </td>";
        else 
        echo "<td> &nbsp; </td>";
    } 
    echo "</tr>"; 
} 
echo "</table>"; 





