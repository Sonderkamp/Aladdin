<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: Simon / Max-->
<!-- * Date: 8-3-2016 Rewritten on: 14-05-2016-->
<!-- */-->

<div class="container">

    <h5>Wensen Beheer</h5>
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

    <form class="col-xs-10 row" action="/#/#" method="get">
        <div class="col-lg-7 col-md-7 col-sm-9 col-xs-9">
            <input class="form-control" name="search" placeholder="Zoek gebruiker">
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <button type="submit" class="btn btn-primary">Zoek</button>
        </div>
    </form>


    <div class="col-md-10">
        <div class="tab-content">
            <div class="tab-pane fade in {if $currentPage == "users"}active{/if}" id="requestedTab">
                {if $users}
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Gebruiker</th>
                            <th>E-mail</th>
                            <th>Land</th>
                            <th>Plaats</th>
                            <th>Actie</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$users item=user}
                            <tr>
                                <td>{$user->displayName}</td>
                                <td>{$user->email}</td>
                                <td>{$user->country|ucfirst}</td>
                                <td>{$user->city|ucfirst}</td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                           aria-haspopup="true"
                                           aria-expanded="false"></span>
                                            Kies</span><span class="caret"></span></a>

                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="#">Bekijk profiel</a>
                                            </li>
                                            <li>
                                                <a href="/AdminUser/action=blockUser/email={$user->email}">Blokkeren</a>
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
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Melder</th>
                            <th>Reden</th>
                            <th>Gebruiker</th>
                            <th>Status</th>
                            <th>Datum</th>
                            <th>Actie</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$unhandled item=report}
                            {if $report -> getReported() -> getBlocked() == false}
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($report -> getReporter() -> getDisplayName())}
                                    </td>
                                    <td>{htmlspecialcharsWithNL($report -> getMessage()|substr:0:20)}</td>
                                    <td>
                                        <span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($report -> getReported() -> getDisplayName())}
                                    </td>
                                    <td>{htmlspecialcharsWithNL($report -> getStatus())}</td>
                                    <td>
                                        <span class="glyphicon glyphicon-calendar"></span> {htmlspecialcharsWithNL($report -> getDate())}
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                               aria-haspopup="true"
                                               aria-expanded="false"></span>
                                                Kies</span><span class="caret"></span></a>


                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a data-toggle="modal"
                                                       data-target="#myModal{preg_replace('/\s+/', '', $report->getId())}">
                                                        Bekijken
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="/AdminUser/action=delete/id={$report->getId()}">Verwijderen</a>
                                                </li>
                                                <li>
                                                    <a href="/AdminUser/action=block/id={$report->getId()}">Blokkeren</a>
                                                </li>
                                                {*<li>*}
                                                {*<a href="/AdminUser/action=check/id={$report->getId()}">Bekijken</a>*}
                                                {*</li>*}
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
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Melder</th>
                            <th>Reden</th>
                            <th>Gebruiker</th>
                            <th>Status</th>
                            <th>Datum</th>
                            <th>Actie</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$handled item=report}
                            {if $report -> getReported() -> getBlocked() == false}
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($report -> getReporter() -> getDisplayName())}
                                    </td>
                                    <td>{htmlspecialcharsWithNL($report -> getMessage()|substr:0:20)}</td>
                                    <td>
                                        <span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($report -> getReported() -> getDisplayName())}
                                    </td>
                                    <td>{htmlspecialcharsWithNL($report -> getStatus())}</td>
                                    <td>
                                        <span class="glyphicon glyphicon-calendar"></span> {htmlspecialcharsWithNL($report -> getDate())}
                                    </td>
                                    <td>
                                        <a href="/profilecheck/action=viewProfile/user={$report -> getReported() -> getEmail()}">
                                            <span>Bekijk profiel</span>
                                        </a>
                                    </td>
                                </tr>
                            {/if}
                        {/foreach}
                        </tbody>
                    </table>
                {else}
                    <div class="center-block text-center"><h4>Er zijn momenteel geen behandelde wensen</h4></div>
                {/if}
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
                        Melder: {htmlspecialcharsWithNL($report -> getReporter() -> getDisplayName())}
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
                    <a class="btn btn-default" href="/AdminUser/action=delete/id={$report->getId()}">Verwijderen</a>
                    <a class="btn btn-default" href="/AdminUser/action=block/id={$report->getId()}"><span
                                class="glyphicon glyphicon-remove"></span> Blokkeren</a>
                </div>
                {*<form action="/report/action=report" method="post">*}
                    {*<div class="modal-footer">*}
                        {*<button type="submit" name="submit" class="btn btn-inbox info">*}
                            {*<span class="glyphicon glyphicon-remove"></span> Gebruiker blokkeren*}
                        {*</button>*}
                    {*</div>*}
                {*</form>*}
            </div>
        </div>
    </div>
{/foreach}


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


{*<div class="container">*}
{*<div class="row">*}
{*<h5>Gebruikersbeheer</h5>*}
{*<div class="col-xs-12 col-md-2 col-sm-2 col-lg-2">*}
{*<ul class="nav nav-pills nav-stacked">*}
{*{if isset($current)}*}
{*<li {if $current == "users"} class="active" {/if}><a href="/AdminUser/action=unhandled">Alle*}
{*gebruikers</a></li>*}
{*<li {if $current == "unhandled"} class="active" {/if}><a href="/AdminUser/action=unhandled">Nieuwe*}
{*rapportages</a></li>*}
{*<li {if $current == "handled"} class="active" {/if}><a href="/AdminUser/action=handled">Behandelde*}
{*rapportages</a></li>*}
{*{/if}*}
{*</ul>*}
{*</div>*}

