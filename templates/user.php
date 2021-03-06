<h2>Notifiche</h2>
<table class="table">
    <thead>
        <tr>
            <th scope="col">Data</th>
            <th scope="col">Messaggio</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($notifications as $notification) :?>
        <tr>
            <td scope="col"><?= date("d/m/Y H:i", intval($notification["date"])) ?></td>
            <td scope="col"><?= $notification["message"] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<h2>I tuoi dati</h2>
<form action = "/update_user.php" method="POST">
		<?php if(isset($_SESSION) && $_SESSION["admin"]==1): ?>
        <p class="alert alert-info">Sei un amministratore, puoi <a href="/add_admin.php">elevare altri utenti ad amministratore</a></p>
    <?php endif;?>
    <input required type="hidden" name="action" value="user_details">
    <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input required type="text" name="name" class="form-control" id="name" value="<?= $user["name"] ?>">
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Indirizzo email</label>
      <input required type="email" name="email" class="form-control" id="email" value="<?= $user["email"] ?>">
    </div>
  <button type="submit" class="btn btn-primary">Aggiorna</button>
</form>
<h2>Cambia password</h2>
<form action = "/update_user.php" method="POST">
    <input required type="hidden" name="action" value="password">
    <div class="mb-3">
        <label for="oldpassword" class="form-label">Password attuale</label>
        <input required type="password" name="oldpassword" class="form-control" id="oldpassword">
    </div>
    <div class="mb-3">
        <label for="newpassword" class="form-label">Nuova password</label>
        <input required type="password" name="newpassword" class="form-control" id="newpassword">
    </div>
  <button type="submit" class="btn btn-primary">Aggiorna</button>
</form>
<h2>I tuoi ordini</h2>
<?php if(count($orders) == 0): ?>
  <div class="alert alert-info" role="alert">
    Sembra che tu non abbia ancora effettuato alcun ordine.
    Potresti andare sulla <a href="/">homepage</a> per vedere se c'?? qualcosa di interessante&hellip;
    </div>
<?php else: ?>
<table class="table">
    <thead>
        <tr>
            <th scope="col">Data</th>
            <th scope="col">Numero d'ordine</th>
            <th scope="col">Totale ordine</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($orders as $order) :?>
        <tr>
            <td><?= date("d/m/Y H:i", intval($order["date"])) ?></td>
            <td><?= $order["id"] ?></td>
            <td><?= price_to_string($order["total_amount"]) ?></td>
            <td>
                <a class="btn btn-info" role="button" href="/order_status.php?order_id=<?= $order["id"] ?>">
                    <i class="fa fa-truck" aria-hidden="true"></i>
                    Traccia l'ordine
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<?php
