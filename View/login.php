<div class="container">

    {if isset($error)}
    <p>Error: {htmlspecialchars($error)}</p>
    {/if}

    <form action="/Account" method="post">
        <p>Gebruikersnaam: <input type="text" name="username" value="{$username}"  data-validation="email"
                         data-validation-error-msg="vul een valide gebruikersnaam in."></p>
        <p>Wachtwoord: <input type="password" name="password" data-validation="required"  data-validation-error-msg="vul een wachtwoord in."></p>
        <input type="submit">
    </form>
    <a type="button" href="/Account/action=Register">Register</a>
    <a type="button" href="/Account/action=Recover">Forgot</a>

</div>

<script>
    // http://www.formvalidator.net/
    $.validate();
</script>


