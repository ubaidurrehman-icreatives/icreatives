<?php
 session_start();
 
$candidate_arr = $_SESSION['candidate_arr'];
$candidate_arr['email'] = $_POST['email'] ?? '';

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
?>

<script>
  window.onload = function() {
    if (window.top !== window.self) {
      window.parent.scrollTo({ top: 0, behavior: 'smooth' });
    }
  };
</script>

<?php

global $signupform;
global $valid_step;
global $wpdb;
global $formpath;
$formpath = "/LongForm";    

 
 require_once  dirname(__DIR__) . '/vendor/autoload.php';

// $candidate_arr['full_name'] = $_POST['full_name']; // cannot change, sorry
$candidate_arr['address'] = $_POST['address'] ?? '';
$candidate_arr['custom_fields']['postalcode'] = $_POST['postalcode'] ?? '';
$candidate_arr['phone_number'] = $_POST['phone_number'] ?? '';
$candidate_arr['custom_fields']['referencename'] = $_POST['referencename'] ?? '';
$candidate_arr['custom_fields']['referenceurl'] = $_POST['referenceurl'] ?? '';
$candidate_arr['custom_fields']['referenceemail'] = $_POST['referenceemail'] ?? '';
$candidate_arr['custom_fields']['referencephone'] = $_POST['referencephone'] ?? '';
$candidate_arr['custom_fields']['referencerelationship'] = $_POST['referencerelationship'] ?? '';
$candidate_arr['custom_fields']['referencecompany'] = $_POST['referencecompany'] ?? '';
$candidate_arr['custom_fields']['referencename_b'] = $_POST['referencename_b'] ?? '';
$candidate_arr['custom_fields']['referenceurl_b'] = $_POST['referenceurl_b'] ?? '';
$candidate_arr['custom_fields']['referenceemail_b'] = $_POST['referenceemail_b'] ?? '';
$candidate_arr['custom_fields']['referencephone_b'] = $_POST['referencephone_b'] ?? '';
$candidate_arr['custom_fields']['referencerelationship_b'] = $_POST['referencerelationship_b'] ?? '';
$candidate_arr['custom_fields']['referencecompany_b'] = $_POST['referencecompany_b'] ?? '';
$candidate_arr['custom_fields']['referencename_c'] = $_POST['referencename_c'] ?? '';
$candidate_arr['custom_fields']['referenceurl_c'] = $_POST['referenceurl_c'] ?? '';
$candidate_arr['custom_fields']['referencephone_c'] = $_POST['referencephone_c'] ?? '';
$candidate_arr['custom_fields']['referenceemail_c'] = $_POST['referenceemail_c'] ?? '';
$candidate_arr['custom_fields']['referencerelationship_c'] = $_POST['referencerelationship_c'] ?? '';
$candidate_arr['custom_fields']['referencecompany_c'] = $_POST['referencecompany_c'] ?? '';
$candidate_arr['current_company'] = $_POST['current_company'] ?? '';
$candidate_arr['current_department'] = $_POST['current_department'] ?? '';
$candidate_arr['current_position'] = $_POST['current_position'] ?? '';
$candidate_arr['description'] = ($_POST['description'] ?? '') . ' ';
$candidate_arr['latest_university'] = $_POST['latest_university'] ?? '';
$candidate_arr['latest_degree'] = $_POST['latest_degree'] ?? '';
$candidate_arr['custom_fields']['link'] = $_POST['link'] ?? '';
$candidate_arr['custom_fields']['link_b'] = $_POST['link_b'] ?? '';
$candidate_arr['custom_fields']['link_c'] = $_POST['link_c'] ?? '';
$candidate_arr['custom_fields']['linkname'] = $_POST['linkname'] ?? '';
$candidate_arr['custom_fields']['linkname_b'] = $_POST['linkname_b'] ?? '';
$candidate_arr['custom_fields']['linkname_c'] = $_POST['linkname_c'] ?? '';
// Do this even if they do not finish
$candidate_arr['custom_fields']['link_d'] = "https://www.icreatives.com/wp-content/themes/porto-child/longform/displaylongform.php?CID=" . ($candidate_arr['id'] ?? '');
$candidate_arr['custom_fields']['linkname_d'] = "Skill Self-Eval";

