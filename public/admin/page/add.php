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
		$metadata_index = strtolower(str_replace(' ', '-', preg_replace("/[^[:alnum:][:space:]]/u", '', $_POST['title'])));
		if(count($pages_metadata->seekMetadata($metadata_index)) != 0) {
			$_SESSION['flash']['warning'] = 'Title is already exist';
		} else {
			$file = [
				'author' => $_POST['author'],
				'title' => $_POST['title'], 
				'description' => $_POST['description'], 
				'save_path' => strtolower($_POST['save_path']), 
				'created_at' => time(),
				'updated_at' => time(),
				'content' => $_POST['content'],
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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.10.0/ui/trumbowyg.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.10.0/plugins/colors/ui/trumbowyg.colors.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.10.0/plugins/emoji/ui/trumbowyg.emoji.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.10.0/plugins/highlight/ui/trumbowyg.highlight.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.10.0/plugins/mathml/ui/trumbowyg.mathml.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.10.0/plugins/mention/ui/trumbowyg.mention.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.10.0/plugins/table/ui/trumbowyg.table.min.css">
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
		<h1 class="text-center mt-5">Add Page</h1>
		<form id="form-login" class="add-page" action="/admin/page/add.php" method="post">
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
		  <button id="login" class="btn btn-primary btn-sm" type="submit">Save Page</button>
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
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.10.0/trumbowyg.min.js"></script>
	<script>
		$.trumbowyg.svgPath = '/icons.svg';
		$('#html-editor').trumbowyg({
			autogrow: true,
			removeformatPasted: false,
			resetCss: false,
		});
	</script>
  </body>
</html>
