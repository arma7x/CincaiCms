<?php 
	session_start(); 
	include_once('../../../system/FileMetadata.php');
	include_once('../../../system/FileEditor.php');

	if ($_SESSION['admin'] !== true) {
		$_SESSION['flash']['warning'] = 'Forbidden Access';
		header('Location: /admin/index.php');
		die;
	}

	$blogs_metadata = new FileMetadata(__dir__.'/../../../application/blogs_metadata.json');
	if (isset($_GET['index'])) {
		$file = $blogs_metadata->seekMetadata($_GET['index']);
		if(count($file) === 0) {
			$_SESSION['flash']['warning'] = 'File `'.$_GET['index'].'` does not exist';
			header('Location: /admin/blog');
			die;
		} else {
			$file['metadata_index'] = $_GET['index'];
			$editor = new FileEditor($file, $blogs_metadata);
			$editor->getContent(__dir__.'/../../../application/blogs');
		}
	}
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if ($editor->removeFile(__dir__.'/../../../application/blogs')) {
			$editor->content = $_POST['content'];
			$editor->author = $_POST['author'];
			$editor->title = $_POST['title'];
			$editor->description = $_POST['description'];
			$editor->save_path = FileEditor::FolderFriendly($_POST['save_path']);
			$editor->updated_at = time();
			if ($editor->storeFile(__dir__.'/../../../application/blogs')) {
				$_SESSION['flash']['success'] = 'Successfully edit blog `'.$_POST['title'].'`';
				header('Location: /admin/blog');
				die;
			} else {
				$_SESSION['flash']['warning'] = 'Fail edit blog `'.$_POST['title'].'`';
				header('Location: /admin/blog');
				die;
			}
		} else {
			$_SESSION['flash']['success'] = 'Fail edit blog `'.$_POST['title'].'`';
			header('Location: /admin/blog');
			die;
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

		<title>Admin Panel - Edit blog[<?php echo $editor->title; ?>]</title>
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
			<h1 class="text-center mt-5">Edit Post[<?php echo $editor->title; ?>]</h1>
			<small>* Please use alphanumeric character and dash only</small><br>
			<small>* Save path convert space between word as subfolder, etc <strong>`folder`</strong> -> <strong>folder</strong> / <strong>`folder subfolder`</strong> -> <strong>folder<?php echo DIRECTORY_SEPARATOR ?>subfolder</strong></small><br>
			<hr>
			<form id="form-login" class="add-blog" action="/admin/blog/edit.php?index=<?php echo $_GET['index'] ?>" method="post">
				<div class="form-label-group mb-2">
					<input value="<?php echo isset($_POST['title']) ? $_POST['title'] : $editor->title ?>" type="text" name="title" id="title" class="form-control" placeholder="Please enter title" required>
				</div>
				<div class="form-label-group mb-2">
					<input value="<?php echo isset($_POST['author']) ? $_POST['author'] : $editor->author ?>" type="text" name="author" id="author" class="form-control" placeholder="Please enter author" required>
				</div>
				<div class="form-label-group mb-2">
					<input value="<?php echo isset($_POST['description']) ? $_POST['description'] : $editor->description ?>" type="text" name="description" id="description" class="form-control" placeholder="Please enter description" required>
				</div>
				<div class="form-label-group mb-2">
					<input value="<?php echo isset($_POST['save_path']) ? $_POST['save_path'] : $editor->save_path ?>" type="text" name="save_path" id="save_path" class="form-control" placeholder="Please enter save path">
				</div>
				<div class="form-label-group mb-2">
					<textarea type="text" name="content" id="html-editor" class="form-control" placeholder="Please enter content" required>
						<?php echo isset($_POST['content']) ? $_POST['content'] : $editor->content ?>
					</textarea>
				</div>
				<button id="login" class="btn btn-primary btn-sm" type="submit">Edit Blog Post</button>
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
