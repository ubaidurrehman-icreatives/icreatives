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
		<a href="trms_import.php"><button class="button">Import Time</button></a>
		<a href="trms_remind_clients.php"><button class="button">Remind Clients</button></a>
		<a href="trms_remind_talent.php"><button class="button">Remind_talent</button></a>
		<a href="trms_unapproved.php"><button class="button">Unapproved Import</button></a>
		<a href="trms_timesheet_history.php"><button class="button">History</button></a>

	</header>
</body>
</html>
