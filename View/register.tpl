<div class="container">
    <br>

    {if !isset($type)}
        <div class="panel panel-default">

            <div class="panel-body">
                <div class="text-center">
                    <h3>Registreren</h3>
                    <p>Klik op een categorie om verder te gaan.</p>
                </div>

                <br>
                <div class="media-body text-center">
                    <div class="col-lg-12">
                        <p>
                            <a href="/account/action=register/type=elder" class="btn btn-primary register">
                                <img class="smaller" src="/Resources/Images/Icons/elder.svg" type="image/svg+xml">
                                </img>
                                <br/>

                                Ouderen
                                <br>
                            </a>
                            <a href="/account/action=register/type=adult" class="btn btn-warning register">
                                <i class="fa fa-male fa-5x"></i> <i class="fa fa-female fa-5x"></i><br/><br/>
                                Volwassenen <br>
                            </a>
                            <a href="/account/action=register/type=child" class="btn btn-info register">
                                <i class="fa fa-child fa-5x"></i><br/><br/>
                                Kinderen <br>
                            </a>
                            <a href="/account/action=register/type=disabled" class="btn btn-default register">
                                <i class="fa fa-wheelchair fa-5x"></i><br/><br/>
                                Beperkten <br>
                            </a>
                            <a href="/account/action=register/type=business" class="btn btn-success register">
                                <i class="fa fa-briefcase fa-5x"></i><br/><br/>
                                Bedrijven <br>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    {/if}
    {if isset($error)}
        <div id="error">Error: {htmlspecialchars($error)}</div>
    {else}
        <div id="error"></div>
    {/if}

    {if isset($type)}
        <form name="registerForm" action="/Account/action=register" method="post" onsubmit="return validateEmail()">

            <p>Email: <input type="text" name="username" data-validation="email"
                             data-validation-error-msg="Geen valide email adres ingevuld." required maxlength="254"></p>

            {literal}
                <p>Wachtwoord: <input type="password" name="password1" required
                                      required data-validation="custom"
                                      data-validation-regexp="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d\W]{8,}$"
                                      data-validation-error-msg="het wachtwoord moet minimaal 8 tekens lang, een hoofdletter, een kleine letter en een nummer bevatten.">
                </p>
            {/literal}
            <p>Wachtwoord opnieuw: <input type="password" name="password2" required data-validation="confirmation"
                                          data-validation-confirm="password1"
                                          data-validation-error-msg="wachtwoorden komen niet overeen."></p>

            <p>Voornaam: <input type="text" name="name" data-validation="custom"
                                data-validation-regexp="^([a-zA-Z][A-Za-z\- ]+)$"
                                data-validation-error-msg="Geen valide voornaam ingevuld." maxlength="45">
            </p>
            <p>Initialen: <input type="text" name="initial" required data-validation="custom"
                                 data-validation-regexp="^([a-zA-Z\.]+)$"
                                 data-validation-error-msg="Initialen mogen alleen letters en punten bevatten."
                                 maxlength="25">
            </p>
            <p>Achternaam: <input type="text" name="surname" data-validation="custom"
                                  data-validation-regexp="^([a-zA-Z][A-Za-z\- ]+)$"
                                  data-validation-error-msg="Geen valide achternaam ingevuld." maxlength="45"></p>
            <p>Straat en huisnummer: <input type="text" name="address" data-validation="custom"
                                            data-validation-regexp="^([a-zA-Z][A-Za-z0-9\- ]+)$"
                                            data-validation-error-msg="Straat en huisnummer kan alleen letters, nummers, spaties en streepjes(-) bevatten"
                                            maxlength="255">
            </p>
            {literal}
                <p>Postcode: <input type="text" name="postalcode" required data-validation="custom"
                                    data-validation-regexp="^[0-9]{4}[\s]{0,1}[a-zA-z]{2}"
                                    data-validation-error-msg="invalide postcode gegeven" maxlength="6"></p>
            {/literal}
            <p>Stad: <input type="text" name="city" required data-validation="custom"
                            data-validation-regexp="^[a-zA-Z][a-zA-Z ]+$"
                            data-validation-error-msg="Stad kan alleen letters en spaties bevatten" maxlength="255">
            </p>
            <p>Land: <input type="text" name="country" data-validation="country" value="Netherlands" required
                            data-validation-error-msg="invalide land gekozen." maxlength="60"></p>

            <p>Geboortedatum: <input type="text" data-validation="birthdate" name="dob"
                                     data-validation-format="dd-mm-yyyy"
                                     required
                                     data-validation-error-msg="invalide datum." data-validation-help="dd-mm-yyyy"></p>

            <p>
                <input type="radio" name="gender" value="male" checked> Man
                <input type="radio" name="gender" value="female"> Vrouw
                <input type="radio" name="gender" value="other"> Anders
            </p>
            <p><input type="checkbox" name="handicap" value="Yes"> Handicap</p>

            TODO: 3 wensen, 3 talenten<br><br>

            <input class="btn btn-default" value="Registreren" type="submit">
        </form>
        <br>
        <br>
        <a type="button" class="btn btn-default" href="/Account">Log in</a>
        <a type="button" class="btn btn-default" href="/Account/action=Recover">Vergeten</a>
    {/if}
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
                    $("#error").addClass("form-error");
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
