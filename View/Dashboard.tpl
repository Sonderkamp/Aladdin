<script src="/JS/dashboard.js"></script>
<div class="container">

       <span class="info">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#InfoModal">
                <span class="glyphicon glyphicon-info-sign"></span>
            </button>
       </span>

    {if isset($errorString)}
        <div class="alert alert-warning">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {$errorString}
        </div>
    {/if}
    <div class="row dashboard-row">

        <div class="col-xs-6">
            <h3 class="dashboard-header">Informatie van {htmlspecialchars($user->name)}</h3>
            <div class="row">
                <label class="col-md-4 col-xs-4">Adres:</label>
                <label>{htmlspecialchars($user->address)}</label>
            </div>
            <div class="row">
                <label class="col-md-4 col-xs-4">postcode:</label>
                <label>{htmlspecialchars($user->postalcode)}</label>
            </div>
            <div class="row">
                <label class="col-md-4 col-xs-4">Land:</label>
                <label>{htmlspecialchars($user->country)}</label>
            </div>
            <div class="row">
                <label class="col-md-4">Geboortedatum:</label>
                <label>{htmlspecialchars($user->dob)}</label>
            </div>
            <div class="row">
                <label class="col-md-4">Wens limiet:</label>
                <label>{htmlspecialchars($wishLimit)}</label>
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
                <a href="/survey" class="btn btn-info">
                    Vul de vragenlijst in!
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <h3 class="col-xs-6 col-sm-4 col-md-12 dashboard-header">Mijn wensen</h3>
        {if $wishCheck}
            <a href="/wishes/action=openAddView" class="btn btn-info col-xs-1 visible-sm visible-xs">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </a>
        {/if}
    </div>
    <div class="row dashboard-row">
        {if !empty($wishes)}
            {if $wishCheck}
                <div class="col-md-4 hidden-sm hidden-xs">
                    <div class="thumbnail">
                        <div class="caption">
                            <div class="row">
                                <h4 class="col-md-12">Nieuwe Wens</h4>
                                <a href="/wishes/action=openAddView" class="btn btn-info btn-dashboard">
                                    Voeg wens toe
                                    <i class="fa fa-plus" aria-hidden="true"></i>
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
                                <div id="wishcontent{$value->id}" class="collapse collapse-button collapse-comment">
                                    <strong>Omschrijving:</strong> {htmlspecialchars($value->content)}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {/foreach}
        {else}
            <div class="center-block text-center"><p>Je hebt geen wensen<br>
                    <a href="/wishes/action=openAddView">Voeg wensen toe</a></p></div>
        {/if}
    </div>
    <div class="row">
        <h3 class="col-xs-6 col-sm-4 col-md-12 dashboard-header">Mijn talenten</h3>
        <a href="/Talents/p=allTalents" class="btn btn-info col-xs-1 visible-sm visible-xs">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </a>
    </div>
    <div class="row dashboard-row">
        {if isset($talents)}
            <div class="col-md-4 hidden-sm hidden-xs">
                <div class="thumbnail">
                    <div class="caption">
                        <div class="row">
                            <h4 class="col-md-12">Nieuw talent</h4>
                            <a href="/Talents/p=allTalents" class="btn btn-info btn-dashboard">
                                Voeg talent toe
                                <i class="fa fa-plus" aria-hidden="true"></i>
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
                                <label class="col-xs-12 text-center">{htmlspecialchars($value->name)}</label>
                            </div>
                        </div>
                    </div>
                </div>
            {/foreach}
        {else}
            <div class="center-block text-center"><p>Je hebt geen wensen<br>
                    <a href="/Talents/p=allTalents">Voeg talenten toe</a></p></div>
        {/if}
    </div>
</div>

<div id="InfoModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Dashboard</h4>
            </div>
            <div class="modal-body">

                <p>Dit is uw persoonlijke dashboard, vanuit hier kunt u uw wensen & talenten toevoegen en bekijken.<br>
                    Geen wens in gedachten? vul dan de <a href="/survey">vragenlijst</a> in, misschien kunnen wij u
                    hierbij helpen.
                    <br><br>
                    Voor het wijzigen van uw profiel klikt u op
                    <a href="/profile">
                        naar mijn profiel.
                    </a>
                </p>

                <div>
                    Een wens toevoegen doet u met:
                    <a href="/wishes/action=openAddView">
                        voeg wens toe
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>
                </div>

                <div>
                    Een talent toevoegen doet u met:
                    <a href="/Talents/p=allTalents">
                        voeg talent toe
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Sluiten
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
