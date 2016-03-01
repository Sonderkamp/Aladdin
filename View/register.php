<div class="container">

    {if isset($error)}
    <p id="error">Error: {htmlspecialchars($error)}</p>
    {else}
    <p id="error"></p>
    {/if}

    <form name="registerForm" action="/Account/action=register" method="post" onsubmit="return validateEmail()">
        <p> Email adres: <input type="text" name="username" data-validation="email"
                                data-validation-error-msg="Geen valide email adres ingevuld." required></p>
        {literal}
        <p>Wachtwoord: <input type="password" name="password1" required
                              required data-validation="custom"
                              data-validation-regexp="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d\W]{8,}$"
                              data-validation-error-msg="het wachtwoord moet minimaal 8 tekens lang, een hoofdletter, een kleine letter en een nummer bevatten.">
        </p>
        {/literal}
        <p>Wachtwoord opnieuw: <input type="password" name="password2" required data-validation="confirmation"
                                      data-validation-confirm="password1" data-validation-error-msg="wachtwoorden komen niet overeen."></p>

        <p>Voornaam: <input type="text" name="name" required data-validation="alphanumeric"
                            data-validation-error-msg="Wachtwoorden komen niet overeen."
                            data-validation-allowing="- " data-validation-error-msg="Geen valide voornaam ingevuld.">
        </p>
        <p>Achternaam: <input type="text" name="surname" required data-validation="alphanumeric"
                              data-validation-allowing="- "
                              data-validation-error-msg="Geen valide achternaam ingevuld."></p>
        <p>Straat en huisnummer: <input type="text" name="address" required data-validation="alphanumeric"
                                        data-validation-allowing="-_ "
                                        data-validation-error-msg="Straat en huisnummer kan alleen letters, nummers, spaties en streepjes(-_) bevatten">
        </p>
        {literal}
        <p>Postcode: <input type="text" name="postalcode" required data-validation="custom"
                            data-validation-regexp="^[0-9]{4}[\s]{0,1}[a-zA-z]{2}"
                            data-validation-error-msg="invalide postcode gegeven"></p>
        {/literal}
        <p>Stad: <input type="text" name="city" required required data-validation="alphanumeric"
                        data-validation-allowing="-_ "
                        data-validation-error-msg="Straat en huisnummer kan alleen letters, spaties en streepjes(-_) bevatten">
        </p>
        <p>Land: <input type="text" name="country" data-validation="country" value="Netherlands" required
                        data-validation-error-msg="invalide land gekozen."></p>

        <p>Geboortedatum: <input type="text" data-validation="birthdate" name="dob" data-validation-format="dd-mm-yyyy"
                                 required
                                 data-validation-error-msg="invalide datum." data-validation-help="dd-mm-yyyy"></p>

        <p>
            <input type="radio" name="gender" value="male" checked> Man
            <input type="radio" name="gender" value="female"> Vrouw
            <input type="radio" name="gender" value="other"> Anders
        </p>
        <p><input type="checkbox" name="handicap" value="Yes"> Handicap</p>

        TODO: 3 wensen, 3 talenten<br>

        <input type="submit">
    </form>
    <a type="button" href="/Account">Log in</a>
    <a type="button" href="/Account/action=Recover">Forgot</a>

</div>

<script>


    function validateEmail() {
        {literal}
        var val = {username: document.forms["registerForm"]["username"].value};
        {/literal}
        var ret = true;
        $.ajax({
            type: 'POST',
            url: "/Account/action=check",
            data: val,
            dataType: "text",
            success: function (resultData) {
                resultData = JSON.parse(resultData);
                console.log(resultData);
                if (resultData.result == true) {
                    ret = false;
                    $("#error").text("Error: emailadres bestaat al.");
                }
                return false;
            },
            async: false
        });

        return ret;
    }

    // http://www.formvalidator.net/
    $.validate({
        modules: 'location, security, date',
        onModulesLoaded: function () {
            $('input[name="country"]').suggestCountry();
        }
    });


</script>
