<?php
$page_title = 'All User';
require_once ('includes/load.php');
require_once "../group3/dbcon.php";

// Check what level user has permission to view this page
page_require_level(1);

// Initialize $timecards as an empty array
$timecards = [];
$totalHours = 0; // Initialize total hours counter

// Fetch data from the timecards table along with employee names and total hours worked
try {
  $stmt = $pdo->query('SELECT u.name AS Fullname, 
                                ROUND(SUM(TIMESTAMPDIFF(MINUTE, t.clockIn, t.clockOut)) / 60, 2) AS totalHours 
                         FROM timecards t
                         INNER JOIN users u ON t.employeeID = u.id
                         GROUP BY u.name');
  $timecards = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Calculate total hours worked by all employees
  foreach ($timecards as $timecard) {
    $totalHours += $timecard['totalHours'];
  }
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
?>
<?php include_once ('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Users</span>
        </strong>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="align-left">Employee</th>
              <th class="align-left">Total hours</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($timecards as $timecard): ?>
              <tr>
                <td>
                  <?php echo $timecard['Fullname']; ?>
                </td>
                <td>
                  <?php echo $timecard['totalHours']; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="1" style="text-align: left;">Total</th>
              <th>
                <?php echo $totalHours; ?>
              </th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include_once ('layouts/footer.php'); ?>
