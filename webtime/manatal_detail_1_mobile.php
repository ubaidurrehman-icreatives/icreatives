
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="description" content="Contact your nearest i creatives office in the LA, San Francisco, Miami, Fort Lauderdale, San Jose, New York City, New Jersey and Atlanta for your creative staffing needs, or for freelance marketing or creative design positions.">
	<meta name="keywords" content="creative staffing, jobs in marketing, freelance employment, Graphic Designers, Web Designers">
	
	<title>i creative staffing login screen</title>
	<style>
  /* Force the Next button to be visible even if mobile/styles.css hides it */
  #Next { display:block !important; visibility:visible !important; opacity:1 !important; }
</style>
	  <style>
	 .lable_class{
	  font-family: Verdana,Geneva,sans-serif;
	  font-size: 12px;
	  color: #6a7785;
	  line-height: 22px;
	  margin-left:10px;
	 }
	 .contact_input_class{
	  font-family: Verdana,Geneva,sans-serif;
	  font-size: 12px;
	  color: #6a7785;
	  height: 30px;
	  width:95%;
	 }
	 .contact_input_class1{
	  font-family: Verdana,Geneva,sans-serif;
	  font-size: 12px;
	  color: #6a7785;
	  height: 30px;
	  width:20px;
	 }
	  .contact_textarea_class{
	  font-family: Verdana,Geneva,sans-serif;
	  font-size: 12px;
	  color: #6a7785;
	  height: 100px;
	  width:95%;
	 }
	 .Button{
	padding-top:3px;
	WIDTH:99%;
	height:40px; 
	padding-left: 11px;
	margin-top: 12px;
	background-color: #b22625;
	/*border-radius: 5px;*/
	color: white;
	border:none;
	}
	input[type=button]
{
	-webkit-appearance:none;
	-webkit-border-radius:0px !important;
}
	.outerboxT
	{
	position: relative;
	float: left;
	width: 100%;
	height: 71px;
	background-color: #e5e5e5;
	  border-top-left-radius: 20px;
	  border-top-right-radius: 20px;
	  border-bottom-right-radius: 20px;
	  margin-top: 7px;
	}
	</style>
	<style>
	.ontop {
		/*z-index: 999;*/
		width: 100%;
		height: 100%;
		display: none;
		position:relative;
		background-color: rgba(204, 204, 204 0.4);
		color: #aaaaaa;
		
		filter: alpha(opacity = 50);
	}
	#popup {
		width: 200px;
		/*height: 200px;*/
		position: absolute;
		color: #000000;
		background-color: #ffffff;
		border: 1px solid #666;
		/* To align popup window at the center of screen*/
		top: 50%;
		left: 50%;
		margin-top: -100px;
		margin-left: -150px;
	}
	select {
		border: solid 1px #ccc;
	/*	border-radius: 10px;
		-moz-border-radius: 10px;
		-webkit-border-radius: 10px;*/
		height: 25px;
		width: 50px;
	}
	.text1 {
	border:2px solid #b3b3b3;
	background:#fff;
	width:50px;
	height: 22px;
	border-radius:25px;
	-moz-border-radius:25px; 
	-moz-box-shadow:    1px 1px 1px #ccc;
	-webkit-box-shadow: 1px 1px 1px 1px #ccc;
	 box-shadow:         1px 2px 2px 2px #ccc;
	
	}
	.Button1{
	/*padding-top:3px;*/
	WIDTH:45%;
	height:30px; 
	/*padding-left: 11px;*/
	/*margin-top: 12px;*/
	background-color: #b22625;
	/*border-radius: 5px;*/
	color: white;
	border:0px;
	border:none;
	
	}
	</style>
	<script>
// Accepts "HH:MM" or "HH:MM:SS" (or empty) → {h,m} or null
function getHM(v){
  if(!v) return null;
  var p = String(v).split(':');
  var h = parseInt(p[0]||'0',10);
  var m = parseInt(p[1]||'0',10);
  if (isNaN(h) || isNaN(m)) return null;
  return {h:h, m:m};
}
// Convert "HH:MM" or "HH:MM:SS" to seconds (0 if bad)
function timeStrToSeconds(v){
  var hm = getHM(v);
  return hm ? (hm.h*3600 + hm.m*60) : 0;
}
</script>

	<script type="text/javascript">

				function pop(div) {
					document.getElementById(div).style.display = 'block';
				}
				function hide(div) {
					document.getElementById(div).style.display = 'none';
				}
				//To detect escape button
				document.onkeydown = function(evt) {
					evt = evt || window.event;
					if (evt.keyCode == 27) {
						hide('popDiv');
					}
				};
			
		  </script>


    <link href="/webtime/css/mobile/styles.css" rel="stylesheet" type="text/css" />
  <!--   <link rel="stylesheet" type="text/css" href="/webtime/css/css.css" /> -->
	
		<script type="text/javascript" src="/webtime/css/js.js"></script>
		<script type="text/javascript" src="/webtime/css/js/jquery.js"></script>
		<script type="text/javascript" src="/webtime/css/custom-form-elements.js"></script>
		
	
	
	<script type="text/javascript">
	
	function submitform()
	{
	window.parent.scroll(0,0);
		document.form.submit();
		document.forms["webtime"].submit();
		if (validateFields()==true)	
		{	
			document.forms["webtime"].submit();
		}
		
	}
	</script>
	<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>
</head>
<body>
	
	<?php
	Function TotalHrs($i) {
	
	$TotalHrs = $row["OUTHR" . $i] - $row["INHR" . $i] - $row["BREAK" . $i];
	
	return $TotalHrs;
	} 
	
	Function DecToTime($Hrs) {
	$Hrs = str_pad(floor($Hrs),2,"0", STR_PAD_LEFT) . ":" . str_pad(round(($Hrs - floor($Hrs))*60,0),2,"0", STR_PAD_LEFT) ;

	return $Hrs;
}
?>
	<script language="JavaScript">
