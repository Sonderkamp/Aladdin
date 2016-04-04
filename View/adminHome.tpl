<div class="container">
    <h1>{$title}</h1>

    <div class="col-xs-12 col-md-6">
        <div class="btn-group chart" role="group" aria-label="...">
            <button type="button" onclick="setXaxis(1);" class="btn btn-default">Gebruikers</button>
            <button type="button" onclick="setXaxis(0);" class="btn btn-default">Wensen</button>
            <span class="filter info"> <input class="slider" id="slider" type="text" class="span2"
                                                            value=""/> </span>

        </div>


        <div id="chart-area"></div>
       <p> Datum bereik: <span id="mindate">2013-1-1</span> - <span id="maxdate">2013-1-1</span> </p>
    </div>
    <div class="col-md-1"></div>
    <div class="col-xs-12 col-md-5 col-md-offset-1 toppad">
        <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut malesuada faucibus ornare. Sed gravida et lacus et
            feugiat. Donec ullamcorper facilisis ultrices. Donec elementum finibus odio. Nulla in vestibulum augue. Integer
            vehicula accumsan auctor. Vivamus porta eget sapien non suscipit. Curabitur pretium mattis accumsan. Class
            aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec quam dolor,
            dignissim sit amet commodo a, pulvinar ac massa. Sed bibendum gravida lorem non pretium. Fusce ac placerat nisi,
            sed mattis est. Phasellus rhoncus eros imperdiet, varius urna non, malesuada lacus. Aenean id justo condimentum,
            laoreet lorem ac, sodales elit. Nulla dapibus lobortis suscipit.</p>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.14/d3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3-tip/0.6.7/d3-tip.min.js"></script>
<script src="js/queue.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/6.0.16/bootstrap-slider.min.js"></script>
<script src="/JS/adminchart.js"></script>