<h1>Login</h1>

<?php if (isset($error)): ?>

    <p><?= $error ?></p>

<?php endif; ?>

<form method="post" action="/login">
    <label for="email">email</label>
    <input type="email" name="email" id="email"
           value="<?= htmlspecialchars($data['email'] ?? '') ?>">

    <label for="password">Password</label>
    <input type="password" name="password" id="password">

    <button>Log in</button>
</form>