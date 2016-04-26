<div class="container">
    <div class="row">
        <h5>Gebruikersbeheer</h5>
        <div class="col-xs-12 col-md-2 col-sm-2 col-lg-2">
            <ul class="nav nav-pills nav-stacked">
                {if isset($current)}
                    <li {if $current == "unhandled"} class="active" {/if}><a href="AdminUser/action=unhandled" >Nieuwe rapportages</a></li>
                    <li {if $current == "handled"} class="active" {/if}><a href="AdminUser/action=handled">Behandelde rapportages</a></li>
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
                        <tr>
                            <td><span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($report -> getReporter() -> getDisplayName())}</td>
                            <td>{htmlspecialcharsWithNL($report -> getMessage()|substr:0:20)}</td>
                            <td><span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($report -> getReported() -> getDisplayName())}</td>
                            <td>{htmlspecialcharsWithNL($report -> getStatus())}</td>
                            <td><span class="glyphicon glyphicon-calendar"></span> {htmlspecialcharsWithNL($report -> getDate())}</td>
                            <td>
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                       aria-haspopup="true"
                                       aria-expanded="false"></span>
                                        Kies</span><span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="/AdminUser/action=check/id={$report->getId()}">Bekijken</a></li>
                                        <li><a href="/AdminUser/action=block/id={$report->getId()}">Blokkeren</a></li>
                                        <li><a href="/AdminUser/action=delete/id={$report->getId()}">Verwijderen</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    {/foreach}
                {/if}

                </tbody>
            </table>
        </div>

    </div>
</div>