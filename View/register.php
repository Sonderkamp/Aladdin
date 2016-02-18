<div class="container">

    {if isset($error)}
    <p>Error: {htmlspecialchars($error)}</p>
    {/if}

    <form action="/Account/action=register" method="post">
        Email adress: <input type="text" name="username"><br>
        Wachtwoord: <input type="password" name="password1"><br>
        Wachtwoord opnieuw: <input type="password" name="password2"><br>
        Voornaam: <input type="text" name="name"><br>
        Achternaam: <input type="text" name="surname"><br>
        Straat en huisnummer <input type="text" name="address"><br>
        Postcode <input type="text" name="postalcode"><br>
        Stad <input type="text" name="city"><br>
        Provincie <input type="text" name="province"><br>
        Land <input type="text" name="country"><br>
        <input type="submit">
    </form>
    <a type="button" href="/Account">Log in</a>
    <a type="button" href="/Account/action=Recover">Forgot</a>

</div>

