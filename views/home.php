<h1>Welcome</h1>

<?php if (empty($_SESSION['user_id'])): ?>

    <a href="/signup">Sign up for an API key</a>

    or 

    <a href="/login">log in</a>

<?php else: ?>

    <a href="/profile">View profile</a>

    or

    <a href="/logout">log out</a>

<?php endif; ?>