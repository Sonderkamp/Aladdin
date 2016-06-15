<div class="container">
 <span class="info infoButtonMargin">
            <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#InfoModal">
                <span class="glyphicon glyphicon-info-sign"></span>
            </button>
            </span>
</div>

<div class="noflow">
    <div id="slide-panel">
        <button class="btn btn-default" onclick="userinfo(null)">Terug</button>
        <br><br>
        <article id="content">
         <span class="h4" id="detailText">
             Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore
        magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
        pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
        laborum.
        </span>


            <div id="detailchart">
            </div>

            <table class="table">
                <thead>
                <tr>
                    <th>Categorie</th>
                    <th>Aantal</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Handicap</td>
                    <td id="Handicap"></td>
                </tr>
                <tr>
                    <td>Volwassen</td>
                    <td id="Volwassen"></td>
                </tr>
                <tr>
                    <td>Kinderen</td>
                    <td id="Kind"></td>
                </tr>
                <tr>
                    <td>Ouderen</td>
                    <td id="Ouderen"></td>
                </tr>
                </tbody>
            </table>

        </article>
    </div>
    <div class="container">
        <h1>{$title}</h1>

        <div class="col-xs-2">
            <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#tab1" data-toggle="tab">Gebruiker</a></li>
                <li><a href="#tab4" data-toggle="tab">Gebruikers locaties</a></li>
                <li><a href="#tab2" data-toggle="tab">Matches</a></li>
                <li><a href="#tab3" data-toggle="tab" onclick="sort()">Donaties</a></li>
            </ul>
        </div>
        <div class="col-xs-10">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab1">
                    <div class="btn-group chart" role="group" aria-label="...">
                        <button type="button" onclick="newUsers();" class="btn btn-default">Nieuwe Gebruikers</button>
                        <button type="button" onclick="perCat();" class="btn btn-default">Categorie</button>
                        <button type="button" onclick="perAge();" class="btn btn-default">Leeftijd</button>

                    </div>

                    <div id="chart-area"></div>
                    <p><span id="dateinfo">Datum bereik: 2013-1-1 - 2013-1-1</span>  <span id="slideObject"
                                                                                           class="slide-object">
                </div>

                <div class="tab-pane fade" id="tab2">
                    <div id="map"></div>
                </div>
                <div class="tab-pane fade" id="tab4">
                    <div id="map2"></div>
                </div>
                <div class="tab-pane fade" id="tab3">

                    {if count($donations) > 0}
                        <table class="table sortable">
                            <thead>
                            <tr>
                                <th id="first">Datum</th>
                                <th>Persoon</th>
                                <th>Hoeveelheid</th>
                                <th>Beschrijving</th>
                                <th>IP</th>

                            </tr>
                            </thead>
                            <tbody>
                            {foreach from=$donations item=donation}
                                <tr>
                                    <td>{$donation->date}</td>
                                    {if $donation->anonymous == 1}
                                        <td>Anoniem</td>
                                    {elseif $donation->name !== null}
                                        <td>{$donation->name}</td>
                                    {else}
                                        <td>
                                            <a href="/AdminUser/action=showProfile/email={$donation -> user -> email}">
                                                <span class="glyphicon glyphicon-user"></span> {htmlspecialcharsWithNL($donation -> user -> displayName)}
                                            </a></td>
                                    {/if}
                                    <td>&euro;{number_format($donation->amount, 2, ',', ' ')}</td>
                                    <td>{$donation->description}</td>
                                    <td>{$donation->IP}</td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    {else}
                        <h6 class="text-center">Er zijn nog geen donaties via de site uitgevoerd.</h6>
                    {/if}
                </div>

            </div>
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
                <h4 class="modal-title">Statistieken</h4>
            </div>
            <div class="modal-body">

                <p>
                    Op deze pagina kun je de statistieken zien van:<br><br>
                    <a><b>Gebruiker</b></a><br>
                    - Aantal nieuwe gebruikers<br>
                    - Aantal gebruikers per categories (kind/volwassen)<br>
                    - Aantal gebruikers per leeftijd<br><br>

                    <a><b>Gebruikers locatie</b></a><br>
                    - Een kaart met het aantal plaatsen per gerbuiker, door met de muis over de bolletjes heen te gaan
                    worden de aantal gebruikers van dat gebied getoont.<br><br>

                    <a><b>Matches</b></a><br>
                    - Een kaart met de matches, de lijnen op de kaart zijn van de plaats van de wensen naar de plaats
                    van de vervuller<br><br>

                    <a><b>Donaties</b></a><br>
                    - Toont de gedane donaties, met datum, de donateur, het bedrag en een korte beschrijving dat de gebruiker
                    kan toevoegen tijdens het doneren.

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


<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.14/d3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3-tip/0.6.7/d3-tip.min.js"></script>
<script src="/JS/queue.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/6.0.16/bootstrap-slider.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
<script src="/JS/topojson.v1.min.js"></script>
<script src="/JS/arc.js"></script>
<script src="/JS/countryToCode.js"></script>
<script src="/JS/colorbrewer.js"></script>
<script src="/JS/userchart.js"></script>
<script src="/JS/userdetailchart.js"></script>
<script src="/JS/matchmap.js"></script>
<script src="/JS/userMap.js"></script>
<script src="/JS/main.js"></script>
<script src="/JS/Sortable.js"></script>
<script>
    function sort() {
        $("#first").click();
        $("#first").click();
    }

</script>
