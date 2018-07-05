<?php 
	session_start();
	include_once('../../../system/DevScript.php');
	include_once('../../../system/FileLocator.php');
	include_once('../../../system/FileMetadata.php');

	$data = [];
	if ($_SESSION['admin'] !== true) {
		header('Location: /admin/index.php');
	}
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$new_order = [];
		foreach($_POST['index'] as $index => $value) {
			$new_order[] = ['index'=> (int)$value, 'ico' => $_POST['icon'][$index]];
		}
		file_put_contents(__dir__.'/../../../application/pages_ordering.js', serialize($new_order));
		$_SESSION['flash']['success'] = 'Successfully update page sorting';
		header('Location: /admin/page/sort.php');
		die;
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

		<title>Admin Panel - Sort Page</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="/theme.css">
	</head>

	<body>
		<header>
			<!-- Fixed navbar -->
			<?php require_once('../../../application/template/admin_navbar.php'); ?>
		</header>

		<!-- Begin page content -->
		<main role="main" class="container">
			<?php require_once('../../../application/template/flash_message.php'); ?>
			<h1 class="mt-5 text-center">Sort Page</h1>
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
									<option value="<?php echo $i ?>" <?php echo isset($data['pages_ordering'][$index]) ? ($data['pages_ordering'][$index]['index'] == $i ? ' selected' : '') : '' ?>>
										<?php //echo $i ?> <?php echo isset($data['default_sort'][$i]) ? $data['default_sort'][$i][0] : '' ?>
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
								<input type="text" value="<?php echo isset($data['pages_ordering'][$index]) ? $data['pages_ordering'][$index]['ico'] : '' ?>" name="icon[]" class="form-control" placeholder="Enter fa icon" required>
							</td>
							<?php endforeach; ?>
						</tr>
					</table>
				</div>
				<button id="page-sort" class="btn btn-sm btn-primary" type="submit">Sort Now</button>
			</form>
		</main>

		<?php require_once('../../../application/template/admin_footer.php'); ?>
	</body>
</html>
