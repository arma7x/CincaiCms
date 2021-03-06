<?php 
	session_start();
	include_once('../../../system/DevScript.php');
	include_once('../../../system/FileMetadata.php');
	include_once('../../../system/FileEditor.php');

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
				if ($editor->removeFile(__dir__.'/../../../application/pages')) {
					$_SESSION['flash']['success'] = 'Successfully remove page `'.$_GET['delete'].'`';
					header('Location: /admin/page');
					die;
				} else {
					$_SESSION['flash']['warning'] = 'Fail remove page `'.$_GET['delete'].'`';
					header('Location: /admin/page');
					die;
				}
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

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link rel="stylesheet" href="/theme.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
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
			<hr>
			<div class="table-responsive">
				<table id="table" class="table">
					<thead>
						<tr>
							<th class="table-dark">Index</th>
							<th class="table-dark">Title</th>
							<th class="table-dark">Author</th>
							<th class="table-dark">Description</th>
							<th class="table-dark">Save Path</th>
							<th class="table-dark">Created At</th>
							<th class="table-dark">Updated At</th>
							<th class="table-dark">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($pages_metadata->getMetadata() as $index => $meta): ?>
						<tr>
							<td><?php echo $index ?></td>
							<td><?php echo $meta['title'] ?></td>
							<td><?php echo $meta['author'] ?></td>
							<td><?php echo $meta['description'] ?></td>
							<td><?php echo FileEditor::FolderFriendlyToURL($meta['save_path']); ?></td>
							<td><?php echo date("jS F, Y, H:i:s P", $meta['created_at']) ?></td>
							<td><?php echo date("jS F, Y, H:i:s P", $meta['updated_at']) ?></td>
							<td>
								<button class="mb-1 btn btn-sm btn-info text-white" onclick="viewPage('<?php echo FileEditor::FolderFriendlyToURL($meta['save_path']).'/'.$index; ?>')">View</button><br/>
								<button class="mb-1 btn btn-sm btn-warning text-white" onclick="editPage('<?php echo $index ?>')">Edit</button><br/>
								<button class="btn btn-sm btn-danger" onclick="deletePage('<?php echo $index ?>')">Delete</button>
							</td>
						</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</main>

		<?php require_once('../../../application/template/admin_footer.php'); ?>
		<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
		<script>
			$(document).ready(function() {
				$('#table').DataTable( {
					"pagingType": "full_numbers"
				});
			});
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
			function viewPage(url) {
				var res = confirm('Areyou sure to open url `'+url+'` in new tab ?')
				if (res) {
					window.open(url);
				}
			}
		</script>
	</body>
</html>
