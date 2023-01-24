<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Trakt Github Card</title>
	<link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.classless.min.css">
	<script>
		const API_URL = './card'
	</script>
</head>
<body>
	<main class="container">
		<h1>Trakt Github Cards</h1>
		<form id="settings_form">
			<label for="theme">Theme</label>
			<select name="theme" value="default">
				<?php foreach ($themes as $theme): ?>
					<option value="<?= $this->e($theme)?>"><?= $this->e($theme, 'ucfirst') ?></option>
				<?php endforeach ?>
			</select>
			<label for="mode">Mode</label>
			<select name="mode" value="default">
                <?php foreach ($modes as $mode): ?>
                	<option value="<?= $this->e($mode)?>"><?= $this->e($mode, 'ucfirst') ?></option>
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
		<img id="svg"/>
		<h5>Copy as Markdown</h5>
		<textarea id="textarea" readonly></textarea>
	</main>
	<footer class="container">
		<small>Made with <span style="color: #e25555;">&#9829;</span> in <a target="_blank" href="https://github.com/pablouser1/trakt-github-card">Github</a></small>
	</footer>
	<script src="./scripts/home.js"></script>
</body>
</html>
