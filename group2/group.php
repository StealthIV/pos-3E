<?php
$page_title = 'All Group';
require_once ('includes/load.php');
require_once "../group3/dbcon.php";

// Checkin What level user has permission to view this page
page_require_level(1);
$all_groups = find_all('user_groups');

try {
  // Execute the query to count the number of employees for each role and access
  $stmt = $pdo->query('SELECT ar.accessID, ar.name, ar.access, COUNT(e.userID) AS EmployeeCount
                       FROM accessright ar
                       LEFT JOIN emp e ON ar.accessID = e.accessID
                       GROUP BY ar.accessID, ar.name, ar.access');
  $roleAccessCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
          <span>Groups</span>
        </strong>
        <a href="add_group.php" class="btn btn-warning pull-right btn-sm"> Add New Group</a>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
            <th class="align-left"><input type="checkbox" id="select-all"></th>
                            <th class="align-left">No</th>
                            <th class="align-left">Role</th>
                            <th class="align-left">Access</th>
                            <th class="align-left">Employee Count</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($roleAccessCounts as $index => $roleAccessCount): ?>
              <tr>
                <!-- Add a checkbox for each row -->
                <td><input type="checkbox" name="selectedRoles[]" value="<?php echo $roleAccessCount['accessID']; ?>">
                </td>
                <td>
                  <?php echo $index + 1; ?>
                </td>
                <td onclick="location.href='crud/updateaccess.php?id=<?php echo $roleAccessCount['accessID']; ?>';"
                  style="cursor: pointer;">
                  <?php echo $roleAccessCount['name']; ?>
                </td>
                <td onclick="location.href='crud/updateaccess.php?id=<?php echo $roleAccessCount['accessID']; ?>';"
                  style="cursor: pointer;">
                  <?php echo $roleAccessCount['access']; ?>
                </td>
                <td onclick="location.href='crud/updateaccess.php?id=<?php echo $roleAccessCount['accessID']; ?>';"
                  style="cursor: pointer;">
                  <?php echo $roleAccessCount['EmployeeCount']; ?>
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

