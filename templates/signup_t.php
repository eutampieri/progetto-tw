<h2>Registrati</h2>
<form method="post" action="auth.php">
	<input required type="hidden" name="action" value="signup" />
  <div class="form-group">
    <label for="name">Nome e cognome</label>
    <input required type="text" class="form-control" id="name" name="name" placeholder="Il tuo nome" />
  </div>
  <div class="form-group">
    <label for="email">Indirizzo email</label>
    <input required type="email" class="form-control" id="email" name="email" placeholder="La tua email" />
	</div>
  <div class="form-group">
    <label for="password">Password</label>
    <input required type="password" class="form-control" id="password" name="password" placeholder="Password" />
  </div>
  <div class="text-center mt-3">
    <button type="submit" class="btn btn-primary">Registrati</button>
    <hr>
    <p class="text-muted">Sei gia registrato?</p>
    <a href="/login.php">Entra</a>
  </div>
</form>

