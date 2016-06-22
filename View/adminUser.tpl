<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: Simon / Max-->
<!-- * Date: 8-3-2016 Rewritten on: 14-05-2016-->
<!-- */-->

<img src="/Resources/Images/banner.jpg" class="img-responsive width background">
<div class="container">

    <span class="info infoButtonMargin">
            <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#InfoModal">
                <span class="glyphicon glyphicon-info-sign"></span>
            </button>
    </span>

    <h5>Gebruikers Beheer</h5>
    <div class="col-md-2">
        <ul class="nav nav-pills nav-stacked">
            <li {if $currentPage == "users"}class="active"{/if}>
                <a href="#requestedTab" data-toggle="tab">Gebruikers</a>
            </li>

            <li {if $currentPage == "unhandled"}class="active"{/if}>
                <a href="#publishedTab" data-toggle="tab">Onbehandelde rapportages</a>
            </li>

            <li {if $currentPage == "handled"}class="active"{/if}>
                <a href="#matchedTab" data-toggle="tab">Behandelde rapportages</a>
            </li>

        </ul>
    </div>


    <form action="/AdminUser/action=search/" method="get">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><input class="form-control" name="search"
                                                                placeholder="Zoeken.."></div>
        <div>
            <button type="submit" class="btn btn-primary">Zoek</button>
            <button type="submit" class="btn btn-primary pull-right">Reset zoekfilter</button>
        </div>
    </form>

    <div class="col-md-10">
        {if isset($error)}
            <div class="alert  form-error alert-dismissible " role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                {htmlspecialchars($error)}
            </div>
        {/if}

    </div>

    <div class="col-md-10">
        <div class="tab-content">
            <div class="tab-pane fade in {if $currentPage == "users"}active{/if}" id="requestedTab">
                {if $users}
                    <br><br>
                    <table class="table panel">
                        <thead>
                        <tr>
                            <th>Naam</th>
                            <th>Displayname</th>
                            <th>E-mail</th>
                            <th>Land</th>
                            <th>Plaats</th>
                            <th>Status</th>
                            <th>Actie</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$users item=selectedUser}
                            <tr>
                                <td>{$selectedUser->name|ucfirst}</td>
                                <td>{$selectedUser->displayName}</td>
                                <td>{$selectedUser->email}</td>
                                <td>{$selectedUser->country|ucfirst}</td>
                                <td>{$selectedUser->city|ucfirst}</td>
                                <td>{if $selectedUser->blocked === 1}
                                        Geblokkeerd
                                    {else}
                                        Niet geblokkeerd
                                    {/if}
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                           aria-haspopup="true"
                                           aria-expanded="false"></span>
                                            Kies</span><span class="caret"></span></a>

                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="/AdminUser/action=showProfile/email={$selectedUser->email}">Bekijk
                                                    profiel</a>
                                            </li>
                                            <li>
                                                {if $selectedUser->blocked === 1}
                                                    <a href="/AdminUser/action=unblockUser/email={$selectedUser->email}">Deblokkeren</a>
                                                {else}
                                                    <a href="/AdminUser/action=blockUser/email={$selectedUser->email}">Blokkeren</a>
                                                {/if}

                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                {else}
                    <div class="center-block text-center"><h4>Er zijn geen gebruikers.</h4></div>
                {/if}
            </div>


            <div class="tab-pane fade in {if $currentPage == "unhandled"}active{/if}" id="publishedTab">
                {if $unhandled}
                    <br><br>
                    <table class="table panel">
                        <thead>
                        <tr>
                            <th>Melder</th>
                            <th>Reden</th>
                            <th>Gebruiker</th>
                            <th>Status</th>
                            <th>Datum</th>
                            <th>Type</th>
                            <th>Actie</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$unhandled item=report}
                            {if $report -> reported -> getBlocked() == false}
                                <tr>
                                    <td>
                                        <a href="/AdminUser/action=showProfile/email={$report -> reporter -> email}">
                                            <span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($report -> reporter -> displayName)}
                                        </a>

                                    </td>
                                    <td>{htmlspecialcharsWithNL($report -> message)}</td>
                                    <td>
                                        <a href="/AdminUser/action=showProfile/email={$report -> reported -> email}">
                                            <span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($report -> reported -> displayName)}
                                        </a>
                                    </td>
                                    <td>{htmlspecialcharsWithNL($report -> status)}</td>
                                    <td>
                                        <span class="glyphicon glyphicon-calendar"></span> {htmlspecialcharsWithNL($report -> date)}
                                    </td>
                                    <td>
                                        {if !empty($report->wishID)}
                                            Wens
                                        {else}
                                            {* bekijk bericht *}
                                            Bericht
                                        {/if}
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                               aria-haspopup="true"
                                               aria-expanded="false"></span>
                                                Kies</span><span class="caret"></span></a>


                                            <ul class="dropdown-menu">
                                                <li>
                                                    {*bekijk wens*}
                                                    {if !empty($report->wishID)}
                                                        <a href="wishes/action=getSpecificWish?admin=true&Id={$report->wishID}"
                                                           onClick="return popup(this, 'notes',900,400)">Bekijk
                                                            wens</a>
                                                        <a href="AdminWish/action=deleteWish?Id={$report->wishID}">
                                                            Verwijder wens
                                                        </a>
                                                    {else}
                                                        {* bekijk bericht *}
                                                        <a href="adminmail/action=show/id={$report->messageID}/user={$report -> reporter -> email}"
                                                           onClick="return popup(this, 'notes',700,400)">Bekijk
                                                            bericht</a>
                                                    {/if}

                                                </li>

                                                <li>
                                                    <a href="/AdminUser/action=delete/id={$report->id}">Verwijderen</a>
                                                </li>
                                                <li>
                                                    <a href="/AdminUser/action=block/id={$report->id}">Blokkeren</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            {/if}
                        {/foreach}
                        </tbody>
                    </table>
                {else}
                    <div class="center-block text-center"><h4>Er zijn momenteel geen onbehandelde wensen</h4></div>
                {/if}
            </div>

            {*Matched Wishes*}

            <div class="tab-pane fade in {if $currentPage == "handled"}active{/if}" id="matchedTab">
                {if $handled}
                    <br><br>
                    <table class="table panel">
                        <thead>
                        <tr>
                            <th>Melder</th>
                            <th>Reden</th>
                            <th>Gebruiker</th>
                            <th>Status</th>
                            <th>Datum</th>
                            <th>Type</th>
                            <th>Actie</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$handled item=report}
                            <tr>
                                <td>
                                    <a href="/AdminUser/action=showProfile/email={$report -> reporter -> email}">
                                        <span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($report -> reporter -> displayName)}
                                    </a>

                                </td>
                                <td>{htmlspecialcharsWithNL($report -> message)}</td>
                                <td>
                                    <a href="/AdminUser/action=showProfile/email={$report -> reported -> email}">
                                        <span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($report -> reported -> displayName)}
                                    </a>
                                </td>
                                <td>{htmlspecialcharsWithNL($report -> status)}</td>
                                <td>
                                    <span class="glyphicon glyphicon-calendar"></span> {htmlspecialcharsWithNL($report -> date)}
                                </td>
                                <td>
                                    {if !empty($report->wishID)}
                                        Wens
                                    {else}
                                        {* bekijk bericht *}
                                        Bericht
                                    {/if}
                                </td>
                                <td>

                                    {if !empty($report->wishID)}
                                        <a href="wishes/action=getSpecificWish?admin=true&Id={$report->wishID}"
                                           onClick="return popup(this, 'notes',900,400)">Bekijk
                                            wens</a>
                                    {else}
                                        {* bekijk bericht *}
                                        <a href="adminmail/action=show/id={$report->messageID}/user={$report -> reporter -> email}"
                                           onClick="return popup(this, 'notes',700,400)">Bekijk
                                            bericht</a>
                                    {/if}

                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                {else}
                    <div class="center-block text-center"><h4>Er zijn momenteel geen behandelde rapporten</h4></div>
                {/if}
            </div>

        </div>
    </div>
