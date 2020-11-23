window.addEventListener('load', ajax);
window.addEventListener('load', parents);
function ajax() {
  var parents_name= document.getElementById("parents_name");
  parents_name.innerHTML='';
    var message= document.getElementById("message");
    message.innerHTML="";
  // alert('aaa');
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     var a=JSON.parse(this.responseText);
     for(i in a)
		{ 
          
      var div=document.createElement("div");
      var p=document.createElement("p");
      var id=a[i]["id"];
      var user=a[i]["user"];
      user="p"+user;
			var message_body=a[i]["message"];
			var date=a[i]["date_and_time"];
      var last_name=a[i]["last_name"];
      var first_name=a[i]["first_name"];	
      last_name=last_name.charAt(0).toUpperCase() + last_name.slice(1);
      first_name=first_name.charAt(0).toUpperCase() + first_name.slice(1);        
      p.innerHTML+=message_body;
      p.innerHTML+="<br>";   
      p.innerHTML+=date; 
      p.innerHTML+="<br>";   
      p.innerHTML+=last_name+" "; 
      p.innerHTML+=first_name;   
      div.setAttribute('data-id',user); 
      div.setAttribute('class','clickabile newMessage'); 
      div.setAttribute('onclick',"isRead(this.id)");
      div.setAttribute('onclick',"chat(this.dataset.id)");
      div.setAttribute('id','c'+id);
      div.append(p);
      message.prepend(div);
            
			
			
        };
    }
  };
  xhttp.open("GET", "http://localhost/eDiary/task1/parent/ajax/", true);
  xhttp.send();
}

function isRead(id) {
  var send_id=id.substr(1);
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var a=JSON.parse(this.responseText);
    
      if(a['response']){
        var message= document.getElementById(id);
        if(message.style.backgroundColor!="gold")
          message.setAttribute('class','isRead');
          message.style="background-color:silver;border-radius:10px;min-height:50px;width:200px;margin-top:5px;margin-left:130px;padding:5px;";
     }
    }
  };
  xhttp.open("GET", "http://localhost/ednevnik/app/parent/ajax_is_read?id="+send_id, true);
  xhttp.send();
}


function chat(id) {
  
  var parents_name= document.getElementById("parents_name");
  var parent= document.getElementById(id);
  if( parent!=null)
    parents_name.innerHTML=parent.innerHTML;
   id=id.substr(1);
   var subject= document.getElementById("subject");
   subject.className =id;
   var message= document.getElementById("message");
   message.innerHTML="";
   var xhttp = new XMLHttpRequest();
   xhttp.onreadystatechange = function() {
     if (this.readyState == 4 && this.status == 200) {
        var a=JSON.parse(this.responseText);
        for(i in a)
      {  
              
          var div=document.createElement("div");
          var p=document.createElement("p");
          var msg_id=a[i]["id"];
          var message_body=a[i]["message"];
          var date=a[i]["date_and_time"];
          var is_read=a[i]["is_read"];	        
          p.innerHTML+=message_body+"<br>";
          p.innerHTML+=date;      
          div.setAttribute('style',"background-color:silver;border-radius:10px;min-height:50px;width:200px;margin-top:5px;padding-left:5px;");
        
          if(a[i]["from_user"]==id){
            div.setAttribute('onclick','isRead(this.id)');
            div.setAttribute('style',"background-color:silver;border-radius:10px;min-height:50px;width:200px;margin-top:5px;margin-left:130px;padding-left:5px;");
            if(is_read==0){
              div.setAttribute('class','clickabile');
              div.setAttribute('style',"background-color:#44ff3d;border-radius:10px;min-height:50px;width:200px;margin-top:5px;margin-left:130px;padding-left:5px;");
            }
          }
          else{
            div.setAttribute('style',"background-color:gold;border-radius:10px;min-height:50px;width:200px;margin-top:5px;padding-left:5px;");
            
          }
          div.setAttribute('id','d'+msg_id);
          div.append(p);
          message.append(div);
           
			
        };
     
     }
   };
   xhttp.open("GET", "http://localhost/ednevnik/app/parent/ajax_chat?id="+id, true);
   xhttp.send();
 }


function ajaxSendMessage(){
  var parents_name= document.getElementById("parents_name").innerHTML;
  if(parents_name==''){
    alert('Niste izabrali primaoca');
    return;
  }
  var msg= document.getElementById("subject");
  var message= document.getElementById("subject").value;
  if(message=='')
    return;
  var parent=msg.className;
  var id="a"+parent;
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var a=JSON.parse(this.responseText);
      msg.value='';
      chat(id);  
    
      
    } 
  }; 
  
  xhttp.open("GET", "http://localhost/ednevnik/app/parent/ajax_send_message?message="+message+"&id="+parent, true);
  xhttp.send();
  
  
}