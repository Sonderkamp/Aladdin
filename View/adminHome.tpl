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
                <li><a href="#tab2" data-toggle="tab">Matches</a></li>
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
                    <input type="checkbox" value="Yes" checked onclick="toggleLines()"> Matches


                    <div id="map"></div>
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
<script src="/JS/main.js"></script>