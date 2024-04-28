<?php $this->layout('layout') ?>

<?php foreach ($services as $service): ?>
  <a href="./<?=$this->e($service)?>">
    <article><?= $this->e($service, 'ucfirst') ?></article>
  </a>
<?php endforeach; ?>
