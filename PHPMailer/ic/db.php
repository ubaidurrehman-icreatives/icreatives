<?php
// $connection_string = 'DRIVER={SQL Server};SERVER=brodyville.dynalias.com,19997;DATABASE=EMPACT_001_PROD_PDI';
// $connection_string = 'DRIVER={SQL Server};SERVER=u17899881.onlinehome-server.com,1433;DATABASE=EMPACT_001_PROD_PDI';

function db_authenticate($user, $pass) {
  $connection_string = 'DRIVER={SQL Server};SERVER=5de1f42.online-server.cloud,1433;DATABASE=EMPACT_001_PROD_PDI';
  // $connection_string = 'DRIVER={ODBC Driver 11 for SQL Server};SERVER=5de1f42.online-server.cloud,1433;DATABASE=EMPACT_001_PROD_PDI';

  set_error_handler(function($errno, $errstr, $errfile, $errline ) {
      throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
   });

  try {
    odbc_connect($connection_string, $user, $pass );
    return "ok";
  } catch (Exception $e) {
    $error = "Connection failed";
    return $error;
  }
}

function db_connect($user, $pass) {
  $connection_string = 'DRIVER={SQL Server};SERVER=5de1f42.online-server.cloud,1433;DATABASE=EMPACT_001_PROD_PDI';
  // $connection_string = 'DRIVER={ODBC Driver 11 for SQL Server};SERVER=5de1f42.online-server.cloud,1433;DATABASE=EMPACT_001_PROD_PDI';

  $user = 'sa';
  $pass = 'ic3eempact!';

  set_error_handler(function($errno, $errstr, $errfile, $errline ) {
      throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
   });

  try {
    return odbc_connect($connection_string, $user, $pass );
  } catch (Exception $e) {
    $error = "Connection failed";
    return $error;
  }
}
?>
