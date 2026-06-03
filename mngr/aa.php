
 <script>
        function openPopup(url) {
            // Specify the size and position of the window
            var width = 800;
            var height = 850;
            var left = (screen.width - width) / 2;
            
            // Adjust the top value to move the popup higher on the page
            var top = (screen.height - height) / 2; // Adjust this value as needed

            // Open the pop-up window with the provided URL
            var popup = window.open(url, 'PopupWindow', 'width=' + width + ', height=' + height + ', left=' + left + ', top=' + top);

            // Focus the pop-up window (optional)
            popup.focus();
        }
    </script>

<!DOCTYPE html>
<html>
<head>
	<title>icreatives timesheet manager</title>
	<style>
		/* Style for the buttons */
		.button {
			display: inline-block;
			padding: 10px 20px;
			border: none;
			background-color: #b22625;
			color: white;
			font-size: 16px;
			cursor: pointer;
			transition: background-color 0.3s;
		}

		/* Hover style for the buttons */
		.button:hover {
			background-color: #ff8080;
		}
	</style>
</head>
<body>
	<header>
	<p>
		<!-- Navigation links -->
		<a href="manatal_import.php"><button class="button">Import Time</button></a>
		<a href="manatal_remind_clients.php"><button class="button">Remind Clients</button></a>
		<a href="manatal_remind_talent.php"><button class="button">Remind_talent</button></a>
		<a href="manatal_unapproved.php"><button class="button">Unapproved Import</button></a>
		<a href="manatal_timesheet_history.php"><button class="button">History</button></a>
		<a href="manatal_invoicing.php"><button class="button">Create Invoices</button></a>
		<a href="manatal_send_invoices.php"><button class="button">Send Invoices</button></a>
		<a href="manatal_edit_invoice.php"><button class="button">Edit Invoices</button></a>
		<a href="manatal_statements.php"><button class="button">Statements</button></a>
	</header>
<h1>MANATAL STATEMENTS</h1>
<form method="post">
	Company Name: <input type="text" id="company_filter" name="company_filter" >
	<label for="paid_invoices">Include Paid: </label>  
	<select name="paid_invoices" id="paid_invoices">
		<option value="0" selected>Unpaid</option>
		<option value="1">Paid</option>	
	</select>
	Alternet Email: <input type="email" id="alternet_email" name="alternet_email" >
	<input type="submit" value="Submit">
</form>

<form method="post">
<br>
<table>
<thead><tr><th><input type='checkbox' id='check_all'></th><th><P>Check All</p></th></tr></thead><tr align="center">
<th> </th>
<th>A/P Name</th>
<th>A/P Email</th>
<th>Client</th>
<th>Amt Due</th>
<th> </th>
</tr><tr align="center"><td><input type="checkbox" name="recipients[]" value="100541"></td><td ALIGN="LEFT">John Contact-Smith</td><td ALIGN="LEFT">johnsmith@blindemail.com</td><td ALIGN="LEFT">ABC Corp</td><td>: 570.00</td><td>08/01/2023</td>	<td>
	<input type="button" onclick="openPopup('https://www.icreatives.com/mngr/manatal_view_statement.php?invnum=jhtDE4CNgKnIe9n7voI3l3MaTd7t6QV86mnhI4P7EvzQgxudwj0Erio/oqCSoWZV80ya6BkwL4+wM4C5Oom1jw==');" value="Preview" />
	</td>
	</tr>
</table>
<p><input type="submit" value="Email Checked Statements"></form>

 <script>
        function openPopup(url) {
            // Specify the size and position of the window
            var width = 800;
            var height = 850;
            var left = (screen.width - width) / 2;
            
            // Adjust the top value to move the popup higher on the page
            var top = (screen.height - height) / 2; // Adjust this value as needed

            // Open the pop-up window with the provided URL
            var popup = window.open(url, 'PopupWindow', 'width=' + width + ', height=' + height + ', left=' + left + ', top=' + top);

            // Focus the pop-up window (optional)
            popup.focus();
        }
    </script>

	<div style = "
    clear:both;
	height: 1000px;
	width: 750px; 
    margin-left: auto; 
    margin-right: auto; 
    border-radius: 20px 20px 20px 20px;
    padding: 40px; 
    font-family:Arial, Helvetica, sans-serif; 
    font-size:12px; 
    background-color: #FFFFFF; 
    border: 2px solid #A50F14; 
    background-image:url('http://www.icreatives.com/webtime/email/images/assets/background.gif');
    background-position:center; 
    background-repeat:no-repeat; 
    vertical-align: top;">

</div>
<script>
// Add a "Check All" checkbox to select all checkboxes at once
var checkAll = document.getElementById('check_all');
checkAll.addEventListener('click', function() {
    var checkboxes = document.getElementsByName('recipients[]');
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = checkAll.checked;
    }
});
</script>

