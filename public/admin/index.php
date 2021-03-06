<?php
	session_start();
	include_once('../../../system/DevScript.php');

	if (isset($_GET['action'])) {
		if ($_GET['action'] === 'logout' && $_SESSION['admin'] === true) {
			$_SESSION['admin'] = false;
			$_SESSION['flash']['success'] = 'Successfully logout from admin panel';
			header('Location: /admin/index.php');
			die;
		} else if ($_GET['action'] === 'login' && $_SESSION['admin'] !== true && $_SERVER['REQUEST_METHOD'] === 'POST') {
			require_once('../../system/Passwd.php');
			if (isset($_POST['password'])) {
				if (password_verify($_POST['password'], $password)) {
					$_SESSION['admin'] = true;
					$_SESSION['flash']['success'] = 'Successfully login into admin panel';
					header('Location: /admin/index.php');
					die;
				} else {
					$_SESSION['flash']['danger'] = 'Fail login into admin panel';
				}
			}
		} else if ($_GET['action'] === 'update-password' && $_SESSION['admin'] !== true && $_SERVER['REQUEST_METHOD'] === 'GET') {
			header('Location: /admin/index.php');
		} else if ($_GET['action'] === 'update-password' && $_SESSION['admin'] === true && $_SERVER['REQUEST_METHOD'] === 'POST') {
			require_once('../../system/Passwd.php');
			if (password_verify($_POST['current_password'], $password) && ($_POST['confirm_password'] === $_POST['new_password'])) {
				$hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
				file_put_contents('../../system/Passwd.php', '<?php $password = \''.$hash.'\'; ?>');
				$_SESSION['flash']['success'] = 'Successfully update current password';
				header('Location: /admin/index.php');
				die;
			} else {
				$_SESSION['flash']['danger'] = 'Incorrect current password';
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

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link rel="stylesheet" href="/theme.css">
	</head>

	<body>
		<header>
			<!-- Fixed navbar -->
			<?php require_once('../../application/template/admin_navbar.php'); ?>
		</header>

		<!-- Begin page content -->
		<main role="main" class="container">
			<?php require_once('../../application/template/flash_message.php'); ?>
			<div class="row justify-content-sm-center align-items-center" style="height:70vh;">
				<div class="col-sm-5 align-items-center">
				<?php if(isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
					<?php if ($_GET['action'] === 'update-password'): ?>
					<form id="form-update-password" class="form-signin" action="/admin/index.php?action=update-password" method="post">
						<h1 class="text-center">UPDATE PASSWORD</h1>
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
						<h1 class="text-center">PLEASE LOGIN</h1>
						<div class="form-label-group mb-2">
							<input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
						</div>
						<button id="login" class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
					</form>
				<?php endif; ?>
				</div>
			</div>
		</main>

		<?php require_once('../../application/template/admin_footer.php'); ?>
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
