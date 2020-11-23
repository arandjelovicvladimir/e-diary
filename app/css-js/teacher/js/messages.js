window.addEventListener('load', ajax);
window.addEventListener('load', parents);
function ajax(){
  var parents_name = document.getElementById("parents_name");
  parents_name.innerHTML = '';
  var message = document.getElementById("message");
  message.innerHTML = "";
  
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200){
     var a=JSON.parse(this.responseText);
     for(i in a){ 
        var div = document.createElement("div");
        var p = document.createElement("p");
        var parentName = document.createElement("p");
         var data_time = document.createElement("p");

        var id = a[i]["id"];
        var user = a[i]["user"];
        user = "p"+user;
        var message_body = a[i]["message"];
        var date = a[i]["date_and_time"];
        var last_name = a[i]["last_name"];
			  var first_name = a[i]["first_name"];
        var is_read = a[i]["is_read"];	
        parentName.innerHTML+=last_name+" "; 
        parentName.innerHTML+=first_name;
        p.innerHTML+="<br>"; 
        p.innerHTML+=message_body;
        p.innerHTML+="<br>";   
        data_time.innerHTML+=date; 
        data_time.innerHTML+="<br>";
        div.setAttribute('data-id',user);
        //div.setAttribute('class',user); 
        div.setAttribute('class','clickabile'); 
        div.setAttribute('style',"background-color:#f1f1f1;color:#000;padding:8px;border-bottom: 4px solid rgba(4,15,53,0.47);border-right: 4px solid rgba(4,15,53,0.47);border-radius:10px;margin-top:8px;overflow: hidden;");
        parentName.setAttribute('style', "text-transform:capitalize;margin:0;border-bottom:1px solid #000;text-align: center;font-size:20px;font-weight:bold;");
        p.setAttribute('style', 'font-size:22px;border-bottom:1px solid black;');
        data_time.setAttribute('style', 'font-size:14px;float:right;margin:0;padding:2px;font-weight:bold;font-style:italic;');
        div.setAttribute('onclick', "isRead(this.id)");
        div.setAttribute('onclick', "chat(this.dataset.id)");
        div.setAttribute('id','c'+id);
        div.append(parentName);
        div.append(p);
        div.append(data_time);
        message.prepend(div);
      };
    } 
  }; 
  xhttp.open("GET", "http://localhost/eDiary/task1/teacher/ajax/", true);
  xhttp.send();
}

function isRead(id) {
  var send_id = id.substr(1);
  var xhttp = new XMLHttpRequest(); 
  xhttp.onreadystatechange = function() {
    if(this.readyState == 4 && this.status == 200){
      var a = JSON.parse(this.responseText);
    
      if(a['response']){
        var message= document.getElementById(id);
        if(message.style.backgroundColor!="gold")
          message.style="background-color:silver;border-radius:10px;min-height:50px;width:200px;margin-top:5px;margin-left:70px;";
      }
    } 
  }; 

  xhttp.open("GET", "http://localhost/ednevnik/app/teacher/ajax_is_read?id="+send_id, true);
  xhttp.send();
}

function chat(id){
  var parents_name = document.getElementById("parents_name");
  var parent = document.getElementById(id);
  if( parent != null)
  parents_name.innerHTML = parent.innerHTML;
  
  id=id.substr(1);
  var subject = document.getElementById("subject");
  subject.className = id;
  var message = document.getElementById("message");
  message.innerHTML = "";
  var xhttp = new XMLHttpRequest(); 
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200){
      var a = JSON.parse(this.responseText);
      for(i in a){  
        var div = document.createElement("div");
        var p = document.createElement("p");
        var msg_id = a[i]["id"];
		    var message_body = a[i]["message"];
        p.innerHTML += message_body;	     
        p.innerHTML += "<br>";
        var p1 = document.createElement("p");
        var date = a[i]["date_and_time"];
        var is_read = a[i]["is_read"];
        p1.innerHTML += date;
        div.setAttribute('style', "background-color:red;border-radius:10px;width:200px;");
        p.setAttribute('style', "font-size:20px;border-bottom:1px solid #000;");
        p1.setAttribute('style', "font-size:12px;float:right;margin:0;padding:2px;font-weight:bold;font-style:italic;");
        if(a[i]["from_user"] == id){
          div.setAttribute('onclick', 'isRead(this.id)');
          div.setAttribute('style', "background-color:silver;border-radius:10px;;width:200px;margin-top:5px;margin-left:70px;");
          if(is_read == 0){
            //for incoming and outgoing messages
            div.setAttribute('class', 'clickabile'); 
            div.setAttribute('style',"background-color:#dedede;border:1px solid #000;border-radius:10px;width:400px;margin:5px 0;padding:15px;float:right;");
          }
        }else{
          div.setAttribute('style',"background-color:rgba(0,0,0,0.25);color:#000;padding:15px;border:1px solid #000;border-radius:10px;width:400px;margin-top:5px;float:left;");
 
        }
          div.setAttribute('id','d' + msg_id);
          div.append(p);
          div.append(p1);
          message.append(div);
      };
    
      }
    };
   xhttp.open("GET", "http://localhost/ednevnik/app/teacher/ajax_chat?id="  + id, true); 
   xhttp.send(); 
 } 


function  ajaxSendMessage(){
  var parents_name = document.getElementById("parents_name").innerHTML;
  if(parents_name == ''){
    alert('Niste izabrali primaoca!');
  return;
}
  var msg = document.getElementById("subject");
  var message = document.getElementById("subject").value;
  var parent = msg.className;
  var id = "a" + parent;
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var a=JSON.parse(this.responseText);
      msg.value = '';
      chat(id);
    } 
  }; 
  
  xhttp.open("GET", "http://localhost/ednevnik/app/teacher/ajax_send_message?message="+message+"&id="+parent, true); 
  xhttp.send(); 
}


