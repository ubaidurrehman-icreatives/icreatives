<?php

    
    // ** MySQL settings - You can get this info from your web host ** //
    /** The name of the database for WordPress */
    define('DB_NAME', 'admin_mysql2acd74a282ea5911b51c5f85ec5da6e2');
    
    /** MySQL database username */
    define('DB_USER', 'usrae91614eeb76e');
    
    /** MySQL database password */
    define('DB_PASSWORD', 'markiam1');
    
    /** MySQL hostname */
    define('DB_HOST', 'localhost');
    
    /** Database Charset to use in creating database tables. */
    define('DB_CHARSET', 'utf8');
    
    /** The Database Collate type. Don't change this if in doubt. */
    define('DB_COLLATE', '');
    

    ##save item into database.
    $step=$_POST["current_state"];
    $item = $_POST["item_number"];    
    
    if ($step=="s3" || $step=="s2" || $step=="s4" || $step=="s1" || $step=="s5")
    {
        //$url = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];
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
           $link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
           $dbcheck = mysql_select_db(DB_NAME);
           
           $sql="delete from ic_app_info where item_num='". $item ."' and f_name='" . $f_name . "'";
           $result = mysql_query($sql) or  die (mysql_error());
           
           $sql="insert into ic_app_info(item_num, f_name, f_val, d_t) select '". $item ."','". $f_name . "','" . $f_value . "',now()";

           $result = mysql_query($sql) or  die ($sql);
           ##mysql_free_result($result);
           
           return;
    }
    
?>