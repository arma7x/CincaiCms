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
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$blogs_metadata = new FileMetadata(__dir__.'/../../../application/blogs_metadata.json');
		$metadata_index = FileEditor::TitleFriendly($_POST['title']);
		if(count($blogs_metadata->seekMetadata($metadata_index)) != 0) {
			$_SESSION['flash']['warning'] = 'Title is already exist';
		} else {
			$file = [
				'author' => $_POST['author'],
				'title' => $_POST['title'], 
				'description' => $_POST['description'], 
				'save_path' => FileEditor::FolderFriendly($_POST['save_path']), 
				'created_at' => time(),
				'updated_at' => time(),
				'content' => $_POST['content'],
				'metadata_index' => $metadata_index
			];
			$editor = new FileEditor($file, $blogs_metadata);
			if ($editor->storeFile(__dir__.'/../../../application/blogs')) {
				$_SESSION['flash']['success'] = 'Successfully add new blog post';
				header('Location: /admin/blog');
				die;
			} else {
				$_SESSION['flash']['warning'] = 'Fail add new blog post';
				header('Location: /admin/blog');
				die;
			}
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

		<title>Admin Panel - Add Blog Post</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link rel="stylesheet" href="/theme.css">
		<link rel="stylesheet" href="https://unpkg.com/lite-editor@1.6.39/css/lite-editor.css">
	</head>

	<body>
		<header>
			<!-- Fixed navbar -->
			<?php require_once('../../../application/template/admin_navbar.php'); ?>
		</header>

		<!-- Begin blog content -->
		<main role="main" class="container">
			<?php require_once('../../../application/template/flash_message.php'); ?>
			<h1 class="text-center mt-5">Add Blog Post</h1>
			<hr>
			<form id="form-login" class="add-blog" action="/admin/blog/add.php" method="post">
				<div class="form-label-group mb-2">
					<input type="text" name="title" id="title" class="form-control" placeholder="Please enter title" required>
				</div>
				<div class="form-label-group mb-2">
					<input type="text" name="author" id="author" class="form-control" placeholder="Please enter author" required>
				</div>
				<div class="form-label-group mb-2">
					<input type="text" name="description" id="description" class="form-control" placeholder="Please enter description" required>
				</div>
				<div class="form-label-group mb-2">
					<input type="text" name="save_path" id="save_path" class="form-control" placeholder="Please enter save path">
				</div>
				<div class="form-label-group mb-2">
					<textarea type="text" name="content" id="html-editor" class="form-control" placeholder="Please enter content"  required></textarea>
				</div>
				<button id="login" class="btn btn-primary btn-sm" type="submit">Save Blog Post</button>
			</form>
		</main>

		<?php require_once('../../../application/template/admin_footer.php'); ?>
		<script src="https://unpkg.com/lite-editor@1.6.39/js/lite-editor.min.js"></script>
		<script>
			var editor = new LiteEditor('#html-editor', {
				nl2br: false,
				minHeight: 400,
				maxHeight: 700,
			});
		</script>
	</body>
</html>
