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

                <form id="form" name="registerForm" action="/Account/action=register/type={$type}" method="post"
                      onsubmit="return validateEmail()">
                    <input type="hidden" name="type" value="{$type}">

                    <table class="table table-user-information">
                        <tbody>
                        {if $type == "business"}
                            <tr>
                                <td>Bedrijfsnaam:</td>
                                <td>

                                    <input type="text" name="companyName" data-validation="custom"
                                           data-validation-regexp="^([0-9a-zA-Z][A-Za-z0-9\- ]+)$"
                                           data-validation-error-msg="Geen valide naam ingevuld."
                                           maxlength="45">

                                </td>
                            </tr>
                        {/if}

                        <tr>
                            <td>Email:</td>
                            <td>
                                <input type="text" name="username" data-validation="email"
                                       data-validation-error-msg="Geen valide email adres ingevuld." required
                                       maxlength="254">
                                <input type="hidden" name="Lat">
                                <input type="hidden" name="Lon">
                            </td>
                        </tr>
                        <tr>

                            <td>Wachtwoord:</td>
                            <td>
                                {literal}
                                    <input type="password" name="password1" required
                                           required data-validation="custom"
                                           data-validation-regexp="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d\W]{8,}$"
                                           data-validation-error-msg="het wachtwoord moet minimaal 8 tekens lang, een hoofdletter, een kleine letter en een nummer bevatten.">
                                {/literal}
                            </td>
                        </tr>
                        <tr>
                            <td>Herhaal wachtwoord:</td>
                            <td>
                                {literal}
                                    <input type="password" name="password2" required data-validation="confirmation"
                                           data-validation-confirm="password1"
                                           data-validation-error-msg="wachtwoorden komen niet overeen.">
                                {/literal}
                            </td>
                        </tr>
                        {if $type != "business"}
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
                                <td><input type="text" name="initial"
                                           required data-validation="custom"
                                           data-validation-regexp="^([a-zA-Z\.]+)$"
                                           data-validation-error-msg="Initialen mogen alleen letters en punten bevatten.">

                                </td>
                            </tr>
                        {else}
                            <tr>
                                <td>Contactpersoon Voornaam:</td>
                                <td>
                                    <input type="text" name="name" data-validation="custom"
                                           data-validation-regexp="^([a-zA-Z][A-Za-z\- ]+)$"
                                           data-validation-error-msg="Geen valide voornaam ingevuld."
                                           maxlength="45">
                                </td>
                            </tr>
                            <tr>
                                <td>Contactpersoon Achternaam:</td>
                                <td><input type="text" name="surname"
                                           data-validation="custom"
                                           data-validation-regexp="^([a-zA-Z][A-Za-z\- ]+)$"
                                           data-validation-error-msg="Geen valide achternaam ingevuld.">
                                </td>
                            </tr>
                            <tr>
                                <td>Contactpersoon initialen:</td>
                                <td><input type="text" name="initial"
                                           required data-validation="custom"
                                           data-validation-regexp="^([a-zA-Z\.]+)$"
                                           data-validation-error-msg="Initialen mogen alleen letters en punten bevatten.">

                                </td>
                            </tr>
                        {/if}
                        <tr>
                            <td>Straat en huisnummer:</td>
                            <td>
                                <input type="text" name="address" onblur="validateAddress()" data-validation="custom"
                                       data-validation-regexp="^([a-zA-Z][A-Za-z0-9\- ]+)$"
                                       data-validation-error-msg="Straat en huisnummer kan alleen letters, nummers, spaties en streepjes(-) bevatten"
                                       maxlength="255">
                            </td>
                        </tr>
                        <tr>
                            <td>Stad:</td>
                            <td><input type="text" name="city" required onblur="validateAddress()"
                                       maxlength="255">
                            </td>

                        </tr>
                        <tr>
                            <td>Postcode:</td>
                            <td>

                                <input type="text" name="postalcode" required
                                       maxlength="6" readonly>

                            </td>
                        </tr>

                        <tr>
                            <td>Land:</td>
                            <td><input type="text" name="country" required
                                       data-validation-error-msg="invalide land gekozen." readonly>
                            </td>
                        </tr>
                        {if $type != "business"}
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
                            {if $type == "child"}
                                <tr>
                                    <td>Email-adress Ouder/Verzorger:</td>
                                    <td>

                                        <input type="text" name="guardian" data-validation="email"
                                               data-validation-error-msg="Geen valide gebruikers-email ingevuld."
                                               required
                                               maxlength="254">

                                    </td>
                                </tr>
                            {/if}
                            {if $type != "disabled"}
                                <tr>
                                    <td>Handicap:</td>
                                    <td>
                                        <input onchange="toggle()" type='checkbox' name='handicap'>
                                    </td>

                                </tr>
                            {/if}
                            <tr id="tog">
                                <td>Wat is uw beperking:</td>
                                <td>
                                    <input type='text' name='handicap_info'>
                                </td>

                            </tr>
                        {/if}

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

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
<script>

    var done = false;

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
                if (resultData.result == true) {
                    ret = false;
                    $("#error").text("Error: emailadres bestaat al.");
                    $("#error").addClass("form-error");
                }
                return false;
            },
            async: false
        });

        {if isset($type) && $type == "child"}
        {literal}
        var val = {username: document.forms["registerForm"]["guardian"].value};
        $.ajax({
            type: 'POST',
            url: "/Account/action=check",
            data: val,
            dataType: "text",
            success: function (resultData) {
                resultData = JSON.parse(resultData);
                if (resultData.result != true) {
                    ret = false;
                    $("#error").text("Error: account van voogd bestaat niet.");
                    $("#error").addClass("form-error");
                }
                return false;
            },
            async: false
        });
        {/literal}
        {/if}

        if ($('input[name=city]').val() === "" ||
                $('input[name=country]').val() === "" ||
                $('input[name=postalcode]').val() === "") {
            $("#error").text("Niet alle gegevens zijn ingevuld.");
            $("#error").addClass("form-error");
            return false;
        }

        {if $type != "business" && $type != "child"}

        var dob = $('input[name=dob]').val();
        var arr = dob.split("-");
        var age = getAge(arr[2] + "-" + arr[1] + "-" + arr[0]);

        if (age < 18) {
            $("#error").text("Je moet ouder zijn dan 18. Ben je jonger dan 18? registreer dan als kind.");
            $("#error").addClass("form-error");
            return false;
        }
        {/if}

        return ret;
    }

    // http://www.formvalidator.net/
    $.validate({
        modules: 'location, security, date'
    });


    function getAge(birth) {

        var d = new Date();
        var n = d.getTime();
        var c = Date.parse(birth);
        var a = (d - c) / (1000 * 60 * 60 * 24 * 365);
        var result = Math.round(a * 100) / 100;
        return Math.floor(result);
        //alert(result);
    }


