<!doctype html>
<html lang="en">
  <head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?php echo isset($data['metadata']['description']) ? $data['metadata']['description'] : '' ?>">
	<meta name="author" content="<?php echo isset($data['metadata']['author']) ? $data['metadata']['author'] : '' ?>">

	<title>CinCaiCMS - <?php echo isset($data['metadata']['title']) ? $data['metadata']['title'] : ucwords(humanReadable('-', $data['title'])) ?></title>

	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/theme.css?<?php echo filemtime(realpath(__dir__.'/../../public/theme.css')) ?>">

	</head>
	<body>
		<header>
		<!-- Fixed navbar -->
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<a class="navbar-brand" href="/">CinCaiCMS</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<?php if (count($data['blog_tree']) > 0): ?>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<?php echo pageTreeNavigator($data['page_tree'], $data['page_ordering'], $file->request_query, $data['pages_metadata']); ?>
				<form id="search-form" class="form-inline my-2 my-lg-0" autocomplete="off">
					<input id="search_keyword" class="form-control mr-sm-2" type="search" placeholder="Search blog posts" aria-label="Search">
				</form>
			</div>
			<?php endif ?>
		</nav>
	</header>
	<?php if (count($data['blog_tree']) > 0): ?>
	<div class="row">
		<div id="search_result" class="bg-info text-white position-absolute search-result"></div>
	</div>
	<?php endif ?>
	<!-- Body is here -->
	<main role="main" class="container">
		<div class="row">
			<div class="<?php echo (count($data['blog_tree']) > 0) ? 'col-md-8 col-lg-9': 'col-md-12 col-lg-12' ?>">
				<?php if (isset($file->request_query[0]) && $file->request_query[0] === 'blogs'): ?>
					<?php if (count($data['metadata']) > 0): ?>
						<h3><?php echo isset($data['metadata']['title']) ? $data['metadata']['title'] : ucwords(humanReadable('-', $data['title'])) ?></h3>
						<small>
							<b>Author: <?php echo isset($data['metadata']['author']) ? $data['metadata']['author'] : 'Admin' ?></b>
							<b>/ Published: <?php echo date("jS F, Y, H:i:s P", $data['metadata']['created_at']) ?></b>
							<?php if ($data['metadata']['created_at'] !== $data['metadata']['updated_at']): ?>
								<b>/ Modified: <?php echo date("jS F, Y, H:i:s P", $data['metadata']['updated_at']) ?></b>
							<?php endif ?>
						</small><br/>
					<?php endif ?>
				<?php endif ?>
				<?php echo $data['content']; ?>
			</div>
			<?php if (count($data['blog_tree']) > 0): ?>
			<div class="col-md-4 col-lg-3" id="sidebar">
				<h3>Blog Posts</h3>
				<?php echo blogTreeNavigator('/blogs', $data['blog_tree'], '', $data['blogs_metadata']); ?>
			</div>
			<?php endif; ?>
		</div>
	</main>
	<!-- Footer is here -->
	<footer class="footer">
		<div class="container-fluid">
		<?php  $data['stat_ram'] = ramUsage(); $data['stat_timer'] = endTimer(); ?>
		<span class="text-muted"><?php echo $data['stat_timer']; ?>Second/<?php echo $data['stat_ram']; ?>MB <a href="/admin" class="float-md-right"><i class="fa fa-user-secret"></i> Admin Panel</a></span>
		</div>
	</footer>
   
	<!-- Bootstrap core JavaScript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script>
		var blogs_metadata = <?php echo json_encode($data['blogs_metadata']) ?>

		function searching(word) {
			var prependBody = ''
			$('#search_result').empty();
			for (key in blogs_metadata) {
				if (blogs_metadata[key]['title'].toLowerCase().search(word.toLowerCase()) != -1) {
					if (blogs_metadata[key]['save_path'] == '') {
						prependBody += '<li><a class="m-1 text-white" href="/blogs/'+key+'">'+blogs_metadata[key]['title']+'</a></li>'
					} else {
						var folders = blogs_metadata[key]['save_path'].split(' ')
						if (folders.length > 1) {
							prependBody += '<li><a class="m-1 text-white" href="/blogs/'+folders.join('/')+'/'+key+'">'+blogs_metadata[key]['title']+'</a></li>'
						} else {
							prependBody += '<li><a class="m-1 text-white" href="/blogs/'+blogs_metadata[key]['save_path']+'/'+key+'">'+blogs_metadata[key]['title']+'</a></li>'
						}
					}
				}
			}
			if (prependBody != '') {
				$('#search_result').prepend('<ol class="pt-3">'+prependBody+'</ol>');
			}
		}

		$(document).ready(function() {
			var wait
			$('#search_keyword').on('keyup', function() {
				if (wait) {
					clearTimeout(wait)
				}
				wait = setTimeout(function () {
					if ($('#search_keyword').val() != 'undefined' && $('#search_keyword').val() != '') {
						(function(word, cb) {
							cb(word)
						})($('#search_keyword').val(), searching)
					} else if ($('#search_keyword').val() == '') {
						$('#search_result').empty();
					}
				}, 600)
			});
			$('#search_keyword').on('focus', function() {
				if ($('#search_keyword').val() != 'undefined' && $('#search_keyword').val() != '') {
					(function(word, cb) {
						cb(word)
					})($('#search_keyword').val(), searching)
				}
			});
			$('#search_keyword').on('focusout', function() {
				setTimeout(function() {
					$('#search_result').empty();
				}, 600)
			});
			$('#search-form').submit(function(e) {
				e.preventDefault();
			})
		})
		
	</script>
  </body>
</html>