{*{if $smarty.session.current == "handled" || $smarty.session.current == "unhandled"}*}
{*<div class="col-md-10">*}
{*<table class="table">*}
{*<thead>*}
{*<tr>*}
{*<th>Melder</th>*}
{*<th>Reden</th>*}
{*<th>Gebruiker</th>*}
{*<th>Status</th>*}
{*<th>Datum</th>*}
{*<th>Actie</th>*}
{*</tr>*}
{*</thead>*}
{*<tbody>*}


{*{if isset($reports)}*}
{*{foreach from=$reports item=report}*}
{*{if $report -> getReported() -> getBlocked() == false}*}
{*<tr>*}
{*<td>*}
{*<span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($report -> getReporter() -> getDisplayName())}*}
{*</td>*}
{*<td>{htmlspecialcharsWithNL($report -> getMessage()|substr:0:20)}</td>*}
{*<td>*}
{*<span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($report -> getReported() -> getDisplayName())}*}
{*</td>*}
{*<td>{htmlspecialcharsWithNL($report -> getStatus())}</td>*}
{*<td>*}
{*<span class="glyphicon glyphicon-calendar"></span> {htmlspecialcharsWithNL($report -> getDate())}*}
{*</td>*}
{*<td>*}
{*{if $current == "unhandled"}*}
{*<div class="dropdown">*}
{*<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"*}
{*aria-haspopup="true"*}
{*aria-expanded="false"></span>*}
{*Kies</span><span class="caret"></span></a>*}


{*<ul class="dropdown-menu">*}
{*<li>*}
{*<a data-toggle="modal"*}
{*data-target="#myModal{preg_replace('/\s+/', '', $report->getId())}">*}
{*Bekijken*}
{*</a>*}
{*</li>*}
{*<li>*}
{*<a href="/AdminUser/action=delete/id={$report->getId()}">Verwijderen</a>*}
{*</li>*}
{*<li>*}
{*<a href="/AdminUser/action=block/id={$report->getId()}">Blokkeren</a>*}
{*</li>*}
{*<li>*}
{*<a href="/AdminUser/action=check/id={$report->getId()}">Bekijken</a>*}
{*</li>*}
{*</ul>*}
{*</div>*}
{*{else}*}
{*<a href="/profilecheck/action=viewProfile/user={$report -> getReported() -> getEmail()}">*}
{*<span>Bekijk profiel</span>*}
{*</a>*}
{*{/if}*}
{*</td>*}
{*</tr>*}
{*{/if}*}
{*{/foreach}*}
{*{/if}*}
{*</tbody>*}
{*</table>*}
{*</div>*}
{*{/if}*}

{*</div>*}
{*</div>*}

{*{foreach from=$users item=user}*}
{*<div id="myModalUser{preg_replace('/\s+/', '', $user->email)}" class="modal fade" role="dialog">*}
{*<div class="modal-dialog">*}
{*<!-- Modal content-->*}
{*<div class="modal-content">*}
{*<div class="modal-header">*}
{*<button type="button" class="close" data-dismiss="modal">&times;</button>*}

{*<h6 class="modal-title"><span*}
{*class="glyphicon glyphicon-user"></span>*}
{*Melder: {htmlspecialcharsWithNL($user -> displayname)}*}
{*</h6>*}

{*<h6 class="modal-title"><span class="glyphicon glyphicon-calendar"></span> Aangevraagd*}
{*op: {htmlspecialcharsWithNL($report -> getDate())}*}
{*</h6>*}

{*<h6 class="modal-title"><span*}
{*class="glyphicon glyphicon-user"></span>*}
{*Gebruiker: {htmlspecialcharsWithNL($report -> getReported() -> getDisplayName())}*}
{*</h6>*}
{*<h6 class="modal-title"><span*}
{*class="glyphicon glyphicon-eye-open"></span>*}
{*Status: {htmlspecialcharsWithNL($report -> getStatus())}*}
{*</h6>*}
{*<br>*}
{*<h6 class="modal-title"><b>Reden:</b> <br> {htmlspecialcharsWithNL($report -> getMessage())}*}
{*</h6>*}
{*</div>*}
{*<div class="modal-footer">*}
{*<a class="btn btn-default infoLeft" data-dismiss="modal">Annuleren</a>*}
{*<a class="btn btn-default" href="/AdminUser/action=delete/id={$report->getId()}">Verwijderen</a>*}
{*<a class="btn btn-default" href="/AdminUser/action=block/id={$report->getId()}"><span*}
{*class="glyphicon glyphicon-remove"></span> Blokkeren</a>*}
{*</div>*}
{*<form action="/report/action=report" method="post">*}
{*<div class="modal-footer">*}
{*<button type="button" class="btn btn-default infoLeft" data-dismiss="modal">Annuleren</button>*}

{*<button type="submit" name="submit" class="btn btn-inbox info">*}
{*<span class="glyphicon glyphicon-remove"></span> Gebruiker blokkeren*}
{*</button>*}
{*</div>*}
{*</form>*}
{*</div>*}
{*</div>*}
{*</div>*}
{*{/foreach}*}