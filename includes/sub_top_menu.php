<?php

if ( isset($_SESSION['username']) ) {
	$status = '';
	if ( @$_SESSION['sex'] == 'Male') {
		$status = 'Mr. '; 
	} else {
		$status = 'Ms. ';
	}
	echo 'Welcome to '.$status.' <strong>'.$_SESSION['fullname'].'</strong> <a href="pages/actions/action-login.php?action=logout" title="Logout">Logout</a>';
} else {
	echo '<a href="index.php?id=login" title="Login">Login</a> &nbsp;||&nbsp; <a href="index.php?id=register" title="Register">Register</a>';
}

?>
<!-- &nbsp;||&nbsp; <a href="index.php?id=register" title="Register">Register</a>-->