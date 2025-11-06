<?php
include("connection.php"); // make sure connection.php uses mysqli

$body = "none"; // default: hide pass details
$row = null;
$id = "";

if (isset($_POST['pass_id'])) {
    $id = $_POST['pass_id'];
    $password = $_POST['password'];

    // Use mysqli prepared statement instead of mysql_query
    $stmt = $conn->prepare("SELECT * FROM pass WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['password'] != $password) {
            header("Location: manage2.php"); // wrong password page
            exit;
        }
        $body = "block"; // show pass details
    } else {
        $body = "none"; // no record found
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cloud Based Bus Pass System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,700,900|Display+Playfair:200,300,400,700"> 
    <link rel="stylesheet" href="fonts/icomoon/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mediaelement@4.2.7/build/mediaelementplayer.min.css">
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
        .pass_body { display: <?php echo $body; ?>; }
        @media print {
            .print { display: none; }
            footer { display: none; }
        }
    </style>
</head>
<body>
<div class="site-wrap">

    <div class="site-mobile-menu">
        <div class="site-mobile-menu-header">
            <div class="site-mobile-menu-close mt-3">
                <span class="icon-close2 js-menu-toggle"></span>
            </div>
        </div>
        <div class="site-mobile-menu-body"></div>
    </div>

    <header class="site-navbar py-1" role="banner">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-6 col-xl-2">
                    <h1 class="mb-0"><a href="index.html" class="text-black h2 mb-0">Travelers</a></h1>
                </div>
                <div class="col-10 col-md-8 d-none d-xl-block">
                    <nav class="site-navigation position-relative text-right" role="navigation">
                        <ul class="site-menu js-clone-nav mx-auto d-none d-lg-block">
                            <li class="active"><a href="index.html">Home</a></li>
                            <li><a href="about.html">About</a></li>
                            <li><a href="contact.html">Contact</a></li>
                            <li><a href="manage.php">Manage Pass</a></li>
                            <li><a href="bus_details.html">Bus Details</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <div class="site-blocks-cover inner-page-cover print" style="background-image: url(images/hero_bg_2.jpg);" data-aos="fade" data-stellar-background-ratio="0.5">
        <div class="container">
            <div class="row align-items-center justify-content-center text-center">
                <div class="col-md-8" data-aos="fade-up" data-aos-delay="400">
                    <h1 class="text-white font-weight-light">Manage Pass</h1>
                    <div><a href="index.html">Home</a> <span class="mx-2 text-white">&bullet;</span> <span class="text-white">Manage Pass</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <form class="print mb-4" action="manage.php" method="post">
            ID: <input type="text" name="pass_id" value="<?php echo $id; ?>" required>
            Password: <input type="password" name="password" required>
            <input type="submit" class="btn btn-primary" value="Search">
        </form>

        <div class="pass_body">
            <?php if ($row): ?>
            <div class="site-section bg-light">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="p-4 mb-3 bg-white">
                                <p><strong>Id:</strong> <?php echo $row['id']; ?></p>
                                <p><strong>Name:</strong> <?php echo $row['name']; ?></p>
                                <p><strong>Mobile:</strong> <?php echo $row['contact']; ?></p>
                                <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
                                <p><strong>Valid Till:</strong> <?php echo $row['date']; ?></p>
                                <p><strong>From - To:</strong> VIT - <?php echo $row['dest']; ?></p>
                                <p><strong>Trip Type:</strong> <?php echo ($row['tripType'] == "round") ? "Round Trip" : "One Way"; ?></p>
                                <p><strong>Amount Paid:</strong> Rs. <?php echo $row['paid']; ?></p>

                                <form class="print mb-2" action="renew.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <input type="date" name="new_date" min="<?php echo $row['date']; ?>" required>
                                    <input type="submit" class="btn btn-primary" value="Renew Pass">
                                </form>

                                <button class="print btn btn-success mb-2" onclick="window.print()">Print Pass</button>

                                <form class="print" action="suspend.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <input type="submit" class="btn btn-danger mt-2" value="Suspend Pass">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <footer class="site-footer mt-5">
        <div class="container text-center">
            <p>Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved</p>
        </div>
    </footer>

</div>

<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/jquery.stellar.min.js"></script>
<script src="js/aos.js"></script>
<script src="js/main.js"></script>
</body>
</html>
