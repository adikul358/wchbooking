<?php 
    session_start(); 
?>

<!DOCTYPE html>
<html>

<head>
    <title>Hall Booking Interface - Shiv Nadar School, Noida</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/mdb.min.css" rel="stylesheet">
    <link href="css/badge.css" rel="stylesheet">
    <link href="cal/css/responsive-calendar.css" rel="stylesheet">
    <?php 
        require 'php/conn.php'; 
        require 'php/functions.php'; 
        
        // reset submitted status
        $_SESSION['query_status'] = false;
    
        // number of events of all days
        $events = array();
        // all dates with bookings
        $dates_u = array();
        
        // set hall-to-be-booked
        $hall = "Wild Cats Hall";
        if (isset($_SESSION['hall'])) { 
            $hall = $_SESSION['hall']; 
        }
        $_SESSION['hall'] = $hall;
        
        $_SESSION['date'] = new DateTime("today");
        $_SESSION['date'] = $_SESSION['date']->format("Y-m-d");

        // array for dropdown hall list
        $active = array("WCH"=>"", "CONR"=>"", "MEER"=>"", "GYM"=>"", "COTEL"=>"", "SENL"=>"");
        // function to make selected hall active in dropdown
        hall_active();

        // select all dates with bookings
        $dates_query = "SELECT DISTINCT date FROM " . $hall_table . " ORDER BY date";
        $result = mysqli_query($link, $dates_query);
        if (!$result) { exit("Failed to fetch:<br>" . mysqli_error($link)); }
        $dates = array();
        while($row = mysqli_fetch_assoc($result)) { $dates[] = $row; }
        foreach ($dates as $c) { $dates_u[] = $c['date']; }

        // fetch number of bookings for each date        
        foreach ($dates_u as $d) {
            $bookings_query = "SELECT * FROM " . $hall_table . " WHERE date='$d' ORDER BY date";
            $result = mysqli_query($link, $bookings_query);
            if (!$result) { exit("Failed to fetch:<br>" . mysqli_error($link)); }
            $bookings = array();
            while($row = mysqli_fetch_assoc($result)) { $bookings[] = $row; }
            $bno = mysqli_num_rows($result);
            $events[] = array("date"=>$d, 'no'=>$bno);
        }
    ?>
</head>

<body style="width:100vw; overflow-x:hidden; min-height:100vh;">
    <!-- Navbar -->
    <div class=container-fluid style=padding:0>
        <nav class="navbar white navbar-expand-lg navbar-light sticky-top">
            <a class="navbar-brand" href="/">
                <img src="images/SNS_Logo.png" id=header-logo style="padding:2px; margin-right: 5px; border-right: 1px solid black; padding-right: 10px;"
                    height="30" class="d-inline-block align-top" alt=""> Hall Booking Interface
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#basicExampleNav"
                aria-controls="basicExampleNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="basicExampleNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">Halls</a>
                        <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item <?php echo $active['WCH']?>" onclick="setSessionHall('Wild Cats Hall','false')">Wild Cats Hall</a>
                            <a class="dropdown-item <?php echo $active['CONR']?>" onclick="setSessionHall('Conference Room','false')">Conference Room</a>
                            <a class="dropdown-item <?php echo $active['MEER']?>" onclick="setSessionHall('Meeting Room','false')">Meeting Room</a>
                            <a class="dropdown-item <?php echo $active['GYM']?>" onclick="setSessionHall('Gymnasium','false')">Gymnasium</a>
                            <a class="dropdown-item <?php echo $active['COTEL']?>" onclick="setSessionHall('Composite Lab','false')">Composite Lab</a>
                            <a class="dropdown-item <?php echo $active['SENL']?>" onclick="setSessionHall('Senior Library','false')">Senior Library</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <!-- Navbar -->

    <br>

    <!-- Main Calendar -->
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body text-center ">
                    <h4 class=card-title>
                        <?php echo $hall?>
                    </h4>
                    <div class="responsive-calendar">
                        <div class="controls">
                            <div class="flex-center justify-content-center">
                                <div style="width:100%" class="row">
                                    <div class="col-xs-auto">
                                        <a class=float-left data-go="prev">
                                            <div class="btn btn-primary" style=border-radius:50px>Prev</div>
                                        </a>
                                    </div>
                                    <div class="col" style=margin:auto;height:100%text-align:center>
                                        <div style=color:black;box-shadow:none;>
                                                <h5 style=margin:0>
                                                    <span data-head-month></span> - <span data-head-year></span>
                                                </h5>
                                        </div>
                                    </div>
                                    <div class="col-xs-auto">
                                        <a class=float-right data-go="next">
                                            <div class="btn btn-primary" style=border-radius:50px>Next</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="day-headers">
                            <div class="day header">Mon</div>
                            <div class="day header">Tue</div>
                            <div class="day header">Wed</div>
                            <div class="day header">Thu</div>
                            <div class="day header">Fri</div>
                            <div class="day header">Sat</div>
                            <div class="day header">Sun</div>
                        </div>
                        <div class="days" data-group="days">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Calendar -->

    <br>

    <script src="cal/js/jquery.js"></script>
    <script src="cal/js/responsive-calendar.js"></script>

    <script type="text/javascript">
        // Add Badges to Days
        $(document).ready(function () {
            $(".responsive-calendar").responsiveCalendar({
                events: { <?php foreach ($events as $curr) { echo '"' . $curr['date'] . '": {"number":' . $curr['no'] . '},'; } ?> }
            });
        });
    </script>

    <script type="text/javascript" src="js/popper.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/mdb.min.js"></script>
    <script type="text/javascript" src="js/script.js"></script>

</body>
</html>