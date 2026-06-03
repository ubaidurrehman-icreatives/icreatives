<?php    
    // ** MySQL settings - You can get this info from your web host ** //
    /** The name of the database for WordPress */
    define('DB_NAME', 'wordpress_f');
    
    /** MySQL database username */
    define('DB_USER', 'wordpress_c');
    
    /** MySQL database password */
    define('DB_PASSWORD', 'm5Z1bD2Hu_');
    
    /** MySQL hostname */
    define('DB_HOST', 'localhost:3306');
    
    /** Database Charset to use in creating database tables. */
    define('DB_CHARSET', 'utf8');
    
    /** The Database Collate type. Don't change this if in doubt. */
    define('DB_COLLATE', '');
    

    ##save item into database.
    $step=$_POST["current_state"];
    $item = $_POST["item_number"];    
    if ($step=="s3" || $step=="s2" || $step=="s4" || $step=="s1" || $step=="s5")
    {
        //$url = "https://" . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];
        //$QUERYVAR= parse_url($url, PHP_URL_QUERY);
        //$GETVARS = explode('&',$QUERYVAR);
        //foreach($GETVARS as $string)
        //{     
//            list($is,$what) = explode('=',$string);    
//            if ($is!="item_number" && $is!="current_state")
//            {
//                ic_db_write_field($item, $is, str_replace("'", "''", urldecode($what)));
            //}
        //}
        
        
        foreach($_POST as $is=>$what)
        {
            if ($is!="item_number" && $is!="current_state")
            {
                ic_db_write_field($item, $is, str_replace("'", "''", urldecode($what)));
            }
        }
        echo "OK";
    }
    else
    {
        echo "BAD COMMAND";
    }
    
    
    
    function ic_db_write_field($item, $f_name, $f_value)
    {
           // $link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	//		$link = mysqli_connect('localhost', 'wordpress_2', '9g1EXn$q8A','wordpress_7') or die("Error: " . mysqli_error());
		 	$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());
           // $dbcheck = mysql_select_db(DB_NAME);


           $sql="delete from ic_app_info where item_num='". $item ."' and f_name='" . $f_name . "'";
           $result = mysqli_query($link, $sql) or die ('Error updating database: '.mysqli_error( $link));
           if($f_name=='PAST1_JOB_DUTIES'){
			$f_value=str_replace('"','',$f_value);
			$f_value=str_replace("'","",$f_value);
		   }
		   if($f_name=='PAST2_JOB_DUTIES'){
			$f_value=str_replace('"','',$f_value);
			$f_value=str_replace("'","",$f_value);
		   }
		   if($f_name=='PAST3_JOB_DUTIES'){
			$f_value=str_replace('"','',$f_value);
			$f_value=str_replace("'","",$f_value);
		   }
         $sql="insert into ic_app_info(item_num, f_name, f_val, d_t) select '". $item ."','". $f_name . "','" . $f_value . "',now()";

           $result = mysqli_query($link, $sql) or die ('Error updating database: '.mysqli_error( $link));
           ##mysql_free_result($result);
           
           return;
    }
    
?>