function getHM(v) {
  if (!v) return null;
  var p = String(v).split(':');
  var h = parseInt(p[0] || '0', 10);
  var m = parseInt(p[1] || '0', 10);
  if (isNaN(h) || isNaN(m)) return null;
  if (h < 0) h = 0; if (h > 24) h = 24;
  if (m < 0) m = 0; if (m > 59) m = 59;
  return {h: h, m: m};
}

function ampm(timeStr) {
  if (!timeStr) return '';
  var parts = String(timeStr).split(':');

  var h = parseInt(parts[0] || '0', 10);
  var m = parts[1] || '00';

  if (isNaN(h)) return timeStr; // just in case

  var suffix = (h >= 12) ? 'PM' : 'AM';
  if (h === 0)      h = 12;
  else if (h > 12)  h -= 12;

  if (m.length > 2) m = m.substring(0, 2);
  var hStr = h < 10 ? '0' + h : '' + h;

  return hStr + ':' + m + ' ' + suffix;
}

function validateFields() {
  try {
    // Parse "HH:MM" or "HH:MM:SS" -> {h,m} or null
    function getHM(v) {
      if (!v) return null;
      var p = String(v).split(':');
      var h = parseInt(p[0] || '0', 10);
      var m = parseInt(p[1] || '0', 10);
      if (isNaN(h) || isNaN(m)) return null;
      return {h: h, m: m};
    }

    function timeStrToSeconds(v) {
      var hm = getHM(v);
      return hm ? (hm.h * 3600 + hm.m * 60) : 0;
    }

    // Seconds for a single day, with validation
    function daySeconds(i) {
      var inEl  = document.getElementById('TimeInHr'  + i);
      var outEl = document.getElementById('TimeOutHr' + i);
      var brEl  = document.getElementById('BreakHr'   + i);

      var tin  = inEl  ? inEl.value  : '';
      var tout = outEl ? outEl.value : '';
      var br   = brEl  ? brEl.value  : '00:00';

      if (tin  === '00:00:00') tin  = '';
      if (tout === '00:00:00') tout = '';

      // No entries for this day
      if (!tin && !tout) return 0;

      // One side missing
  if ((tin && !tout) || (!tin && tout)) {
    alert("Your Time-In and Time-Out must both be entered for day " + i + " (or both left blank).");
    var inEl = document.getElementById("TimeInHr" + i);
    if (inEl && inEl.focus) inEl.focus();
    return 0;
  }


      var inSec  = timeStrToSeconds(tin);
      var outSec = timeStrToSeconds(tout);
      var brSec  = timeStrToSeconds(br);

      var diff = outSec - inSec - brSec;

      if (diff < 0) {
        alert("Your Time-Out must be later than your Time-In (after subtracting break). Please correct this day.");
        if (outEl && outEl.focus) outEl.focus();
        throw new Error('validation-stop');
      }

      return diff;
    }

    function weekdayName(i) {
      var names = ['', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
      return names[i] || ('Day ' + i);
    }

    var anyHours    = false;
    var weekSeconds = 0;
    var minDaily    = 4 * 3600; // 4 hours

    // Check each day
    for (var i = 1; i <= 7; i++) {
      var sec = daySeconds(i);
      weekSeconds += sec;
      if (sec > 0) {
        anyHours = true;

        // ✅ Less than 4 hours → Ask "are you sure?"
        if (sec < minDaily) {
          var ok = confirm(
            weekdayName(i) + ' has less than 4 hours (' +
            Math.floor(sec / 3600) + 'h ' + Math.floor((sec % 3600) / 60) + 'm).' +
            '\nPress OK to proceed (waive), or Cancel to edit that day.'
          );
          if (!ok) {
            var inEl = document.getElementById('TimeInHr' + i);
            if (inEl && inEl.focus) inEl.focus();
            return false;
          }
        }
      }
    }

    if (!anyHours) {
      alert('No hours were entered. Please enter time for at least one day.');
      return false;
    }

    // Check "Assignment continuing" radio
 // === Assignment continuing check ===
var contRadio = document.querySelector('input[name="Continuing"]:checked');
if (!contRadio) {
  alert('Please select one of the "Assignment continuing" options.');
  var firstRadio = document.querySelector('input[name="Continuing"]');
  if (firstRadio && firstRadio.focus) firstRadio.focus();
  return false;
}

    return true;

  } catch (e) {
    var msg = e && e.message ? String(e.message) : '';

    // Our intentional early-stop errors
    if (msg.indexOf('validation-stop') !== -1) {
      return false;
    }

    if (window.console && console.error) {
      console.error(e);
    }
    alert('Sorry, something went wrong validating your times. Please review your entries.');
    return false;
  }
}

	
 function totalSeconds (sec) {
  var hours   = Math.floor(sec / 3600);
  var minutes = Math.floor((sec - (hours * 3600)) / 60);
  var seconds = sec - (hours * 3600) - (minutes * 60);

  // round seconds
  seconds = Math.round(seconds * 100) / 100

  var result = (hours < 10 ? "0" + hours : hours);
     // result += ":" + (minutes < 10 ? "0" + minutes : minutes);
 //     result += ":" + (seconds  < 10 ? "0" + seconds : seconds);
  return result;
}
	
	
	
	function TotalDailyCal_All()
	{
	
		Converttimeformat(1);
		Converttimeformat(2);
		Converttimeformat(3);
		Converttimeformat(4);
		Converttimeformat(5);
		Converttimeformat(6);
		Converttimeformat(7);
		
	}
	
	function setdefaultvalue(i)
	{
	
	if (document.getElementById("TimeInHr"+i+"").value=="00:00:00")
	{
	
	document.getElementById("TimeInHr"+i+"").value="09:00";
	}
	if (document.getElementById("TimeOutHr"+i+"").value=="00:00:00")
	{
	
	document.getElementById("TimeOutHr"+i+"").value="18:00";
	}
	}

// Convert "HH:MM" or "HH:MM:SS" into seconds
function timeStrToSeconds(str) {
  if (!str) return 0;
  var parts = String(str).split(':');
  var h = parseInt(parts[0] || '0', 10);
  var m = parseInt(parts[1] || '0', 10);
  if (isNaN(h) || isNaN(m)) return 0;
  return h * 3600 + m * 60;
}

// Format "HH:MM[:SS]" to "hh:mm AM/PM" for display
function ampm(timeStr) {
  if (!timeStr) return '';
  var parts = String(timeStr).split(':');
  var h = parseInt(parts[0] || '0', 10);
  var m = parts[1] || '00';

  if (isNaN(h)) return timeStr; // fallback

  var suffix = (h >= 12) ? 'PM' : 'AM';
  if (h === 0) {
    h = 12;
  } else if (h > 12) {
    h -= 12;
  }

  if (m.length > 2) m = m.substring(0, 2);
  var hStr = h < 10 ? '0' + h : '' + h;
  return hStr + ':' + m + ' ' + suffix;
}

function Converttimeformat1(i) {
  var tinEl  = document.getElementById("TimeInHr"  + i);
  var toutEl = document.getElementById("TimeOutHr" + i);
  var brEl   = document.getElementById("BreakHr"   + i);

  var tin  = tinEl  ? tinEl.value  : "";
  var tout = toutEl ? toutEl.value : "";
  var br   = brEl   ? brEl.value   : "00:00";

  // Treat "00:00:00" as empty/no time
  if (tin  === "00:00:00") tin  = "";
  if (tout === "00:00:00") tout = "";

  // If either in or out is missing, don't complain yet; just return 0
  if (!tin || !tout) {
    return 0;
  }

  // Use the existing helper to convert to seconds
  var inSec  = timeStrToSeconds(tin);
  var outSec = timeStrToSeconds(tout);
  var brSec  = timeStrToSeconds(br);

  var diff = outSec - inSec - brSec;

  // For *display*, never show negative hours; clamp to zero
  if (diff < 0 || isNaN(diff)) {
    diff = 0;
  }

  return diff;
}

	
	
	function getTimeAsSeconds(time){
    var timeArray = time.split(':');
    return (Number(timeArray [0]) * 3600) + (Number(timeArray [1]) * 60 );
}
	
function ampm(timeStr) {
  if (!timeStr) return '';
  var parts = String(timeStr).split(':');
  var h = parseInt(parts[0] || '0', 10);
  var m = parts[1] || '00';

  if (isNaN(h)) return timeStr; // fallback

  var suffix = (h >= 12) ? 'PM' : 'AM';
  if (h === 0) {
    h = 12;
  } else if (h > 12) {
    h -= 12;
  }

  if (m.length > 2) m = m.substring(0, 2);
  var hStr = h < 10 ? '0' + h : '' + h;

  return hStr + ':' + m + ' ' + suffix;
}

function Converttimeformat(i) {
  var tinEl  = document.getElementById("TimeInHr"  + i);
  var toutEl = document.getElementById("TimeOutHr" + i);
  var brEl   = document.getElementById("BreakHr"   + i);

  var tin  = tinEl  ? tinEl.value  : "";
  var tout = toutEl ? toutEl.value : "";
  var br   = brEl   ? brEl.value   : "00:00";

  if (tin  === "00:00:00") tin  = "";
  if (tout === "00:00:00") tout = "";

  var sec = Converttimeformat1(i);
  var hrs = Math.floor(sec / 3600);
  var mins = Math.floor((sec % 3600) / 60);
  if (mins < 10) mins = "0" + mins;

  // 👉 Safely get the elements before touching innerHTML
  var totalEl = document.getElementById("TotalDaily" + i);
  if (totalEl) {
    totalEl.innerHTML = hrs + ":" + mins + " hrs";
  }

  var fromEl  = document.getElementById("divFrom"  + i);
  var toEl    = document.getElementById("divTo"    + i);
  var lunchEl = document.getElementById("divLunch" + i);

  // Only try to format labels if elements exist AND there is a value
  if (fromEl && tin) {
    fromEl.innerHTML = "From " + ampm(tin);
  }
  if (toEl && tout) {
    toEl.innerHTML = "To " + ampm(tout);
  }
  if (lunchEl && br) {
    lunchEl.innerHTML = "Lunch " + br + " Hrs";
  }
}

	function TotalDailyCal(i)
	{
		//	check for missing in or out
	
		var gt = 0;
		var err_num=0;
		var sInHours;
		var sInMinutes;
		var sInAmPm;
		var sOutHours;
		var sOutMinutes;
		var sOutAmPm;
		var sBreakHr;
		var sBreakMin;
	
		eval("var sh 	= document.forms[0].TimeInHr"+i+".value;")
		//eval("var sm 	= document.forms[0].TimeInMin"+i+".value;")
		//eval("var sap 	= document.forms[0].TimeInAmPm"+i+".value;")		
		eval("var fh 	= document.forms[0].TimeOutHr"+i+".value;")
		//eval("var fm 	= document.forms[0].TimeOutMin"+i+".value;")
		//eval("var fap 	= document.forms[0].TimeOutAmPm"+i+".value;")	
		eval("var bh 	= document.forms[0].BreakHr"+i+".value;")
		eval("var bm 	= document.forms[0].BreakMin"+i+".value;")
		
		gt = gt + eval('sh') + eval('fh');
	
			//  check for time-in = 0 or time out = 0 but not both
				if ((eval('sh') == "0" && eval('fh') != "0") || (eval('fh')== "0" && eval('sh') != "0" ))
				{
					eval("document.getElementById('TotalDaily" + i+"').innerHTML = 0");	
					eval("document.forms[0].TimeInHr" + i +".select();")
					eval("document.forms[0].TimeInHr" + i +".focus();")		
					return false;
				}
					
				// check for PM to AM	
				//Buu added 12am
				if ( (eval('sap') == "PM") && (eval('fap') == "AM")  && eval('sh') != "12")
				{
					eval("document.getElementById('TotalDaily" + i+"').innerHTML = 0");	
					eval("document.forms[0].TimeInHr" + i +".select();")
					eval("document.forms[0].TimeInHr" + i +".focus();")	
					return (false);
				}					
							
				// check for not a number
				if (!IsNumeric(eval('sh')) || !IsNumeric(eval('fh')) )
				// || len(eval('sh'+i))==0 || len(eval('fh'+i)) ==0 )
				{
					eval("document.getElementById('TotalDaily" + i+"').innerHTML = 0");	
					eval("document.forms[0].TimeInHr" + i +".select();")
					eval("document.forms[0].TimeInHr" + i +".focus();")	
					return (false);
				}		
	
				// check time in = time out
				if ( eval('sh') == eval('fh')  && eval('sm') == eval('fm') && eval('sh') != 0 && eval('fh') != 0 && eval('sap') == eval('fap') )
				{
					eval("document.getElementById('TotalDaily" + i+"').innerHTML = 0");	
					eval("document.forms[0].TimeInHr" + i +".select();")
					eval("document.forms[0].TimeInHr" + i +".focus();")	
					return (false);
				}		
	
	
			// check for numbers over 12
				if ( eval('sh') > 12 || eval('fh')> 12 )
				{
					eval("document.getElementById('TotalDaily" + i+"').innerHTML = 0");	
					eval("document.forms[0].TimeInHr" + i +".select();")
					eval("document.forms[0].TimeInHr" + i +".focus();")	
					return (false);
				}
	
			
			// check for not Null
				if ( eval('sh') =="" && eval('fh')== "" )
				// || len(eval('sh'+i))==0 || len(eval('fh'+i)) ==0 )
				{
					eval("document.forms[0].TotalDaily" + i +".value=0" );
					eval("document.forms[0].TimeInHr" + i +".select();")
					eval("document.forms[0].TimeInHr" + i +".focus();")	
					return (false);
				}		
	
			// check for no Hours	
				if ( i >= 7 && gt == 0 )
				{
					eval("document.getElementById('TotalDaily" + i+"').innerHTML = 0");	
					document.forms[0].TimeInHr1.select();
					document.forms[0].TimeInHr1.focus();	
				   return (false);
				}		
				
	
			//End of validate - start calcualte total hours   
			sInHours = Number(eval('sh'));
						
			if (eval('sap')=='PM' && sInHours < 12)
			{
				sInHours = sInHours + 12;
			}    
			//12am
			if (eval('sap')=='AM' && sInHours == 12)
			{
				sInHours = 0;
			}
			
			sInMinutes = Number(eval('sm'))/60;    	
		
			sOutHours = Number(eval('fh'));
		
			 if (eval('fap')=='PM' && Number(eval('fh'))>0 && Number(eval('fh'))<12)
			 {
				sOutHours = sOutHours + 12;
			 } 
		 
			if (eval('fap')=='AM' && eval('sap')=='AM' && Number(eval('sh'))== 12 && Number(eval('fh')) > 0 && Number(eval('fh')) < 12 )
			{
				sInHours = 0;
			}
			
			sOutMinutes = Number(eval('fm'))/60;
			sBreakHr = Number(eval('bh'));
			sBreakMin = Number(eval('bm'))/60;
		
			var daily_conv = (Number(eval(sOutMinutes)) + Number(eval(sOutHours))) - (Number(eval(sInMinutes)) + Number(eval(sInHours)) ) -(Number(eval(sBreakHr)) + Number(eval(sBreakMin)));
			
			//set final total value
			eval("document.getElementById('TotalDaily" + i+"').innerHTML = " + daily_conv);	
		
			return true;
			}		
	
function CLearText(i) {
  document.getElementById("TimeInHr"+i).value = "00:00:00";
  document.getElementById("TimeOutHr"+i).value = "00:00:00";
  document.getElementById("BreakHr"+i).value = "00:00";

  var From  = "From: 00:00 ";
  var To    = "To: 00:00";
  var Lunch = "Lunch: 00:00";

  var totalEl = document.getElementById("TotalDaily"+i);
  if (totalEl) {
    totalEl.innerHTML = "0:00 hrs";
  }

  document.getElementById("divFrom"+i).innerHTML  = From;
  document.getElementById("divLunch"+i).innerHTML = Lunch;
  document.getElementById("divTo"+i).innerHTML    = To;
}

	</script>
	
<style>
/* Default (Desktop) */
.mobile-spacer{
  clear: left;
  height: 0px;
}

/* Tablet */
@media (max-width: 1024px) and (min-width: 769px){
  .mobile-spacer{
    height: 60px;
  }
}

/* Mobile */
@media (max-width: 768px){
  .mobile-spacer{
    height: 50px;
  }
}
</style>

 <div class="mobile-spacer"></div>
	

<?php session_start(); 
 
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
require_once  dirname(__DIR__) . '/vendor/autoload.php';


$test = $_Request["Assignment"] ?? 0;
require_once __DIR__ . '/../db/db.php';
$link = db();   

if (strlen($test) > 1) { $i = 1; } 

$i=1; 

$lsNewWeb = "False";

Function HrComp($lsHr) {
If ($lsHr >= 13) {
   $lsHr = intval($lsHr -12);
 }Else {
   $lsHr = intval($lsHr);
 }
 
return $lsHr;
}

Function HrComp1($lsHr) {
	
	if ($lsHr < 12) {
   		$HrComp1 = intval($lsHr);
 	}
	if ($lsHr == 12) {
   		$HrComp1 = intval($lsHr);
	}
 	if ($lsHr > 12) {
   		$HrComp1 = intval($lsHr) - 12;
	}
	IF (strLEN($HrComp1) < 2) {
		$HrComp1 = "0" . $HrComp1;
	}
	return $HrComp1 ;
}


Function AmPmCompIn($lsHr) { // need two because out should default to PM for new record
If ($lsHr >= 12) {
        $AmPmCompIn = "<option class='defaulttable' value = 'AM'>AM</option> <option value='PM' selected>PM</option>";
} Else {
        $AmPmCompIn = "<option value='AM' selected>AM</option> <option value='PM'>PM</option>";
}
return $AmPmCompIn;
}

Function AmPmCompOut($lsHr) {
If ($lsHr >= 12 || $lsHr == 0) {
        $AmPmCompOut = "<option value = 'AM'>AM</option> <option value = 'PM' selected>PM</option>";
} Else {
        $AmPmCompOut = "<option value = 'AM' selected>AM</option> <option value = 'PM'>PM</option>";
}
return $AmPmCompOut;
}

Function AmPmCompOut1($lsHr) {
If ($lsHr >= 12 || $lsHr == 0) {
	$AmPmCompOut1 = "PM";
} Else {
	$AmPmCompOut1 = "AM";
}
return $AmPmCompOut1;
}

Function AmPmComp($lsHr) {
    // If the hour is exactly 12, it should be PM
    if ($lsHr >= 0 || $lsHr < 12) {
        $AmPmComp = "AM";
    } else {
        $AmPmComp = "PM";
    }
    return $AmPmComp;
}




function truncate($value, $precision) {
    $multiplier = pow(10, $precision);
    $value = (int)($value * $multiplier);
    return $value / $multiplier;
}

Function MinComp($lsHr) {

$lsMin = ($lsHr - floor($lsHr)) * 60;

Switch ($lsMin) {

Case 0:
$MinComp = "<option selected='Yes' value=0>00</option> <option value=15>15</option> <option value=30>30</option> <option value=45>45</option>";
break;        
Case 15:
$MinComp = "<option value=0>00</option> <option selected='Yes' value=15>15</option> <option value=30>30</option> <option value=45>45</option>";
break; 
Case 30:
$MinComp = "<option value=0>00</option> <option value=15>15</option> <option selected='Yes' value=30>30</option> <option value=45>45</option>";
break; 
Case 45:
$MinComp = "<option value=0>00</option> <option value=15>15</option> <option value=30>30</option> <option selected='Yes' value=45>45</option>";
break; 
default:
$MinComp = "<option value=0>00</option> <option value=15>15</option> <option value=30>30</option> <option value=45>45</option>";
break; 
}
return $MinComp;
}
	
	Function MinComp1($lsHr) {
	$MinComp1 = round(($lsHr - round($lsHr,0))*60,0);
	If ($MinComp1 < 10) {
		$MinComp1 = "0" . $MinComp1;
	}
	If ($MinComp1 == 0) {
//		$MinComp1 = "00" . $MinComp1;
	}

	 return 	$MinComp1;

	}
		
// list($street, $town, $city, $postcode) = explode(',', $address);
// $Contractor_ID = SubStr($_REQUEST["Contractor_ID"],0,9);
$Contractor_ID = $_REQUEST["Contractor_ID"];
$PassThru = $_REQUEST["Assignment"];

$lsAssignment_ID = (SubStr($PassThru,0,strpos($PassThru,"|")));

// echo "assignment_id - " . $lsAssignment_ID ."<br>";

// echo "time: = " . Substr($PassThru,strpos($PassThru,"|")+1) . "<br>";

$lsWeekEnd = date("Y-m-d", strtotime(Substr($PassThru,strpos($PassThru,"|")+1)));

// echo "weekend - " . $lsWeekEnd ."xxxx<br>";

$strSQL = "SELECT 'TIMEACT' as '!TIMEACT' ";
$strSQL = $strSQL . ", wt.first_name as FIRST ";
$strSQL = $strSQL . ", wt.last_name as LAST ";
$strSQL = $strSQL . ", REPLACE(oj.company_name,' ','_') as JOB ";
$strSQL = $strSQL . ", oj.job_name as ITEM ";
$strSQL = $strSQL . ", '-' as NOTE, oj.job as PROJ ";
$strSQL = $strSQL . ", oj.job as xORDER ";

$strSQL = $strSQL . ", '40' as DURATION ";
$strSQL = $strSQL . ", '1' as BILLINGSTATUS ";
$strSQL = $strSQL . ", 'CONTR' as PITEM ";
$strSQL = $strSQL . ", '0' as BITEM ";
$strSQL = $strSQL . ", oj.po_number as PO ";

$strSQL = $strSQL . ", 'Yes' as Contract ";

for ($i = 1; $i <= 7; $i++) {

 	$strSQL = $strSQL . ", wt.TimeInHr" . $i . " as INHR" . $i . " ";
	$strSQL = $strSQL . ", wt.TimeOutHr" . $i . " as OUTHR" . $i . " ";
	$strSQL = $strSQL . ", wt.Break" . $i . " as BREAK" . $i . " ";
}
$strSQL = $strSQL . ", wt.Continuing as CONT " ;
$strSQL = $strSQL . ", wt.ApproveDate as APPROVE " ;
$strSQL = $strSQL . ", wt.SentDate as SENT " ;
$strSQL = $strSQL . ", wt.DeclineDate as DECLINE " ;
$strSQL = $strSQL . "from ic_timesheets wt ";
$strSQL = $strSQL . "JOIN ic_matches oj on oj.candidate = wt.Employee_ID";
$strSQL = $strSQL . " WHERE wt.void = FALSE AND wt.Assignment_ID = '" .  $lsAssignment_ID . "' AND wt.weekending = '" . $lsWeekEnd ."' ";
$strSQL = $strSQL . " AND wt.Employee_ID='" . $Contractor_ID . "' ";
$strSQL = $strSQL . " ORDER BY PROJ ";

$strOK = True;		  
$resMySel2 = mysqli_query($link,$strSQL );	
$count = mysqli_num_rows($resMySel2);
$row = mysqli_fetch_array($resMySel2);	

// echo "<br>" . $strSQL;

	// check to see if it is OK to edit, if back button was click twice

	//  test if back button was hit a couple of times with approve
if ( ((($row['APPROVE'] ?? null) === null) && (($row['SENT'] ?? null) === null)) || (($row['DECLINE'] ?? null) !== null) ) {
    $strOK = true;
} else {
    $strOK = false;
    echo "<p><br><br></p><center><h1><b>Timesheet was already sent for approval</b></h1></center>";
}
// forcing tru for now
   $strOK = 1;
/*
if (Is_Null($row["PROJ"]) ) {
}
*/
	$strSQLz = "SELECT * FROM ic_matches WHERE job = '" . $lsAssignment_ID . "' AND candidate = '" . $Contractor_ID . "'";
//	echo $strSQLz;
	$resultz = mysqli_query($link,$strSQLz);
	$countz = mysqli_num_rows($resultz);
	$rowz = mysqli_fetch_array($resultz);

// if ($countz == 0) {

	$PROJ = $rowz["job"];
	$JOB = $rowz["company_name"];
	$xORDER = $rowz["job"];
	$TITLE = $rowz["job_name"];		
	$EMP = $rowz["candidate_name"];
  	// $REPORTTO = $row["Primary_Contact_Email"];
  	// $REPORTTOCC = $row["Second_Contact_Email"];
	

$client = new \GuzzleHttp\Client([
    'timeout'         => 30,  // wait up to 10 seconds per attempt
    'connect_timeout' => 10
]);

require_once  dirname(__DIR__) . '/db/token.php';
$response = $client->request('GET', 'https://api.manatal.com/open/v3/jobs/'. $PROJ .'/', [
  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
  ],
]);


