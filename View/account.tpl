<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: Simon-->
<!-- * Date: 7-Mar-16-->
<!-- * Time: 12:42-->
<!-- */-->
<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <div class="profile-usertitle">
                <div class="text-center">
                    <b>{$user->displayName}</b>
                </div>
                <div class="text-center">
                    {$user->email}
                </div>
            </div>
            <br>
        </div>
        <div class="col-sm-9">
            {if isset($errorc)}
                <div id="err">
                    <div class="form-error">Error: {htmlspecialchars($errorc)}</div>
                </div>
            {else}
                <div id="err">
                </div>
            {/if}
            {if isset($error)}
                <div id="err2">
                    <div class="form-error">Error: {htmlspecialchars($error)}</div>
                </div>
            {else}
                <div id="err2">
                </div>
            {/if}

            {if isset($success)}
                <div id="suc">
                    <div class="form-error form-success">{htmlspecialchars($success)}</div>
                </div>
            {else}
                <div id="suc">
                </div>
            {/if}

        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <ul class="nav nav-pills nav-stacked">
                <li class="active">
                    <a href="#tab1" data-toggle="tab"> <i class="glyphicon glyphicon-user"></i> Overzicht</a>
                </li>
                <li><a href="#tab4" data-toggle="tab"> <i class="glyphicon glyphicon-euro"></i> Donaties</a></li>
                <li><a href="#tab2" data-toggle="tab"><i class="glyphicon glyphicon-edit"></i> Bewerken</a></li>
                <li><a href="#tab3" data-toggle="tab"><i class="glyphicon glyphicon-option-horizontal"></i>
                        Wachtwoord</a>
                </li>
            </ul>

        </div>
        <div class="col-sm-9">
            <div class="profile-content">
                <div class="panel">
                    <div class="tab-content">
                        <div class="tab-pane fade" id="tab3">
                            <div class="panel-heading text-center">
                                <h3 class="panel-title">Wachtwoord</h3>
                            </div>
                            <div class="panel-body text-center">
                                <form action="/profile/action=changepw" method="post"><p>
                                        <br>
                                        <input type="hidden" readonly="true" name="username" value="{$user->email}">
                                        {literal}
                                    <p>
                                        Oud wachtwoord: <input type="password" name="pwo" required
                                                               required data-validation="custom"
                                                               data-validation-regexp="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d\W]{8,}$"
                                                               data-validation-error-msg="het wachtwoord moet minimaal 8 tekens lang, een hoofdletter, een kleine letter en een nummer bevatten.">
                                    <p>
                                        Nieuw wachtwoord:<input type="password" name="password1" required
                                                                required data-validation="custom"
                                                                data-validation-regexp="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d\W]{8,}$"
                                                                data-validation-error-msg="het wachtwoord moet minimaal 8 tekens lang, een hoofdletter, een kleine letter en een nummer bevatten.">
                                    <p>
                                        Nieuw wachtwoord: <input type="password" name="password2" required
                                                                 required data-validation="custom"
                                                                 data-validation-regexp="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d\W]{8,}$"
                                                                 data-validation-error-msg="het wachtwoord moet minimaal 8 tekens lang, een hoofdletter, een kleine letter en een nummer bevatten.">

                                    <p>
                                        {/literal}

                                    <p>
                                        <br>
                                        <input type="submit" value="wijzig" class="btn btn-default"
                                               style="float: right;">
                                        <br>


                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab4">
                            <div class="panel-heading text-center">
                                <h3 class="panel-title">Donaties</h3>
                            </div>
                            <div class="panel-body">
                                {if count($donations) > 0}
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Datum</th>
                                            <th>Hoeveelheid</th>
                                            <th>Beschrijving</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {foreach from=$donations item=donation}
                                            <tr>
                                                <td>{$donation->date}</td>
                                                <td>&euro;{number_format($donation->amount, 2, ',', ' ')}</td>
                                                <td>{$donation->description}</td>
                                            </tr>
                                        {/foreach}
                                        </tbody>
                                    </table>
                                {else}
                                    <h6 class="text-center">Je hebt niet gedoneerd aan Stichting Aladdin. <br> Je kan
                                        dit <a href="/donate">Hier</a> doen
                                    </h6>
                                {/if}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab2">
                            <div class="panel-heading text-center">
                                <h3 class="panel-title">Bewerken</h3>
                            </div>
                            <div class="panel-body text-center">
                                <div class="row">
                                    <div class="col-xs-10 col-xs-offset-1">
                                        <form id="form" action="/profile/action=change" method="post"
                                              onsubmit="return validateEmail()"><p>
                                                <input name="email" type="hidden" readonly="true"
                                                       value="{$user->email}">
                                            <table class="table table-user-information">
                                                <tbody>
                                                <tr>
                                                    <td>Voornaam:</td>
                                                    <td>
                                                        <input type="text" name="name" data-validation="custom"
                                                               data-validation-regexp="^([a-zA-Z][A-Za-z\- ]+)$"
                                                               data-validation-error-msg="Geen valide voornaam ingevuld."
                                                               maxlength="45" value="{$user->name}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Achternaam:</td>
                                                    <td><input type="text" name="surname" value="{$user->surname}"
                                                               data-validation="custom"
                                                               data-validation-regexp="^([a-zA-Z][A-Za-z\- ]+)$"
                                                               data-validation-error-msg="Geen valide achternaam ingevuld.">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>initialen:</td>
                                                    <td><input type="text" name="initials" value="{$user->initials}"
                                                               required data-validation="custom"
                                                               data-validation-regexp="^([a-zA-Z\.]+)$"
                                                               data-validation-error-msg="Initialen mogen alleen letters en punten bevatten.">

                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Straat en huisnummer:</td>
                                                    <td>
                                                        <input type="text" name="address" onblur="validateAddress()"
                                                               data-validation="custom"
                                                               data-validation-regexp="^([a-zA-Z][A-Za-z0-9\- ]+)$"
                                                               data-validation-error-msg="Straat en huisnummer kan alleen letters, nummers, spaties en streepjes(-) bevatten"
                                                               maxlength="255" value="{$user->address}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Stad:</td>
                                                    <td><input type="text" name="city" required
                                                               onblur="validateAddress()"
                                                               maxlength="255" value="{$user->city}">
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td>Postcode:</td>
                                                    <td>

                                                        <input type="text" name="postalcode" required
                                                               maxlength="6" readonly value="{$user->postalcode}">

                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Land:</td>
                                                    <td><input type="text" name="country" required
                                                               readonly value="{$user->country}">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Geboortedatum:</td>
                                                    <td><input type="text" data-validation="birthdate"
                                                               value="{$user->dob|date_format:"%d-%m-%Y"}"
                                                               name="dob" data-validation-format="dd-mm-yyyy"
                                                               required
                                                               data-validation-error-msg="invalide datum."
                                                               data-validation-help="dd-mm-yyyy">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Geslacht:</td>
                                                    <td>
                                                        {if $user->gender eq 'male'}
                                                            <input type='radio' name='gender' value='male' checked>
                                                            Man
                                                            <input type='radio' name='gender' value='female'>
                                                            Vrouw
                                                            <input type='radio' name='gender' value='other'>
                                                            Anders
                                                        {elseif $user->gender eq 'female'}
                                                            <input type='radio' name='gender' value='male'>
                                                            Man
                                                            <input type='radio' name='gender' value='female' checked>
                                                            Vrouw
                                                            <input type='radio' name='gender' value='other'>
                                                            Anders
                                                        {elseif $user->gender eq 'other'}
                                                            <input type='radio' name='gender' value='male'>
                                                            Man
                                                            <input type='radio' name='gender' value='female'>
                                                            Vrouw
                                                            <input type='radio' name='gender' value='other' checked>
                                                            Anders
                                                        {else}
                                                            <input type='radio' name='gender' value='male'>
                                                            Man
                                                            <input type='radio' name='gender' value='female'>
                                                            Vrouw
                                                            <input type='radio' name='gender' value='other'>
                                                            Anders
                                                        {/if}
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td>Handicap:</td>
                                                    <td>{if $user->handicap}
                                                            <input type='checkbox' name='handicap' checked>
                                                        {elseif !$user->handicap}
                                                            <input type='checkbox' name='handicap'>
                                                        {/if}
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="right">
                                                        <input type="submit" value="wijzig"
                                                               class="btn btn-default">
                                                    </td>


                                                </tr>
                                                </tbody>
                                            </table>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade in active" id="tab1">
                            <div class="panel-heading text-center">
                                <h3 class="panel-title">Profiel</h3>
                            </div>
                            <div class="panel-body text-center">
                                <div class="row">
                                    <div class="col-xs-10 col-xs-offset-1">
                                        <table class="table table-user-information">
                                            <tbody>
                                            <tr>
                                                <td>Naam:</td>
                                                <td>{$user->name} {$user->surname}</td>
                                            </tr>
                                            <tr>
                                                <td>E-Mail</td>
                                                <td>{$user->email}</td>
                                            </tr>
                                            <tr>
                                                <td>Initialen</td>
                                                <td>{$user->initials}</td>
                                            </tr>
                                            <tr>
                                                <td>Adress</td>
                                                <td>{$user->address}</td>
                                            </tr>
                                            <tr>
                                                <td>Postcode</td>
                                                <td>{$user->postalcode}</td>
                                            </tr>
                                            <tr>
                                                <td>Plaats</td>
                                                <td>{$user->city}</td>
                                            </tr>
                                            <tr>
                                                <td>Land</td>
                                                <td>{$user->country}</td>
                                            </tr>
                                            <tr>
                                                <td>Geboortedatum</td>
                                                <td>{$user->dob|date_format:"%d-%m-%Y"}</td>
                                            </tr>
                                            <tr>
                                                <td>Geslacht</td>
                                                {if $user->gender eq 'male'}
                                                    <td>Man</td>
                                                {elseif $user->gender eq 'female'}
                                                    <td>Vrouw</td>
                                                {elseif $user->gender eq 'other'}
                                                    <td>-</td>
                                                {/if}
                                            </tr>
                                            <tr>
                                                <td>Handicap</td>
                                                {if $user->handicap}
                                                    <td>Ja</td>
                                                {else}
                                                    <td>Nee</td>
                                                {/if}
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
<script>

    function validateEmail() {
        if ($('input[name=city]').val() === "" ||
                $('input[name=country]').val() === "" ||
                $('input[name=postalcode]').val() === "") {
            $("#error").text("Niet alle gegevens zijn ingevuld.");
            $("#error").addClass("form-error");
            return false;
        }
        return true;
    }

    // http://www.formvalidator.net/
    $.validate({
        modules: 'location, security, date',
        form: '#form'
    });


