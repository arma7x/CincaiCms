<?php if(isset($_SESSION['flash'])): ?>
	<?php foreach($_SESSION['flash'] as $type => $message): ?>
		<div class="alert alert-<?php echo $type ?> alert-dismissible fade show" role="alert">
		  <strong><?php echo $message ?></strong>
		  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
		<?php unset($_SESSION['flash'][$type]); ?>
	<?php endforeach; ?>
<?php endif; ?>