</script>


<script>
    {literal}

    function validateAddress() {


        $("#error").text("");
        $("#error").removeClass("form-error");

        $('input[name=country]').val('');
        $('input[name=postalcode]').val('');
        var location = $('input[name=address]').val();
        var location2 = $('input[name=city]').val();

        if (location != "" && location2 != "")
            return getAddress(location + ", " + location2, false);

        return false;
    }

    function getAddress(location, submit) {
        done = false;

        geocoder = new google.maps.Geocoder();
        geocoder.geocode({"address": location}, function (results, status) {
            if (status == "OK") {

                if (results[0].types[0] !== "street_address") {
                    $("#error").text("Error: Geen valide adres ingevuld.");
                    $("#error").addClass("form-error");
                }
                else {


                    var postalcode = "";
                    var city = "";
                    var country = "";

                    results[0].address_components.forEach(function (d) {
                        if (d.types[0] == "postal_code") {
                            postalcode = d.long_name.replace(/\s+/g, '');
                        }
                        else if (d.types[0] == "country") {
                            country = d.long_name;
                        }
                        else if (d.types[0] == "locality") {
                            city = d.long_name;
                        }
                    });

                    if ($('input[name=city]').val() !== city ||
                            $('input[name=country]').val() !== country ||
                            $('input[name=postalcode]').val() !== postalcode) {
                        $('input[name=city]').val(city);
                        $('input[name=country]').val(country);
                        $('input[name=postalcode]').val(postalcode);

                        $('input[name=Lat]').val(results[0].geometry.location.lat());
                        $('input[name=Lon]').val(results[0].geometry.location.lng());

                    }

                    if (submit == true) {
                        done = true;
                        $('#form').submit();
                    }
                }
            } else {

                $("#error").text("Adres niet gevonden. Error Code: " + status);
                $("#error").addClass("form-error");
            }
        });


    }

    {/literal}
</script>

{if isset($type) && $type != "disabled"}
    <script>
        function toggle() {
            $("#tog").slideToggle(1);
            $('input[name=handicap_info]').val('');
        }

        toggle();
    </script>
{/if}