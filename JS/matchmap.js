/**
 * Created by Marius on 1-3-2016.
 */
var geo = new google.maps.Geocoder();
var map = null;
// SVG drawing area
matchmap = function (_parentElement, _data) {

    var vis = this;
    this.margin = {top: 40, right: 40, bottom: 40, left: 40};

    this.width = 1000 - this.margin.left - this.margin.right,
        this.height = 500 - this.margin.top - this.margin.bottom,
        this.scale0 = (this.width - 1) / 2 / Math.PI;

    this.svg = d3.select("#map").append("svg")
        .attr("width", this.width + this.margin.left + this.margin.right)
        .attr("height", this.height + this.margin.top + this.margin.bottom)


    this.svg.append("rect")
        .attr("width", this.width + this.margin.left + this.margin.right)
        .attr("height", this.height + this.margin.top + this.margin.bottom)
        .attr("x", 0)
        .attr("y", 0);

    this.svg = this.svg.append("g")
        .attr("transform", "translate(" + this.margin.left + "," + this.margin.top + ")");

    this.projection = d3.geo.mercator()
        .translate([this.width / 2, this.height / 2]);

    this.path = d3.geo.path()
        .projection(this.projection);

    this.count = 0;



    this.zoom = d3.behavior.zoom()
        .translate([this.width / 2, this.height / 2])
        .scale(this.scale0)
        .scaleExtent([this.scale0, 8 * this.scale0])
        .on("zoom", this.zoomed);

    map = this;
    this.data = [
        {"from": 'Den Bosch, nl', "to": 'Utrecht, nl', "strength": 6},
        {"from": 'Den Bosch, nl', "to": 'Boston, us', "strength": 2},
        {"from": 'Den Bosch, nl', "to": 'Amsterdam, nl', "strength": 8},
        {"from": 'Den Bosch, nl', "to": 'Rosmalen, nl', "strength": 8},
        {"from": 'Amsterdam, nl', "to": 'Groesbeek, nl', "strength": 8},
        {"from": 'Amsterdam, nl', "to": 'leeuwarden, nl', "strength": 8},
        {"from": 'Zeeland, nl', "to": 'hoofddorp, nl', "strength": 8},
        {"from": 'Den Bosch, nl', "to": 'london, uk', "strength": 1},
        {"from": 'london, uk', "to": 'bath, uk', "strength": 1}
    ];

    vis.showlines = true;

    this.parseData();


};
var address;
matchmap.prototype.parseData = function () {

    var vis = this;
    var geocoder = new google.maps.Geocoder();

    vis.cities = [];
    vis.delay = 100;
    var lookup = [];
    address = [];

    this.data.forEach(function (d) {

        // make lookup table with all different cities
        if (lookup[d.from] == null) {
            lookup[d.from] = true;
            vis.cities.push({"city": d.from, "lat": 0, "lon": 0});
            address.push(d.from);
        }
        if (lookup[d.to] == null) {
            lookup[d.to] = true;
            vis.cities.push({"city": d.to, "lat": 0, "lon": 0});
            address.push(d.to);
        }

    });
    // get lat-lon for those cities

    vis.nextAddress = 0;


    // ======= Call that function for the first time =======
    theNext();


};

function theNext() {

    if (map.nextAddress < address.length) {
        setTimeout('map.getAddress("' + address[map.nextAddress] + '",theNext)', map.delay);
        map.nextAddress++;
    } else {
        map.call();

    }
}

matchmap.prototype.getAddress = function (search, next) {
    geo.geocode({address: search}, function (results, status) {
            // If that was successful
            if (status == google.maps.GeocoderStatus.OK) {
                // Lets assume that the first marker is the one we want
                var p = results[0].geometry.location;
                var lat = p.lat();
                var lng = p.lng();
                // Output the data

                map.cities.forEach(function (d) {
                    if (d.city == search) {
                        d.lat = lat;
                        d.lon = lng;
                    }

                })
            }
            // ====== Decode the error status ======
            else {
                // === if we were sending the requests to fast, try this one again and increase the delay
                if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
                    nextAddress--;
                    delay++;
                } else {
                    console.log("MAPS ERROR. ERROR-CODE: " + status + ". On city: " + search);

                }
            }
            next();
        }
    );
};

matchmap.prototype.call = function () {
    var vis = this;

    // connect the two again

    vis.newd = [];
    this.data.forEach(function (d) {

        // set from
        vis.cities.forEach(function (e) {
            if (d.from == e.city) {
                d.from_lat = e.lat;
                d.from_lon = e.lon;

            }
            if (d.to == e.city) {
                d.to_lat = e.lat;
                d.to_lon = e.lon;

            }
        });

        vis.newd.push(d);

        while (d.strength >= 1) {
            var nd = JSON.parse(JSON.stringify(d));

            nd.from_lat += ((Math.random() - Math.random()) / 30);
            nd.from_lon += ((Math.random() - Math.random()) / 30);

            nd.to_lat += ((Math.random() - Math.random()) / 30);
            nd.to_lon += ((Math.random() - Math.random()) / 30);
            vis.newd.push(nd);
            d.strength--;
        }

    });


    this.data = this.newd;
    this.updateVisualization();
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

            vis.data.forEach(function (d) {
                vis.links.push({
                    type: "LineString",
                    coordinates: [
                        [d.from_lon, d.from_lat],
                        [d.to_lon, d.to_lat]
                    ],
                    name: d.from + d.to,
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

            vis.svg
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

    if(map.showlines)
    {
        if(map.pathArcs == null)
        {
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
    else{
        map.pathArcs.remove();
        map.pathArcs = null;
    }

};

