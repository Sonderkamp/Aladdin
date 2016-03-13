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
                initialen: <input type="text" name="initials" value="{$user->initials}">
            <p>
                Voornaam: <input type="text" name="name" value="{$user->name}">
            <p>
                Achternaam: <input type="text" name="surname" value="{$user->surname}">
            <p>
                Adress: <input type="text" name="address" value="{$user->address}">
            <p>
                Land: <input type="text" name="country" value="{$user->country}">
            <p>
                Postcode: <input type="text" name="postalcode" value="{$user->postalcode}">
            <p>
                Plaats: <input type="text" name="city" value="{$user->city}">
            <p>
                Geboortedatum: <input type="date" name="dob" value="{$user->dob}">
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

