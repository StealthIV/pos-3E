<?php 
include '../include/customerSessionHistory.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="../customercss/purchaseHistory.css">

    <title>AdminHub</title>
</head>
<body>

    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <img src="Picsart_24-03-29_12-28-55-400.png">
            <span class="text">Clothing</span>
        </a>
        <ul class="side-menu top">
            <li>
                <a href="dashboard.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="customers.php">
                    <i class='bx bxs-group'></i>
                    <span class="text">Customers</span>
                </a>
            </li>
            <li class="active">
                <a href="#">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Customer History</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="#">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <a href="logout.php" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
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
            <i class='bx bx-menu'></i>
            <form action="#"></form>
            <input type="checkbox" id="switch-mode" hidden>
            <a href="#" class="profile">
                <img src="img/people.png">
            </a>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Purchase History</h3>
                        <i class='bx bx-search'></i>
                        <i class='bx bx-filter'></i>
                    </div>
                    <?php
                    try {
                        $pdoQuery = 'SELECT * FROM customer_history WHERE customerName = :customerName ORDER BY customerPdate DESC';
                        $pdoResult = $pdoConnect->prepare($pdoQuery);
                        $pdoResult->execute(['customerName' => $customerHistory['FullName']]);

                        echo '<table>';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>FullName</th>';
                        echo '<th>Purchase Date</th>';
                        echo '<th>Purchase Total</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['customerName']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['customerPdate']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['customerPtotal']) . '</td>';
                            echo '</tr>';
                        }

                        echo '</tbody>';
                        echo '</table>';
                    } catch (PDOException $error) {
                        // Log the error message instead of displaying it to the user
                        error_log("Database error: " . $error->getMessage());
                        echo '<p>An error occurred while fetching the purchase history. Please try again later.</p>';
                    }
                    ?>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script src="script.js"></script>
</body>
</html>
