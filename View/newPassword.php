<div class="container">

    {if isset($error)}
    <p>Error: {htmlspecialchars($error)}</p>
    {/if}
    <h3>
        Nieuw wachtwoord voor: {$username}
    </h3>
    <form action="/account/action=recover/" method="post">
        Wachtwoord: <input type="password" name="password1"><br>
        Wachtwoord opnieuw: <input type="password" name="password2"><br>
        <input type="hidden" name="token" value="{$token}">
        <input type="hidden" name="username" value="{$username}">
        <input type="submit">
    </form>

</div>

