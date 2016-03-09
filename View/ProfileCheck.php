
<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: simon-->
<!-- * Date: 9-3-2016-->
<!-- * Time: 20:14-->
<!-- */-->


<div class="container">
    <div class="row">


        <body>
        <div class=" col-xs-2 col-lg-10">
            <h5>Profiel van {$cuser.DisplayName}</h5>
            {if ($cuser.IsActive eq 0)}
            <h5 style="color:red">{$cuser.DisplayName} is geblokeerd</h5>
            {/if}
            <hr/>



        </div>
        <div class="col-lg-10 ">


            <form action="/ProfileCheck/action=block?user={$cuser.Email}" method="post"> <p>

                    Email: {$cuser.Email}<p>
                    initialen: {$cuser.Initials}<p>
                    Voornaam: {$cuser.Name}<p>
                    Achternaam: {$cuser.Surname}<p>
                    Adress: {$cuser.Address}<p>
                    Land: {$cuser.Country}<p>
                    Postcode: {$cuser.Postalcode}<p>
                    Plaats: {$cuser.City}<p>
                    Geboortedatum: {$cuser.Dob}<p>
Geslacht:
                    {if $cuser.Gender eq  'male'}Man
                    {elseif $cuser.Gender eq 'female'} Vrouw
                    {elseif $cuser.Gender eq  'other'} Anders
                    {else}
                    {/if} <p>
Gehandicapt?: {if $cuser.Handicap}
                    Ja
                    {elseif !$cuser.Handicap}
                    Nee
                    {/if} <p>
                <p>
                    <br>
                    {if ($cuser.IsActive eq 1)}
                    <input type="submit" formaction="/ProfileCheck/action=block?user={$cuser.Email}" value="Blokeer" class="btn btn-default">

                    {elseif ($cuser.IsActive eq 0)}
                    <input type="submit" formaction="/ProfileCheck/action=unblock?user={$cuser.Email}" value="Deblokeer" class="btn btn-default">
                    {/if}
<br>


            </form>




                </body>

        </div>
    </div>
</div>

