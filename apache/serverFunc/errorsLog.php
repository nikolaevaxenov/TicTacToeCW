<?php if (count($errorsLog) > 0) : ?>
	<div class="error">
		<?php foreach ($errorsLog as $error) : ?>
			<p class="<?= $theme['textColor'] ?>"><?php echo $error ?></p>
		<?php endforeach ?>
	</div>
<?php endif ?>