</script>


<script>
    {literal}

    function validateAddress() {

        $("#err").text("");
        $("#err").removeClass("form-error");

        $('input[name=country]').val('');
        $('input[name=postalcode]').val('');
        var location = $('input[name=address]').val();
        var location2 = $('input[name=city]').val();

        if (location != "" && location2 != "")
            return getAddress(location + ", " + location2, false);

        return false;
    }

    function getAddress(location, submit) {


        geocoder = new google.maps.Geocoder();
        geocoder.geocode({"address": location}, function (results, status) {
            if (status == "OK") {

                if (results[0].types[0] !== "street_address") {
                    $("#err").text("Error: Geen valide adres ingevuld.");
                    $("#err").addClass("form-error");
                }
                else {


                    var city = results[0].address_components[2].long_name;
                    var country = results[0].address_components[5].long_name;
                    var postalcode = results[0].address_components[6].long_name.replace(/\s+/g, '');

                    if ($('input[name=city]').val() !== city ||
                            $('input[name=country]').val() !== country ||
                            $('input[name=postalcode]').val() !== postalcode) {
                        $('input[name=city]').val(city);
                        $('input[name=country]').val(country);
                        $('input[name=postalcode]').val(postalcode);
                    }

                    if (submit == true) {
                        console.log("submit!");
                        $('#form').submit();
                    }
                }
            } else {

                $("#err").text("Adres niet gevonden. Error Code: " + status);
                $("#err").addClass("form-error");
            }
        });


    }

    {/literal}
</script>