</div>

<div id="InfoModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Gebruikersbeheer</h4>
            </div>
            <div class="modal-body">

                <p>
                    Op deze pagina kun je de gebruikers en rapportages bekijken.<br><br>
                    <a><b>Gebruikers</b></a><br>
                    - Toont alle gebruikers<br>
                    - Status: toont of de gebruikers is geblokkeerd<br>
                    - Actie: hiermee kun je de gebruiker (de)blokkeren of naar het dasboard van de gebruiker
                    kijken<br><br>

                    <a><b>Onbehandelde rapportages</b></a><br>
                    - Toont een lijst met rapportages die door gebruikers zijn gemeld, door op de gebruikersnaam te
                    klikken kun je navigeren naar het profiel van de gebruiker
                    <br><br>

                    <b>Acties dat je kunt uitvoeren:</b> <br>
                    - Bekijk wens: toont de wens van de gebruiker waarop hij/zei is gerapporteerd<br>
                    - Verwijder wens: hiermee kun je de wens verwijderen<br>
                    - Verwijderen: hiermee verwijder je de rapportage, dus als je vindt dat er niets abnormaals in
                    staat<br>
                    - Blokkeren: hiermee kun je de gebruiker eenvoudig blokkeren als deze de regels heeft overtreden<br><br>

                    <a><b>Behandelde rapportages</b></a><br>
                    - Een lijst met rapportages welke door een admin zijn behandelt.

                </p>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Sluiten
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


