<?php
ini_set('date.timezone', 'Africa/Accra');
@include('dbconnect.php');
//@include('booking.php');
@include('alternative.php');

session_start();
$timelimit = 10 * 60;
$now = time();

if(!isset($_SESSION['email'])){
    echo "<script>alert('You can not access this page! Kindly login before, you can access this page');window.location='signup.php'</script>";
    exit;
}
if($now > $_SESSION['time']+ $timelimit) {
    echo "<script>alert('Your session has expired! Login to continue your process');window.location='signup.php'</script>";
    exit;
}

//define variables to empty
$guestErr = $inErr = $outErr = $roomsErr= $adultsErr ="";
$guest = $checkin = $checkout = $rooms= $adults ="";

$guestObj = new Guest($conn);
 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Get form data
    $guest = $_POST['guestName'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $rooms = $_POST['rooms'];
    $adults = $_POST['adults'];
    $children = $_POST['children'];

    // Call the register method
    try {
        $guestObj->register($guest, $checkin, $checkout, $rooms, $adults, $children);
    } catch (Exception $e) {
        echo "<script>alert('Error: ".$e->getMessage()."');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">
    <style>
        body{
            margin: 0;
            padding: 0;
        }

        .error{
            color: #ff0000;
            font-size: 15px;
            font-weight: bold;
        }

        nav {
            float: right;
            padding: 20px;
        }
        nav li {
            list-style: none;
        }

        nav li a {
            text-decoration: none;
            color: whitesmoke;
            font-size: 18px;
            font-weight: bolder;
            position: relative;
            right: 20px;
            background-color: #1e62d8;
            padding: 10px;
            border-radius: 5px;
        }

        nav li a:hover {
            color: white;
        }

        .section {
            position: relative;
            height: 100vh;
        }

        .section .section-center {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
        }

        #booking {
            font-family: 'Montserrat', sans-serif;
            /*background-image: url('https://i.imgur.com/ZaRYfYW.jpg');*/
            background-image: url(../luxuryhotel/images/01.jpg);
            background-size: cover;
            background-position: center;
        }

        #booking::before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            top: 0;
            background: rgba(47, 103, 177, 0.6);
        }

        .booking-form {
            background-color: #fff;
            padding: 50px 20px;
            -webkit-box-shadow: 0px 5px 20px -5px rgba(0, 0, 0, 0.3);
            box-shadow: 0px 5px 20px -5px rgba(0, 0, 0, 0.3);
            border-radius: 4px;
        }

        .booking-form .form-group {
            position: relative;
            margin-bottom: 30px;
        }

        .booking-form .form-control {
            background-color: #ebecee;
            border-radius: 4px;
            border: none;
            height: 40px;
            -webkit-box-shadow: none;
            box-shadow: none;
            color: #3e485c;
            font-size: 14px;
        }

        .booking-form .form-control::-webkit-input-placeholder {
            color: rgba(62, 72, 92, 0.3);
        }

        .booking-form .form-control:-ms-input-placeholder {
            color: rgba(62, 72, 92, 0.3);
        }

        .booking-form .form-control::placeholder {
            color: rgba(62, 72, 92, 0.3);
        }

        .booking-form input[type="date"].form-control:invalid {
            color: rgba(62, 72, 92, 0.3);
        }

        .booking-form select.form-control {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .booking-form select.form-control+.select-arrow {
            position: absolute;
            right: 0px;
            bottom: 4px;
            width: 32px;
            line-height: 32px;
            height: 32px;
            text-align: center;
            pointer-events: none;
            color: rgba(62, 72, 92, 0.3);
            font-size: 14px;
        }

        .booking-form select.form-control+.select-arrow:after {
            content: '\279C';
            display: block;
            -webkit-transform: rotate(90deg);
            transform: rotate(90deg);
        }

        .booking-form .form-label {
            display: inline-block;
            color: #3e485c;
            font-weight: 700;
            margin-bottom: 6px;
            margin-left: 7px;
        }

        .booking-form .submit-btn {
            margin-right:20px;
            display: inline-block;
            color: #fff;
            background-color: #1e62d8;
            font-weight: 700;
            padding: 14px 30px;
            border-radius: 4px;
            border: none;
            -webkit-transition: 0.2s all;
            transition: 0.2s all;
            position: relative;
            left: 55px;
        }

        .booking-form .submit-btn a {
            color: #fff;
            text-decoration: none;
        }


        .booking-form .submit-btn:hover,
        .booking-form .submit-btn:focus {
            opacity: 0.9;
        }

        .booking-cta {
            margin-top: 80px;
            margin-bottom: 30px;
        }

        .booking-cta h1 {
            font-size: 52px;
            text-transform: uppercase;
            color: #fff;
            font-weight: 700;
        }

        .booking-cta p {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.8);
        }
    </style>
