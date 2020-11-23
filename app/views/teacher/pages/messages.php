<div id="messagee">
    <div class="wrapper">

        <div id="sendMessage">
        <div id="parents_name" style="text-transform: capitalize;font-size:20px;display:inline-block;text-align:left;"></div> <br>

                <div id="scroll">
                    <div class="container">
                        <div id="message"></div><!-- end #message -->
                    </div>
                </div><!-- end #scroll -->
                <div id="chat">
                    <textarea id ="subject" name="subject" placeholder ="Write something.." rows="4"></textarea><br>
                    <button onclick='ajaxSendMessage();'>Posalji</button>
                    <button onclick='ajax();'>Pregled novih poruka</button>
                </div>
        </div><!-- end #sendMessage -->
        <div id="listUsers">
                <ul> 
                    <div id="parents" style=" display:inline-block;overflow:auto;">
                    <?php foreach($this->data['parents'] as $parents):
                     echo  "<li><div onclick='chat(this.id)' id='p".$parents['id']."' class='clickabile' style='color:#000;width:650px;text-transform: capitalize;'> <b>Roditelj:</b> ". $parents['first_name'] . " " . $parents['last_name'] . "<br><b>Ucenik:</b>  ".$parents['students_first_name']." ".$parents['students_last_name']."<br> </div></li>"; 
endforeach; ?>
       </ul>
    </div><!-- end .wrapper -->
</div><!-- end #message -->
            
           

<script src="<?php echo URLROOT; ?>/assets/teacher/js/messages.js"></script>