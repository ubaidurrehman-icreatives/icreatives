<script>
$(document).ready(function() {

    <?php
    global $signupform;
    if ($signupform==1)
    {
        //Keep item number into cookie
        echo ("setCookie(\"item_number\",document.getElementById(\"item_number\").value,365);");
    }
    ?>
    
	
    //adding the event listerner for Mozilla
    if(window.addEventListener)
        document.addEventListener('DOMMouseScroll', moveObject, false);
 
    //for IE/OPERA etch
    document.onmousewheel = moveObject;

	window.onscroll = moveObject;
	
	$("#news").slideDown(500);
    
	// create the loading window and set autoOpen to false
    $("#loadingScreen").dialog({
		autoOpen: false,	// set this to false so we can manually open it
		dialogClass: "loadingScreenWindow",
		closeOnEscape: false,
		draggable: false,
		width: 560,
		minHeight: 320,
		modal: true,
		buttons: {},
		resizable: false,
        open: function() {
			// scrollbar fix for IE
			$('body').css('overflow','hidden');
		},
		close: function() {
			// reset overflow
			$('body').css('overflow','auto');
		}
	}); // end of dialog   
    
})



function waitingDialog(waiting) { // I choose to allow my loading screen dialog to be customizable, you don't have to
	$("#loadingScreen").html(waiting.message && '' != waiting.message ? waiting.message : 'Please wait...');
	$("#loadingScreen").dialog('option', 'title', waiting.title && '' != waiting.title ? waiting.title : 'Loading');
	$("#loadingScreen").dialog('open');
    
   
}
function closeWaitingDialog() {
	$("#loadingScreen").dialog('close');
}



var count = 0;
function moveObject(event)
{
	/*if(getScrollXY() == 0)
		document.getElementById("news").style.display='';
	else
   		document.getElementById("news").style.display='none';*/
	if(count == 0)
	{
		$("#news").slideUp(400);
		
		var delay = null;
		
		clearTimeout(delay);
		
		delay = setTimeout("ShowNews()", 3000);
		count = 1;
	}
}

function ShowNews()
{
	$("#news").slideDown(400);
	count = 0;
}

function getScrollXY() {
  var scrOfX = 0, scrOfY = 0;
  if( typeof( window.pageYOffset ) == 'number' ) {
    //Netscape compliant
    scrOfY = window.pageYOffset;
    scrOfX = window.pageXOffset;
  } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
    //DOM compliant
    scrOfY = document.body.scrollTop;
    scrOfX = document.body.scrollLeft;
  } else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
    //IE6 standards compliant mode
    scrOfY = document.documentElement.scrollTop;
    scrOfX = document.documentElement.scrollLeft;
  }
  return scrOfY;
}


 function setCookie(c_name,value,exdays)
    {
        var exdate=new Date();
        exdate.setDate(exdate.getDate() + exdays);
        var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
      // document.cookie=c_name + '=' + c_value + 'path=/; domain=beta.icreatives.com';
       document.cookie=c_name + '=' + c_value + 'path=/; domain=<?php echo $_SERVER['SERVER_NAME']; ?>';

    }
    
</SCRIPT>
        
