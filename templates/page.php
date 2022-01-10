<header class="navbar navbar-expand-lg navbar-light bg-light mr-auto d-flex p-3">
	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
		<i class="fa fa-bars"></i>
	</button>
	<div class="collapse navbar-collapse" id="navbarToggler">
		<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
			<li class="nav-item">
				<a class="nav-link" href="/"><i class="me-1 fa fa-home" aria-hidden="true"></i>Pagina iniziale</a>
			</li>
			<?php if(isset($_SESSION["user_id"])): ?>
				<?php
				$database = get_db();
				$stmt = $database->prepare("SELECT COUNT(*) AS n FROM `notification` WHERE `user_id` = :id AND `status` = 0");
				$stmt->bindParam("id", $_SESSION["user_id"]);
				$stmt->execute();
				$notif_count = intval($stmt->fetch(PDO::FETCH_ASSOC)["n"]);
				?>
			<li class="nav-item">
				<a class="nav-link" href="/me.php"><i class="me-1 fa fa-user" aria-hidden="true"></i>La mia pagina personale
					<?php if($notif_count > 0) :?>
					<span id="notif-count" class="ms-2 badge rounded-pill bg-danger text-light"><?= $notif_count ?></span>
					<?php endif;?>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/logout.php"><i class="me-1 fa fa-sign-out" aria-hidden="true"></i>Esci</a>
			</li>
			<?php else: ?>
			<li class="nav-item">
				<a class="nav-link" href="/signup.php"><i class="me-1 fa fa-user-plus" aria-hidden="true"></i>Registrati</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/login.php"><i class="me-1 fa fa-sign-in" aria-hidden="true"></i>Entra</a>
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
