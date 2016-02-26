<div class="container">

    {if isset($error)}
    <p>Error: {htmlspecialchars($error)}</p>
    {/if}

    <form action="/Account" method="post">
        Username: <input type="text" name="username" value="{$username}"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit">
    </form>
    <a type="button" href="/Account/action=Register">Register</a>
    <a type="button" href="/Account/action=Recover">Forgot</a>

</div>

