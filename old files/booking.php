<?php
//check if the form is submitted
$guest = $checkin = $checkout = $rooms = $adults = $children ="";
$guestErr = $inErr = $outErr = $roomsErr ="";
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
	//Get the form data
	$checkin = sanitizeInput($_POST['checkin']);
	$checkout = sanitizeInput($_POST['checkout']);
	$rooms = (int)$_POST['rooms'];
	$adults = (int)$_POST['adults'];
	$children = (int)$_POST['children'];
	// Validate dates
    /*if (!validateDate($checkin) || !validateDate($checkout)) {
        die("Invalid date format. Please use YYYY-MM-DD format.");
    }*/
    //validate guestname 
    if(empty($_POST['guestName'])){
    	$guestErr = "Name is required";
    } else {
    	$guest = sanitizeInput($_POST['guestName']);
    	if(!preg_match("/^[a-zA-Z- ]*$/", $guest)){
    		$guestErr = "Name can contain letters and hyphens";
    	}
    }

    //validate checkin
    if(empty($_POST['checkin'])) {
    	$inErr = "Check-in cannot be left empty";
    } else {
    	$checkin = sanitizeInput($_POST['checkin']);
    }

    if(empty($_POST['checkout'])){
    	$outErr = "Check-out cannot be left empty";
    } else {
    	$checkout = sanitizeInput($_POST['checkout']);
    }

    /*if($_POST['checkin'] < $checkin || $_POST['checkout'] < '$checkin') {
    	echo "<script>alert('Invalid date')<script>";
    	exit();
    }*/

    // Validate number of rooms, adults, and children
    if ($rooms < 1 || $adults < 1) {
        die("Invalid number of rooms, adults");
    }


    if(empty($inErr) && empty($outErr) && empty($guestErr) && $rooms !== false && $adults !== false && $children !== false){
    	try{
    		//query to check availablity
			//$sql = "SELECT COUNT(*) FROM bookings WHERE NOT ((checkin_date < '$checkin' AND checkout_date <= '$checkin') OR
		    //(checkin_date >= '$checkout' AND checkout_date > '$checkout'));";
		    $sql = "SELECT * FROM bookings WHERE rooms = ?";
			$stmt = $conn->prepare($sql);
			$stmt->execute([$rooms]);
			//$count = $stmt->fetchColumn();

			if($stmt->rowCount() > 0){
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				if($row['rooms'] == $rooms){
					$roomsErr = "Room not available";
				}
			} else {
				//Insert to add booking
				$sql = "INSERT INTO bookings (guestName, checkin_date, checkout_date, rooms, adults, children) VALUES(?, ?, ?, ?, ?, ?)";

				$stmt = $conn->prepare($sql);
				$stmt->execute([$guest, $checkin, $checkout, $rooms, $adults, $children]);

				if($stmt->rowCount() > 0){
					//$receipt = "Your booking has been confirmed. \nCheck-in: $checkin\nCheck-out: $checkout\nRooms: $rooms\nAdults: $adults\nChildren: $children";
					//Generate BookingID
					$bookingID = rand(100, 10000);

					//update booking with generated ID
					$sql = "UPDATE bookings SET bookingId =? WHERE rooms = ?";
					$stmt = $conn->prepare($sql);
					$stmt->execute([$bookingID, $rooms]);

					//Generate and display receipt
					$receipt = "Your booking has been confirmed. Booking ID is $bookingID. Thank you";
					echo "<script>alert('$receipt');</script>";
				} else {
					echo "<script>alert('Rooms are not available for the selected dates.');</script>";
				} 
			}
    	} catch(PDOException $e) {
    		echo "Error: " . $e->getMessage();
    	}
    }
}

function sanitizeInput($data) {
	return htmlspecialchars(stripslashes(trim($data)));
}

/*function validateDate($date, $format = 'd-m-Y') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}*/
?>