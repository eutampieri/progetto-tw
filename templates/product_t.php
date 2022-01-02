<?php
require_once(dirname(dirname(__FILE__))."/utils.php");
?>
<main>
    <section>
        <img src="/image.php?id=<?= $product["id"] ?>">
        <div class="d-flex justify-content-between">
            <h2>
                <?= $product["name"] ?>
            </h2>
            <span class="price">
                <?= price_to_string($product["price"])?>
            </span>
        </div>
        <p>
            <?= $product["description"] ?>
        </p>
        <a href="/cart.php" class="btn btn-primary showlink"><i class="fa fa-cart-plus" aria-hidden="true"></i>
            Aggiungi al carrello
        </a>
    </section>
</main>