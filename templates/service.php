<?php $this->layout('layout') ?>

<form id="settings_form">
  <label for="theme">Theme</label>
  <select name="theme" value="default">
    <?php foreach ($themes as $theme): ?>
      <option value="<?= $this->e($theme) ?>"><?= $this->e($theme, 'ucfirst') ?></option>
    <?php endforeach ?>
  </select>
  <label for="mode">Mode</label>
  <select name="mode" value="default">
    <?php foreach ($modes as $mode): ?>
      <option value="<?= $this->e($mode) ?>"><?= $this->e($mode, 'ucfirst') ?></option>
    <?php endforeach ?>
  </select>
  <label for="username">Username</label>
  <input name="username" type="text" />
  <div class="field">
    <div class="control">
      <button type="submit">Apply changes</button>
    </div>
  </div>
</form>
<img id="svg" />
<h5>Copy as Markdown</h5>
<textarea id="textarea" readonly></textarea>
<script src="../scripts/service.js"></script>