$candidate_arr['custom_fields']['worktype'] = [];
if (!empty($_POST['worktype_contract']) && $_POST['worktype_contract'] === 'on') {
    $candidate_arr['custom_fields']['worktype'][] = "Contract";
}
if (!empty($_POST['worktype_full-time']) && $_POST['worktype_full-time'] === 'on') {
    $candidate_arr['custom_fields']['worktype'][] = "Full-time";
}
if (!empty($_POST['worktype_contact-to-hire']) && $_POST['worktype_contact-to-hire'] === 'on') {
    $candidate_arr['custom_fields']['worktype'][] = "Contract To Hire";
}
if (!empty($_POST['worktype_part-time']) && $_POST['worktype_part-time']  === 'on') {
    $candidate_arr['custom_fields']['worktype'][] = "Part Time";
}

$client = new \GuzzleHttp\Client([ 'timeout' => 30, 'connect_timeout' => 10]);

	// Extract "custom_fields"
		$customFields = json_encode($candidate_arr['custom_fields']);
		// $position_name = $candidate_arr['position_name'];
		// If we don't do this, we will never access the record without patching the custom fields
		if ($customFields == "[]") {
			$customFields= "{}";
		}
$_SESSION['candidate_arr'] = $candidate_arr; //store for updating self eval link when complete

require_once  dirname(__DIR__) . '/db/token.php';
		// json_encode($candidate_arr);

		$response = $client->request('PATCH', 'https://api.manatal.com/open/v3/candidates/'.$candidate_arr['id'].'/', [
		'body' => ''.json_encode($candidate_arr).'',
		'headers' => [
		'Authorization' => $token,
		'accept' => 'application/json',
		'content-type' => 'application/json',
		],
		]);

// echo "ZZZ";
// echo $response->getBody();
        global $signupform;
        require_once(dirname(__DIR__) . '/longform/functions/form-functions.php');
        require_once(dirname(__DIR__) . '/longform/functions/list.php');
        include (dirname(__DIR__) . '/longform/javascript.php');
// echo "XXX";
// echo json_encode($candidate_arr);
// exit();

include  dirname(__DIR__) . '/longform/templates/signmeuplongform2.tpl.php';

require_once __DIR__ . '/../db/db.php';
// require_once  dirname(__DIR__) . '/../db/db.php';
$link = db(); 

// do this once for every reference
if (!empty($_POST['referencename'] ?? '')) {
	// remember to add company name 02/06/26 sc
    $sql = "INSERT INTO ic_reference (
		candidate, 	candidate_name, referencename, referenceurl,  referenceemail, referencephone, referencerelationship,
		recruiter_id, recruiter_name   
    ) VALUES ('".
	$candidate_arr['id']."', '". 
	($_POST['full_name'] ?? '')."', '". 
	($_POST['referencename'] ?? '')."', '". 
	($_POST['referenceurl'] ?? '')."', '". 
	($_POST['referenceemail'] ?? '')."', '". 
	($_POST['referencephone'] ?? '')."', '". 
	($_POST['referencerelationship'] ?? '')."', '". 
	($_POST['recruiter_id'] ?? '')."', '". 
	($_POST['recruiter_name'] ?? '')."');";
/*	
// add this if we get too many dups sjc 02/06/26 
    ON DUPLICATE KEY UPDATE 
        candidate_name = VALUES(candidate_name),
        referencename = VALUES(referencename),
        referenceurl = VALUES(referenceurl),
        referenceemail = VALUES(referenceemail),
        referencephone = VALUES(referencephone),
        referencerelationship = VALUES(referencerelationship),
        recruiter_id = VALUES(recruiter_id),
        recruiter_name = VALUES(recruiter_name)";
		echo $sql;
*/

 $sucess = false;
    if ($link->query($sql) === TRUE) {
        $sucess = true;
    } else {
        echo "Error: " . $sql . "<br>" . $link->error;
    }
}
// 2nd reference
if (!empty($_POST['referencename_b'] ?? '')) {
    $sql = "INSERT INTO ic_reference (
		candidate, 	candidate_name, referencename, referenceurl,  referenceemail, referencephone, referencerelationship,
		recruiter_id, recruiter_name   
    ) VALUES ('".
	$candidate_arr['id']."', '". 
	($_POST['full_name'] ?? '')."', '". 
	($_POST['referencename_b'] ?? '')."', '". 
	($_POST['referenceurl_b'] ?? '')."', '". 
	($_POST['referenceemail_b'] ?? '')."', '". 
	($_POST['referencephone_b'] ?? '')."', '". 
	($_POST['referencerelationship_b'] ?? '')."', '". 
	($_POST['recruiter_id'] ?? '')."', '". 
	($_POST['recruiter_name'] ?? '')."');";
 $sucess = false;
    if ($link->query($sql) === TRUE) {
        $sucess = true;
    } else {
        echo "Error: " . $sql . "<br>" . $link->error;
    }
}
// 3nd reference
if (!empty($_POST['referencename_c'] ?? '')) {
    $sql = "INSERT INTO ic_reference (
		candidate, 	candidate_name, referencename, referenceurl,  referenceemail, referencephone, referencerelationship,
		recruiter_id, recruiter_name   
    ) VALUES ('".
	$candidate_arr['id']."', '". 
	($_POST['full_name'] ?? '')."', '". 
	($_POST['referencename_c'] ?? '')."', '". 
	($_POST['referenceurl_c'] ?? '')."', '". 
	($_POST['referenceemail_c'] ?? '')."', '". 
	($_POST['referencephone_c'] ?? '')."', '". 
	($_POST['referencerelationship_c'] ?? '')."', '". 
	($_POST['recruiter_id'] ?? '')."', '". 
	($_POST['recruiter_name'] ?? '')."');";

 $sucess = false;
    if ($link->query($sql) === TRUE) {
        $sucess = true;
    } else {
        echo "Error: " . $sql . "<br>" . $link->error;
    }
}

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

        
        ajax_db_app="/longform/funtions/ajax/ajax.php";
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
            window.location.hash="top1"
            waitingDialog({});
            
            
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

