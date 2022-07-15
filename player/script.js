var editbutton = document.getElementById('edit');
var readonly = true;
var inputs = document.querySelectorAll('input[type="text"]');
var selects = document.getElementsByTagName('select');

editbutton.addEventListener('click',function(){

  if (editbutton.innerHTML=="edit" ) {
    document.getElementById('lastNameRow').setAttribute('style', 'margin-top:32px !important');
  } else { 
    document.getElementById('lastNameRow').setAttribute('style', 'margin-top:0');
  } 
});