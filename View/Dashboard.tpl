<script src="/JS/dashboard.js"></script>
<div class="container">
    {if isset($errorString)}
        <div class="alert alert-warning">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {$errorString}
        </div>
    {/if}
    <div class="row dashboard-row">

        <div class="col-xs-6">
            <h3 class="dashboard-header">Informatie van {htmlspecialchars($smarty.session.user->name)}</h3>
            <div class="row">
                <label class="col-md-4 col-xs-4">Adres:</label>
                <label>{htmlspecialchars($smarty.session.user->address)}</label>
            </div>
            <div class="row">
                <label class="col-md-4 col-xs-4">postcode:</label>
                <label>{htmlspecialchars($smarty.session.user->postalcode)}</label>
            </div>
            <div class="row">
                <label class="col-md-4 col-xs-4">Land:</label>
                <label>{htmlspecialchars($smarty.session.user->country)}</label>
            </div>
            <div class="row">
                <label class="col-md-4">Geboortedatum:</label>
                <label>{htmlspecialchars($smarty.session.user->dob)}</label>
            </div>
            <div class="row">
                <a href="/profile" class="btn btn-info">
                    Naar mijn profiel
                </a>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="btn-text">
                <h4 class="dashboard-header">Weet u niet waarvoor u moet wensen?</h4>
                <a href="/" class="btn btn-info">
                    Vul de vragenlijst in!
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <h3 class="col-xs-6 col-sm-4 col-md-12 dashboard-header">Mijn wensen</h3>
        {if $wishCheck}
        <a href="/Wishes/action=open_wish" class="btn btn-info col-xs-1 visible-sm visible-xs">
            <span class="glyphicon glyphicon-plus"></span>
        </a>
        {/if}
    </div>
    <div class="row dashboard-row">
        {if isset($wishes)}
            {if $wishCheck}
                <div class="col-md-4 hidden-sm hidden-xs">
                    <div class="thumbnail">
                        <div class="caption">
                            <div class="row">
                                <h4 class="col-md-12">Nieuwe Wens</h4>
                                <a href="/Wishes/action=open_wish" class="btn btn-info btn-dashboard">
                                    Voeg wens toe
                                    <span class="glyphicon glyphicon-plus btn-dashboard btn-text"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            {/if}
            {foreach from=$wishes item=$value}
                <div class="col-sm-4">
                    <div class="thumbnail">
                        <div class="caption">
                            <div class="row">
                                <label class="col-md-4">Title:</label>
                                <label>{htmlspecialchars($value->title)}</label>
                            </div>
                            <div class="row">
                                <label class="col-md-4">Status:</label>
                                <label>{$value->status}</label>
                            </div>
                            <div class="row">
                                <button type="button" class="btn btn-info btn-dashboard" data-toggle="collapse"
                                        data-target="#wishcontent{$value->id}">Omschrijving
                                </button>
                                <div id="wishcontent{$value->id}" class="collapse collapse-button">
                                    <strong>Omschrijving:</strong> {htmlspecialchars($value->content)}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {/foreach}
        {else}
            <div class="center-block text-center"><p>Je hebt geen wensen<br>
                    <a href="/Wishes/action=open_wish">Voeg wensen toe</a></p></div>
        {/if}
    </div>
    <div class="row">
        <h3 class="col-xs-6 col-sm-4 col-md-12 dashboard-header">Mijn talenten</h3>
        <a href="/Talents/p=t" class="btn btn-info col-xs-1 visible-sm visible-xs">
            <span class="glyphicon glyphicon-plus"></span>
        </a>
    </div>
    <div class="row dashboard-row">
        {if isset($talents)}
            <div class="col-md-4 hidden-sm hidden-xs">
                <div class="thumbnail">
                    <div class="caption">
                        <div class="row">
                            <h4 class="col-md-12">Nieuw talent</h4>
                            <a href="/Talents/p=t" class="btn btn-info btn-dashboard">
                                Voeg talent toe
                                <span class="glyphicon glyphicon-plus btn-dashboard btn-text"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            {foreach from=$talents item=$value}
                <div class="col-md-4">
                    <div class="thumbnail">
                        <div class="caption">
                            <div class="row">
                                <label class="col-md-4">Naam:</label>
                                <label>{htmlspecialchars($value->name)}</label>
                            </div>
                        </div>
                    </div>
                </div>
            {/foreach}
        {else}
            <div class="center-block text-center"><p>Je hebt geen wensen<br>
                    <a href="/Talents/p=t">Voeg talenten toe</a></p></div>
        {/if}
    </div>
</div>

