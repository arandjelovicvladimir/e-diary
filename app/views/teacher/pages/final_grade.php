<div id="addGrade">
        <div class="wrapper">
            <form action="<?php echo 'http://localhost/ednevnik/app/teacher/save_final_grade/' . $this->data['id_students']['id'];?>" method="POST">
                <table>
                    <tr>
                        <td>Ime ucenika</td>
                        <td><input type="text" name="first_name" value="<?php echo $this->data['id_students']['first_name']; ?>" readonly /></td>
                    </tr>
                    <tr>
                        <td>Prezime ucenika</td>
                        <td><input type="text" name="last_name"  value="<?php echo $this->data['id_students']['last_name'];?>" readonly /> </td>
                    </tr>
                    <tr>
                        <td>Naziv predmeta</td>
                        <td id="list_subjects">
                            <select name="id_subjects">
                                <?php foreach($this->data['subjects'] as $subjects): ?>
                                <option value="<?php echo $subjects['id'];?>"><?php echo $subjects['name'];?></option>
                                <?php endforeach; ?>
                            </select> 
                        </td> 
                    </tr>
                    <tr>
                        <td>Ocena</td>
                        <td id="grade">
                            <select name="grade">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="tdSubmit" style="border:none;"><input type="submit" name="add" value="Zakljuci ocenu"></td>
                    </tr> 
                </table>
            </form>
    </div><!-- end .wrapper -->
</div><!-- end #addGrade -->