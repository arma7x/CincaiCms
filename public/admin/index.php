<?php
session_start();
if (isset($_GET['action'])) {
	if ($_GET['action'] === 'logout' && $_SESSION['admin'] === true) {
		$_SESSION['admin'] = false;
		header('Location: /admin/index.php');
	} else if ($_GET['action'] === 'login' && $_SESSION['admin'] !== true && $_SERVER['REQUEST_METHOD'] === 'POST') {
		require_once('../../system/Passwd.php');
		if (isset($_POST['password'])) {
			if (password_verify($_POST['password'], $password)) {
				$_SESSION['admin'] = true;
				header('Location: /admin/index.php');
			}
		}
	} else if ($_GET['action'] === 'update-password' && $_SESSION['admin'] !== true && $_SERVER['REQUEST_METHOD'] === 'GET') {
		header('Location: /admin/index.php');
	} else if ($_GET['action'] === 'update-password' && $_SESSION['admin'] === true && $_SERVER['REQUEST_METHOD'] === 'POST') {
		require_once('../../system/Passwd.php');
		if (password_verify($_POST['current_password'], $password) && ($_POST['confirm_password'] === $_POST['new_password'])) {
			$hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
			file_put_contents('../../system/Passwd.php', '<?php $password = \''.$hash.'\'; ?>');
			header('Location: /admin/index.php');
		}
	}
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin Panel</title>

    <!-- Bootstrap core CSS -->
	<!--
		<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
	-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<style>
		/* Sticky footer styles
		-------------------------------------------------- */
		html {
		  position: relative;
		  min-height: 100%;
		}
		body {
		  /* Margin bottom by footer height */
		  margin-bottom: 60px;
		}
		.footer {
		  position: absolute;
		  bottom: 0;
		  width: 100%;
		  /* Set the fixed height of the footer here */
		  height: 60px;
		  line-height: 60px; /* Vertically center the text there */
		  background-color: #f5f5f5;
		}


		/* Custom page CSS
		-------------------------------------------------- */
		/* Not required for template or sticky footer method. */

		body > .container {
		  padding: 60px 15px 0;
		}

		.footer > .container {
		  padding-right: 15px;
		  padding-left: 15px;
		}

		code {
		  font-size: 80%;
		}
	</style>
  </head>

  <body>

    <header>
      <!-- Fixed navbar -->
      <?php require_once('../../application/template/admin_navbar.php'); ?>
    </header>

    <!-- Begin page content -->
    <main role="main" class="container">
    <div class="row justify-content-sm-center align-items-center" style="height:80vh;">
      <div class="col-sm-4 align-items-center">
	  <?php if(isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
		<?php if ($_GET['action'] === 'update-password'): ?>
			<form id="form-update-password" class="form-signin" action="/admin/index.php?action=update-password" method="post">
			  <h3 class="text-center">Update Password</h3>
			  <div class="form-label-group mb-2">
				<label for="currentPassword">Current Password</label>
				<input type="password" name="current_password" id="currentPassword" class="form-control" placeholder="Current Password" required>
			  </div>
			  <div class="form-label-group mb-2">
				<label for="newPassword">New Password</label>
				<input type="password" name="new_password" id="newPassword" class="form-control" placeholder="New Password" required>
			  </div>
			  <div class="form-label-group mb-2">
				<label for="confirmPassword">Confirm Password</label>
				<input type="password" name="confirm_password" id="confirmPassword" class="form-control" placeholder="Confirm Password" required>
			  </div>
			  <button id="update-password" class="btn btn-lg btn-primary btn-block" type="submit">Update Password</button>
			</form>
		<?php else: ?>
			<h1 class="text-center">WELCOME TO ADMIN PANEL</h1>
		<?php endif; ?>
	  <?php else: ?>
		<form  id="form-login" class="form-signin" action="/admin/index.php?action=login" method="post">
		  <h3 class="text-center">Login To Access Dashboard</h3>
		  <div class="form-label-group mb-2">
			<input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
		  </div>
		  <button id="login" class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		</form>
      <?php endif; ?>
      </div>
    </div>
    </main>

    <footer class="footer">
      <div class="container">
        <span class="text-muted">Place sticky footer content here.</span>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<!--
		<script src="/assets/js/jquery.min.js"></script>
		<script src="/assets/js/popper.min.js"></script>
		<script src="/assets/js/bootstrap.min.js"></script>
	-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script>
		$(document).ready(function() {
			$('#update-password').prop('disabled','disabled');
			$('#confirmPassword').on('keyup', function(){
				if ($('#confirmPassword').val() != $('#newPassword').val()) {
					$('#update-password').prop('disabled','disabled');
				} else {
					$('#update-password').prop('disabled',false);
				}
			});
			$('#newPassword').on('keyup', function(){
				if ($('#confirmPassword').val() != $('#newPassword').val()) {
					$('#update-password').prop('disabled','disabled');
				} else {
					$('#update-password').prop('disabled',false);
				}
			});
			$('#form-update-password').on('submit', function(){
				if ($('#confirmPassword').val() != $('#newPassword').val() || $('#confirmPassword').val() == 'undefined' || $('#newPassword').val() == 'undefined') {
					return false;
				}
				return true;
			});
	});
	</script>
  </body>
</html>
