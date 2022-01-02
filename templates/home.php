<?php
require_once(dirname(dirname(__FILE__))."/utils.php");
?>
<div id="showlist" class="family d-flex flex-wrap justify-content-around">
  <?php foreach($products as $product): ?>
  <div class="card m-2" style="width: 15rem;">
    <img src="<?= $product["picture"] ?>" class="card-img-top"
      alt="">
    <div class="card-body">
      <h2><?= $product["title"] ?></h2>
      <a href="." class="btn btn-primary showlink"><i class="fa fa-cart-plus" aria-hidden="true"></i> <?= price_to_string($product["price"])?></a>
    </div>
  </div>
  <?php endforeach; ?>
</div>
