<?php 
include_once('includes/load.php');
include_once "../group3/dbcon.php";

$req_fields = array('username', 'password');
validate_fields($req_fields);

$username = remove_junk($_POST['username']);
$password = remove_junk($_POST['password']);

if (empty($errors)) {
    $user_id = authenticate($username, $password);
    if ($user_id) {
        // Create session with id
        $session->login($user_id);
        
        // Update Sign in time
        updateLastLogIn($user_id);
        
        // Insert time-in record into attendance table
        $clockInTime = date('Y-m-d H:i:s');
        $attendanceID = recordClockIn($user_id, $clockInTime);
        
        // Add attendanceID to session
        $_SESSION['attendanceID'] = $attendanceID;
        
        $session->msg("s", "Welcome to Inventory Management System");
        redirect('admin.php', false);
    } else {
        $session->msg("d", "Sorry Username/Password incorrect.");
        redirect('index.php', false);
    }
} else {
    $session->msg("d", $errors);
    redirect('index.php', false);
}

function recordClockIn($user_id, $clockInTime) {
    global $pdo;
    
    try {
        // Prepare and execute the SQL statement using prepared statements
        $stmt = $pdo->prepare("INSERT INTO attendance (userID, clockInTime) VALUES (?, ?)");
        $stmt->execute([$user_id, $clockInTime]);
        
        // Return the ID of the inserted attendance record
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        // Handle any database errors
        return false;
    }
}
?>
