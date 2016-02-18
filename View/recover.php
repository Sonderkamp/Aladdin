<div class="container">

    {if isset($error)}
    <p>Error: {htmlspecialchars($error)}</p>
    {/if}

    <form action="/Account/action=recover" method="post">
        Username: <input type="text" name="username" value="{$username}"><br>
        <input type="submit">
    </form>
    <a type="button" href="/Account/action=Register">Register</a>
    <a type="button" href="/Account">log in</a>

</div>

