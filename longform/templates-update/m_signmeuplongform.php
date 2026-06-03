<?php
$RMail = $_GET["RMail"];
global $wpdb;
global $formpath;

$formpath = "/wp-content/themes/porto-child/LongForm";

$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());
global $signupform;
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/custom-functions/ic_functions.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/custom-php/list.php');
// require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/custom-php/signup_process.php');


        include ($_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/longform/javascript.php');

        $portal="TALENT";
        
        ##signup form - to notify headers, footer...
        $signupform=1;
        
        ic_gen_signup_form_state();
        
        ic_process_signup_form_state();
        
        ic_get_signnup_longform_variables();
             
        
?>




<script language="javascript">


    
    function isvalid(id, t, ctrl, val, id_desc)
    {
        if (t=="S")
        {
        
           
        	if(document.getElementById(id) != null)
            if (document.getElementById(id).value==val || document.getElementById(id).value=="")
            {        
                alert("Field " + id_desc +  " is required, Please input information for it to complete application.");               
	            return false;
            }        
        }
        return true;
    }

    
    function isvalidRegex(id, val, desc ,type,custom_message,not_required)
    {
    

    	
       // These fields are not required, we only check if something is input
       //if( (not_required && $('#'+id).val() == '' ) || (not_required && $('#'+id).val() == val) ) return true;
       
     
     
        switch(type)
        {
	        case 'alpha': var regex = /^[a-zA-Z\x20]{2,100}$/; var desc2 = 'Only characters A thru Z allowed'; break;
	        case 'alphaperiod': var regex = /^[-\.\a-zA-Z.\x20]{2,100}$/; var desc2 = 'Please do not use the following characters: , / ! @ # $ % ^ & * ( ) _ + ? < > "'+" '"; break;
	        case 'month': var regex = /^(0?[1-9]|1[012])$/;  var desc2 = ''; break;
	        case 'year': 
	        var currentYear = new String((new Date).getFullYear());

	        
	        var regex = /^(19[6-9][0-9]|200[0-9]|201[0-9])$/;  var desc2 = ''; break;
	        
	        case 'alphanumeric': var regex = /^[a-zA-Z0-9\x20]{2,100}$/;  var desc2 = 'Only Characters A thru Z and 0 thru 9 Allowed'; break;
	        case 'required': var regex = /^(.|\n){2,100}$/;  var desc2 = ''; break;
	        case 'requiredlong': var regex = /^(.|\n){2,2000}$/;  var desc2 = ''; break;
	        case 'numeric': var regex = /^[0-9]{2,100}$/; var desc2 = 'Only numbers 0 thru 9 alllowed'; break;
	        case 'numericstrict3': var regex = /^[0-9]{3}$/;  var desc2 = ''; break;
	        case 'numericstrict4': var regex = /^[0-9]{4}$/;  var desc2 = ''; break;
	        case 'email': var regex = /^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.(([0-9]{1,3})|([a-zA-Z]{2,3})|(aero|coop|info|museum|name))$/;  var desc2 = 'invalid email address' ;break;

 case 'website': var regex = /((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[.\!\/\\w]*))?)/; break;

	        case 'zipcode': var regex = /^((\d{5}-\d{4})|(\d{5})|([A-Z]\d[A-Z]\s\d[A-Z]\d))$/; var desc2 = ''; break;
        }
        
       
        if ( $('#'+id).val() == val || !$('#'+id).val().match(regex))
        {    
        
         
           
             if(!custom_message)  
             {
                alert("Field " + desc + " " + desc2 + " input must be valid to complete application.");
                }
                
             else
             {
                alert(custom_message);       
                }
	         return false;
        }           
               
        return true;  
    }



    function isvalidRadio(id,  id_desc)
    {
       
           
        	if( $("input[name='" + id + "']:checked").val()  == null)            
        	{        
                alert("Field " + id_desc +  " is required, Please input information for it to complete application.");               
	            return false;
            }        
      
        return true;
    }

    function all_required_filed_valid()
    {
            
        
       
         
        
     
        if(!isvalidRegex("First", "firstname" , "First Name" , 'alpha')) { return false; }
        if(!isvalidRegex("Last", "lastname" , "Last Name" , 'alphaperiod')) { return false; }
        
  //      if(!isvalidRegex("ADDRESS", "street number and name" , "Address" , 'alphaperoid')) { return false; }

        if(!isvalidRegex("CITY", "city" , "City" , 'alphaperiod')) { return false; }
        if (!isvalid("State", "S", "TB", "", "State"))    {  return false;  }
        if(!isvalidRegex("ZIP", "zipcode" , "Zip Code" , 'zipcode')) { return false; }
        
//        if(!isvalidRegex("HOME_PHONE_AREA", "area code" , "Home Phone Area" , 'numericstrict3')) { return false; }
  //      if(!isvalidRegex("HOME_PHONE_PREFIX", "123" , "Home Phone Prefix" , 'numericstrict3')) { return false; }
//        if(!isvalidRegex("HOME_PHONE_SUBSCRIBER", "4567" , "Home Phone Subcriber" , 'numericstrict4')) { return false; }       
//        
        if(!isvalidRegex("MOBILE_PHONE_AREA", "area code" , "Mobile Phone Area" , 'numericstrict3')) { return false; }
        if(!isvalidRegex("MOBILE_PHONE_PREFIX", "123" , "Mobile Phone Prefix" , 'numericstrict3')) { return false; }
        if(!isvalidRegex("MOBILE_PHONE_SUBSCRIBER", "4567" , "Mobile Phone Subcriber" , 'numericstrict4')) { return false; }
        if (!isvalid("EM_USER_DEF4", "S", "TB", "", "Cell Carrier"))    {  return false;  }
        
        if(!isvalidRegex("Email", "email address" , "Email" , 'email','You must input a valid email address')) { return false; }
      //  if(!isvalidRegex("PAST1_JOB_TITLE", "current job title" , "Current Job Title" , 'alphaperiod')) { return false; }
      
      //  if (!isvalidRadio("AVAIL_TEMP",  "Work Preferences"))    {  return false;  }
     //   if (!isvalidRadio("AVAIL_CAREER",  "Work Preferences"))    {  return false;  }
    //    if (!isvalidRadio("AVAIL_CONTRACT", "Work Preferences"))    {  return false;  }
        
      
        
        if(!isvalidRegex("PAST1_COMPANY", "company name" , "Job 1 Company name" , 'required')) { return false; }
      	if(!isvalidRegex("PDF_PAST1_URL", "company url" , "Job 1 URL" , 'website')) { return false; }
	if(!isvalidRegex("PAST1_CITY", "city" , "Job 1 City" , 'alphaperiod')) { return false; }
        if(!isvalid("PAST1_STATE", "S", "TB", "", "Job 1 State"))    {  return false;  }
        if(!isvalidRegex("PAST1_ZIP", "zipcode" , "Job 1 Zip Code" , 'zipcode',false,true)) { return false; }
        if(!isvalidRegex("PAST1_PHONE_AREA", "area code" , "Job 1 Phone Area" , 'numericstrict3',false,true)) { return false; }
        if(!isvalidRegex("PAST1_PHONE_PREFIX", "123" , "Job 1 Phone Prefix" , 'numericstrict3',false,true)) { return false; }
        if(!isvalidRegex("PAST1_PHONE_SUBSCRIBER", "4567" , "Job 1 Phone Subcriber" , 'numericstrict4',false,true)) { return false; }       
        if(!isvalidRegex("PAST1_SUPERVISOR", "supervisor name" , "Job 1 Supervisor Name" , 'alphaperiod')) { return false; }
        if(!isvalidRegex("PAST1_SUPERVISOR_TITLE", "supervisor title" , "Job 1 Supervisor Title" , 'required')) { return false; }
        if(!isvalidRegex("PAST1_JOB_TITLE", "your job title" , "Job 1 Title" , 'required')) { return false; }
 
        if (!isvalid("PAST1_START_MONTH", "S", "TB", "", "Job 1 Start Month"))    {  return false;  }
        if (!isvalid("PAST1_START_YEAR", "S", "TB", "", "Job 1 Start Year"))    {  return false;  }
//        if (!isvalid("PAST1_END_MONTH","S", "TB", "", "Job 1 End Month"))    {  return false;  }   
//        if (!isvalid("PAST1_END_YEAR", "S", "TB", "", "Job 1 End Year"))    {  return false;  }
        
        if(!isvalidRegex("PAST1_JOB_DUTIES", "" , "Job 1 Description" , 'requiredlong')) { return false; }
          
  
 
/* 
         if(!isvalidRegex("PAST2_COMPANY", "company name" , "Job 2 Company Name" , 'required',false,true)) { return false; }     
//        if(!isvalidRegex("PDF_PAST2_URL", "company url" , "Job 2 URL" , 'website',false,true)) { return false; }
        if(!isvalidRegex("PAST2_CITY", "city" , "Job 2 City" , 'alphaperiod',false,true)) { return false; }
        if(!isvalidRegex("PAST2_ZIP", "zipcode" , "Job 2 Zip Code" , 'zipcode',false,true)) { return false; }
//        if(!isvalidRegex("PAST2_PHONE_AREA", "area code" , "Job 2 Phone Area" , 'numericstrict3',false,true)) { return false; }
 //       if(!isvalidRegex("PAST2_PHONE_PREFIX", "123" , "Job 2 Phone Prefix" , 'numericstrict3',false,true)) { return false; }
 //       if(!isvalidRegex("PAST2_PHONE_SUBSCRIBER", "4567" , "Job 2 Phone Subcriber" , 'numericstrict4',false,true)) { return false; }       
        if(!isvalidRegex("PAST2_SUPERVISOR", "supervisor name" , "Job 2 Supervisor Name" , 'alphaperiod',false,true)) { return false; }
        if(!isvalidRegex("PAST2_SUPERVISOR_TITLE","supervisor title" ,"Job 2 Supervisor Title" , 'required',false,true)) { return false; }
        if(!isvalidRegex("PAST2_JOB_TITLE", "your job title" , "Job 2 Title" , 'required',false,true)) { return false; }
        
        if(!isvalidRegex("PAST2_START_MONTH", 'mo' , "Job 2 Start Month" , 'month','Job 2 Start Month input is invalid',true)) { return false; }
        if(!isvalidRegex("PAST2_START_YEAR", 'year start' , "Job 2 Start Year" , 'year','Job 2 Start Year input is invalid, must be an year between 1960 to present',true)){ return false; }  
        
        if(!isvalidRegex("PAST2_JOB_DUTIES", "" , "Job 2 Description" , 'requiredlong',false,true)) { return false; }
        
    
    
    
        if(!isvalidRegex("PAST3_COMPANY", "company name" , "Job 3 Company Name" , 'required',false,true)) { return false; }
//        if(!isvalidRegex("PDF_PAST3_URL", "company url" , "Job 3 URL" , 'website',false,true)) { return false; }
        if(!isvalidRegex("PAST3_CITY", "city" , "Job 3 City" , 'alphaperiod',false,true)) { return false; }
        if(!isvalidRegex("PAST3_ZIP", "zipcode" , "Job 3 Zip Code" , 'zipcode',false,true)) { return false; }
//        if(!isvalidRegex("PAST3_PHONE_AREA", "area code" , "Job 3 Phone Area" , 'numericstrict3',false,true)) { return false; }
 //       if(!isvalidRegex("PAST3_PHONE_PREFIX", "123" , "Job 3 Phone Prefix" , 'numericstrict3',false,true)) { return false; }
  //      if(!isvalidRegex("PAST3_PHONE_SUBSCRIBER", "4567" , "Job 3 Phone Subcriber" , 'numericstrict4',false,true)) { return false; }       
        if(!isvalidRegex("PAST3_SUPERVISOR", "supervisor name" , "Job 3 Supervisor Name" , 'alphaperiod',false,true)) { return false; }
        if(!isvalidRegex("PAST3_SUPERVISOR_TITLE","supervisor title" ,"Job 3 Supervisor Title" , 'required',false,true)) { return false; }
        if(!isvalidRegex("PAST3_JOB_TITLE", "your job title" , "Job 3 Title" , 'required',false,true)) { return false; }
        
        if(!isvalidRegex("PAST3_START_MONTH", 'mo' , "Job 3 Start Month" , 'month','Job 3 Start Month input is invalid',true)) { return false; }
        if(!isvalidRegex("PAST3_START_YEAR", 'year start' , "Job 3 Start Year" , 'year','Job 3 Start Year input is invalid, must be an year between 1960 to present',true)){ return false; }  

        if(!isvalidRegex("PAST3_JOB_DUTIES", "" , "Job 3 Description" , 'requiredlong',false,true)) { return false; }
*/        
        
        
         if(!isvalidRegex("EDUC1_SCHOOL_NAME", "educational institution" , "Education 1 School Name" , 'required')) { return false; }
        if(!isvalid("EDUC1_DEGREE", "S", "TB", "", "Education 1 Degree"))    {  return false;  }                         
        if(!isvalidRegex("EDUC1_CITY", "city" , "Education 1 City" , 'alphaperiod')) { return false; }
        if(!isvalid("EDUC1_STATE", "S", "TB", "", "Education 1 State"))    {  return false;  }   
        if (!isvalid("EDUC1_END_MONTH", "S", "TB", "", "Education 1 Month"))    {  return false;  }
        if (!isvalid("EDUC1_END_YEAR", "S", "TB", "", "Education 1 Year"))    {  return false;  }                      
        if(!isvalidRegex("EDUC1_MAJOR", "field of study" , "Education 1 Major" , 'required')) { return false; }
        
        
        // if(!isvalidRegex("EDUC2_SCHOOL_NAME", "educational institution" , "Education 2 School Name" , 'required',false,true)) { return false; }                        
        // if(!isvalidRegex("EDUC2_CITY", "city" , "Education 2 City" , 'alphaperiod',false,true)) { return false; }  
        
                        
        //  if(!isvalidRegex("EDUC2_MAJOR", "field of study" , "Education 2 Major" , 'required',false,true)) { return false; }
             


        

        return true;
        
    }



    function submit_app()
    {
    
        var item_number;
        var radio_field;
        var cbo_field;
        var text_field;
        var field;
        var prm;
        var i;
        var url;
        var ajax_db_app;
        var httpxml;
        
        
        
        
        ajax_db_app="<?php echo CURRENTDOMAIN . '/wp-content/custom-php/ajax/ajax.php'; ?>";
        item_number = document.getElementById("item_number").value;
        prm = "item_number=" + item_number;


        cbo_field = document.getElementById("cbo_field").value;
        text_field = document.getElementById("text_field").value;
        radio_field = document.getElementById("radio_field").value;
        
        
        
        if (all_required_filed_valid())
        {
            field = cbo_field;

            var ida = field.split(";");
            for(i = 0; i < ida.length; i++)
            {
                               
                val = $("#" + ida[i]).val();
                if (val!="undefined")
                {
                    prm = prm + "&" + ida[i] + "=" + encodeURIComponent(val);
                }
            }

            field = text_field;
            var ida = field.split(";");

            for(i = 0; i < ida.length; i++)
            {
                
                val = $("#" + ida[i]).val();
                if (val!="undefined")
                {
                    prm = prm + "&" + ida[i] + "=" + encodeURIComponent(val);
                }
            }

            
            field = radio_field;
            var ida = field.split(";");
            for(i = 0; i < ida.length; i++)
            {
                val = $("input[name='" + ida[i] + "']:checked").val();
                if (val!="undefined")
                {
                    prm = prm + "&" + ida[i] + "=" + encodeURIComponent(val);
                }
            }
            
            prm = prm + "&current_state=" + document.getElementById("current_state").value;
           
            //move to top then show dialog
            window.location.hash="top1"
            waitingDialog({});            
            $.ajax({
                type: "POST",  
                data: prm,
                url: ajax_db_app,
				
                success: function( data ) 
                {                   
					//closeWaitingDialog();
					alert(data); 
                    if (data=="OK")
                    {   //next step                              
                        document.signupform.submit();
                    }
                    else
                    {					
                        alert("Invalid character in data field (most likely a field was pasted from word document with hidden characters)");
                    }            
                }
                });
    
        }        
        return;
    }
    
    
    
    


    
