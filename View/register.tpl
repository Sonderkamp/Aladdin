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
                <div class="text-center">

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
    {/if}
    {if isset($error)}
        <div id="error">Error: {htmlspecialchars($error)}</div>
    {else}
        <div id="error"></div>
    {/if}

    {if isset($type)}
    <div class="col-md-6 col-md-offset-3">
        <div class="panel">
            <div class="panel-heading text-center">
                <h3 class="panel-title">Registreren</h3>
            </div>
            <div class="panel-body">

                <form name="registerForm" action="/Account/action=register" method="post"
                      onsubmit="return validateEmail()">


                    <table class="table table-user-information">
                        <tbody>
                        <tr>
                            <td>Email:</td>
                            <td>
                                <input type="text" name="username" data-validation="email"
                                       data-validation-error-msg="Geen valide email adres ingevuld." required
                                       maxlength="254">
                            </td>
                        </tr>

                        <tr>
                            <td>Voornaam:</td>
                            <td>
                                <input type="text" name="name" data-validation="custom"
                                       data-validation-regexp="^([a-zA-Z][A-Za-z\- ]+)$"
                                       data-validation-error-msg="Geen valide voornaam ingevuld."
                                       maxlength="45">
                            </td>
                        </tr>
                        <tr>
                            <td>Achternaam:</td>
                            <td><input type="text" name="surname"
                                       data-validation="custom"
                                       data-validation-regexp="^([a-zA-Z][A-Za-z\- ]+)$"
                                       data-validation-error-msg="Geen valide achternaam ingevuld.">
                            </td>
                        </tr>
                        <tr>
                            <td>initialen:</td>
                            <td><input type="text" name="initials"
                                       required data-validation="custom"
                                       data-validation-regexp="^([a-zA-Z\.]+)$"
                                       data-validation-error-msg="Initialen mogen alleen letters en punten bevatten.">

                            </td>
                        </tr>

                        <tr>
                            <td>Adress:</td>
                            <td>
                                <input type="text" name="address" data-validation="custom"
                                       data-validation-regexp="^([a-zA-Z][A-Za-z0-9\- ]+)$"
                                       data-validation-error-msg="Straat en huisnummer kan alleen letters, nummers, spaties en streepjes(-) bevatten"
                                       maxlength="255">
                            </td>
                        </tr>
                        <tr>
                            <td>Postcode:</td>
                            <td>

                                <input type="text" name="postalcode" required
                                       data-validation="custom"
                                        {literal}
                                       data-validation-regexp="^[0-9]{4}[\s]{0,1}[a-zA-z]{2}"
                                       data-validation-error-msg="invalide postcode gegeven"
                                       maxlength="6">
                                {/literal}
                            </td>
                        </tr>
                        <tr>
                            <td>Stad:</td>
                            <td><input type="text" name="city" required data-validation="custom"
                                       data-validation-regexp="^[a-zA-Z][a-zA-Z ]+$"
                                       data-validation-error-msg="Stad kan alleen letters en spaties bevatten"
                                       maxlength="255"></td>

                        </tr>
                        <tr>
                            <td>Land:</td>
                            <td><input type="text" name="country"
                                       required data-validation="country"
                                       data-validation-error-msg="invalide land gekozen." value="netherlands">
                            </td>
                        </tr>
                        <tr>
                            <td>Geboortedatum:</td>
                            <td><input type="text" data-validation="birthdate"
                                       name="dob" data-validation-format="dd-mm-yyyy"
                                       required
                                       data-validation-error-msg="invalide datum."
                                       data-validation-help="dd-mm-yyyy">
                            </td>
                        </tr>
                        <tr>
                            <td>Geslacht:</td>
                            <td>

                                <input type='radio' name='gender' value='male'>
                                Man
                                <input type='radio' name='gender' value='female'>
                                Vrouw
                                <input type='radio' name='gender' value='other'>
                                Anders

                            </td>

                        </tr>
                        <tr>
                            <td>Handicap:</td>
                            <td>
                                <input type='checkbox' name='handicap'>
                            </td>

                        </tr>
                        <tr>
                            <td colspan="2" class="right">
                                <input type="submit" value="Registreren"
                                       class="btn btn-default">
                            </td>


                        </tr>
                        </tbody>
                    </table>
                </form>

            </div>
        </div>
        <a type="button" class="btn btn-default" href="/Account">Log in</a>
        <a type="button" class="btn btn-default" href="/Account/action=Recover">Vergeten</a>
        {/if}
    </div>

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
