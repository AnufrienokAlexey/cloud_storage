<h4>Получить пользователя по id</h4>
<form class="row g-3" action="get" method="get">
    <div class="col-auto">
        <label for="staticEmail2" class="visually-hidden">Email</label>
        <input type="text" readonly class="form-control-plaintext" id="staticEmail2" value="Введите id пользователя">
    </div>
    <div class="col-auto">
        <input type="text" class="form-control" id="inputPassword2" placeholder="id" name="id" required>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary mb-3">Найти пользователя</button>
    </div>
</form>
<ul>
    <?php
    foreach ($vars as $item) { ?>
        <li><?= 'id = ' . $item['id'] . ', email = ' . $item['email']; ?></li>
        <?php
    } ?>
</ul>
</body>
</html>