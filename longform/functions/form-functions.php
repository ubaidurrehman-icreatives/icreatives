<?php
function if_mobile(): bool {
    $ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');

    // Treat iPhone/iPod/Android as mobile.
    // (Add ipad if you also want iPad to use mobile layout.)
    return (bool) preg_match('/iphone|ipod|android|blackberry|bb10|windows phone|opera mini|opera mobi|mobile/', $ua);
}


    function ic_gen_signup_form_state()
    {
        //generate state variables
        global $current_state;
        global $item_num;
        
        $current_state = $_POST['current_state'];
        
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
            require_once(ABSPATH. '/wp-content/custom-php/signup_process.php');
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
          //  $link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
          //  $dbcheck = mysql_select_db(DB_NAME);

require_once dirname(__DIR__,2) . '/db/db.php';
$link = db();   

  
           
           $sql="delete from ic_app_info where item_num='". $item ."' and f_name='" . $f_name . "'";
	$result = mysqli_query($link, $sql) or die ('Error updating database: '.mysqli_error( $link));
           
           $sql="insert into ic_app_info(item_num, f_name, f_val) select '". $item ."','". $f_name . "','" . $f_value . "'";
           $result = mysqli_query($link, $sql) or die ('Error updating database: '.mysqli_error( $link));
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
           
           // $link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
           // $dbcheck = mysql_select_db(DB_NAME);

	$link = mysqli_connect('localhost', 'wordpress_2', '9g1EXn$q8A','wordpress_7') or die("Error: " . mysqli_error());
           
           $sql="select * from ic_app_info where item_num='". $item ."' and f_name='" . $f_name . "'";
           $result = mysqli_query($link, $sql) or die ('Error updating database: '.mysqli_error( $link));

           $row = mysqli_fetch_array($result) or die(mysql_error());

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
         //  $link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
         //  $dbcheck = mysql_select_db(DB_NAME);
	$link = mysqli_connect('localhost', 'wordpress_2', '9g1EXn$q8A','wordpress_7') or die("Error: " . mysqli_error());
	// $dbcheck =  mysql_select_db('wordpress_b');

 
           
           //backup records       
           $sql="insert into  ic_app_info_all(item_num, f_name, f_val, d_t) select item_num, f_name, f_val, d_t from ic_app_info where item_num='". $item ."'";
           mysqli_query($link,$sql) or  die (mysql_error());
           
           //clear old one
           $sql="delete  from ic_app_info where item_num='". $item ."'";
           mysqli_query($link,$sql) or  die (mysql_error());
           
           return true;
    }    
    
    
    function ic_db_read_field($item)
    {
           global $row_loaded;
           global $rows;
           global $valid_step;
           
require_once dirname(__DIR__,2) . '/db/db.php';
$link = db();   
             
           $sql="select f_name, f_val from ic_app_info where item_num='". $item ."'";
           $result = mysqli_query($link, $sql) or die ('Error updating database: '.mysqli_error( $link));

            $rows = array();            
            //mysql_data_seek($result, 0);            
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
?>
<?php
function printRadio($name)
{
	output_toggle_radio_script(); // 🔹 ensures script is present
    // Track field names for your hidden radio_field list
    global $radio_field;

    $val = loadValue($name); // existing helper
    $radio_field = $radio_field ? ($radio_field . ';' . $name) : $name;

    // Sanitize for IDs (IDs must be unique & safe)
    $baseId = preg_replace('/[^a-z0-9_-]+/i', '_', $name);

    // Map of value => label (adjust if your scale differs)
$options = [
    '33'  => 'beginner',
    '66'  => 'proficient',
    '100' => 'expert',
];


    echo '<div class="radio-group" style="float:left;">';
	// echo '<div class="radio-group">';


    foreach ($options as $valCode => $label) {
        $id = $baseId . '_' . $valCode;
        $checked = ($val == $valCode) ? " checked" : "";
        echo '
          <div style="float:left; padding-right:10px;">
            <label for="'.htmlspecialchars($id,ENT_QUOTES).'"
                   class="graytxt2"
                   style="display:inline-block; margin-right:5px;">'.htmlspecialchars($label).'</label>
            <input type="radio"
                   id="'.htmlspecialchars($id,ENT_QUOTES).'"
                   name="'.htmlspecialchars($name,ENT_QUOTES).'"
                   class="styled"
                   value="'.htmlspecialchars($valCode,ENT_QUOTES).'"'.$checked.' />
          </div>';
    }

    echo '</div>';
}

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
        $item = $_POST["item_number"];
        
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
	
	function output_toggle_radio_script()
{
    static $printed = false;
    if ($printed) return;
    $printed = true;
    ?>
    <script>
    (function () {

      function resolveRadio(target) {
        // If label clicked, resolve its input
        if (target && target.tagName === "LABEL" && target.htmlFor) {
          const el = document.getElementById(target.htmlFor);
          if (el) target = el;
        }
        return (target && target.matches && target.matches('input[type="radio"].toggle-radio'))
               ? target : null;
      }

      document.addEventListener("pointerdown", function (e) {
        const r = resolveRadio(e.target);
        if (!r) return;
        r.dataset.wasChecked = r.checked ? "1" : "0";
      }, true);

      document.addEventListener("click", function (e) {
        const r = resolveRadio(e.target);
        if (!r) return;

        if (r.dataset.wasChecked === "1") {
          r.checked = false;
          r.dispatchEvent(new Event("change", { bubbles: true }));
        }
      }, true);

    })();
    </script>
    <?php
}


?>
<?php
// Only output the script once
if (!defined('RADIO_TOGGLE_SCRIPT')) {
    define('RADIO_TOGGLE_SCRIPT', true);
?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const radios = document.querySelectorAll("input[type='radio']");

    radios.forEach(radio => {
        radio.addEventListener("mousedown", function () {
            this.wasChecked = this.checked;
        });

        radio.addEventListener("click", function () {
            if (this.wasChecked) {
                this.checked = false;
                this.wasChecked = false;

                // Optional: clear hidden field if you're storing radio values
                const hiddenField = document.getElementById("radio_field");
                if (hiddenField) hiddenField.value = "";
            }
        });
    });
});
</script>
<?php
}
?>
<script>
(function () {

  let lastRadio = null;

  document.addEventListener("click", function (e) {

    // Handle clicks on the real radio input
    let radio = null;

    if (e.target.tagName === "INPUT" && e.target.type === "radio") {
      radio = e.target;
    }

    // Handle clicks on the styled span.radio
    if (!radio && e.target.classList && e.target.classList.contains("radio")) {
      const wrapper = e.target.parentNode;
      if (wrapper) {
        radio = wrapper.querySelector('input[type="radio"]');
      }
    }

    if (!radio) return;

    // If the same radio was clicked twice in a row → uncheck it
    if (radio === lastRadio && radio.checked) {
      setTimeout(() => {
        radio.checked = false;

        // visually reset the skin
        const span = radio.parentNode.querySelector('span.radio');
        if (span) {
          span.style.backgroundPosition = "0px 0px";
        }

        lastRadio = null;
      }, 0);

    } else {
      lastRadio = radio;
    }

  }, true);

})();
</script>