$response->getBody();

	$responseStr = $response->getBody();
	$job = json_decode($responseStr, true);
	
	$billingcycle = $job['custom_fields']['billingcycle'] ?? '';
	$REPORTTO = $job['custom_fields']['timeapproveremail'];
	$REPORTTOCC  = $job['custom_fields']['timeapproveremail_b'] ?? '';
	$job_name = $job['position_name'];
	$invoice_cc_mail = $job['custom_fields']['apinvoiceemailcommadelimited'] ?? '';
	$PO = $job['custom_fields']['ponumber'] ?? '';
	
	
	
IF ($strOK ) {

	// Figure out who to email timesheet to:

	
	// echo( "<div style='padding-top:18px; padding-left:0px' class='formText'><font color='grey'>&nbsp;&nbsp;Assignment Number " . $row["xORDER"] . "</font></div><BR>" );


	
	// Conversion time 
	function ampmTime($InTime) {

	if ( Date('h', strtotime($InTime))  < 12) {
	$OutHour = Date('h', strtotime(InTime));
	$ampm = "am";
	}
	if ( Date('h', strtotime($InTime)) == 12 ) {
	$OutHour = Date('h', strtotime($InTime));
	$ampm = "PM";
	}
	if ( Date('h', strtotime($InTime)) > 12) {
	$OutHour = Date('h', strtotime($InTime)) - 12;
	$ampm = "PM";
	}
	$minutes = Date('m', strtotime($InTime));
	IF (strLEN($minutes) < 2 ) {$minutes ="0" . $minutes;} 
	IF (strLEN($OutHour) < 2 ) { $OutHour = "0" . $OutHour; }
	$ampmTime = $OutHour . ":" . $minutes . $ampm;

	return $ampmTime;
	}

	Function AmPmCompIn1($lsHr) { // need two because out should default to PM for new record
	If ($lsHr >= 12) {
			$AmPmCompIn1 = "PM";
	} Else {
			$AmPmCompIn1 = "AM";
	}
	return $AmPmCompIn1;
	}


	// php iif equivelent	 (condition ? true : false)
	// function iff($cond,$istrue,$isfalse) {
	// 	return(($cond == $istrue)?$istrue:$isfalse);
	// }
		
	?>

	 <div class="mobile-spacer"></div>
	<form id ="webtime" action="manatal_save_mobile.php" method="post" onSubmit="return  validateFields()"> 
	<?php include "global2.php"; ?>
	<div class="lable_class"> Assignment Number: <?php echo  $xORDER ; ?></div>
	
	<div class="lable_class"> Contractor: <?php echo $EMP ; ?></div>
	
	<div class="lable_class"> Customer: <?php echo $JOB ; ?></div>
	
		<div class="lable_class"> Position: <?php echo $TITLE ; ?></div>
		<!--
	<div class="lable_class"> PO Number:</div>

	<div class="lable_class"><input type='text' name='PoNumber' size='20' Value='<?php echo $row["PO"]; ?>' style="background-color:#edebeb;border:0px;" class="contact_input_class"></div>
	-->
	<div class="lable_class"> Week Ending: <?php echo $lsWeekEnd; ?></div>
		
	 <BR>

	 <?php for ($i = 1; $i <= 7; $i++) { ?>
<!-- start cut -->
		<div class='outerboxT'>
			<div style="float:left;width:77%;margin-top: 5px; margin-left:10px;"> 
				<a onClick="pop('popDiv<?php echo $i; ?>')">
				<h1 style="font-size: 22px;font-family:lato;color:#b22625;marging-left:10px;"><?php echo jddayofweek($i-2, 1); ?></h1>
				</a>
				<?php
					IF ( HrComp($row["INHR" . $i] ?? '') <>"00" ) { ?>
					<p style="font-size:11px;" >
					<a style=" text-decoration:none;marging-left:10px;" onClick="pop('popDiv<?php echo $i; ?>')">	
					<span id='divFrom<?php echo($i) ?>' >	From <?php echo ( HrComp1($row["INHR" . $i])) ?>:<?php	echo MinComp1($row["INHR" . $i]);?><?php echo (AmPmCompIn1($row["INHR" . $i])) ?></span> 
					<span id='divTo<?php echo $i; ?>'>	To <?php	echo (HrComp1($row["OUTHR" . $i])) ?>:<?php	echo MinComp1($row["OUTHR" . $i]);?><?php echo (AmPmComp($row["OUTHR" . $i])) ;?></span>
					<span id='divLunch<?php echo($i) ?>'>Lunch <?php echo (HrComp($row["BREAK"  . $i])); ?>:<?php echo MinComp1($row["BREAK" . $i]); ?> Hrs</span>
					<span id="TotalDaily<?php echo $i; ?>">0:00 hrs</span>
	 				</a></p>
				<?php } Else { ?>
					<a style="font-size:11px; text-decoration:none;marging-left:10px;" onClick="pop('popDiv<?php echo($i) ;?>')">Click to enter daily timeframe</a>
				<?php  } ?>
			</div>
			<div style="float:right;width:19%;margin-top:30px;"> 
			

<label id="TotalDaily<?php echo $i; ?>">
  <?php
    echo number_format(
      round(
        (float)($row["OUTHR{$i}"] ?? 0)
        - (float)($row["INHR{$i}"] ?? 0)
        - (float)($row["BREAK{$i}"] ?? 0),
      2),
    2);
  ?>&nbsp;hrs
</label>

			</div>
		</div>
		<div style="margin-left:-20px;" id='popDiv<?php echo $i; ?>' class="ontop">
			<div id="popup1">
	  		<table>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr> 

			<tr>
				<td style="text-align:center; color:#b22625;"> START</td>
				<td style="text-align:center; color:#b22625;"> FINISH</td>
				<td style="text-align:center; color:#b22625;"> BREAK</td>
			</tr>
			<tr>
				<td style="padding-left:10px;"><input type="time"  id='TimeInHr<?php echo $i; ?>'  name='TimeInHr<?php echo $i; ?>' onchange='Converttimeformat(<?php echo $i; ?>);' placeholder="hrs:mins"  style="width:100px;" pattern="^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$" Value='<?php echo DecToTime($row["INHR" . $i]);?>'/>
				</td>			
			
				<td style= "padding-left:5px;" >
					<input type="time" id='TimeOutHr<?php echo($i) ?>'  style="width:100px;" name="TimeOutHr<?php echo($i) ?>"  onchange='Converttimeformat(<?php echo $i; ?>);' placeholder="hrs:mins"  pattern="^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$" value='<?php echo DecToTime($row["OUTHR" . $i]);?>'/>
				</td>
<td style="padding-left:5px;">
  <select
    id="BreakHr<?php echo $i; ?>"
    name="BreakHr<?php echo $i; ?>"
    onchange="Converttimeformat(<?php echo $i; ?>);"
    size="1"
    style="width: 60px;"
  >
    <?php
    for ($Hours = 0; $Hours <= 23; $Hours++) {
      for ($Mins = 0; $Mins <= 45; $Mins += 15) {
        $hm = str_pad($Hours, 2, "0", STR_PAD_LEFT) . ":" . str_pad($Mins, 2, "0", STR_PAD_LEFT);
        $selected = (DecToTime($row["BREAK" . $i]) === $hm) ? " selected" : "";
        echo "<option value=\"{$hm}\"{$selected}>{$hm}</option>";
      }
    }
    ?>
  </select>
</td>
			</tr>
			<tr>
				<td  colspan="3">&nbsp;</td>
			</tr>
			<tr>

				<td  colspan="3">

					<div style="width:100%; text-align:center;"> 
						<input type="button" style="width:31%;" class="Button1" name="btnCacel"  onClick="hide('popDiv<?php echo($i) ?>')"  value="Cancel"> &nbsp;		  
						<input
							type="button"
							style="width:30%;"
							class="Button1"
							name="btnCacel"
							onClick="Converttimeformat(<?php echo($i) ?>); hide('popDiv<?php echo($i) ?>')"
							value="OK"
						>				&nbsp; 
						<input style="width:31%;" type="button" class="Button1" name="btnClear"  onClick="retun:CLearText('<?php echo($i) ?>');"  value="Clear"> 
		  			</div>
				</td>
			</tr>
			</table>
		</div>
	</div>
	<input type='hidden' name='TimeInAmPm<?php echo($i); ?>' value='<?php echo AmPmComp($row["INHR" . $i]);?> '>
	<input type='hidden' name='TimeOutAmPm<?php echo($i); ?>' value='<?php echo AmPmComp($row["OUTHR" . $i]);?> '>
	 <?php } ?>

<?php
echo "<input type='hidden' name='EMP' value='" . htmlspecialchars($EMP ?? '', ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='EMAIL' value='" . htmlspecialchars($_REQUEST['Email'] ?? '', ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='DATE' value='" . htmlspecialchars($row['DATE'] ?? '0000-00-00', ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='JOB' value='" . htmlspecialchars($JOB ?? '', ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='ITEM' value='" . htmlspecialchars($TITLE ?? '', ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='TITLE' value='" . htmlspecialchars($TITLE ?? '', ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='NOTE' value='" . htmlspecialchars($row['NOTE'] ?? '', ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='PROJ' value='" . htmlspecialchars($PROJ ?? '', ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='xORDER' value='" . htmlspecialchars(($xORDER ?? ''), ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='DURATION' value='" . htmlspecialchars(($row['DURATION'] ?? ''), ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='BILLINGSTATUS' value='" . htmlspecialchars(($row['BILLINGSTATUS'] ?? ''), ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='PITEM' value='" . htmlspecialchars(($row['PITEM'] ?? 0), ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='BITEM' value='" . htmlspecialchars(($row['BITEM'] ?? 0), ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='Contractor_ID' value='" . htmlspecialchars($Contractor_ID ?? '', ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='BILLINGPROFILE' value='" . htmlspecialchars(($row['BILLINGPROFILE'] ?? ''), ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='WKEND' value='" . htmlspecialchars($lsWeekEnd ?? '', ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='billingcycle' value='" . htmlspecialchars(($billingcycle ?? ''), ENT_QUOTES, 'UTF-8') . "'>";
?>


	<input type="hidden" name="REPORTTO" value="<?php echo $REPORTTO; ?>">
	<input type="hidden" name="REPORTTOCC" value="<?php echo $REPORTTOCC; ?>">
<?php
echo "<input type='hidden' name='BRANCH' value='" . htmlspecialchars(($row['BRANCH'] ?? ''), ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='INVMETHOD' value='" . htmlspecialchars(($row['INVMETHOD'] ?? ''), ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='ALTETIME' value='" . htmlspecialchars(($ALTETIME ?? ''), ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='ALTEMPMAIL' value='" . htmlspecialchars(($row['ALTEMPMAIL'] ?? ''), ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='AcctEmail' value='" . htmlspecialchars(($invoice_cc_mail ?? ''), ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='DIVISION' value='" . htmlspecialchars(($row['DIVISION'] ?? ''), ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='CONTRACT' value='" . htmlspecialchars(($row['Contract'] ?? ''), ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='INVOICECC' value='" . htmlspecialchars(($invoice_cc_mail ?? ''), ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='PO' value='" . htmlspecialchars(($PO ?? ''), ENT_QUOTES, 'UTF-8') . "'>";
echo "<input type='hidden' name='PoNumber' value='" . htmlspecialchars(($row['po_number'] ?? ''), ENT_QUOTES, 'UTF-8') . "'>";
?>

	&nbsp;		

			  <div class="lable_class" style="float:left; height:60px;"><img src="/webtime/css/star_img.png" alt="" />
				This assignment is:
					<?php IF ($lsNewWeb <> "True") { ?>
						<?php IF ($row["CONT"] ?? 0 == "1") { ?>
							<div class="lable_class" >
								<input id="Assignment1" name="Continuing" type="radio"  value ="1" >Continuing&nbsp;
							</div>
							<div style="float:left;" class="lable_class" >
								<input id="Assignment2" type="radio"  name="Continuing" value="0">Over<!--, this is my last timesheet for this job-->
							</div>
						<?php } Else { ?>
							<div class="lable_class" >
								<input id="Assignment1" name="Continuing" type="radio"  Value = "1">Continuing&nbsp;
							</div>
							<div style="float:left;" class="lable_class" >
								<input id="Assignment2" type="radio"  name="Continuing" value="0" >Over<!--, this is my last timesheet for this job-->
							</div>
						<?php } ?>
					</div>
					<?php } ELSE { ?>
						<div class="lable_class" >
				  			<input id="Assignment1" name="Continuing" type="radio"  Value = "1" required>Continuing&nbsp;</font>
						</div>
				  		<div class="lable_class" >
							<input id="Assignment2" type="radio"  name="Continuing" value="0" >Over<!--, this is my last timesheet for this job-->
						</div>
					<?php } ?>
					

			<br />
			<!-- this is how the buttons should be -->
			<div  class="center" style="height:40px;">
<div style="clear:both;height:1px;"></div>
<div class="center" style="clear:both; padding-top:10px; position:relative; z-index:1;">
<!-- <input type="submit" id="Next" onclick = "return window.parent.scroll(0,0);" name="Next" value="Next" class="Button" style="border:0px solid #fff;" >
-->
  <input
    type="submit"
    id="Next"
    name="Next"
    value="Next"
    class="Button"
    style="border:0; width:99%; display:block;"
    onclick="(window.parent && window.parent.scroll ? window.parent.scroll(0,0) : window.scroll(0,0)); return true;"
  >
</div>
			</div>
<!-- end cut -->	
	<?php

	} ELSE {
		echo("You must check an assignment, please click the back button and try again");
	
	}
	
	?>		


	</Form>
		
	<P>&nbsp;
			
	</p>
	

	<script language="javascript1.2">
	
	window.onload = function() {
  	TotalDailyCal_All();
	};
</script>
	</body>
	</html>
