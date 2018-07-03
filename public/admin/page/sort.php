<?php 
	session_start();

	ini_set('xdebug.var_display_max_depth', 5);
	ini_set('xdebug.var_display_max_children', 256);
	ini_set('xdebug.var_display_max_data', 1024);

	include_once('../../../system/FileLocator.php');
	include_once('../../../system/FileMetadata.php');

	$data = [];
	if ($_SESSION['admin'] !== true) {
		header('Location: /admin/index.php');
	}
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$new_order = [];
		var_dump($_POST);
		foreach($_POST['index'] as $index => $value) {
			$new_order[] = ['index'=> (int)$value, 'ico' => $_POST['icon'][$index]];
		}
		file_put_contents(__dir__.'/../../../application/pages_ordering.js', serialize($new_order));
		header('Location: /admin/page/sort.php');
	}
	$data['default_sort'] = [];
	$page_tree = FileLocator::getDirectoryTree(__dir__.'/../../../application/pages');
	foreach($page_tree as $index => $value) {
		if (is_string($value)) {
			$data['default_sort'][$index] = [$value, 'File'];
		} else {
			foreach ($value as $sub_index => $sub_value) {
				if (count($sub_value) == count($sub_value, COUNT_RECURSIVE)) {
					$data['default_sort'][$index] = [$sub_index, 'Folder'];
				}
			}
		}
	}
	$pages_ordering = file_get_contents(__dir__.'/../../../application/pages_ordering.js');
	$data['pages_ordering'] = unserialize($pages_ordering);
	if ($data['pages_ordering'] === null)
		$data['pages_ordering'] = [];
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin Panel - Page Sorting</title>

    <!-- Bootstrap core CSS -->
	<!--
		<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
	-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
	  <h1 class="mt-5 text-center">Page Sorting</h1>
	  <small>* Sorting from left to right</small>
	  <hr>
	  <form id="form-page-sorting" action="/admin/page/sort.php" method="post">
		  <div class="table-responsive">
			  <table class="table">
				<tr>
					<td class="table-dark">Default Sort</td>
					<?php foreach($data['default_sort'] as $index => $value): ?>
						<td><?php //echo $index ?><?php echo $value[0] ?>[<?php echo $value[1] ?>]</td>
					<?php endforeach; ?>
				</tr>
				<tr>
					<td class="table-dark">Current Sort</td>
					<?php if (count($data['pages_ordering']) === count($data['default_sort'])): ?>
						<?php foreach($data['pages_ordering'] as $index => $value): ?>
							<td><?php //echo $value['index'] ?><?php echo $data['default_sort'][$value['index']][0] ?>[<i class="fa <?php echo $value['ico'] ?>"></i> ~ <?php echo $value['ico'] ?>]</td>
						<?php endforeach; ?>
					<?php endif; ?>
				</tr>
				<tr>
					<td class="table-dark">New Sort</td>
					<?php foreach($data['default_sort'] as $index => $value): ?>
						<td>
							<select name="index[]" class="form-control mb-1" required>
								<?php for($i=0;$i<count($data['default_sort']);$i++): ?>
									<option value="<?php echo $i ?>" <?php echo $data['pages_ordering'][$index]['index'] == $i ? ' selected' : '' ?>>
										<?php //echo $i ?> <?php echo $data['default_sort'][$i][0] ?>
									</option>
								<?php endfor; ?>
							</select>
						</td>
					<?php endforeach; ?>
				</tr>
				<tr>
					<td class="table-dark">New Icon</td>
					<?php foreach($data['default_sort'] as $index => $value): ?>
						<td>
							<input type="text" value="<?php echo $data['pages_ordering'][$index]['ico'] ?>" name="icon[]" class="form-control" placeholder="Enter fa icon" required>
						</td>
					<?php endforeach; ?>
				</tr>
			  </table>
			</div>
		<button id="page-sort" class="btn btn-sm btn-primary" type="submit">Sort Now</button>
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
