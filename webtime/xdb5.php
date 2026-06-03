<?php
// $connection_string = 'DRIVER={SQL Server};SERVER=brodyville.dynalias.com,19997;DATABASE=EMPACT_001_PROD_PDI';
// $connection_string = 'DRIVER={SQL Server};SERVER=u17899881.onlinehome-server.com,1433;DATABASE=EMPACT_001_PROD_PDI';

// $connection_string = 'DRIVER={SQL Server};SERVER=5de1f42.online-server.cloud,1433;DATABASE=EMPACT_001_PROD_PDI';

$connection_string = 'DRIVER={ODBC Driver 17 for SQL Server};SERVER=5de1f42.online-server.cloud,1433;DATABASE=EMPACT_001_PROD_PDI';


$user = 'sa';
$pass = 'ic3eempact!';

$conn = odbc_connect($connection_string, $user, $pass );
?>