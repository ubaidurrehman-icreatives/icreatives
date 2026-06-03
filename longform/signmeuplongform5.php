<?php
/*
Template Name: Sign Me Up Long Form 5
*/
?>
<?php

                global $signupform;
        global $valid_step;
global $wpdb;
global $formpath;
global $state;
$formpath = "/wp-content/themes/porto-child/LongForm";        
        
        $valid_step=false;

	// $link = mysqli_connect('localhost', 'wordpress_2', '9g1EXn$q8A','wordpress_7') or die("Error: " . mysqli_error());
		$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

        global $signupform;
         require_once($_SERVER['DOCUMENT_ROOT']. '/wp-content/custom-php/functions.php');
         require_once($_SERVER['DOCUMENT_ROOT']. '/wp-content/custom-php/list.php');
        
        ##signup form - to notify headers, footer...
        $signupform=1;
         $state = $_REQUEST["current_state"];
        ic_gen_signup_form_state();
        
        ic_process_signup_form_state();
        
        ic_get_signnup_longform_variables();
        
    
     /*   if ($valid_step==false && $state!="s4" && $state!="done")
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

<script language="javascript">

   
    
    function isvalid(id, t, ctrl, val)
    {
        if (t=="S")
        {
            
            if (document.getElementById(id).value==val || document.getElementById(id).value=="")
            {
                alert("Field " + id +  " is required, Please input information for it to complete application.");                    
                return false;
            }        
        }
        return true;
    }

    function all_required_filed_valid()
    {
        //if (!isvalid("firstname", "S", "TB", "firstname"))  { return false; }
        
        //if (!isvalid("lastname", "S", "TB", "lastname"))    { return false;  }
        
        //if (!isvalid("nickname", "S", "TB", "nickname"))    {  return false;  }
        
/*        if (!isvalid("region", "S", "CB", ""))    {  return false;  }
        
        if (!isvalid("state", "S", "CB", ""))    {  return false;  }
        
        if (!isvalid("home_area", "S", "TB", "area code"))    {  return false;  }
        if (!isvalid("home_num1", "S", "TB", "###"))    {  return false;  }
        if (!isvalid("home_num2", "S", "TB", "####"))    {  return false;  }
        
        if (!isvalid("mobile_area", "S", "TB", "area code"))    {  return false;  }
        if (!isvalid("mobile_num1", "S", "TB", "###"))    {  return false;  }
        if (!isvalid("mobile_num2", "S", "TB", "####"))    {  return false;  }        
        
        if (!isvalid("email", "S", "TB", "email address"))    {  return false;  }*/
        
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
                        document.signupform.submit();
                    }
                    else
                    {
                        alert("Invalid post:" + result);
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
</style>
    <link rel="stylesheet" type="text/css" href="/webtime/css/css.css" />

    <script type="text/javascript" src="/webtime/css/js.js"></script>
    <script type="text/javascript" src="/webtime/css/jquery.js"></script>
    <script type="text/javascript" src="/webtime/css/custom-form-elements.js"></script>
    
<style type="text/css"> 
body {background:#ffffff;} 
</style>
<?php

  
    
    if ($state=="done")
    {
        ic_clear_signup_form_state();
       include($_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/longform/templates/signmeuplongform6.tpl.php'); 
      
        
    }
    else
    {
		
		 include($_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/porto-child/longform/templates/signmeuplongform5.tpl.php');
      
    }
?>     

</body>
</html>  
