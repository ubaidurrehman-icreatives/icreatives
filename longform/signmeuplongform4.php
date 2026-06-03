<?php
 session_start();

function renderStartDateInput($fieldName = 'start_date') {
    $today = date('Y-m-d');
    $threeMonthsFromNow = date('Y-m-d', strtotime('+3 months'));

    // echo '<label for="' . $fieldName . '">Start Date:</label>';
    echo '<input type="date" id="' . $fieldName . '" name="' . $fieldName . '" min="' . $today . '" max="' . $threeMonthsFromNow . '">';
}
           global $signupform;
        global $valid_step;
	global $wpdb;
	global $formpath;
	$formpath = "/longform";   
	$RMail = $_POST['RMail'];         
        
        $valid_step=false;
require_once __DIR__ . '/../db/db.php';
$link = db();   
       global $signupform;

        require_once(dirname(__DIR__) . '/longform/functions/form-functions.php');
        require_once(dirname(__DIR__) . '/longform/functions/list.php');
        include (dirname(__DIR__) . '/longform/javascript.php');



        
        ##signup form - to notify headers, footer...
        $signupform=1;
        
        ic_gen_signup_form_state();
        
        ic_process_signup_form_state();
        
        ic_get_signnup_longform_variables();
        
  /*      if ($valid_step==false)
        {
            ##refirect to step 1     
           header( 'Location:'. CURRENTDOMAIN .'/sign-me-up-long-form/') ;
            exit;
        }
        else
        {
            get_header(); 

            get_sidebar();     
        }
       */ 
        
?>
<script>
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".accordion-header").forEach(function (header) {
    header.addEventListener("click", function () {
      const content = header.nextElementSibling;
      header.classList.toggle("active");
      content.style.display = content.style.display === "block" ? "none" : "block";
    });
  });
});
</script>

