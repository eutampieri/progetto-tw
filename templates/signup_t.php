<h1>Registrati</h1>
<form method="post" action="auth.php">
	<input type="hidden" name="action" value="signup" />
  <div class="form-group">
    <label for="name">Nome utente</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="Inserire nome utente" />
  </div>
  <div class="form-group">
    <label for="email">Indirizzo email</label>
    <input type="email" class="form-control" id="email" name="email" placeholder="Inserire indirizzo email" />
	</div>
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" name="password" placeholder="Password" />
  </div>
  <div class="text-center mt-3">
    <button type="submit" class="btn btn-primary">Registrati</button>
    <hr>
    <p class="text-muted">Sei gia registrato?</p>
    <a href="/login.php">Entra</a>
  </div>
</form>

