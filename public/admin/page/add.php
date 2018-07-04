<?php 

	session_start();
	include_once('../../../system/FileMetadata.php');
	include_once('../../../system/FileEditor.php');

	if ($_SESSION['admin'] !== true) {
		$_SESSION['flash']['warning'] = 'Forbidden Access';
		header('Location: /admin/index.php');
		die;
	}
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$pages_metadata = new FileMetadata(__dir__.'/../../../application/pages_metadata.json');
		$metadata_index = str_replace(' ', '-', preg_replace("/[^[:alnum:][:space:]]/u", '', $_POST['title']));
		if(count($pages_metadata->seekMetadata($metadata_index)) != 0) {
			$_SESSION['flash']['warning'] = 'Title is already exist';
		} else {
			$file = [
				'author' => $_POST['author'] ? 'test' : 'author here',
				'title' => $_POST['title'], 
				'description' => $_POST['description'] ? 'test' : 'description here', 
				'save_path' => strtolower($_POST['save_path']), 
				'created_at' => time(),
				'updated_at' => time(),
				'content' => $_POST['content'] ? 'test' : 'test',
				'metadata_index' => $metadata_index
			];
			$editor = new FileEditor($file, $pages_metadata);
			$editor->storeFile(__dir__.'/../../../application/pages');
			$_SESSION['flash']['success'] = 'Successfully add new page';
			header('Location: /admin/page');
			die;
		}
	} else {
		
	}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin Panel - Add Page</title>

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
      <?php require_once('../../../application/template/admin_navbar.php'); ?>
    </header>

    <!-- Begin page content -->
    <main role="main" class="container">
      <?php require_once('../../../application/template/flash_message.php'); ?>
      <h1 class="mt-5">Page Add</h1>
		<form id="form-login" class="form-signin" action="/admin/page/add.php" method="post">
		  <h1 class="text-center">PLEASE LOGIN</h1>
		  <div class="form-label-group mb-2">
			<input type="text" name="title" id="title" class="form-control" placeholder="Please enter title" required>
		  </div>
		  <div class="form-label-group mb-2">
			<input type="text" name="save_path" id="save_path" class="form-control" placeholder="Please save path">
		  </div>
		  <button id="login" class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		</form>
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
  </body>
</html>
