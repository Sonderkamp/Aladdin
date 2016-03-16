<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: simon-->
<!-- * Date: 9-3-2016-->
<!-- * Time: 20:14-->
<!-- */-->


<div class="container">
    <div class="row">


        <body>
        <div class=" col-xs-2 col-lg-4">

            {if ($blockstatus.IsBlocked eq 1)}
            <h5 style="color:red">{$cuser.DisplayName} is geblokeerd!</h5>

            {elseif ($cuser.IsActive eq 0)}
            <h5 style="color:red">{$cuser.DisplayName} heeft zijn account verwijderd!</h5>
            {else}
            <h5>Profiel van {$cuser.DisplayName}</h5>
            {/if}
            <hr/>


        </div>
        <div class=" col-xs-2 col-lg-8">
            <h5>Wensen van {$cuser.DisplayName}</h5>

            <hr/>



        </div>

        <div class="col-lg-4 ">


            <form action="/ProfileCheck/action=block?user={$cuser.Email}" method="post"><p>

                    Email: {$cuser.Email}
                <p>
                    initialen: {$cuser.Initials}
                <p>
                    Voornaam: {$cuser.Name}
                <p>
                    Achternaam: {$cuser.Surname}
                <p>
                    Adress: {$cuser.Address}
                <p>
                    Land: {$cuser.Country}
                <p>
                    Postcode: {$cuser.Postalcode}
                <p>
                    Plaats: {$cuser.City}
                <p>
                    Geboortedatum: {$cuser.Dob}
                <p>
                    Geslacht:
                    {if $cuser.Gender eq 'male'}Man
                    {elseif $cuser.Gender eq 'female'} Vrouw
                    {elseif $cuser.Gender eq 'other'} Anders
                    {else}
                    {/if}
                <p>
                    Gehandicapt?: {if $cuser.Handicap}
                    Ja
                    {elseif !$cuser.Handicap}
                    Nee
                    {/if}
                <p>
                <p>
                    <br>

                    {if ($blockstatus.IsBlocked eq 0)}
                    <input type="submit" formaction="/ProfileCheck/action=block?user={$cuser.Email}" value="Blokeer"
                           class="btn btn-default">

                    {elseif ($blockstatus.IsBlocked eq 1)}
                    <input type="submit" formaction="/ProfileCheck/action=unblock?user={$cuser.Email}" value="Deblokeer"
                           class="btn btn-default">
                    {/if}
                    <br>


            </form>

        </div>


        <div class="col-lg-8 ">
            <table class="table">
                <thead>
                <tr>
                    <th>Titel</th>
                    <th width="1%">Status</th>
                    <th width="1%">Wens</th>
                </tr>
                </thead>
            {foreach from=$wishes item=wish}
                <tr>
            <td>{$wish.title|escape:"html"}</td>
                    <td>{$wish.status}</td>
                <td>
                    <form method="post">
                    <button type="submit"  formaction="/Wishes/wish_id={$wish.wishid}"><span class="glyphicon glyphicon-eye-open"></span></button>
                    </form>

                </td>

            {/foreach}
                </table>


                <div class="col-lg-6 ">
                    <h5>Talenten</h5>

                    <hr/>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Talent</th>
                        </tr>
                        </thead>
                        {foreach from=$talents item=talent}
                        <tr>
                            <td>{$talent.name|escape:"html"}</td>
                        </tr>
                        {/foreach}
                    </table>
                    </div>
            <div class="col-lg-6 ">
                <h5>Blokkeergeschiedenis</h5>

                <hr/>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Blokkeergeschiedenis</th>
                    </tr>
                    </thead>
                    {foreach from=$blocks item=block item=i}
                    <tr>
                        <td>{$i.bdate}</td>
                        {if {$i.isblocked} eq 1}
                        <td>Geblokeerd</td>
                        {else}
                        <td>Gedeblokeerd</td>
                        {/if}
                    </tr>
                    {/foreach}
                </table>
            </div>
        </div>





        </body>

    </div>
</div>
</div>

