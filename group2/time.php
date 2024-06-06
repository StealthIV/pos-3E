<?php
$page_title = 'All User';
require_once ('includes/load.php');
require_once "../group3/dbcon.php";

// Fetch data from the timecards table along with employee names
try {
  $stmt = $pdo->query('SELECT t.*, u.name 
                       FROM timecards t
                       INNER JOIN users u ON t.employeeID = u.id');
  $timecards = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
?>
<?php
// Checkin What level user has permission to view this page
page_require_level(1);
$all_users = find_all_user();
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
        <a href="add_time.php" class="btn btn-warning pull-right">Add New User</a>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="align-left">Employee</th>
              <th class="align-left">Clock in</th>
              <th class="align-left">Clock out</th>
              <th class="align-left">Total hours</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($timecards as $timecard): ?>
              <tr>
                <td>
                  <?php echo $timecard['name']; ?>
                </td>
                <td>
                  <?php echo $timecard['clockIn']; ?>
                </td>
                <td>
                  <?php echo $timecard['clockOut']; ?>
                </td>
                <td>
                  <?php echo $timecard['totalHours']; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include_once ('layouts/footer.php'); ?>
