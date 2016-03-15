<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: Simon-->
<!-- * Date: 7-Mar-16-->
<!-- * Time: 12:42-->
<!-- */-->

<div class="container">


    <body>
    <div class=" col-xs-12 col-lg-2">


    </div>
    <div class="col-lg-3 ">
        <h5>Mijn gegevens</h5>

        <form action="/profile/action=change" method="post"><p>
                Email: <input name="email" type="text" readonly="true" style="background-color: transparent"
                              value="{$user->email}">
            <p>
                initialen: <input type="text" name="initials" value="{$user->initials}" required data-validation="custom" data-validation-regexp="^([a-zA-Z\.]+)$"
                                  data-validation-error-msg="Initialen mogen alleen letters en punten bevatten.">
            <p>
                Voornaam: <input type="text" name="name" value="{$user->name}"  data-validation="custom" data-validation-regexp="^([a-zA-Z\- ]+)$" data-validation-error-msg="Geen valide voornaam ingevuld.">
            <p>
                Achternaam: <input type="text" name="surname" value="{$user->surname}"  data-validation="custom" data-validation-regexp="^([a-zA-Z\- ]+)$"
                                   data-validation-error-msg="Geen valide achternaam ingevuld.">
            <p>
                Adress: <input type="text" name="address" value="{$user->address}"  required data-validation="alphanumeric"
                               data-validation-allowing="-_ "
                               data-validation-error-msg="Straat en huisnummer kan alleen letters, nummers, spaties en streepjes(-_) bevatten">
            <p>
                Land: <input type="text" name="country" value="{$user->country}" required  data-validation="country"
                             data-validation-error-msg="invalide land gekozen.">
            <p>

                Postcode: <input type="text" name="postalcode" value="{$user->postalcode}"  required data-validation="custom"
                                 data-validation-regexp="([0-9][0-9][0-9][0-9][aA-zZ][aA-zZ])|([0-9][0-9][0-9][0-9][\s][aA-zZ][aA-zZ])"
                                 data-validation-error-msg="invalide postcode gegeven">
            <p>
                Plaats: <input type="text" name="city" value="{$user->city}"  required required data-validation="alphanumeric"
                               data-validation-allowing="-_ "
                               data-validation-error-msg="Straat en huisnummer kan alleen letters, spaties en streepjes(-_) bevatten">
            <p>
                Geboortedatum: <input type="date" name="dob" value="{$user->dob}"  data-validation-format="dd-mm-yyyy"
                                      required
                                      data-validation-error-msg="invalide datum." data-validation-help="dd-mm-yyyy">
            <p>
                Geslacht:
                {if $user->gender eq 'male'}
                <input type='radio' name='gender' value='male' checked> Man
                <input type='radio' name='gender' value='female'> Vrouw
                <input type='radio' name='gender' value='other'> Anders
                {elseif $user->gender eq 'female'}
                <input type='radio' name='gender' value='male'> Man
                <input type='radio' name='gender' value='female' checked> Vrouw
                <input type='radio' name='gender' value='other'> Anders
                {elseif $user->gender eq 'other'}
                <input type='radio' name='gender' value='male'> Man
                <input type='radio' name='gender' value='female'> Vrouw
                <input type='radio' name='gender' value='other' checked> Anders
                {else}
                <input type='radio' name='gender' value='male'> Man
                <input type='radio' name='gender' value='female'> Vrouw
                <input type='radio' name='gender' value='other'> Anders
                {/if}
            <p>
                Gehandicapt?: {if $user->handicap}
                <input type='checkbox' name='handicap' checked>
                {elseif !$user->handicap}
                <input type='checkbox' name='handicap'>
                {/if}
            <p>
            <p>
                <br>
                <input type="submit" value="wijzig" class="btn btn-default" style="float: right;">
                <br>


        </form>



</div>
<div class="col-lg-1">

</div>

<div class="col-xs-4">
    <h5>Reset wachtwoord</h5>
    <form action="/profile/action=changepw" method="post"><p>
            <br>
           <input type="hidden" readonly="true" name="username" value="{$user->email}">
        <p>
            Oud wachtwoord:   <input type="password" name="pwo" value="">
        <p>
            Nieuw wachtwoord: <input type="password" name="password1" value="">
        <p>
            Nieuw wachtwoord: <input type="password" name="password2" value="">

        <p>
        {$error}
        <p>
            <br>
            <input type="submit" value="wijzig" class="btn btn-default" style="float: right;">
            <br>


    </form>
</div>
<div class="col-lg-2"></div>
    </body>
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

