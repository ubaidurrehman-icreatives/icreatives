<?php
 session_start();
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);
 
global $signupform;
global $valid_step;
global $wpdb;
global $formpath;
$formpath = "/LongForm";       
$RMail = $_POST['RMail'];     
        
        $valid_step=false;

       global $signupform;
        require_once(dirname(__DIR__) . '/longform/functions/form-functions.php');
        require_once(dirname(__DIR__) . '/longform/functions/list.php');
        include (dirname(__DIR__) . '/longform/javascript.php');
        $signupform=1;
    
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
        
       
        ajax_db_app="/longform/functions/ajax/ajax.php";
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
            //waitingDialog({});
            
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

    <script type="text/javascript" src="/webtime/css/js.js"></script>
    <script type="text/javascript" src="/webtime/css/jquery.js"></script>
    <script type="text/javascript" src="/webtime/css/custom-form-elements.js"></script>
<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>
</head>
<body>
hello
	<?php include dirname(__DIR__ ). '/longform/templates/signmeuplongform3.tpl.php'; ?>
</body>
</html>
  