</head>
<body>
    <header>
        
    </header>
    <div id="booking" class="section">
        <nav>
            <ol>
                <li><a href="#"><?php echo'Hey ' . $_SESSION['email'];?></a></li>
            </ol>
        </nav>
        <div class="section-center">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 col-md-push-5">
                        <div class="booking-cta">
                            <h1>Make your reservation</h1>
                            <p><strong>Experience the epitome of luxury at our exquisite hotel. Indulge in unparalleled comfort and sophistication, where every detail is meticulously crafted to ensure a memorable stay. 
                                Discover a haven of elegance and refinement, where your every need is catered to with exceptional service and care. Welcome to a world of luxury beyond compare.</strong>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4 col-md-pull-7">
                        <div class="booking-form">
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
                                <div class="form-group">
                                    <span class="form-label">Your Destination</span>
                                    <input class="form-control" type="text" placeholder="Luxury Hotel" disabled><br/>
                                    <span class="form-label">Name</span>
                                    <input class="form-control" type="text" placeholder="Enter Full Name" name="guestName" value="<?php echo $guest ?>"><span class="error"><?php echo $guestErr; ?></span>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <span class="form-label">Check In</span>
                                            <input class="form-control" type="date" name="checkin" required value="<?php echo $checkin ?>">
                                            <span class="error"><?php if(isset($_POST['checkin']))echo $inErr; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <span class="form-label">Check out</span>
                                            <input class="form-control" type="date" name="checkout" required value="<?php echo $checkout ?>">
                                            <span class="error"><?php if(isset($_POST['checkout']))echo $outErr; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <span class="form-label">Rooms</span>
                                            <select class="form-control" name="rooms" required> 
                                                <option disable>--select--</option>
                                                <option>102</option>
                                                <option>103</option>
                                                <option>104</option>
                                                <option>105</option>
                                                <option>106</option>
                                                <option>107</option>
                                                <option>108</option>
                                                <option>109</option>
                                                <option>110</option>
                                            </select><span class="error"><?php if(isset($_POST['rooms']))echo $roomsErr; ?></span>
                                            <span class="select-arrow"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <span class="form-label">Adults</span>
                                            <select class="form-control" name="adults" required value="<?php echo $adults ?>">
                                                <option disable>--select--</option>
                                                <option>1</option>
                                                <option>2</option>
                                                <option>3</option>
                                                <option>4+</option>
                                            </select>
                                            <span class="select-arrow"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <span class="form-label">Children</span>
                                            <select class="form-control" name="children" required value="<?php echo $children ?>">
                                                <option disable>--select--</option>
                                                <option>0</option>
                                                <option>1</option>
                                                <option>2</option>
                                                <option>3</option>
                                                <option>4+</option>
                                            </select>
                                            <span class="select-arrow"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-btn">
                                    <!--<button class="submit-btn">Check availability</button>-->
                                    <input type="submit" name="submit" value="BOOK" class="submit-btn">
                                    <button class="submit-btn"><a href="logout.php">SIGN OUT</a></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
         window.onload = function() {
             if (document.cookie.indexOf('logged_out=true') !== -1) {
        // Clear the cookie
        document.cookie = 'logged_out=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;';

        // Redirect to the login page
        window.location.href = 'signup.php';
             }
        };
</script>
</body>
</html>