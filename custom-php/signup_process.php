<?php 
    
    $item_num = $_POST['item_num'];
    global $current_state;
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $nickname = $_POST['nickname'];

?>


<html>
        <head><title>::Application::</title></head>
        <body >
        <form method="post" name="signup_form" action= "/entake/register/application1.asp">
        
            <input id="item_num" name="item_num" value="<?php echo $item_num ?>" type="text"/>
            <br/>
            <input id="firstname" name="firstname" value="<?php echo $firstname ?>" type="text" />
            <br/>
            <input id="lastname" name="lastname" value="<?php echo $lastname ?>" type="text"  />
            <br/>
            <input id="nickname" name="nickname" value="<?php echo $nickname ?>" type="text"   />
            <br/>            
    
            onLoad="document.signup_form.submit();"
            
        <center><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="333333">Submitting application . . . </font></center>
        
        </form>
        </body>   
        </html>
