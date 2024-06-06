<?php
$page_title = 'Add Group';
require_once ('includes/load.php');
require_once "../group3/dbcon.php";


// Checkin What level user has permission to view this page
page_require_level(1);
?>

<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  try {
    // Determine the access type based on the checkboxes
    $access = '';
    if (isset($_POST['pos']) && isset($_POST['back_office'])) {
      $access = 'pos,back_office'; // Both POS and Back Office
    } elseif (isset($_POST['pos'])) {
      $access = 'pos'; // Only POS
    } elseif (isset($_POST['back_office'])) {
      $access = 'back_office'; // Only Back Office
    }

    // Prepare a SQL statement to insert/update the role and access
    $stmt = $pdo->prepare("INSERT INTO accessright (name, access, accept, discounts, taxes, drawer, viewreceipts, refunds, Reprint, shift, Manageitem, costitem, settings, bViewsales, bmanageitem, bviewcost, bmanageemployee, bmanagecustomers, bmanagefeatured, bmanagebilling, bmanagepayment, bmanageloyalty, bmanagetaxes) 
                       VALUES (:name, :access, :accept, :discounts, :taxes, :drawer, :viewreceipts, :refunds, :Reprint, :shift, :Manageitem, :costitem, :settings, :bViewsales, :bmanageitem, :bviewcost, :bmanageemployee, :bmanagecustomers, :bmanagefeatured, :bmanagebilling, :bmanagepayment, :bmanageloyalty, :bmanagetaxes)
                       ON DUPLICATE KEY UPDATE 
                       access = VALUES(access), 
                       accept = VALUES(accept), 
                       discounts = VALUES(discounts), 
                       taxes = VALUES(taxes), 
                       drawer = VALUES(drawer), 
                       viewreceipts = VALUES(viewreceipts), 
                       refunds = VALUES(refunds), 
                       Reprint = VALUES(Reprint), 
                       shift = VALUES(shift), 
                       Manageitem = VALUES(Manageitem), 
                       costitem = VALUES(costitem), 
                       settings = VALUES(settings), 
                       bViewsales = VALUES(bViewsales), 
                       bmanageitem = VALUES(bmanageitem), 
                       bviewcost = VALUES(bviewcost), 
                       bmanageemployee = VALUES(bmanageemployee), 
                       bmanagecustomers = VALUES(bmanagecustomers), 
                       bmanagefeatured = VALUES(bmanagefeatured), 
                       bmanagebilling = VALUES(bmanagebilling), 
                       bmanagepayment = VALUES(bmanagepayment), 
                       bmanageloyalty = VALUES(bmanageloyalty), 
                       bmanagetaxes = VALUES(bmanagetaxes)");

    // Bind parameters
    $stmt->bindParam(':name', $_POST['Fullname']);
    $stmt->bindParam(':access', $access);
    // The rest of the parameter bindings remain the same



    // Assign values to variables
    $acceptValue = isset($_POST['accept']) ? 1 : 0;
    $discountsValue = isset($_POST['discounts']) ? 1 : 0;
    $taxesValue = isset($_POST['taxes']) ? 1 : 0;
    $drawerValue = isset($_POST['drawer']) ? 1 : 0;
    $viewreceiptsValue = isset($_POST['viewreceipts']) ? 1 : 0;
    $refundsValue = isset($_POST['refunds']) ? 1 : 0;
    $ReprintValue = isset($_POST['Reprint']) ? 1 : 0;
    $shiftValue = isset($_POST['shift']) ? 1 : 0;
    $ManageitemValue = isset($_POST['Manageitem']) ? 1 : 0;
    $costitemValue = isset($_POST['costitem']) ? 1 : 0;
    $settingsValue = isset($_POST['settings']) ? 1 : 0;
    $bViewsalesValue = isset($_POST['bViewsales']) ? 1 : 0;
    $bmanageitemValue = isset($_POST['bmanageitem']) ? 1 : 0;
    $bviewcostValue = isset($_POST['bviewcost']) ? 1 : 0;
    $bmanageemployeeValue = isset($_POST['bmanageemployee']) ? 1 : 0;
    $bmanagecustomersValue = isset($_POST['bmanagecustomers']) ? 1 : 0;
    $bmanagefeaturedValue = isset($_POST['bmanagefeatured']) ? 1 : 0;
    $bmanagebillingValue = isset($_POST['bmanagebilling']) ? 1 : 0;
    $bmanagepaymentValue = isset($_POST['bmanagepayment']) ? 1 : 0;
    $bmanageloyaltyValue = isset($_POST['bmanageloyalty']) ? 1 : 0;
    $bmanagetaxesValue = isset($_POST['bmanagetaxes']) ? 1 : 0;

    // Bind parameters
    $stmt->bindParam(':name', $_POST['Fullname']);
    $stmt->bindParam(':accept', $acceptValue);
    $stmt->bindParam(':discounts', $discountsValue);
    $stmt->bindParam(':taxes', $taxesValue);
    $stmt->bindParam(':drawer', $drawerValue);
    $stmt->bindParam(':viewreceipts', $viewreceiptsValue);
    $stmt->bindParam(':refunds', $refundsValue);
    $stmt->bindParam(':Reprint', $ReprintValue);
    $stmt->bindParam(':shift', $shiftValue);
    $stmt->bindParam(':Manageitem', $ManageitemValue);
    $stmt->bindParam(':costitem', $costitemValue);
    $stmt->bindParam(':settings', $settingsValue);
    $stmt->bindParam(':bViewsales', $bViewsalesValue);
    $stmt->bindParam(':bmanageitem', $bmanageitemValue);
    $stmt->bindParam(':bviewcost', $bviewcostValue);
    $stmt->bindParam(':bmanageemployee', $bmanageemployeeValue);
    $stmt->bindParam(':bmanagecustomers', $bmanagecustomersValue);
    $stmt->bindParam(':bmanagefeatured', $bmanagefeaturedValue);
    $stmt->bindParam(':bmanagebilling', $bmanagebillingValue);
    $stmt->bindParam(':bmanagepayment', $bmanagepaymentValue);
    $stmt->bindParam(':bmanageloyalty', $bmanageloyaltyValue);
    $stmt->bindParam(':bmanagetaxes', $bmanagetaxesValue);


    // Execute the statement
    $stmt->execute();

    echo "Role and access rights saved successfully!";
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}
?>
<?php
if (isset($_POST['add'])) {

  $req_fields = array('group-name', 'group-level');
  validate_fields($req_fields);

  if (find_by_groupName($_POST['group-name']) === false) {
    $session->msg('d', '<b>Sorry!</b> Entered Group Name already in database!');
    redirect('add_group.php', false);
  }
  if (empty($errors)) {
    $name = remove_junk($db->escape($_POST['group-name']));
    $status = remove_junk($db->escape($_POST['status']));

    $query = "INSERT INTO user_groups (";
    $query .= "group_name,group_level,group_status";
    $query .= ") VALUES (";
    $query .= " '{$name}', '{$level}','{$status}'";
    $query .= ")";
    if ($db->query($query)) {
      //sucess
      $session->msg('s', "Group has been creted! ");
      redirect('add_group.php', false);
    } else {
      //failed
      $session->msg('d', ' Sorry failed to create Group!');
      redirect('add_group.php', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('add_group.php', false);
  }
}
?>
<?php include_once ('layouts/header.php'); ?>
<div class="login-page">
  <div class="text-center">
    <h3>Add new user Group</h3>
  </div>

  <div class="container">
    <form action="" method="POST">
      <label for="fullname">Name:</label>
      <input type="text" id="Fullname" name="Fullname" required><br><br>
      <!-- POS Toggle Button -->
      <label for="pos">POS:</label>
      <input type="checkbox" id="pos" name="pos" class="toggle-btn"><br>

      <!-- Additional options for POS -->
      <div class="additional-options" id="pos-options">
        <label><input type="checkbox" name="accept" value=""> Accept payments</label><br>
        <label><input type="checkbox" name="discounts" value=""> Apply discounts with restricted
          access</label><br>
        <label><input type="checkbox" name="taxes" value=""> Change taxes in a sale</label><br>
        <label><input type="checkbox" name="drawer" value=""> Open cash drawer without making a
          sale</label><br>
        <label><input type="checkbox" name="viewreceipts" value=""> View all receipts</label><br>
        <label><input type="checkbox" name="refunds" value=""> Perform refunds</label><br>
        <label><input type="checkbox" name="Reprint" value=""> Reprint and resend receipts</label><br>
        <label><input type="checkbox" name="shift" value=""> View shift report</label><br>
        <label><input type="checkbox" name="Manageitem" value=""> Manage items</label><br>
        <label><input type="checkbox" name="costitem" value=""> View cost of items</label><br>
        <label><input type="checkbox" name="settings" value=""> Change settings</label><br>
      </div>
      <br>
      <!-- Back Office Toggle Button -->
      <label for="back_office">Back Office:</label>
      <input type="checkbox" id="back_office" name="back_office" class="toggle-btn"><br>

      <!-- Additional options for Back Office -->
      <div class="additional-options" id="back-office-options">
        <label><input type="checkbox" name="bViewsales" value=""> View sales reports</label><br>
        <label><input type="checkbox" name="bcancelreceipts" value=""> Cancel receipts</label><br>
        <label><input type="checkbox" name="bmanageitem" value=""> Manage items</label><br>
        <label><input type="checkbox" name="bviewcost" value=""> View cost of items</label><br>
        <label><input type="checkbox" name="bmanageemployee" value=""> Manage employees</label><br>
        <label><input type="checkbox" name="bmanagecustomers" value=""> Manage customers</label><br>
        <label><input type="checkbox" name="bmanagefeatured" value=""> Manage feature settings</label><br>
        <label><input type="checkbox" name="bmanagebilling" value=""> Manage billing</label><br>
        <label><input type="checkbox" name="bmanagepayment" value=""> Manage payment types</label><br>
        <label><input type="checkbox" name="bmanageloyalty" value=""> Manage loyalty program</label><br>
        <label><input type="checkbox" name="bmanagetaxes" value=""> Manage taxes</label><br>
      </div>

      <button type="submit">Add Role</button>
    </form>
  </div>

  <script>

    document.getElementById('pos').addEventListener('change', function () {
      var posOptions = document.getElementById('pos-options');
      posOptions.style.display = this.checked ? 'block' : 'none';
      // Toggle value between 1 and 0
      document.getElementById('pos_value').value = this.checked ? '1' : '0';
    });

    document.getElementById('back_office').addEventListener('change', function () {
      var backOfficeOptions = document.getElementById('back-office-options');
      backOfficeOptions.style.display = this.checked ? 'block' : 'none';
      // Toggle value between 1 and 0
      document.getElementById('back_office_value').value = this.checked ? '1' : '0';
    });
  </script>

</div>

<?php include_once ('layouts/footer.php'); ?>