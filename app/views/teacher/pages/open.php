<br>
<div>
<?php if($this->data['time']!=null): ?>
   <h1 class="text-center">Otvorena vrata: <span class="h4"><?php echo substr($this->data['time'],0,-3); ?>h</span></h1>
<?php endif; ?>

<div class="col-md-12">
  <form action="<?php echo URLROOT; ?>/professor/open_create/"  method='get'>
    <div class="form-group col-md-4 mx-auto text-center">
      <label for="date">Zakazi otvorena vrata: </label>
      <input class="form-control" type="datetime-local" name="date" id="date">
    </div>
    <div class="form-group col-md-4 mx-auto">
      <input class="form-control btn btn-info" type="submit" value="Zakazi">
    </div>
  </form>
</div>
<table class="table table-striped col-md-6 mx-auto mt-5 open_table_prof">
  <thead  style="border-bottom:1px solid #000;border-top:1px solid #000;">
    <tr>
      <th  style="border:none;">IME</th>
      <th  style="border:none;">STATUS</th>
    </tr>
  </thead>
  <tbody>
<?php
echo '<tr>';
//print_r($this->data['open']);



   
foreach($this->data['open'] as $parent): 
    
switch($parent['accepted']){
    case 0:
      echo '<td>';
      echo ucfirst($parent['last_name']).' '.ucfirst($parent['first_name']);
      echo "</td>";
      echo "<td><a href='".URLROOT."/teacher/open_yes/".$parent['user_open_id']."'  class='btn btn-success' >Potvrdi</a>";
      echo "<a href='".URLROOT."/teacher/open_no/".$parent['user_open_id']."'  class='btn btn-danger' >Odbij</a>";
      echo '</td>';
      break;
    case 1:
      echo '<td>';
      echo ucfirst($parent['last_name']).' '.ucfirst($parent['first_name']);
      echo "</td>";
      echo "<td class='font-weight-bold open_confirm_prof'>Potvrdjeno!!</td>";
  
      break;
    case 2:
      echo '<td>';
      echo ucfirst($parent['last_name']).' '.ucfirst($parent['first_name']);
      echo '</td>';
      echo "<td class='font-weight-bold open_reject_prof'>Odbijeno!!</td>";

      break;
    default: 
       return;
}

echo '</tr>';
endforeach;




?>
</tbody>
</table>

</div>