function validateForm() {
    var x = document.forms["myForm"]["student"].value;
    var y = document.forms["myForm"]["excuse"].value;
    if (x == "" || y== "") {
      alert("Morate izabrati sliku i ucenika");
      return false;
    }
  }