{foreach from=$unhandled item=report}
    <div id="myModal{preg_replace('/\s+/', '', $report->getId())}" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h6 class="modal-title"><span
                                class="glyphicon glyphicon-user"></span>
                        Melder: {htmlspecialcharsWithNL($report -> reporter -> displayName)}
                    </h6>

                    <h6 class="modal-title"><span class="glyphicon glyphicon-calendar"></span> Aangevraagd
                        op: {htmlspecialcharsWithNL($report -> date)}
                    </h6>

                    <h6 class="modal-title"><span
                                class="glyphicon glyphicon-user"></span>
                        Gebruiker: {htmlspecialcharsWithNL($report -> reported -> displayName)}
                    </h6>
                    <h6 class="modal-title"><span
                                class="glyphicon glyphicon-eye-open"></span>
                        Status: {htmlspecialcharsWithNL($report -> status)}
                    </h6>
                    <br>
                    <h6 class="modal-title"><b>Reden:</b> <br> {htmlspecialcharsWithNL($report -> message)}
                    </h6>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-default infoLeft" data-dismiss="modal">Annuleren</a>
                    <a class="btn btn-default" href="/AdminUser/action=delete/id={$report->id}">Verwijderen</a>
                    <a class="btn btn-default" href="/AdminUser/action=block/id={$report->id}"><span
                                class="glyphicon glyphicon-remove"></span> Blokkeren</a>
                </div>
            </div>
        </div>
    </div>
{/foreach}

<div class="modal fade" id="block" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Blokkeer gebruiker</h4>
            </div>
            <div class="modal-body">
                <form action="/profileoverview/action=block/user={$curUser->email}" method="get">
                    <fieldset class="form-group">
                        <input type="text" class="form-control" name="reason"
                               placeholder="Reden">
                        <small class="text-muted">Geef de reden op die de gebruiker ziet als hij probeert in te loggen
                        </small>
                    </fieldset>
                    <button type="submit" class="btn btn-default">Blokkeer</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


{foreach from=$users item=user}
    <div id="myModalUser{preg_replace('/\s+/', '', $user->email)}" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h6 class="modal-title"><span
                                class="glyphicon glyphicon-user"></span>
                        Displaynaam: {htmlspecialcharsWithNL($user -> displayname)}
                    </h6>

                    <h6 class="modal-title"><span class="glyphicon glyphicon-calendar"></span> Aangevraagd
                        op: {htmlspecialcharsWithNL($report -> getDate())}
                    </h6>

                    <h6 class="modal-title"><span
                                class="glyphicon glyphicon-user"></span>
                        Gebruiker: {htmlspecialcharsWithNL($report -> getReported() -> getDisplayName())}
                    </h6>
                    <h6 class="modal-title"><span
                                class="glyphicon glyphicon-eye-open"></span>
                        Status: {htmlspecialcharsWithNL($report -> getStatus())}
                    </h6>
                    <br>
                    <h6 class="modal-title"><b>Reden:</b> <br> {htmlspecialcharsWithNL($report -> getMessage())}
                    </h6>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-default infoLeft" data-dismiss="modal">Annuleren</a>
                    <a class="btn btn-default" href="/AdminUser/action=delete/id={$user->email}">Blokkeren</a>
                    <a class="btn btn-default" href="/AdminUser/action=block/id={$user->email}"><span
                                class="glyphicon glyphicon-remove"></span>Naar profiel</a>
                </div>

                <form action="/report/action=report" method="post">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default infoLeft" data-dismiss="modal">Annuleren</button>

                        <button type="submit" name="submit" class="btn btn-inbox info">
                            <span class="glyphicon glyphicon-remove"></span> Gebruiker blokkeren
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
{/foreach}

<script>
    function popup(mylink, windowname, w, h) {
        if (!window.focus)return true;
        var href;
        if (typeof(mylink) == 'string') href = mylink; else href = mylink.href;
        window.open(href, windowname, 'width=' + w + ',height=' + h + ',scrollbars=yes');
        return false;
    }
</script>

