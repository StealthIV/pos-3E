<?php
$page_title = 'Add User';
require_once 'includes/load.php';
require_once '../group3/dbcon.php';

// Check user permission level
page_require_level(1);

// Fetch employees from the database
try {
    $stmt = $pdo->query('SELECT * FROM users');
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Handle form submission for adding timecard
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_timecard'])) {
    try {
        // Retrieve form data
        $employeeID = $_POST['employee'];
        $clockIn = $_POST['clock_in'];
        $clockOut = $_POST['clock_out'];

        // Calculate total hours worked
        $totalHours = round((strtotime($clockOut) - strtotime($clockIn)) / 3600, 2);

        // Check if total hours are negative
        if ($totalHours < 0) {
            // Handle negative total hours (display warning or take appropriate action)
            echo "<script>alert('Warning: Total hours cannot be negative.');</script>";
        } else {
            // Insert timecard data into the database
            $stmt = $pdo->prepare('INSERT INTO timecards (employeeID, clockIn, clockOut, totalHours) VALUES (?, ?, ?, ?)');
            $stmt->execute([$employeeID, $clockIn, $clockOut, $totalHours]);

            // Redirect back to time.php after adding timecard
            header("Location: ../group2/time.php");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<?php include_once 'layouts/header.php'; ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Add New User</span>
            </strong>
        </div>
        <div class="panel-body">
            <div class="col-md-6">
                <div class="container">
                    <div class="add-timecard-form">
                        <h2>Add Timecard</h2>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="employee">Select Employee:</label>
                                <select id="employee" name="employee" required>
                                    <?php foreach ($employees as $employee): ?>
                                        <option value="<?php echo $employee['id']; ?>">
                                            <?php echo $employee['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="clock_in">Clock In:</label>
                                <input type="datetime-local" id="clock_in" name="clock_in" required>
                            </div>
                            <div class="form-group">
                                <label for="clock_out">Clock Out:</label>
                                <input type="datetime-local" id="clock_out" name="clock_out" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="add_timecard">Add Timecard</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once 'layouts/footer.php'; ?>
