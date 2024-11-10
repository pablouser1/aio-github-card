<svg id="svg" width="<?= $this->e($params->width) ?>" height="190" viewBox="0 0 <?= $this->e($params->width) ?> 190" version="1.1" xmlns="http://www.w3.org/2000/svg">
	<title><?= $this->e($params->username) ?> play info</title>
	<style>
    <?= $this->getThemeCSS($params->theme, 'backloggd') ?>
	</style>
	<rect class="background" width="100%" height="100%" />
	<g>
		<image height="100%" href="data:image/jpg;base64,<?= base64_encode(file_get_contents($data->image)) ?>" />
	</g>
	<image x="180" y="10" width="160" height="60" href="data:image/png;base64,<?= $this->getLogo("backloggd", $params->theme) ?>" />
	<g transform="translate(144, 65)">
		<text class="header" x="10" y="30"><?= $this->e($params->username) ?> played</text>
		<g transform="translate(10, 70)">
			<a target="_blank" href="<?= $this->e($this->backloggd_url($data->path)) ?>">
			  <text class="title scroll"><?= $this->e($data->name) ?></text>
			</a>
      <text class="text" y="20">Rating: <?= $this->e($data->rating) ?></text>
		</g>
	</g>
</svg>
