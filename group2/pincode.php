<?php
session_start();
include_once "../group3/dbcon.php";



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pincode = $_POST['pincode'];
    $action = $_POST['action'];

    // Query the database to find the user with the entered PIN code
    $stmt = $pdo->prepare('SELECT * FROM users WHERE pincode = ?');
    $stmt->execute([$pincode]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Store user information in session
        $_SESSION['Fullname'] = $user['name'];
        $_SESSION['userID'] = $user['id'];

        // Retrieve user's clocked in status from session
        $clocked_in = isset($_SESSION['clocked_in']) ? $_SESSION['clocked_in'] : false;

        // Perform clock-in or clock-out action based on user's status
        if ($action === 'clock_in') {
            if ($clocked_in) {
                // User is already clocked in
                $_SESSION['error_msg'] = "You are already clocked in.";
            } else {
                // User is not clocked in, update the database with clock-in time
                $stmt = $pdo->prepare('INSERT INTO timecards (employeeID, clockIn) VALUES (?, NOW())');
                $stmt->execute([$user['id']]);
                // Update user's clocked_in status in session
                $_SESSION['clocked_in'] = true;
                // Redirect to POS page
                header("Location:../group1/startingcash.php");
                exit();
            }
        } elseif ($action === 'clock_out') {
            if (!$clocked_in) {
                // User is not clocked in, cannot clock out
                $_SESSION['error_msg'] = "You need to clock in first before you can clock out.";
            } else {
                // User is clocked in, update the database with clock-out time and total hours worked
                $stmt = $pdo->prepare('UPDATE timecards SET clockOut = NOW(), totalHours = ROUND((TIMESTAMPDIFF(SECOND, clockIn, NOW()) / 3600), 2) WHERE employeeID = ? AND clockOut IS NULL');
                $stmt->execute([$user['id']]);
                // Update user's clocked_in status in session
                $_SESSION['clocked_in'] = false;
            }
        } else {
            // Invalid action
            $_SESSION['error_msg'] = "Invalid action.";
        }

        // Redirect to appropriate page
        header("Location: pincode.php");
        exit();
    } else {
        // Invalid PIN code
        $_SESSION['error_msg'] = "Invalid PIN code.";
        header("Location: pincode.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter PIN</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .pin-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            text-align: center;
        }

        h2 {
            margin-top: 0;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error {
            color: #ff0000;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="pin-container">
        <h2>Enter PIN</h2>
        <?php if (isset($_SESSION['error_msg'])): ?>
            <div class="error"><?php echo htmlspecialchars($_SESSION['error_msg']); ?></div>
            <?php unset($_SESSION['error_msg']); ?>
        <?php endif; ?>
        <form action="pincode.php" method="post">
            <div class="form-group">
                <label for="pin">PIN</label>
                <input type="password" id="pincode" name="pincode" pattern="\d{4}" title="Pincode must be 4 digits"
                    maxlength="4" required>
            </div>
            <div class="form-group">
                <label for="action">Select Action</label>
                <select id="action" name="action">
                    <option value="clock_in">Clock In</option>
                    <option value="clock_out">Clock Out</option>
                </select>
            </div>
            <button type="submit" value="Submit">Submit</button>
        </form>
    </div>

    <script>
        document.getElementById('pincode').addEventListener('input', function (event) {
            var input = event.target.value;
            var regex = /^[0-9]*$/; // Regular expression to match only digits

            if (!regex.test(input)) {
                // If input contains non-numeric characters, remove them
                event.target.value = input.replace(/\D/g, '');
            }
        });
    </script>
</body>

</html>