<script language="javascript">

   
    


    function all_required_filed_valid()
    {
        //if (!isvalid("firstname", "S", "TB", "firstname"))  { return false; }
        
        //if (!isvalid("lastname", "S", "TB", "lastname"))    { return false;  }
        
        //if (!isvalid("nickname", "S", "TB", "nickname"))    {  return false;  }
        
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
        
        ajax_db_app="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/wp-content/themes/porto-child/longform/functions/ajax/ajax.php'; ?>";
        item_number = document.getElementById("item_number").value;
        prm = "item_number=" + item_number;

        
        cbo_field = document.getElementById("cbo_field").value;
        text_field = document.getElementById("text_field").value;
        radio_field = document.getElementById("radio_field").value;

       

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
       if(not_required && $('#'+id).val() == val) return true;
  
       
        switch(type)
        {
	        case 'alpha': var regex = /^[a-zA-Z\x20]{2,100}$/; var desc2 = 'Only characters A thru Z allowed'; break;
	        case 'month': var regex = /^(0?[1-9]|1[012])$/;  var desc2 = ''; break;
	        case 'year': 
	        var currentYear = new String((new Date).getFullYear());

	        
	        var regex = /^(19[6-9][0-9]|200[0-9]|201[0-9])$/;   var desc2 = ''; break;
	        
	        case 'alphanumeric': var regex = /^[a-zA-Z0-9\x20]{2,100}$/; var desc2 = 'Only Characters A thru Z and 0 thru 9 allowed,'; break;
	        case 'required': var regex = /^(.|\n){2,100}$/;   var desc2 = ''; break;
	        case 'requiredlong': var regex = /^(.|\n){2,2000}$/; var desc2 = ''; break;
	        case 'numeric': var regex = /^[0-9]{2,100}$/; var desc2 = 'Must be numeric digits 0 thru 9 only, no ($/.,),'; break;
	        case 'numericstrict3': var regex = /^[0-9]{3}$/; var desc2 = ''; break;
	        case 'numericstrict4': var regex = /^[0-9]{4}$/; var desc2 = ''; break;
		case 'resume': var regex = /^.*\.(doc|docx|rtf)$/i;  var desc2 = ''; break;
	        case 'email': var regex = /^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.(([0-9]{1,3})|([a-zA-Z]{2,3})|(aero|coop|info|museum|name))$/; var desc2 = ''; break;

  		case 'website': var regex = /((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[.\!\/\\w]*))?)/;  var desc2 = ' Simple websites no http:// and no "/?=&'; break;


// 	        case 'website': var regex = /^((https:\/\/www\.)|(www\.)|(http:\/\/))[a-zA-Z0-9._-]+\.[a-zA-Z.]{2,5}$/; break;

// 	        case 'website': var regex = /^(https?):\/\/(?:[A-Z0-9-]+\.)+[A-Z]{2,6}([\/?].+)?$/i; break;

// 	        case 'website': var regex = /^((http:\/\/www\.)|(www\.)|(http:\/\/))[a-zA-Z0-9._-]+\.[a-zA-Z.]{2,5}$/; break;

//		case 'website': var regex = "/^".
//		  "(?:http:\\/\\/)?".  // Look for http://, but make it optional.
//		  "(?:[A-Z0-9][A-Z0-9_-]*(?:\\.[A-Z0-9][A-Z0-9_-]*))". // Server name
//		  "(?:\\d+)?".         // Optional port number
//		  "(?:\\/\\.*)?/i";  break;  // Optional training forward slash and page info

	        case 'zipcode': var regex = /^((\d{5}-\d{4})|(\d{5})|([A-Z]\d[A-Z]\s\d[A-Z]\d))$/; var desc2 = '';break;
        }
        
       
        if ( $('#'+id).val() == val || !$('#'+id).val().match(regex))
        {    
        
         
           
             if(!custom_message)  
             alert("Field " + desc + ' ' + desc2 + " input must be valid to complete application.");
             else
             alert(custom_message);               
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
    
    
    
        
        if (all_required_filed_valid())
        {
        	 if (!isvalid("AVAIL_START_MONTH", "S", "TB", "", "Month"))    {  return false;  }
        	 if (!isvalid("AVAIL_START_DAY", "S", "TB", "", "Day"))    {  return false;  }
        	 if (!isvalid("AVAIL_START_YEAR", "S", "TB", "", "Year"))    {  return false;  }
        	 if (!isvalid("AVAIL_HOURS_WEEK", "S", "TB", "", "Available Time"))    {  return false;  }
        	 if (!isvalid("AVAIL_SHIFT_WEEKDAY", "S", "TB", "", "Amount of Hours"))    {  return false;  }
        	 if (!isvalidRegex("AVAIL_MIN_PAY", "hourly or yearly (no comma or period)" , "Minimum pay" , 'numeric',false,true)) { return false; }  	
        	 if (!isvalidRegex("FILE1", "must be a DOC or RTF file" , "must be a DOC or RTF file" , 'resume',false,true)) { return false; }  	

        	
            field = cbo_field;
            var ida = field.split(";");
            for(i = 0; i < ida.length; i++)
            {
                val = $("#" + ida[i]).val();
                if (val!="undefined")
                {
                    prm = prm + "&" + ida[i] + "=" + val;
                }
            }

            field = text_field;
            var ida = field.split(";");
            for(i = 0; i < ida.length; i++)
            {
                val = $("#" + ida[i]).val();
                if (val!="undefined")
                {
                    prm = prm + "&" + ida[i] + "=" + val;
                }
            }
            
            field = radio_field;
            var ida = field.split(";");
            for(i = 0; i < ida.length; i++)
            {
                val = $("input[name='" + ida[i] + "']:checked").val();
                if (val!="undefined")
                {
                    prm = prm + "&" + ida[i] + "=" + val;
                }
            }
            
            prm = prm + "&current_state=" + document.getElementById("current_state").value;
            
            //move to top then show dialog
           // window.location.hash="top1"
           // waitingDialog({});
            
            $.ajax({
                type: "POST",  
                data: prm,
                url: ajax_db_app,
                success: function( data ) 
                {
                    if (data=="OK")
                    {   //next step                    
                        document.forms["signupform"].submit();
                    }
                    else
                    {
                        alert("Invalid post:" + data);
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
   <link href="/webtime/css/style.css" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Lato|Rokkitt' rel='stylesheet' type='text/css' />
<link href='https://fonts.googleapis.com/css?family=Lato:400normal,light,regular,bold,bolditalic|Rokkitt:regular,bold,bolditalic' rel='stylesheet' type='text/css' />
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
	
	.graytxt3 {
	padding-left:20px;
  font-weight: 700 !important;  /* make it bold */
  color: #000000;               /* or your preferred gray */
}

</style>
    <script type="text/javascript" src="/webtime/css/js.js"></script>
    <script type="text/javascript" src="/webtime/css/jquery.js"></script>
    <script type="text/javascript" src="/webtime/css/custom-form-elements.js"></script>
    
<style type="text/css"> 
body {background:#ffffff;} 
</style>
<style>
.accordion-header {
  cursor: pointer;
  font-weight: bold;
  padding: 10px;
  background: #f2f2f2;
  margin-top: 10px;
  border-radius: 5px;
  display: flex;
  align-items: center;
}

.accordion-header .icon {
  margin-right: 10px;
  transition: transform 0.3s ease;
  color: #b22625; /* red icon */
}

.accordion-content {
  display: none;
  padding: 0; /* removed indentation */
  margin: 0;
}

.accordion-header.active .icon {
  transform: rotate(90deg);
}

.accordion-header .icon {
  margin-right: 10px;
  transition: transform 0.3s ease;
  color: #b22625; /* Your desired red color */
}

</style>
<?php if (if_mobile()) { ?>
<style>
/* Make the 3 radio options wrap instead of running off-screen */
.radio-group{
  float: none !important;          /* overrides inline float:left */
  display: flex !important;
  flex-wrap: wrap !important;
  gap: 6px 14px;
  align-items: center;
  max-width: 100%;
}

.radio-group > div{
  float: none !important;          /* overrides inline float:left */
  padding-right: 0 !important;
  display: inline-flex;
  align-items: center;
  white-space: nowrap;
}

/* On phones, stack the label and radios so nothing gets clipped */
@media (max-width: 480px){
  .graytxt3{
    float: none !important;
    width: 100% !important;
    padding-left: 0 !important;
    margin: 10px 0 4px;
  }
}
</style>
<?php } ?>

<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>

<?php include(dirname(__DIR__) . '/longform/templates/signmeuplongform4.tpl.php'); ?>             
       

