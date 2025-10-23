<div class="card-news">
	<a href="<?= $args["url"] ?>">
		<?= component::title($args["hx"], $args['title'], "title-2") ?>

		<div class="desc"><?= $args['description'] ?></div>

		<?= component::picture($args['images'],true) ?>
	</a>
</div>