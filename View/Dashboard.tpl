<script src="/JS/dashboard.js"></script>
<div class="container" onload="alertOnLoad({$wishAmount},{$talentAmount})">
    {if $wishAmount < 3 || $talentAmount < 3}
    <div class="alert alert-warning">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Pas op!</strong> U heeft uw profiel nog niet voltooid. Vul alstublieft minimaal 3 wensen en talenten in.
    </div>
    {/if}
    <div class="row">
        <div class="col-md-3">
            <img class="center-block" src="http://placehold.it/168x168" alt="profileImage">
        </div>

        <div class="col-md-9">
            <h3>Informatie van {$smarty.session.user->name}</h3>
            <div class="row">
                <label class="col-md-2">Adres:</label>
                <label>{$smarty.session.user->address}</label>
            </div>
            <div class="row">
                <label class="col-md-2">postcode:</label>
                <label>{$smarty.session.user->postalcode}</label>
            </div>
            <div class="row">
                <label class="col-md-2">Land:</label>
                <label>{$smarty.session.user->country}</label>
            </div>
            <div class="row">
                <label class="col-md-2">Geboortedatum:</label>
                <label>{$smarty.session.user->dob}</label>
            </div>
        </div>
    </div>

    <div class="summit-container">
        <h3>Mijn wensen</h3>
            {if isset($wishes)}
                {foreach from=$wishes item=$value}
                <div class="col-md-4">
                    <div class="thumbnail">
                        <div class="caption">
                            <div class="row">
                                <h4>{$value->title}</h4>
                            </div>
                            <div class="row">
                                <label class="col-md-2">Omschrijving</label>
                                <textarea class="col-md-10 wishcontent">{$value->content}</textarea>
                            </div>
                            <div class="row">
                                <label class="col-md-2">Land:</label>
                                <label>{$smarty.session.user->country}</label>
                            </div>
                            <div class="row">
                                <label class="col-md-2">Geboortedatum:</label>
                                <label>{$smarty.session.user->dob}</label>
                            </div>
                        </div>
                    </div>
                </div>
                {/foreach}
                {else}
                <div class="center-block text-center"><p>Je hebt geen wensen<br>
                    <a href="/">Voeg wensen toe</a></p></div>
            {/if}
    </div>

    <div class="summit-container">
        <h3>Mijn talenten</h3>
        {if isset($talents)}
            {foreach from=$talents item=$value}
                <div class="col-md-4">
                    <div class="thumbnail">
                        <div class="caption">
                            <div class="row">
                                <label class="col-md-4">Naam:</label>
                                <label>{$value->name}</label>
                            </div>
                        </div>
                    </div>
                </div>
            {/foreach}
        {else}
            <div class="center-block text-center"><p>Je hebt geen wensen<br>
                    <a href="/">Voeg wensen toe</a></p></div>
        {/if}
    </div>
</div>

