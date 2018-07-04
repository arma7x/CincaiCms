<?php 
	session_start(); 
	include_once('../../../system/FileMetadata.php');
	include_once('../../../system/FileEditor.php');
	ini_set('xdebug.var_display_max_depth', 5);
	ini_set('xdebug.var_display_max_children', 256);
	ini_set('xdebug.var_display_max_data', 1024);

	if ($_SESSION['admin'] !== true) {
		$_SESSION['flash']['warning'] = 'Forbidden Access';
		header('Location: /admin/index.php');
		die;
	}
	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		$pages_metadata = new FileMetadata(__dir__.'/../../../application/pages_metadata.json');
		if (isset($_GET['delete'])) {
			$file = $pages_metadata->seekMetadata($_GET['delete']);
			if(count($file) === 0) {
				$_SESSION['flash']['warning'] = 'File `'.$_GET['delete'].'` does not exist';
				header('Location: /admin/page');
				die;
			} else {
				$file['metadata_index'] = $_GET['delete'];
				$editor = new FileEditor($file, $pages_metadata);
				$editor->removeFile(__dir__.'/../../../application/pages');
				$_SESSION['flash']['success'] = 'Successfully remove page `'.$_GET['delete'].'`';
				header('Location: /admin/page');
				die;
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

    <title>Admin Panel - Pages</title>

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
      <h1 class="text-center mt-5">Page List</h1>
	  <div class="table-responsive">
      <table class="table">
		  <tr>
			<th class="table-dark">Title</th>
			<th class="table-dark">Author</th>
			<th class="table-dark">Description</th>
			<th class="table-dark">Save Path</th>
			<th class="table-dark">Created At</th>
			<th class="table-dark">Updated At</th>
			<th class="table-dark">Action</th>
		  </tr>
		  <?php foreach($pages_metadata->getMetadata() as $index => $meta): ?>
			<tr>
				<td><?php echo $meta['title'] ?></td>
				<td><?php echo $meta['author'] ?></td>
				<td><?php echo $meta['description'] ?></td>
				<td><?php echo $meta['save_path'] ?></td>
				<td><?php echo date("jS F, Y, H:i:s P", $meta['created_at']) ?></td>
				<td><?php echo date("jS F, Y, H:i:s P", $meta['updated_at']) ?></td>
				<td>
					<button class="btn btn-sm btn-danger" onclick="deletePage('<?php echo $index ?>')">Delete</button>
					<button class="btn btn-sm btn-warning text-white" onclick="editPage('<?php echo $index ?>')">Edit</button>
				</td>
			</tr>
		  <?php endforeach ?>
	  </table>
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
		function deletePage(index) {
			var res = confirm('Are you sure to remove page `'+index+'` ?')
			if (res) {
				window.location.href = '/admin/page?delete='+index;
			}
		}
		function editPage(index) {
			var res = confirm('Are you sure to edit page `'+index+'` ?')
			if (res) {
				window.location.href = '/admin/page/edit.php?index='+index;
			}
		}
	</script>
  </body>
</html>
