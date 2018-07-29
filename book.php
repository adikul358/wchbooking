<html>

<head>
	<link href="style.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="Java-Calendar/main.css">
	<script src="Java-Calendar/main.js"></script>
	<?php require "php/connect.php"?>
	<title>WCH Booking Interface - Shiv Nadar School, Noida</title>

	<?php

		$m = $_GET['m'];
		$m++; 
		$currdate = date("Y\-m\-d", mktime(0,0,0,$m, $_GET['d'], $_GET['y']));

		$booking_query = "SELECT start, name FROM bookings WHERE date='$currdate'";
		$result = mysqli_query($link, $booking_query);

		if (!$result) {
			exit("Failed to fetch:<br>" . mysqli_error($link));
		}

		$bookings = array();
		while($row = mysqli_fetch_assoc($result)) {
			$bookings[] = $row;
		}
		$r = mysqli_num_rows($result);

		if (isset($_POST['submit'])) {
			$name = mysqli_real_escape_string($link, $_POST['name']);
			$email = mysqli_real_escape_string($link, $_POST['email']);
			$phone = mysqli_real_escape_string($link, $_POST['phone']);
			$event = mysqli_real_escape_string($link, $_POST['event']);
			$start = mysqli_real_escape_string($link, $_POST['start']);
			$end = mysqli_real_escape_string($link, $_POST['end']);

			// $event_query = "INSERT INTO "
		}		
	?>
</head>

<body>

	<div class="header">
		<div id="title">
			<a href="index.php" style="text-decoration: none">
				<h1>WCH Booking Interface</h1>
			</a>
		</div>
		<img src="images\SNS_Logo.png" alt="Shiv Nadar School logo" id="schlo">

	</div>

	<body>
		<div id="content">
			<div id="titlespan" style="display: block; border-bottom: 2px solid black; width: 80%;">Booked Slots</div>
			<div id="bkdslts">
				<?php 
						$counter = 1;

						switch ($r) {
							case 0:
								echo '<h3 id="noro">No bookings yet</h3>';
								break;
							
							default:
							# code...
							echo '<table> 
							<tr>
								<th class="daysheader">S No.</th>
								<th class="daysheader">Event</th>
								<th class="daysheader">Start time</th>
								<th class="daysheader">End time</th>
								<th class="daysheader" colspan="2">Booker</th>
							</tr>';
	
							foreach ($bookings as $row) {
								$html = '<tr>';
								$html .= '<td class="bkdslts">' . $counter . '</td>';
								$html .= '<td class="bkdslts">' . $event . '</td>';
								$html .= '<td class="bkdslts">' . $row['start'] . '</td>';
								$html .= '<td class="bkdslts">' . $row['end'] . '</td>';
								$html .= '<td class="bkdslts">' . $row['name'] . '</td>';
								$html .= '<td class="bkdslts">' . '<a onclick="discon">Contact Info</a>' . '</td>';
								$html .= '</tr>';
								$counter++;
								echo $html;
							}
								break;
						}
					?>
				</table>
			</div>
			<div style="margin:15px">
				<span id="titlespan" style="margin-right: 15px">Book a Slot</span>
				<a href="#" id="add" onclick="launchForm()">
					<img src="images/add.svg" id="addico">
				</a>
			</div>
		</div>
		<div id="booking-form">
			<div id=mbf>
				<form method="POST">
					<div id="form">

						<label>Event Name</label>
						<br>
						<input type=text name="event">
						<br>
						<br>

						<div id="times">
							<div style="width: 50%; float: left">
								<label>Start Time</label>
								<br>
								<select name="start" id=s onchange="cp()">
									<?php

								$slots = array();
								for($i = strtotime("09:00"); $i<= strtotime("14:50"); $i= $i+35*60) {
									$slots[] = date("h:i", $i); 
									$server_slots[] = date("H:i:s", $i); 
								}
								$c = 0;
								foreach ($slots as $s) {
									echo '<option value="' . $server_slots[$c] . '" name="' .$s .'">' . $s . '</option>';
									$c++;
								}
							?>
								</select>
								<br>
							</div>
							<div>
								<label>End Time</label>
								<br>
								<select name="end" id=e></select>
								<br>
						</div>
						<br>
						<br>

						<label>Name of Booker</label>
						<br>
						<input type="text" name="name">
						<br>
						<br>
						<label>Contact No.</label>
						<br>
						<input type="number" name="phone">
						<br>
						<br>
						<label>Email</label>
						<br>
						<input type="email" name="email">
						<br>
						<br>


					</div>
					<input type="submit" value="Book Hall" onclick="removeForm()" style="float:right">
				</form>
							</div>
	</body>

</html>