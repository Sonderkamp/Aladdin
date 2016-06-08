/**
 * Created by Marius on 1-3-2016.
 */
var map = null;
// SVG drawing area
matchmap = function (_parentElement, _data) {

    var vis = this;

    queue()
        .defer(d3.csv, "/admin/csv=matches")
        .await(function (error, matches) {


            console.log("AAA");
            console.log(matches);

            vis.margin = {top: 40, right: 40, bottom: 40, left: 40};

            vis.width = 1000 - vis.margin.left - vis.margin.right,
                vis.height = 500 - vis.margin.top - vis.margin.bottom,
                vis.scale0 = (vis.width - 1) / 2 / Math.PI;

            vis.svg = d3.select("#map").append("svg")
                .attr("width", vis.width + vis.margin.left + vis.margin.right)
                .attr("height", vis.height + vis.margin.top + vis.margin.bottom);


            vis.total = vis.svg.append("g");

            vis.total.append("rect")
                .attr("width", vis.width + vis.margin.left + vis.margin.right)
                .attr("height", vis.height + vis.margin.top + vis.margin.bottom)
                .attr("x", 0)
                .attr("y", 0);

            vis.svg = vis.total.append("g")
                .attr("transform", "translate(" + vis.margin.left + "," + vis.margin.top + ")");

            vis.projection = d3.geo.mercator()
                .translate([vis.width / 2, vis.height / 2]);

            vis.path = d3.geo.path()
                .projection(vis.projection);

            vis.count = 0;


            vis.zoom = d3.behavior.zoom()
                .translate([vis.width / 2, vis.height / 2])
                .scale(vis.scale0)
                .scaleExtent([vis.scale0, 8 * vis.scale0])
                .on("zoom", vis.zoomed);

            map = vis;
            vis.data = matches;


            vis.showlines = true;

            vis.call();

        });
};


matchmap.prototype.call = function () {
    var vis = this;
    vis.updateVisualization();
};

// Render visualization
matchmap.prototype.updateVisualization = function () {


    var vis = this;
    queue()
        .defer(d3.json, "/Resources/Data/world-110m.json")
        .defer(d3.tsv, "/Resources/Data/world-country-names.tsv")
        .await(function (error, world, names) {


            vis.linesizeScale = d3.scale.linear()
                .domain([1, 10])
                .range([0.4, 2]);

            //  Convert the TopoJSON to GeoJSON
            var geo = topojson.feature(world, world.objects.countries);
            var countries = topojson.feature(world, world.objects.countries).features;
            countries = countries.filter(function (d) {
                return names.some(function (n) {
                    if (d.id == n.id) return d.name = n.name;
                });
            }).sort(function (a, b) {
                return a.name.localeCompare(b.name);
            });

            var map = vis.svg.append("g");
            vis.lines = vis.svg.append("g");
            // Render the world atlas by using the path generator
            var feature = map.selectAll("path")
                .data(countries)
                .enter()
                .append("path")
                .attr("fill", "#17488c")
                .attr("d", vis.path)
                .attr("class", "world");

            vis.links = [];

            vis.data.forEach(function (d, i) {
                vis.links.push({
                    type: "LineString",
                    coordinates: [
                        [+d.from_lon - 0.05, +d.from_lat - 0.05],
                        [+d.to_lon + 0.05, +d.to_lat + 0.05]
                    ]
                });
            });

            vis.pathArcs = vis.lines.selectAll(".arc")
                .data(vis.links);

            //enter
            vis.pathArcs.enter()
                .append("path").attr({
                'class': 'arc'
            }).style({
                fill: 'none'
            });

            //update
            vis.pathArcs.attr({d: vis.path})
                .style("stroke-width", 0.4)
                .style("stroke", "#FFFFFF");

            vis.total
                .call(vis.zoom)
                .call(vis.zoom.event);

        });


};


matchmap.prototype.zoomed = function () {

    map.projection
        .translate(map.zoom.translate())
        .scale(map.zoom.scale());

    map.svg.selectAll("path")
        .attr("d", map.path);

    if (map.showlines) {
        if (map.pathArcs == null) {
            map.pathArcs = map.lines.selectAll(".arc")
                .data(map.links);

            //enter
            map.pathArcs.enter()
                .append("path").attr({
                'class': 'arc'
            }).style({
                fill: 'none'
            });
        }
        map.pathArcs.attr({d: map.path})
            .style("stroke-width", 0.4)
            .style("stroke", "#FFFFFF");

    }
    else {
        map.pathArcs.remove();
        map.pathArcs = null;
    }

};

