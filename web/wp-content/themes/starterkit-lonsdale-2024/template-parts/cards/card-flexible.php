<div class="card-flexible <?= $args['theme'] ?>">
	<a href="<?= $args["url"] ?>">
		<h3><?= $args['title'] ?></h3>

		<div class="desc"><?= $args['description'] ?></div>

		<?= component::picture($args['images']) ?>
	</a>
</div>