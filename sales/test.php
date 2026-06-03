<?PHP
// $connection_string = 'DRIVER={SQL Server};SERVER=brodyville.dynalias.com,19997;DATABASE=EMPACT_001_PROD_PDI';
$connection_string = 'DRIVER={ODBC Driver 11 for SQL Server};SERVER=u17899881.onlinehome-server.com,1433;DATABASE=EMPACT_001_PROD_PDI';

$user = 'sa';
$pass = 'ic2eempact!';

$conn = odbc_connect($connection_string, $user, $pass );

if (!$conn) {
    die('Something went wrong while connecting to MSSQL');
}



								
								$sqlapp="SELECT * FROM newapp1 where APPID='000058N' order by MEDIAID DESC";
								$resData = odbc_exec($conn,$sqlapp);
								
								?>    
            					<table border="0" width="100%">
								<thead>
								<tr >  
								<?php
								$i=0;
								while($rowdata = odbc_fetch_array($resData)){
								$i++;
								?>
								<td scope="col" style="text-align:left" ><div style="border:1px solid #000;width:100px;height:100px;text-align:center;valign:middle;background-color:#ccc;padding-top:20px" >
								
							 	<a href="?p=edit_portfolio&id=<?php echo $rowdata['MEDIAID'];?>" style="font-size:12px;color:grey;text-decoration:underline"> 
<?php $ext2=getExtension($rowdata['IMAGE']); ?>
<?php if($ext2=='swf' || $ext2=='wmv' || $ext2=='mov') { ?>
    <img src='http://<?php echo $_SERVER['HTTP_HOST'];?>/Portfolios/Images/multi_media.gif' border='0' width='80' height='80' alt="Portfolio Image"  Title="Portfolio Image">
<?php } else { ?>
    <img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/thumb.php?file=Portfolios/Images/<?php echo $rowdata['IMAGE'];?>&sizex=80&sizey=80" alt="Portfolio Image"  Title="Portfolio Image">
<?php } ?>

</a><br /></div>
								<a href="?p=del_portfolio&id=<?php echo $rowdata['MEDIAID'];?>" style="font-size:12px;color:grey;text-decoration:underline"> Delete</a> 

								<a href="?p=edit_portfolio&id=<?php echo $rowdata['MEDIAID'];?>" style="font-size:12px;color:grey;text-decoration:underline">Edit</a>
								 </th>
								<?php
								if($i==4){
									echo "</tr><tr>";
									$i=0;
								}
								}
								?>          
								                        
								</tr>
								</thead>
				 
								
							</table> 