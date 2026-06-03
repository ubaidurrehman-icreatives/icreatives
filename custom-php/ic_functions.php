<?php
global $link;
require_once __DIR__ . '/../db/db.php';
$link = db(); 
function register_session(){
    if(!session_id()) session_start();
}
// add_action('init','register_session');

//    include_once(ABSPATH . "/wp-includes/registration.php" );
 //   include_once(ABSPATH. '/wp-content/custom-php/list.php');

    define('CURRENTDOMAIN',"https://".$_SERVER["SERVER_NAME"]);

// add_theme_support( 'post-thumbnails' );

    function HomeContentHandler($type)
    {
        $content = get_the_content();
        
        if ($content=="{Home-Client-Insource-News-Trends}")
        {
            ?>

            <div style="float:left; width:785px;">
                <div style="float:left; padding-left:12px;" class="largetxt1"><span class="redarrowclass1"> [</span> Insourcing news &amp; trends </div>
                <div style="float:left; padding:3px 2px 0 3px;" class="redarrowclass1"> >>> </div>

                <div style="float:left; width:785px; margin:40px 0px 0px 12px;">
                      <?php getPost_Home_Client_Insource_News_Trends();?>
                   <div style="clear:left; float:left;">
                      <div style="float:left; width:520px; margin:20px 0 0 7px;" class="phonebg">
                        <h5 style="padding:40px 80px 20px 140px;" class="whiteclass">Get thought provoking creative news and trends with live phone or email updates.<b> it's free! <a href="/News-Updates/"> (click here)</a></b> </h5>
                      <div> 
		   </div>
            </div>
             </div>
	     <div style="float:right;">
		   <!-- this is how the buttons should be --> 
	       	<div style="clear: left; padding: 92px 15px 0 0px;"> 
                   <div style="padding-top:14px;"> 
                      <div class="blankbtn" style="text-align:right; "> <a href="/iBlog/" class="blankbtntxt">Read our blog</a>		   </div>
          	         </div> 
                      </div>
		   </div>
                </div>
             </div>
             <div style="clear:both"></div>
             
            <?php           
        }       
        elseif ($content=="{Home-Talent-Creative-Trends-Industry-Updates}")
        {
            ?>
            
                <div style="float:left; width:785px;">
                   <div style="float:left; padding-left:12px;" class="largetxt1"><span class="redarrowclass1"> [</span> Creative trends &amp; industry updates </div>
                   <div style="float:left; padding:3px 2px 0 3px;" class="redarrowclass1"> >>> </div>
                    
                   <div style="float:left; width:795px; margin:40px 0px 0px 12px;">    
                        <?php getPost_Home_Talent_Creative_Trends_Industry_Updates();?>
                      <div style="clear:left; float:left height:5px;">
                          <div style=" float:left; width:500px; margin:20px 0 0 0;" class="phonebg">
                       <h5 style="padding:40px 125px 20px 140px;" class="whiteclass"> Follow us on twitter or facebook for up to the minute job posts and industry news. </h5>
                          </div>
		       </div>                    
                    

	         <div style="float:right;">
		     <!-- this is how the buttons should be --> 
	       	    <div style="clear:left; padding: 90px 17px 0 0px;"> 
                       <div style="padding-top:14px;"> 
                          <div class="blankbtn" style="text-align:right; "> <a href="/creative-blog/" class="blankbtntxt">See the blog</a>		   
    			  </div>
          	       </div> 
</div>
                    
		    </div>
                 </div>
                 </div>
                 <div style="clear:both"></div>

                
            <?php
            //get sub post.
            //echo $content;   
            
        }
        else
        {
            the_content();
        }
    }

    function getPost_Home_Client_Insource_News_Trends()
    {

             $i = 0;

            //'category'      => 'Creative-Trends-Industry-Updates',
            $args = array(
                    'category'      => 9,
                    'order'    => 'ASC',
                    'post_status'     => 'publish' 
                    );

            $my_query = get_posts($args);

            foreach($my_query as $post)
            {

                setup_postdata($post);
                    $i++;
                    if (($i>1) && ($i % 4==0))
                    {
                       ?>
            
                        </div>
                        <div style="float:left; width:142px;"> &nbsp; </div>
                        <div style="float:left; width:785px; margin:40px 0 0 12px;">
                        <?php
                    }
                    
                    $content = get_the_content();
                    parseBlog($content);
			global $postid;
			$postid = $post->ID;
			global $imgsrc;
		?>

              <div style="width:255px; float:left;">
                 <div style="height:128px; float:left;"><a href="/iblog/#<?php echo get_the_title($post->ID);?>">
<?php // the_blog_image_no_float(); ?>

<canvas id="area<?php print $post->ID ?>" width="247" height="126">
</canvas>

<!-- Javascript Code -->


<script type="text/javascript">
      function drawImage(imageObj,areaid) {
        var canvas = document.getElementById(areaid);
        var context = canvas.getContext('2d');
        var x = 0;
        var y = 0;
 	canvas.style.border = "1px solid gray"; 
        context.drawImage(imageObj, x, y);

        var imageData = context.getImageData(x, y, imageObj.width, imageObj.height);
        var data = imageData.data;

        for(var i = 0; i < data.length; i += 4) {
          var brightness = 0.34 * data[i] + 0.5 * data[i + 1] + 0.16 * data[i + 2];
          // red
          data[i] = brightness;
          // green
          data[i + 1] = brightness;
          // blue
          data[i + 2] = brightness;
        }

        // overwrite original image
        context.putImageData(imageData, x, y);
      }
      
      var imageObj = new Image();
      imageObj.onload = function() {
	var newarea = 'area<?php Print($post->ID); ?>'
        drawImage(this,newarea);
      };
      imageObj.src = '<?php Print($imgsrc); ?>';
    </script>



</a></div>
                 <div style="float:left;" class="newbottomline"></div>
                 <div style="float:left; padding:0 0 0 7px;">
                <h4> <?php  echo  get_the_title($post->ID); ?> </h4>
                        <h6><?php  
                                global $blog_content;
                                echo substr(strip_tags($blog_content),0,110)."... ";
                          ?><span class="redtxt">[ <a href="/iblog/#<?php echo get_the_title($post->ID);?>"><span class="grayclass"> Check out the blog </span></a> <span class="redtxt">>>></span> 
                     </h6>
                 </div>
              </div>                           
                    <?php                  
                    
            }
            wp_reset_postdata();
            
    }
    
    function getPost_Home_Talent_Creative_Trends_Industry_Updates()
    {
           $i = 0;

            //'category'      => 'Creative-Trends-Industry-Updates',
            $args = array(
                    'category'      => 8,
                    'order'    => 'ASC',
                    'post_status'     => 'publish' 
                    );

            $my_query = get_posts($args);

            foreach($my_query as $post)
            {

                setup_postdata($post);
                    $i++;
                    if (($i>1) && ($i % 4==0))
                    {
                       ?>
            
                        
                        <div style="float:left; width:142px;"> &nbsp; </div></div>
                        <div style="float:left; width:785px; margin:40px 0 0 12px;">
                        <?php
                    }
                    
                    $content = get_the_content();
                    parseBlog($content);

			global $postid;
			$postid = $post->ID;
			global $imgsrc;                 
                    
                    ?>
              <div style="width:255px; float:left;">
                 <div style="height:128px; float:left;"><a href="/creative-blog/#<?php echo get_the_title($post->ID);?>">
<?php // the_blog_image_no_float(); ?>


<canvas id="area<?php print $post->ID ?>" width="247" height="126">
</canvas>

<!-- Javascript Code -->


<script type="text/javascript">
      function drawImageTalent(imageObj,areaid) {
        var canvas = document.getElementById(areaid);
        var context = canvas.getContext('2d');
        var x = 0;
        var y = 0;
 	canvas.style.border = "1px solid gray"; 
        context.drawImage(imageObj, x, y);

        var imageData = context.getImageData(x, y, imageObj.width, imageObj.height);
        var data = imageData.data;

        for(var i = 0; i < data.length; i += 4) {
          var brightness = 0.34 * data[i] + 0.5 * data[i + 1] + 0.16 * data[i + 2];
          // red
          data[i] = brightness;
          // green
          data[i + 1] = brightness;
          // blue
          data[i + 2] = brightness;
        }

        // overwrite original image
        context.putImageData(imageData, x, y);
      }
      
      var imageObj = new Image();
      imageObj.onload = function() {
	var newarea = 'area<?php Print($post->ID); ?>'
        drawImageTalent(this,newarea);
      };
      imageObj.src = '<?php Print($imgsrc); ?>';
    </script>



</a></div>
                 <div style="float:left;" class="newbottomline"></div>
                 <div style="float:left; padding:0 0 0 7px;">
                <h4> <?php  echo  get_the_title($post->ID); ?> </h4>
                        <h6><?php  
                                global $blog_content;
                                echo substr(strip_tags($blog_content),0,110)."... "
                          ?><span class="redtxt">[ <a href="/creative-blog/#<?php echo get_the_title($post->ID);?>"><span class="grayclass"> Check out the blog </span></a> <span class="redtxt">>>></span> 
                     </h6>
                 </div>
              </div>   

                    
 	<?php   
		// if (($i>1) && ($i % 4==0))
                //    { echo "</div>";}
               
                    
            }
            wp_reset_postdata();
    }

    function getWPPost($content, $i)
        {      
                
               $s_array = explode('[paragraph]', $content);
               if ($i<sizeof($s_array))
               {
                   return $s_array[$i];  
               }
            return ""; 
        }
        
    function printTitleBegin_BP()
        {      
        ?>
       <div class="rightpart" >
            <div style="float: left;">
            <div class="tabline1" style="float: left; padding: 54px 0 0 10px;"><span class="redtxt"> i creatives </span>   |  <span class="blackclass"> creative staffing </span></div>
            <div style="float: right; padding: 42px 70px 0 0;">
            <div style="float: left;">
            <div style="float: left;"><img src="<?php bloginfo('template_url'); ?>/images/left_braket.png" alt="" /></div>
            <div style="float: left; padding-top: 8px;"><a href="#"> <img src="<?php bloginfo('template_url'); ?>/images/search.png" alt="" /> </a></div>
            <div style="float: left;"><img src="<?php bloginfo('template_url'); ?>/images/right_bracket.png" alt="" /></div>
            </div>
            </div>
            <div style="clear: left; padding: 92px 0 0 12px;">
                            
        <?php                
        }

    function printTitleEnd_BP()
        {      
        ?>
            </div>
            </div>
            </div>
            </div>          
            </div>
        <?php                
        }
        

    function ic_gen_signup_form_state()
    {
        //generate state variables
        global $current_state;
        global $item_num;
        
        $current_state = $_POST['current_state'] ?? '';
        
        //retrieve item_number
        $item_num = init_app_item();
        
        if (!ISSET($current_state) || $current_state=="")
        {
            $current_state = "INIT";
        }
        elseif ($current_state=="INIT")
        {
            $current_state = "DONE";
        }        
        return;
    }
    
    function ic_clear_signup_form_state()
    {
        global  $item;
        
        $item = $_GET["item_num"];
        
        if ($item=="")
        {
            //Read from $GET
            if (!ISSET($_POST["item_number"]))
            {
                //Read from $post
                $item = $_POST["item_number"];
            }           
        }
        
        //Read from cookies
        if (!ISSET($item))
        {
            $item = $_COOKIE['item_number'];
        }

        //generate new item
        if ($item!="")
        {
            ic_db_backup_field($item);
        }
        

        ##die ("cookies value for it is: " . $item);
        return $item;
        
    }
    

    function ic_process_signup_form_state()
    {
        global $current_state;
        global $item_num;

        
        if ($current_state=="DONE")
        {
            $current_state = "PROCESS";
            require_once(ABSPATH. '/wp-content/themes/porto-child/custom-php/signup_process.php');
        }        
        
        return;
    }   
    
    
    function ic_get_signnup_longform_variables()
    {
    
        //loop in list of variables and set value for them
        ##for(x as y)
        ##{
        ##    ic_get_set_var(x);
        ##}
        return;
    }
    
    
    function ic_get_var($name)
    {
        if (ISSET($_POST[$name]))
        {
            return $_POST[$name];
        }
        else
        {
            return "";
        }
    }
    
    function ic_write_var($value,$name)
    {        
        $html = "<input type='hidden' name='_" . $name . "' value='" . $value."/>";
        return $html;
    }   
    
  
    function ic_db_write_field($item, $f_name, $f_value)
    {
          //  $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
          //  $dbcheck = mysqli_select_db(DB_NAME);

			global $link;

  
           
           $sql="delete from ic_app_info where item_num='". $item ."' and f_name='" . $f_name . "'";
           $result = mysqli_query($sql) or  die (mysqli_error());
           
           $sql="insert into ic_app_info(item_num, f_name, f_val) select '". $item ."','". $f_name . "','" . $f_value . "'";
           $result = mysqli_query($sql) or  die (mysqli_error());
           ##mysqli_free_result($result);
           
           return;
    }
    
    
  // Simple Function to get a Dropdown with Month and if
  // one if there is saved data, one wil get selected
  // @return string html
  function getSelectedMonth($item_num,$id)
  {
	  $legend = 'mo';
	  $value = get_field_value($item_num, $id, ""); 
	  
	  // Generate Select with Options
	  $html = "<select class='styled' style='width:100px; z-index:1000;' id='{$id}' name='{$id}'>
	           <option value=''>{$legend}</option>";
	  
	  	  // Available Month
	      for($i=1;$i<13;$i++)
	      {
	         $html .= "<option ";
	         if($value == $i) $html .= "selected "; 
		    if ($i<10) {
	        	 	$html .= " value='0{$i}'>{$i}</option>";
			}else{
			      	$html .= " value='{$i}'>{$i}</option>";
		    }
	      }
	      
      $html .= "</select>";
      return $html;	  
  }  
  

  // Simple Function to get a Dropdown with Years and if
  // one if there is saved data, one wil get selected
  // @return string html
  function getSelectedYears($item_num,$id,$legend = false)
  {   
  
	  $legend = $legend == true ? $legend : 'year';
	  $value = get_field_value($item_num, $id, ""); 
	  
	  // Generate Select with Options
	  $html = "<select class='styled' style='width:149px; z-index:1000;' id='{$id}' name='{$id}' >
	           <option value =''>{$legend}</option>  ";
	  
	  	  // Available Years
	      for($i=date("Y");$i>=(date("Y")-40);$i--)
	      {
	         $html .= "<option ";
	         if($value == $i) $html .= "selected "; 
	         $html .= " value='{$i}'>{$i}</option> ";
	      }
	      
      $html .= "</select>";
      return $html;	  
  }
  
  
  
  
    function the_field_value($item, $f_name,$default)
    {
        global $text_field;
       
        if ($text_field=="")
        {
            $text_field .= $f_name;
        }
        else
        {
            $text_field .= ";" . $f_name;
        }
        
        echo get_field($item, $f_name,$default);
    }
    
        function get_field_value($item, $f_name,$default)
    {
        global $text_field;
       
        if ($text_field=="")
        {
            $text_field .= $f_name;
        }
        else
        {
            $text_field .= ";" . $f_name;
        }
        
        return get_field($item, $f_name,$default);
    }
    
    
    
    function get_field($item, $f_name,$default)
    {
        global $row;
        
        if (ISSET($_POST[$f_name]))
        {
            $val = $_POST[$f_name];
        }
        elseif (ISSET($_GET[$f_name]))
        {
            $val = $_GET[$f_name];
        }
        else 
        {
            $val = get_field_from_array($item, $f_name,$default);
        }
        return $val;
    }
    
    
    function get_field_from_array($item, $f_name,$default)
    {
        global $row_loaded;
        global $rows;
        
        if ($row_loaded!=1)
        {
            ic_db_read_field($item);
        }
        
        for ($i=0;$i<count($rows);$i++)
        {
            $row=$rows[$i];
            
            $key=$row["f_name"];
            $value=$row["f_val"];
            
            if ($key==$f_name)
            {
               return $value;
            }
        }        
        ##not found
        return $default;
    }    
    
    
    function ic_db_read_single_field($item, $f_name)
    {
           $f_val="";
           
           // $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
           // $dbcheck = mysqli_select_db(DB_NAME);

	$link = mysqli_connect('localhost', 'wordpress_2', '9g1EXn$q8A') or die("Error: " . mysqli_error());
	$dbcheck =  mysqli_select_db($link,'wordpress_7');

 
           
           $sql="select * from ic_app_info where item_num='". $item ."' and f_name='" . $f_name . "'";
           $result = mysqli_query($sql) or  die (mysqli_error());

           $row = mysqli_fetch_array($result) or die(mysqli_error());

           $f_val=$row['f_val'];
           
           mysqli_free_result($result);
           
           return $f_val;
    }
    
    function loadValue($f_name)
    {
        global $item;
        return get_field($item, $f_name,"");
    }
    
    function ic_db_backup_field($item)
    {
         //  $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
         //  $dbcheck = mysqli_select_db(DB_NAME);
	$link = mysqli_connect('localhost', 'wordpress_2', '9g1EXn$q8A') or die("Error: " . mysqli_error());
	$dbcheck =  mysqli_select_db($link,'wordpress_7');

 
           
           //backup records       
           $sql="insert into  ic_app_info_all(item_num, f_name, f_val, d_t) select item_num, f_name, f_val, d_t from ic_app_info where item_num='". $item ."'";
           mysqli_query($sql) or  die (mysqli_error());
           
           //clear old one
           $sql="delete  from ic_app_info where item_num='". $item ."'";
           mysqli_query($sql) or  die (mysqli_error());
           
           return true;
    }    
    
    
    function ic_db_read_field($item)
    {
           global $row_loaded;
           global $rows;
           global $valid_step;
		   global $link;
           
          //  $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
           // $dbcheck = mysqli_select_db(DB_NAME);
	 // $link = mysqli_connect('localhost', 'wordpress_2', '9g1EXn$q8A') or die("Error: " . mysqli_error($link));

			$link = db();   
             
           $msql="select f_name, f_val from ic_app_info where item_num='" . $item . "'";
           $result = mysqli_query($link,$msql) or  die (mysqli_error($link));

            $rows = array();            
            //mysqli_data_seek($result, 0);            
            while ($row = mysqli_fetch_assoc($result)) 
            {
                ##check if first name, last name and email was enter
                if ($row["f_name"]=="Email")
                {
                   
                    $value=$row["f_val"];
                    
                    if ($value!="" && $value!="email address")
                    {
                        $valid_step=true;
                    }
                }
                
                array_push($rows, $row);                
            }
            
            mysqli_free_result($result);
            $row_loaded = 1;
           
            return true;
    }
?><?php function printRadio($name)
    {
        ##load value for this field
    
        global $radio_field;
        ##load value for this field
        $val = loadValue($name);    
        
        if ($radio_field=="")
        {
            $radio_field .= $name;
        }
        else
        {
            $radio_field .= ";" . $name;
        }     
    ?>    
        <div style="float:left;">
                <input type="radio" id="<?php echo $name ?>" name="<?php echo $name ?>" class="styled"  
                 <?php 
                        if ($val=="")
                        {
                            echo "checked='true' ";                    
                        }
                 ?>
                value=""/>
              </div>             
              <div style="float:left; padding-right:5px;" class="graytxt2"> n/a </div>
                
              <div style="float:left;">
                <input type="radio" id="<?php echo $name ?>" name="<?php echo $name ?>" class="styled" 
                 <?php 
                        if ($val=="09")
                        {
                            echo "checked='true' ";                    
                        }
                 ?>
                value="09" />
              </div>
              <div style="float:left; padding-right:5px;" class="graytxt2"> beginner </div>
                <div style="float:left;">
                <input type="radio" id="<?php echo $name ?>" name="<?php echo $name ?>" class="styled" 
                 <?php 
                        if ($val=="19")
                        {
                            echo "checked='true' ";                    
                        }
                 ?>
                value="19" />
              </div>
              <div style="float:left; padding-right:5px;" class="graytxt2"> proficient </div>
              
              <div style="float:left;">
                <input type="radio" id="<?php echo $name ?>" name="<?php echo $name ?>" class="styled"
                  <?php 
                        if ($val=="29")
                        {
                            echo "checked='true' ";                    
                        }
                 ?>
                value="29" />
              </div>
              <div style="float:left; padding-right:30px;" class="graytxt2"> expert </div>

<?php  }
?><?php function printAVAIL()
    {
        ##load value for this field
    
        global $radio_field;
        ##load value for this field
        $name = 'AVAIL_TEMP';
        $val1 = loadValue($name);    
        
        if ($radio_field=="")
        {
            $radio_field .= $name;
        }
        else
        {
            $radio_field .= ";" . $name;
        }
        
        $name = 'AVAIL_CAREER';
        $val2 = loadValue($name);    
        
        if ($radio_field=="")
        {
            $radio_field .= $name;
        }
        else
        {
            $radio_field .= ";" . $name;
        }
        
        $name = 'AVAIL_CONTRACT';
        $val3 = loadValue($name);    
        
        if ($radio_field=="")
        {
            $radio_field .= $name;
        }
        else
        {
            $radio_field .= ";" . $name;
        }      
        
    ?>  
    
    
                <div style="float:left; padding-left:10px;" class="applytxt1"> Yes <br />
                  <input type="radio" name="AVAIL_TEMP" id="AVAIL_TEMP" value="1" class="styled"  <?php 
                        if ($val1=="1")
                        {
                            echo "checked='true' ";                    
                        }
                 ?>/>
                  <br />
                  <input type="radio" name="AVAIL_CAREER"  id="AVAIL_CAREER" value="1" class="styled"  <?php 
                        if ($val2=="1")
                        {
                            echo "checked='true' ";                    
                        }
                 ?> />
                  <br />
                  <input type="radio" name="AVAIL_CONTRACT" id="AVAIL_CONTRACT" value="1"  class="styled" <?php 
                        if ($val2=="1")
                        {
                            echo "checked='true' ";                    
                        }
                 ?>/>
                </div>
                <div style="float:left; padding-left:20px;" class="applytxt1"> No<br />
                  <input type="radio" name="AVAIL_TEMP" id="AVAIL_TEMP" value="0" class="styled"  <?php 
                        if ($val1=="0")
                        {
                            echo "checked='true' ";                    
                        }
                 ?>/>
                  <br />
                  <input type="radio" name="AVAIL_CAREER"  id="AVAIL_CAREER"   value="0" class="styled"  <?php 
                        if ($val2=="0")
                        {
                            echo "checked='true' ";                    
                        }
                 ?>/>
                  <br />
                  <input type="radio" name="AVAIL_CONTRACT" id="AVAIL_CONTRACT"  value="0"  class="styled" <?php 
                        if ($val3=="0")
                        {
                            echo "checked='true' ";                    
                        }
                 ?> />
                </div>
<?php  } 
?><?php function printYearExpr($name)
    {
    
        global $radio_field;
        ##load value for this field
        $val = loadValue($name);    
        
        if ($radio_field=="")
        {
            $radio_field .= $name;
        }
        else
        {
            $radio_field .= ";" . $name;
        }
        
    ?>    
      <div style="float:left;">
                <input type="radio" id="<?php echo $name ?>" name="<?php echo $name ?>" class="styled"  
                    <?php 
                        if ($val=="")
                        {
                            echo "checked='true' ";                    
                        }
                    ?> 
                value=""/>
              </div>
              <div style="float:left; padding-right:20px;" class="graytxt2"> none </div>
              <div style="float:left;">
                <input type="radio" id="<?php echo $name ?>" name="<?php echo $name ?>" class="styled" 
                    <?php 
                        if ($val=="09")
                        {
                            echo "checked='true' ";                    
                        }
                    ?> value="09" />
              </div>
              <div style="float:left; padding-right:20px;" class="graytxt2"> up to 1 </div>
              <div style="float:left;">
                <input type="radio" id="<?php echo $name ?>" name="<?php echo $name ?>" class="styled" 
                <?php 
                        if ($val=="19")
                        {
                            echo "checked='true' ";                    
                        }
                    ?>value="19"/>
              </div>
              <div style="float:left; padding-right:20px;" class="graytxt2"> 2-4 </div>
              <div style="float:left;">
                <input type="radio" id="<?php echo $name ?>" name="<?php echo $name ?>" class="styled" 
                 <?php 
                        if ($val=="29")
                        {
                            echo "checked='true' ";                    
                        }
                 ?>
                 value="29" />
              </div>
              <div style="float:left; padding-right:30px;" class="graytxt2"> 5+ </div>

<?php  }





?><?php function printVolumn($name)
    {
        global $radio_field;
        ##load value for this field
        $val = loadValue($name);    
        
        
        if ($radio_field=="")
        {
            $radio_field .= $name;
        }
        else
        {
            $radio_field .= ";" . $name;
        }        
        
    ?>  
                
               <div style="float:left;">
                <input type="radio" name="<?php echo $name ?>"  id="<?php echo $name ?>" class="styled"  
                 <?php 
                        if ($val=="")
                        {
                            echo "checked='false' ";                    
                        }
                 ?>
                value=""/>
              </div>
              <div style="float:left; padding-right:22px;" class="graytxt2"> none </div>
              <div style="float:left;">
                <input type="radio" name="<?php echo $name ?>"  id="<?php echo $name ?>" class="styled" 
                <?php 
                        if ($val=="09")
                        {
                            echo "checked='true' ";                    
                        }
                 ?>
                value="09"/>
              </div>
              <div style="float:left; padding-right:23px;" class="graytxt2"> 1-4 </div>
              <div style="float:left;">
                <input type="radio" name="<?php echo $name ?>"  id="<?php echo $name ?>" class="styled"  
                 <?php 
                        if ($val=="19")
                        {
                            echo "checked='true' ";                    
                        }
                 ?>
                value="19"/>
              </div>
              <div style="float:left; padding-right:23px;" class="graytxt2"> 5-9 </div>
              <div style="float:left;">
                <input type="radio" name="<?php echo $name ?>"  id="<?php echo $name ?>"  class="styled"  
                 <?php 
                        if ($val=="29")
                        {
                            echo "checked='true' ";                    
                        }
                 ?>
                value="29"/>
              </div>
              <div style="float:left; padding-right:30px;" class="graytxt2"> 10+ </div>

<?php  } 
?><?php

    function init_app_item()
    {
        global $rows;
        global  $item;
        global $radio_field;
        global  $cbo_field;
        global $text_field;
        
        
        //Read from $post
        $item = $_POST["item_number"] ?? '';
        
        //Read from $GET
        if (!ISSET($item))
        {
            $item = $_GET["item_num"];
        }
        
        //Read from cookies
        if (!ISSET($item))
        {
            $item = $_COOKIE['item_number'];
        }

        //generate new item
        if ($item=="")
        {
            $item= uniqid (rand (),false);
        }

        //Read data from db
        ic_db_read_field($item);
        $radio_field="";
        $cbo_field="";
        $text_field="";
        ##die ("cookies value for it is: " . $item);
        return $item;
    }


	function save_form_variables()
	{
		foreach ($_POST as $key => $value) {
		echo "<input id='" . $key . "' name = '" .$key. "' value = '". $value . "' type = 'hidden' />" ;
		}
	}
    
    
    function show_variables()
    {
        global $row_loaded;
        global $rows;

        
        if ($row_loaded!=1)
        {
            ic_db_read_field($item);
        }
        
        for ($i=0;$i<count($rows);$i++)
        {
            $row=$rows[$i];
            $key="_". $row["f_name"];
            $value=$row["f_val"];
            
            ?>
                <input id="<?php echo $key; ?>" name="<?php echo $key; ?>" value="<?php echo $value; ?>" type="hidden"/>
            <?php
        }        
        ##not found
        return true;

    }
    
    
    function parseBlog($str)
    {
        global $blog_image;
        global $blog_content;
        global  $blog_image_no_float;
	global $imgsrc;

        
        ##search for </a?
        $pos = strpos ($str, "<img");
       
        if ($pos === false) {
           $blog_image="";
           $blog_content=$str;           
        }
        else 
        {
        
           $pos2 = strpos ($str, "/>");        
           $blog_image=substr($str, $pos, $pos2+2); 

           $blog_image_no_float = str_replace('style="float: left; padding: 10px;"', '', $blog_image);
          
           $blog_content=substr($str, $pos2+2);   


	## delete the class for proper alignment added by SC 09/10/2012
       $pos = strpos ($blog_image_no_float, "class=");
       $pos2 = strpos ($blog_image_no_float, '"', $pos+8);
	if ($pos !== false) {
           $blog_image_no_float=substr($blog_image_no_float,0,$pos).substr($blog_image_no_float,$pos2+2) ; 
 	}

 // added this to fix image problems on home page SC 07/01/2012
           $blog_image_no_float= SubStr($blog_image_no_float,0,(strpos($blog_image_no_float,'width')-1))."id = 'x". "XX" . "' style='margin:0; padding:0; border: 0px #E0DEDE solid; background:#E0DEDE' width='248' height='127' />";

//	$imgsrc_pos1 = strpos($str, "/wp-content/uploads");
	$imgsrc_pos1 = strpos($str, "https://".$_SERVER["SERVER_NAME"]."/wp-content/uploads");
	$imgsrc_pos2 = strpos($str, '"', $imgsrc_pos1);
	$imgsrc = substr($str,$imgsrc_pos1,$imgsrc_pos2 - $imgsrc_pos1); 

		        
        }
        return true;
    }   
    
    function the_blog_image()
    {
        global $blog_image;
        
        echo $blog_image;
    
        return true;
    }
    
    function the_blog_image_no_float()
    {
        global $blog_image_no_float;
	global $postid;
    	    $blog_image_no_float = str_replace("XX",$postid,$blog_image_no_float) ;
        echo $blog_image_no_float;
    
        return true;
    
    }
    
    function the_blog_content()
    {
        global $blog_content;
        
        echo $blog_content;
    
        return true;
    }
    
 



function docx2text($txtfilename) {
   return readZippedXML($txtfilename, "word/document.xml");
 }

function readZippedXML($archiveFile, $dataFile) {
// Create new ZIP archive
$zip = new ZipArchive;

// Open received archive file
if (true === $zip->open($archiveFile)) {
    // If done, search for the data file in the archive
    if (($txtindex = $zip->locateName($dataFile)) !== false) {
        // If found, read it to the string
        $data = $zip->getFromIndex($txtindex);
        // Close archive file
        $zip->close();
        // Load XML from a string
        // Skip errors and warnings
        $xml = DOMDocument::loadXML($data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
        // Return data without XML formatting tags
        return strip_tags($xml->saveXML());
    }
    $zip->close();
}

// In case of failure return empty string
return "";
}


/*****************************************************************
This approach uses detection of NUL (chr(00)) and end line (chr(13))
to decide where the text is:
- divide the file contents up by chr(13)
- reject any slices containing a NUL
- stitch the rest together again
- clean up with a regular expression
*****************************************************************/

function parseWord($userDoc) 
{
    $fileHandle = fopen($userDoc, "r");
    $line = @fread($fileHandle, filesize($userDoc));   
    $lines = explode(chr(0x0D),$line);
    $outtext = "";
    foreach($lines as $thisline)
      {
        $pos = strpos($thisline, chr(0x00));
        if (($pos !== FALSE)||(strlen($thisline)==0))
          {
          } else {
            $outtext .= $thisline." ";
          }
      }
     $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);
    return $outtext;
} 




   function get_mime_type(&$structure) {
   $primary_mime_type = array("TEXT", "MULTIPART","MESSAGE", "APPLICATION", "AUDIO","IMAGE", "VIDEO", "OTHER");
   if($structure->subtype) {
   	return $primary_mime_type[(int) $structure->type] . '/' .$structure->subtype;
   }
   	return "TEXT/PLAIN";
   }
   function get_part($stream, $msg_number, $mime_type, $structure = false,$part_number    = false) {
   
   	if(!$structure) {
   		$structure = imap_fetchstructure($stream, $msg_number);
   	}
   	if($structure) {
   		if($mime_type == get_mime_type($structure)) {
   			if(!$part_number) {
   				$part_number = "1";
   			}
   			$text = imap_fetchbody($stream, $msg_number, $part_number);
   			if($structure->encoding == 3) {
   				return imap_base64($text);
   			} else if($structure->encoding == 4) {
   				return imap_qprint($text);
   			} else {
   			return $text;
   		}
   	}
   
		if($structure->type == 1) /* multipart */ {
   		while(list($index, $sub_structure) = each($structure->parts)) {
   			if($part_number) {
   				$prefix = $part_number . '.';
   			}
   			$data = get_part($stream, $msg_number, $mime_type, $sub_structure,$prefix .    ($index + 1));
   			if($data) {
   				return $data;
   			}
   		} // END OF WHILE
   		} // END OF MULTIPART
   	} // END OF STRUTURE
   	return false;
   } // END OF FUNCTION

	function aes_encrypt_string($text) {
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, 'GpAeScw2LQWaYnRddbh4cPksce76gQ1z', $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }
	function aes_decrypt_string($text) {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, 'GpAeScw2LQWaYnRddbh4cPksce76gQ1z', base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

?>