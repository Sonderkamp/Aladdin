<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: Simon-->
<!-- * Date: 7-Mar-16-->
<!-- * Time: 12:42-->
<!-- */-->

<div class="container">
    <div class="row">


        <body>
        <div class=" col-xs-12 col-lg-2">
            <h5>Mijn profiel</h5>
            <hr/>


        </div>
        <div class="col-lg-10">


            <form action="/profile/action=change" method="post"> <p>
                Email: <input name="email" type="text"  readonly="true" style="background-color: transparent" value={$user->email}><p>
                initialen: <input type="text" name="initials" value={$user->initials} ><p>
                Voornaam: <input type="text" name="name" value={$user->name} ><p>
                Achternaam: <input type="text" name="surname" value={$user->surname} ><p>
                Adress: <input type="text" name="address" value={$user->address} ><p>
                Land: <input type="text" name="country" value={$user->country} ><p>
                Postcode: <input type="text" name="postalcode" value={$user->postalcode} ><p>
                Plaats: <input type="text" name="city" value={$user->city} ><p>
                Geboortedatum: <input type="date" name="dob" value={$user->dob} ><p>
                Geslacht:
                    {if $user->gender eq  'male'}
                    <input type='radio' name='gender' value ='male' checked> Man
                    <input type='radio' name='gender' value ='female'> Vrouw
                    <input type='radio' name='gender' value ='other'> Anders
                    {elseif $user->gender eq 'female'}
                    <input type='radio' name='gender' value ='male'> Man
                    <input type='radio' name='gender' value ='female'checked> Vrouw
                    <input type='radio' name='gender' value ='other'> Anders
                    {elseif $user->gender eq  'other'}
                    <input type='radio' name='gender' value ='male'> Man
                    <input type='radio' name='gender' value ='female'> Vrouw
                    <input type='radio' name='gender' value ='other' checked> Anders
                    {else}
                    <input type='radio' name='gender'  value ='male'> Man
                    <input type='radio' name='gender'  value ='female'> Vrouw
                    <input type='radio' name='gender'  value ='other'> Anders
                    {/if} <p>
                    Gehandicapt?: {if $user->handicap}
                    <input type='checkbox' name='handicap' checked>
                    {elseif !$user->handicap}
                    <input type='checkbox' name='handicap'>
                    {/if} <p>
                <p>
                    <br>
                    <input type="submit" value="wijzig" class="btn btn-default">
<br>


            </form>
            <form name="recover" action="/Account/action=recover" method="post" onsubmit="return validateEmail()">
                <input  type="hidden" name="username" value="{$user->email}" readonly="true">
                <input class="btn btn-default" value="Reset Wachtwoord" type="submit">
            </form>



                </body>

        </div>
    </div>
</div>

