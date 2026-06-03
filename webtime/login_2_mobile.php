<html>
<Head>

    <link href="/webtime/css/mobile/styles.css" rel="stylesheet" type="text/css" />
     <link rel="stylesheet" type="text/css" href="/webtime/css/css.css" />

        
    <link href='http://fonts.googleapis.com/css?family=Lato|Rokkitt' rel='stylesheet' type='text/css' />
</head>

<script type="text/javascript">
function submitform()
{
  document.myform.submit();
}
window.onload = function() {
  document.getElementById("login").focus();
};
</script>
 <style>
 .lable_class{
 
  font-size: 12px;
  color: #6a7785;
  line-height: 22px;
  margin-left:10px;
 }
 .contact_input_class{
  
  font-size: 12px;
  color: #6a7785;
  height: 30px;
  width:100%;
 }
 .contact_input_class1{
  
  font-size: 12px;
  color: #6a7785;
  height: 30px;
  width:20px;
 }
  .contact_textarea_class{
  
  font-size: 12px;
  color: #6a7785;
  height: 100px;
  width:95%;
 }
 .Button{
padding-top:3px;
WIDTH:93%;
height:40px; 
padding-left: 11px;
margin-top: 12px;
background-color: #b22625;
/*border-radius: 5px;*/
color: white;
border:none;

}
</style>



<div id="content_timesheet">
	<h1 class="center" style="font-size: 37px; color:#6C6A6B;font-weight:bolder;font-family:lato;"> Timesheet  </h1>
	<h1 class="center" style="font-size: 37px; color:#6C6A6B;font-weight:bolder;font-family:lato;">  Login</h1>

	<!-----  start login  -->
  
		 <p style="padding-top:20px;" class="lable_class">Enter your email &amp; password to proceed.</p>


               <form action="asn_browse_mobile.php" method="post" name="myform">

	       <input type="hidden" name="action" value="validate_login" />

		<div class="lable_class">	

                  <input type="email" name="login"  id="login" size="20" type="Password" value="<?php echo($_REQUEST["user"]) ?>"  class="contact_input_class" onfocus="if(this.value=='email')this.value = '';" onblur="if(this.value=='')this.value = 'email';" style="MAX-WIDTH:90%" />
		</div>
		<br />
		<br />
				            

		<div class="lable_class">
                     <input  type="Password" name="password" size="20" value="password" class="contact_input_class" onfocus="if(this.value=='password')this.value='';" onblur="if(this.value=='')this.value='password';" style="MAX-WIDTH:90%" />
 		</div>
             
		<div class="clear"/>
			<div class="clear"/>

				<!-- this is how the buttons should be -->
				<div class="lable_class">
					<input type="button" id="Next" name="Next" value="Login" onclick="javascript:submitform();"  class="Button "  >
				</div>
	         </Form>
		<!-- this is how the buttons should be -->
</div>

<!-----  end login  -->

</html>