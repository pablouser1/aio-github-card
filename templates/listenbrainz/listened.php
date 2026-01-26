<svg id="svg" width="<?= $this->e($params->width) ?>" height="150" viewBox="0 0 <?= $this->e($params->width) ?> 150"
    version="1.1" xmlns="http://www.w3.org/2000/svg">
    <title><?= $this->e($params->username) ?> play info</title>
    <style>
        <?= $this->getThemeCSS($params->theme, 'listenbrainz') ?>
    </style>
    <rect class="background" width="100%" height="100%" />
    <g>
        <image height="100%"
            href="data:image/jpg;base64,<?= base64_encode(file_get_contents($this->listenbrainz_poster_url($data->track_metadata->mbid_mapping->caa_release_mbid))) ?>" />
    </g>
    <image x="180" y="10" width="160" height="40"
        href="data:image/svg+xml;base64,<?= $this->getLogo("listenbrainz", $params->theme) ?>" />
    <g transform="translate(164, 45)">
        <text class="header" x="10" y="30"><?= $this->e($params->username) ?> listened</text>
        <g transform="translate(10, 65)">
            <a target="_blank" href="">
                <text class="title scroll"><?= $this->e($data->track_metadata->track_name) ?></text>
            </a>
            <text class="text" y="20"><?= $this->e($data->track_metadata->artist_name) ?></text>
        </g>
    </g>
</svg>
