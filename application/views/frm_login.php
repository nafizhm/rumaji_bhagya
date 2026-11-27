<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masuk | Bhagya Group</title>
<link rel="stylesheet" href="<?php echo $this->config->item('assets'); ?>/css/login.css?v=2">
</head>
<body>
<main class="login-shell">

<section class="form-panel" aria-labelledby="login-title"><div class="login-card">
<div class="logo-frame"><img src="<?php echo $this->config->item('assets'); ?>/img/bhagya-white.svg" alt="Bhagya Group"></div>
<span class="eyebrow form-eyebrow">PORTAL PENGELOLAAN PERUMAHAN</span>
<h2 id="login-title">Selamat datang kembali</h2><p class="intro">Masuk ke akun Anda untuk memulai aktivitas.</p>
<form action="<?php echo html_escape($form_action); ?>" method="post">
<?php if ($this->config->item('csrf_protection')): ?>
<input type="hidden" name="<?php echo html_escape($this->security->get_csrf_token_name()); ?>" value="<?php echo html_escape($this->security->get_csrf_hash()); ?>">
<?php endif; ?>
<label for="txt_usnam">Username</label><input type="text" id="txt_usnam" name="txt_usnam" placeholder="Masukkan username Anda" value="<?php echo html_escape(set_value('txt_usnam', '', FALSE)); ?>" autocomplete="username" autocapitalize="none" spellcheck="false" required>
<label for="txt_pass">Password</label><div class="password-field"><input type="password" id="txt_pass" name="txt_pass" placeholder="Masukkan password Anda" autocomplete="current-password" required><button type="button" id="toggle-password" aria-controls="txt_pass" aria-pressed="false" hidden>Lihat</button></div>
<button class="submit-button" type="submit">Masuk ke akun</button>
</form><p class="help-text">Kesulitan masuk? Hubungi administrator Anda.</p>
</div><footer>&copy; <?php echo date('Y'); ?> Bhagya Group <span>·</span> Sistem Informasi Perumahan</footer></section>
</main>
<script>
(function(){
var toggle=document.getElementById('toggle-password'),password=document.getElementById('txt_pass');
toggle.hidden=false;
toggle.addEventListener('click',function(){var visible=password.type==='password';password.type=visible?'text':'password';toggle.textContent=visible?'Sembunyi':'Lihat';toggle.setAttribute('aria-pressed',String(visible));});
}());
</script>
</body></html>
