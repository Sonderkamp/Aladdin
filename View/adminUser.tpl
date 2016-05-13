<div class="container">
    <div class="row">
        <h5>Gebruikersbeheer</h5>
        <div class="col-xs-12 col-md-2 col-sm-2 col-lg-2">
            <ul class="nav nav-pills nav-stacked">
                {if isset($current)}
                    <li {if $current == "unhandled"} class="active" {/if}><a href="/AdminUser/action=unhandled">Nieuwe
                            rapportages</a></li>
                    <li {if $current == "handled"} class="active" {/if}><a href="/AdminUser/action=handled">Behandelde
                            rapportages</a></li>
                {/if}
            </ul>
        </div>

        <div class="col-md-10">
            <table class="table">
                <thead>
                <tr>
                    <th>Melder</th>
                    <th>Reden</th>
                    <th>Gebruiker</th>
                    <th>Status</th>
                    <th>Datum</th>
                    <th>Actie</th>
                </tr>
                </thead>
                <tbody>

                {if isset($reports)}
                    {foreach from=$reports item=report}
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
                                    {if $current == "unhandled"}
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
                                                {*<li><a href="/AdminUser/action=check/id={$report->getId()}">Bekijken</a></li>*}
                                            </ul>
                                        </div>
                                    {else}
                                        <a href="/profilecheck/action=viewProfile/user={$report -> getReported() -> getEmail()}">
                                            <span>Bekijk profiel</span>
                                        </a>
                                    {/if}
                                </td>
                            </tr>
                        {/if}
                    {/foreach}
                {/if}
                </tbody>
            </table>
        </div>

    </div>
</div>

{foreach from=$reports item=report}
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
                {*<button type="button" class="btn btn-default infoLeft" data-dismiss="modal">Annuleren</button>*}

                {*<button type="submit" name="submit" class="btn btn-inbox info">*}
                {*<span class="glyphicon glyphicon-remove"></span> Gebruiker blokkeren*}
                {*</button>*}
                {*</div>*}
                {*</form>*}
            </div>
        </div>
    </div>
{/foreach}