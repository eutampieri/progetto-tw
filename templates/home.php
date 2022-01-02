<?php
require_once(dirname(dirname(__FILE__))."/utils.php");
?>
<div class="family d-flex flex-wrap justify-content-around">
  <?php foreach($products as $product): ?>
  <div class="card m-2 product" >
    <a href="/product.php?id=<?= $product["id"] ?>">
    <img src="<?= $product["image"] ?>" class="card-img-top"
      alt=""></a>
    <div class="card-body">
      <a href="/product.php?id=<?= $product["id"] ?>">
      <h2><?= $product["title"] ?></h2>
      </a>
      <a href="." class="btn btn-primary showlink"><i class="fa fa-cart-plus" aria-hidden="true"></i> <?= price_to_string($product["price"])?></a>
    </div>
  </div>
  <?php endforeach; ?>
</div>
