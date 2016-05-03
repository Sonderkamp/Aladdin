<script src="/JS/dashboard.js"></script>
<div class="container">
    {if isset($errorString)}
    <div class="alert alert-warning">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {$errorString}
    </div>
    {/if}
    <div class="row">
        <div class="col-md-3">
            <img class="center-block" src="http://placehold.it/168x168" alt="profileImage">
        </div>

        <div class="col-md-9">
            <h3>Informatie van {htmlspecialchars($smarty.session.user->name)}</h3>
            <div class="row">
                <label class="col-md-2">Adres:</label>
                <label>{htmlspecialchars($smarty.session.user->address)}</label>
            </div>
            <div class="row">
                <label class="col-md-2">postcode:</label>
                <label>{htmlspecialchars($smarty.session.user->postalcode)}</label>
            </div>
            <div class="row">
                <label class="col-md-2">Land:</label>
                <label>{htmlspecialchars($smarty.session.user->country)}</label>
            </div>
            <div class="row">
                <label class="col-md-2">Geboortedatum:</label>
                <label>{htmlspecialchars($smarty.session.user->dob)}</label>
            </div>
        </div>
    </div>

    <div class="summit-container">
        <h3 class="col-xs-12">Mijn wensen</h3>
            {if isset($wishes)}
                {foreach from=$wishes item=$value}
                <div class="col-md-4">
                    <div class="thumbnail large-caption">
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
                                <button type="button" class="btn btn-info btn-dashboard" data-toggle="collapse" data-target="#wishcontent{$value->id}">Omschrijving</button>
                                <div id="wishcontent{$value->id}" class="collapse collapse-button">
                                    <strong>Omschrijving:</strong> {htmlspecialchars($value->content)}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {/foreach}
                {if $wishCheck}
                <div class="col-md-4">
                    <div class="thumbnail large-caption">
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
                {else}
                <div class="center-block text-center"><p>Je hebt geen wensen<br>
                    <a href="/">Voeg wensen toe</a></p></div>
            {/if}
    </div>

    <div class="summit-container">
        <h3 class="col-xs-12">Mijn talenten</h3>
        {if isset($talents)}
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
            {if $talentCheck}
                <div class="col-md-4">
                    <div class="thumbnail large-caption">
                        <div class="caption">
                            <div class="row">
                                <h4 class="col-md-12">Nieuw talent</h4>
                                <a href="/Wishes/action=open_wish" class="btn btn-info btn-dashboard">
                                    Voeg talent toe
                                    <span class="glyphicon glyphicon-plus btn-dashboard btn-text"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            {/if}
        {else}
            <div class="center-block text-center"><p>Je hebt geen wensen<br>
                    <a href="/">Voeg wensen toe</a></p></div>
        {/if}
    </div>
</div>

