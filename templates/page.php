<header class="navbar navbar-expand-lg navbar-light bg-light mr-auto d-flex p-3">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
		<i class="fa fa-bars" aria-hidden="true"></i>
	</button>
	<div class="collapse navbar-collapse" id="navbarTogglerDemo02">
		<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
			<li class="nav-item">
				<a class="nav-link" href="/">Home</a>
			</li>
			<?php if(isset($_SESSION["user_id"])): ?>
			<li class="nav-item">
				<a class="nav-link" href="/me.php">User</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/logout.php"><i class="fa fa-user-times" aria-hidden="true"></i> Logout</a>
			</li>
			<?php else: ?>
			<li class="nav-item">
				<a class="nav-link" href="/signup.php"><i class="fa fa-user-plus" aria-hidden="true"></i> Sign Up</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/login.php"><i class="fa fa-user" aria-hidden="true"></i> Login</a>
			</li>
		</ul>
			<?php endif; ?>
	</div>
	<?php if(isset($cart_count)): ?>
	<a class="btn btn-primary ms-auto me-1" href="/cart.php" role="button">
		<i class="fa fa-shopping-cart" aria-label="Carrello: "></i>
		<span id="cart-count" class="badge rounded-pill bg-light text-dark"><?= $cart_count ?></span>
		<span class="visually-hidden"> elementi</span>
	</a>
	<?php endif; ?>
</header>
<div class="container">
	<?php
		 require_once($page_content_template);
		 ?>
</div>
