<svg id="svg" width="<?= $this->e($width) ?>" height="235" viewBox="0 0 <?= $this->e($width) ?> 235" version="1.1" xmlns="http://www.w3.org/2000/svg">
	<title><?= $this->e($username) ?> stats</title>
	<style>
        <?= $this->getThemeCSS($theme) ?>
	</style>
	<rect class="background" width="100%" height="100%" />
    <image y="10" width="100%" height="50" href="data:image/svg+xml;base64,<?= $this->getLogo($theme) ?>" />
	<text class="header" x="<?= $this->e($width / 4) ?>" y="85"><?= $this->e($username) ?>'s stats</text>
	<g transform="translate(10, 120)">
		<text class="title">Movies:</text>
		<text class="text" x="170"><?= $this->e($data->movies->watched) ?> Watched</text>
		<text class="text" x="170" y="15"><?= $this->e($this->toHours($data->movies->minutes)) ?> hours total</text>
	</g>
	<g transform="translate(10, 165)">
		<text class="title">Shows:</text>
		<text class="text" x="170"><?= $this->e($data->shows->watched) ?> Watched</text>
	</g>
	<g transform="translate(10, 200)">
		<text class="title">Episodes:</text>
		<text class="text" x="170"><?= $this->e($data->episodes->watched) ?> Watched</text>
		<text class="text" x="170" y="15"><?= $this->e($this->toHours($data->episodes->minutes)) ?> hours total</text>
	</g>
</svg>
