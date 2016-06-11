/**
 * Created by Marius on 8-6-2016.
 */
var map2 = null;
// SVG drawing area
userMap = function (_parentElement, _data) {

    var vis = this;

    queue()
        .defer(d3.csv, "/admin/csv=userLocation")
        .await(function (error, matches) {


            vis.margin = {top: 40, right: 40, bottom: 40, left: 40};

            vis.width = 1000 - vis.margin.left - vis.margin.right,
                vis.height = 500 - vis.margin.top - vis.margin.bottom,
                vis.scale0 = (vis.width - 1) / 2 / Math.PI;

            vis.svg = d3.select("#map2").append("svg")
                .attr("width", vis.width + vis.margin.left + vis.margin.right)
                .attr("height", vis.height + vis.margin.top + vis.margin.bottom);


            vis.total = vis.svg.append("g");

            vis.total.append("rect")
                .attr("width", vis.width + vis.margin.left + vis.margin.right)
                .attr("height", vis.height + vis.margin.top + vis.margin.bottom)
                .attr("x", 0)
                .attr("y", 0)
                .attr("class", "backgroundMap");

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

            map2 = vis;
            vis.data = matches;


            vis.showlines = true;

            vis.call();

        });
};


userMap.prototype.call = function () {
    var vis = this;
    vis.updateVisualization();
};

// Render visualization
userMap.prototype.updateVisualization = function () {


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

            var map2 = vis.svg.append("g");
            vis.lines = vis.svg.append("g");
            // Render the world atlas by using the path generator
            var feature = map2.selectAll("path")
                .data(countries)
                .enter()
                .append("path")
                .attr("d", vis.path)
                .attr("class", "world");


            var range = d3.extent(vis.data, function (d) {
                return d.count;
            });

            // create range from red to green
            vis.size = d3.scale.linear()
                .domain([range[0], range[1]])
                .range([2, 8]);

            // tooltip
            vis.tip = d3.tip().attr('class', 'd3-tip').html(function (d) {
                    return d.city + ": " + d.count;
                })
                .offset([-10, 0]);

            vis.svg.call(vis.tip);

            console.log(vis.data);
            // add circles to svg
            vis.lines.selectAll("circle")
                .data(vis.data).enter()
                .append("circle")
                .attr("cx", function (d) {
                    return vis.projection([d.lon, d.lat])[0];
                })
                .attr("cy", function (d) {
                    return vis.projection([d.lon, d.lat])[1];
                })
                .attr("r", function (d) {
                    return vis.size(d.count);
                })
                .attr("fill", function (d) {
                    return "#80CDC1";
                })
                .style("stroke", "black")
                .style("stroke-width", 0.3)
                .on("mouseover", function (d) {
                    d3.select(this)
                        .transition()
                        .duration(100)
                        .attr("r", function (d) {
                            return vis.size(d.count) + 3;
                        });
                    vis.tip.show(d);
                })
                .on("mouseout", function (d) {
                    d3.select(this)
                        .transition()
                        .duration(100)
                        .attr("r", function (d) {
                            return vis.size(d.count);
                        });
                    vis.tip.hide(d);
                });


            vis.total
                .call(vis.zoom)
                .call(vis.zoom.event);

        });


};


userMap.prototype.zoomed = function () {

    map2.projection
        .translate(map2.zoom.translate())
        .scale(map2.zoom.scale());

    map2.svg.selectAll("path")
        .attr("d", map2.path);

    // add circles to svg
    map2.lines.selectAll("circle")
        .data(map2.data)
        .attr("cx", function (d) {
            return map2.projection([d.lon, d.lat])[0];
        })
        .attr("cy", function (d) {
            return map2.projection([d.lon, d.lat])[1];
        });

};

