<header class="navbar navbar-expand-lg navbar-light bg-light mr-auto d-flex p-3">
    <span class="navbar-brand"><?= $page_title ?></span>
    <?php if(!isset($cart_count)): ?>
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
