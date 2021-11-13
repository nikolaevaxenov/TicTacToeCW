<?php if (count($errorsReg) > 0) : ?>
	<div class="error">
		<?php foreach ($errorsReg as $error) : ?>
			<p class="<?= $theme['textColor'] ?>"><?php echo $error ?></p>
		<?php endforeach ?>
	</div>
<?php endif ?>