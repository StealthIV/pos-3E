<?php
require_once '../include/dbcon.php';
session_start();

if (!isset($_SESSION["Email"])) {
    header("location:../login.php");
    exit; 
}

if (!isset($_GET['id'])) {
    header("location:customers.php");
    exit;
}

$customerId = $_GET['id'];

try {

    $pdoQuery = "SELECT * FROM customer_account WHERE id=:id";
    $pdoResult = $pdoConnect->prepare($pdoQuery);
    $pdoResult->execute(['id' => $customerId]);
    $loyaltyCard = $pdoResult->fetch();
    

    if (!$loyaltyCard) {

        header("location:customers.php");
        exit;
    }
} catch (PDOException $error) {
    echo $error->getMessage();
    exit; 
}

require_once '../customer_barcode/vendor/autoload.php'; // Include the Composer autoload file

use Picqer\Barcode\BarcodeGeneratorPNG; 

function generateBarcodeImage($barcode) {
    try {
        // Initialize the barcode generator
        $generator = new BarcodeGeneratorPNG();
        
        // Generate the barcode image
        $barcodeImage = $generator->getBarcode($barcode, $generator::TYPE_CODE_128);
        
        // Output the barcode image
        return $barcodeImage;
    } catch (Exception $e) {
        return false; // Return false if there's an error generating the barcode image
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="../customercss/customers.css">
    <link rel="stylesheet" href="../customercss/loyalty_card.css">

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<title>AdminHub</title>
</head>
<body>
		<!-- SIDEBAR -->
		<section id="sidebar">
		<a href="#" class="brand">
			<img src="Picsart_24-03-29_12-28-55-400.png">
			<span class="text">Banana</span>
		</a>
		<ul class="side-menu top">
			<li >
				<a href="dashboard.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li class="active">
				<a href="customers.php">
					<i class='bx bxs-group' ></i>
					<span class="text">Customers</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			<li>
				<a href="#">
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="logout.php" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->



	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<form action="#">
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<a href="#" class="profile">
				<img src="../image/default_profile.png">
			</a>
		</nav>
		<!-- NAVBAR -->

         <!-- MAIN -->
        <main>
            <div class="head-title">
                <!-- Head title content -->
				 		<form id="barcode-form" action="add_loyalty_points.php" method="post">	
                                <input type="text" name="scannedBarcode" placeholder="Scan barcode...">                  
                        </form>
            </div>
            <ul class="box-info">
                <li>
                <div class="loyalty-card">
                    <div class="header">
                        <h2><?php echo $loyaltyCard['FullName']; ?></h2>
                    </div>
                    <div class="content">

                        <img src="Picsart_24-03-29_12-28-55-400.png" alt="" class="loyalty-picture">

                        <h3>Banana Loyalty Card</h3>

                        <div class="barcode-container">
						<?php if (!empty($loyaltyCard['barcode_image'])): ?>
									<div class="profile-value">
										<?php 
										$barcodeImage = generateBarcodeImage($loyaltyCard['barcode_image']);
										if ($barcodeImage !== false): ?>
											<img src="data:image/png;base64,<?php echo base64_encode($barcodeImage); ?>" class="barcode">
										<?php else: ?>
											<div class="error">Failed to generate barcode image.</div>
										<?php endif; ?>
									</div>
								<?php else: ?>
									<div class="profile-value">No barcode available.</div>
								<?php endif; ?>
                        </div>
                        
                    </div>
                </div>

                        </div>
                    </div>
                </li>
            </ul>
        </main>
        <!-- MAIN -->
	</section>
	<!-- CONTENT -->
	<script src="script.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>
</html>