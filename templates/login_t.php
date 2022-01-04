<form method="post" action="auth.php">
	<input type=hidden name=action value=login />
  <div class="form-group">
    <label for="name">User name</label>
    <input type="text" class="form-control" id="name" placeholder="Enter username" />
  </div>
  <div class="form-group">
    <label for="email">Email address</label>
    <input type="email" class="form-control" id="email" placeholder="Enter email" />
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" placeholder="Password" />
  </div>
  <button type="submit" class="btn btn-primary">Login</button>
</form>

