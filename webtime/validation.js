// ----------------------------------------------------------------------
// Javascript validation fields.
// Author: Tai Tran - i creatives
//
// www.icreatives.com
//
// Created April 2009: using in www.icu.icreatives.com
// ----------------------------------------------------------------------


//------------------------------------------------------------------------------
// Validate text field with min length and max length
//------------------------------------------------------------------------------
function validateField(fld, minlength, maxlength) {
    var error = "";    
 
    if (fld.value == "") {
        fld.style.background = 'Yellow'; 
        error = "You didn't enter a  " + fld.name + ".\n";
        //fld.focus();
    } else if ((fld.value.length < minlength) || (fld.value.length > maxlength)) {
        fld.style.background = 'Yellow'; 
        error = fld.name + " is the wrong length.\n";
    } else {
        fld.style.background = 'White';
    } 
    return error;
}

//------------------------------------------------------------------------------
// Validate phone number field
//------------------------------------------------------------------------------
function validatePhone(fld) {
  var error = "";
  
  var tfld = trim(fld.value);  // value of field with whitespace trimmed off
  var telnr = /^\+?[0-9 ()-]+[0-9]$/  ;    
  var stripped = fld.value.replace(/[\(\)\.\-\ ]/g, '');  
  
 if (fld.value == "") {
        error = "You didn't enter a phone number.\n";
        fld.style.background = 'Yellow';
    } 
    //else if (isNaN(parseInt(stripped))) {
    else if (!telnr.test(tfld)) {
        error = "The phone number contains illegal characters.\n";
        fld.style.background = 'Yellow';
    } 
    else if (!(stripped.length == 10)) {
        error = "The phone number is the wrong length. Make sure you included an area code.\n"
        fld.style.background = 'Yellow';
    } 
    else {
 	  fld.style.background = 'White';}
    return error;
}


function trim(s)
{
  return s.replace(/^\s+|\s+$/, '');
} 

//------------------------------------------------------------------------------
// Validate Email field
//------------------------------------------------------------------------------
function validateEmail(fld) {
    var error="";
    var tfld = trim(fld.value);                        // value of field with whitespace trimmed off
    var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
    var illegalChars= /[\(\)\<\>\,\;\:\\\"\[\]]/ ;
    
    if (fld.value == "") {
        fld.style.background = 'Yellow';
       error = "You didn't enter an email address.\n";     
    } else if (!emailFilter.test(tfld)) {              //test email for illegal characters
        fld.style.background = 'Yellow';
        error = "Please enter a valid email address.\n";
    } else if (fld.value.match(illegalChars)) {
        fld.style.background = 'Yellow';
        error = "The email address contains illegal characters.\n";       
    } else {
        fld.style.background = 'White';
    }
    return error;
}

//------------------------------------------------------------------------------
// Validate Zip code field with format: xxxxx or xxxxx-xxxx
//------------------------------------------------------------------------------
function validateZipCode(fld) {
	var error ="";	
	var re =/^\d{5}$|^\d{5}-\d{4}$/;	
	 if (fld.value == "") {
		 fld.style.background = 'Yellow';
       	error= "You didn't enter a Zip code.\n";   
       }
       else if (!fld.value.match(re)){
		fld.style.background = 'Yellow';
	      error = "Please enter a valid Zip code.\n";
	}
	else {fld.style.background = 'White';}
	return error;
}

//------------------------------------------------------------------------------ 
// Validate list box 
//------------------------------------------------------------------------------ 
function validateListBox(fld) {   
	var error ="";	 
	if (fld.selectedIndex == 0) { 
		if (navigator.appName == "Netscape") {error= "You didn't pick a " + fld.name + ".\n";} 
		else {error= "You didn't pick a " + fld.Desc + ".\n";} 
		fld.style.background = 'Yellow'; 
       } 
	else {fld.style.background = 'White';} 
	return error; 
} 