<meta name="viewport" content="width=device-width, initial-scale=1">

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

<!-- jQuery FIRST -->
<script src="/webtime/css/jquery.js"></script>

<!-- your “skin radios” script -->
<script src="/webtime/css/custom-form-elements.js"></script>

<script>
(function() {
  // handle noConflict environments
  var $j = window.jQuery || window.$;

  if (!$j) { console.error('jQuery not loaded'); return; }

  $j(function(){
    console.log('init radio skin');

    // A few common initializers used by these libraries—try in order:
    if (window.customFormElements && customFormElements.init) { customFormElements.init(); }
    else if (window.Custom && Custom.init) { Custom.init(); }
    else if (window.CFE && CFE.init) { CFE.init(); }

    // Fallback: trigger change to force refresh
    $j('input[type=radio].styled').trigger('change');
  });
})();
</script>

    

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
<style>
/* ===== SUBJECT RADIO BUTTON STYLING ===== */
.subject-group {
  display: flex;
  flex-wrap: wrap;
  gap: 8px 16px;          /* row gap / column gap */
  justify-content: flex-start;
  max-width: 600px;       /* keeps two columns tidy */
  margin-top: 10px;
  margin-bottom: 15px;
}

.subject-item {
  width: calc(50% - 8px); /* two per row */
  display: flex;
  align-items: center;
  gap: 8px;               /* space between radio and label */
  margin-bottom: 8px;
}

.subject-item label {
  font-weight: 700 !important; /* force bold */
  font-size: 13px;
  line-height: 1.3;
  color: #000;
  margin: 0;
}

/* tweak your custom radio sprite spacing */
span.radio {
  margin-right: 6px;
  transform: scale(0.9);
}

/* responsive: switch to one column on narrow screens */
@media (max-width: 600px) {
  .subject-item {
    width: 100%;
  }
}

.graytxt3 {
	padding-left:20px;
  font-weight: 700 !important;  /* make it bold */
  color: #000000;               /* or your preferred gray */
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

.accordion-header active {
  background-color: #E9E9E0 !important;
}

</style>
<?php } ?>
</head>
<body onload="top.scrollTo(0,0)">
<?php


 require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>
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
	
       <script>
  function postHeight() {
    const height = document.documentElement.scrollHeight;
    window.parent.postMessage({ type: 'resize-iframe', height: height }, '*');
  }

  // Retry a few times in case fonts or images delay rendering
  function scheduleHeightPost() {
    postHeight();
    setTimeout(postHeight, 300);  // after 0.3 sec
    setTimeout(postHeight, 1000); // after 1 sec
    setTimeout(postHeight, 2000); // after 2 sec
  }

  window.addEventListener("load", scheduleHeightPost);
  window.addEventListener("resize", postHeight);
</script>

         
</body>
</html> 
