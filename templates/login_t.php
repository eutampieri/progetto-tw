<h1>Entra</h1>
<form method="post" action="auth.php">
	<input type="hidden" name="action" value="login" />
  <div class="form-group">
    <label for="email">Indirizzo email</label>
    <input type="email" class="form-control" id="email" name="email" placeholder="Inserire indirizzo email" />
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" name="password" placeholder="Password" />
  </div>
  <button type="submit" class="btn btn-primary">Entra</button>
	<a href="/signup.php">Non sei ancora un utente? Registrati</a>
</form>

