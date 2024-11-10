<svg id="svg" width="<?= $this->e($params->width) ?>" height="190" viewBox="0 0 <?= $this->e($params->width) ?> 190" version="1.1" xmlns="http://www.w3.org/2000/svg">
  <title><?= $this->e($params->username) ?> watching info</title>
  <style>
    <?= $this->getThemeCSS($params->theme, 'trakt') ?>
  </style>
  <rect class="background" width="100%" height="100%" />
  <g>
    <image height="100%" href="data:image/jpg;base64,<?= base64_encode(file_get_contents($data->poster)) ?>" />
  </g>
  <image x="180" y="10" width="160" height="60" href="data:image/svg+xml;base64,<?= $this->getLogo("trakt", $params->theme) ?>" />
  <g transform="translate(144, 65)">
    <text class="header" x="10" y="30"><?= $this->e($params->username) ?> <?= $data->isWatching ? 'is watching' : 'watched' ?></text>
    <?php if (!$data->isWatching): ?>
      <text class="header" x="10" y="50">On: <?= date("Y-m-d", strtotime($data->watched_at)) ?></text>
    <?php endif ?>
    <?php if ($data->type === 'episode'): ?>
      <!-- Episode with show -->
      <g transform="translate(10, 70)">
        <a target="_blank" href="<?= $this->e($this->trakt_show_url($data->show->ids->slug)) ?>">
          <text class="title text"><?= $this->e($data->show->title) ?> (<?= $this->e($data->show->year) ?>)</text>
        </a>
        <g transform="translate(0, 20)">
          <a target="_blank"
            href="<?= $this->e($this->trakt_show_url($data->show->ids->slug, $data->episode->season, $data->episode->number)) ?>">
            <text class="text">Episode <?= $this->e($data->episode->number) ?> /
              <?= $this->e($data->episode->title) ?></text>
          </a>
          <g>
            <a target="_blank" href="<?= $this->e($this->trakt_show_url($data->show->ids->slug, $data->episode->season)) ?>">
              <text class="text" y="15">Season <?= $this->e($data->episode->season) ?></text>
            </a>
          </g>
        </g>
      </g>
    <?php else: ?>
      <!-- Movie -->
      <g transform="translate(10, 70)">
        <a target="_blank" href="<?= $this->e($this->trakt_movie_url($data->movie->ids->slug)) ?>">
          <text class="title scroll"><?= $this->e($data->movie->title) ?> (<?= $this->e($data->movie->year) ?>)</text>
        </a>
      </g>
    <?php endif ?>
  </g>
</svg>
