<div class="container">
    <div class="row">
        <h5>Gebruikersbeheer</h5>
        <div class="col-xs-12 col-md-2 col-sm-2 col-lg-2">
            <ul class="nav nav-pills nav-stacked">


                {*<li {if $current eq 'home'}class="active"{/if}><a href="AdminUser/action=home" data-toggle="tab">Onbehandelde*}
                {*rapportages</a></li>*}
                {*<li {if $current eq 'handled'}class="active"{/if}><a href="AdminUser/action=handled" data-toggle="tab">Behandelde*}
                {*rapportages</a></li>*}
                {if isset($current)}

                    <li {if $current == "home"} class="active" {/if}><a href="AdminUser/action=home" >Nieuwe rapportages</a></li>
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
                            <td>{htmlspecialcharsWithNL($report -> getReporter())}</td>
                            <td>{htmlspecialcharsWithNL($report -> getMessage())}</td>
                            <td>{htmlspecialcharsWithNL($report -> getReported())}</td>
                            <td>{htmlspecialcharsWithNL($report -> getStatus())}</td>
                            <td>{htmlspecialcharsWithNL($report -> getDate())}</td>
                            <td>
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                       aria-haspopup="true"
                                       aria-expanded="false"></span>
                                        Kies</span><span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="/AdminUser/action=/wish_id={$wish->id}">Bekijken</a></li>
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