</script>
<html>
<head>
<BODY>
	
	<link href='https://fonts.googleapis.com/css?family=Lato|Rokkitt' rel='stylesheet' type='text/css' />
	<link href='https://fonts.googleapis.com/css?family=Lato:400normal,light,regular,bold,bolditalic|Rokkitt:regular,bold,bolditalic' rel='stylesheet' type='text/css' />
	<link href="/webtime/css/style.css" rel="stylesheet" type="text/css" />

<style>

.radio {
	background:url(/webtime/css/images/radio.png) no-repeat;
}
.select {
	position: absolute;
	height:23px;
	width:auto;
	padding: 0px 54px 0px 7px;
	color: #fff;
	font-family:'Lato', 'sans-serif';
	font-size:11px;
	background:url(/webtime/css/images/dropdown_img.png) no-repeat right;
	line-height:24px;
	overflow: hidden;
}
.styled {
    position: relative;
    width: 190px;
    opacity:1 !important;
    filter: alpha(opacity=1);
    z-index: 5;
    }
</style>
    <link rel="stylesheet" type="text/css" href="/wp-content/themes/twentyten/css/css.css" />

    <script type="text/javascript" src="/webtime/css/js.js"></script>
    <script type="text/javascript" src="/webtime/css/jquery.js"></script>
    <script type="text/javascript" src="/webtime/css/custom-form-elements.js"></script>
    
<style type="text/css"> 
body {background:#ffffff;} 
</style>


        <?php include($_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/longform/templates/m_signmeuplongform.tpl.php'); ?>
                
    





        
            








 


                        
            



