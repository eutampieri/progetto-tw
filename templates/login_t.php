<h2>Entra</h2>
<form method="post" action="auth.php">
	<?php if(isset($_GET["login_error"])): ?>
		<div class="alert alert-danger" role="alert">
			Email o password non validi
		</div>
	<?php endif; ?>
	<input required type="hidden" name="action" value="login" />
  <div class="form-group">
    <label for="email">Indirizzo email</label>
    <input required type="email" class="form-control" id="email" name="email" placeholder="La tua mail" />
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input required type="password" class="form-control" id="password" name="password" placeholder="Password" />
  </div>
  <div class="text-center mt-3">
    <button type="submit" class="btn btn-primary">Entra</button>
    <hr>
    <p class="text-muted">Non sei ancora un utente?</p>
    <a href="/signup.php">Registrati</a>
  </div>
</form>

