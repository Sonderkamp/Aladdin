<div id="slide-panel">
    <article id="content">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore
        magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
        pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
        laborum.
    </article>
</div>
<div class="container">
    <h1>{$title}</h1>

    <div class="col-md-2">
        <ul class="nav nav-pills nav-stacked">
            <li><a href="#tab1" data-toggle="tab">Gebruiker</a></li>
            <li><a href="#tab2" data-toggle="tab">Wensen</a></li>
        </ul>
    </div>
    <div class="col-md-10">
        <div class="tab-content">
            <div class="tab-pane fade in active" id="tab1">
                <div class="btn-group chart" role="group" aria-label="...">
                    <button type="button" onclick="setXaxis(0);" class="btn btn-default">Wensen</button>

                </div>

                <div id="chart-area"></div>
                <p> Datum bereik: <span id="mindate">2013-1-1</span> - <span id="maxdate">2013-1-1</span> <span
                            class="slide-object"> <input class="slider" id="slider" type="text" class="span2">
            </div>
            <div class="tab-pane fade in active" id="tab2">
                <div class="btn-group chart" role="group" aria-label="...">

                    <button type="button" onclick="setXaxis(1);" class="btn btn-default">Gebruikers</button>

                </div>

                <div id="chart-area"></div>
                <p> Datum bereik: <span id="mindate">2013-1-1</span> - <span id="maxdate">2013-1-1</span> <span
                            class="slide-object"> <input class="slider" id="slider" type="text" class="span2">
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.14/d3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3-tip/0.6.7/d3-tip.min.js"></script>
<script src="js/queue.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/6.0.16/bootstrap-slider.min.js"></script>
<script src="/JS/wishchart.js"></script>
