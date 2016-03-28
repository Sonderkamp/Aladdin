/**
 * Created by Marius on 1-3-2016.
 */
var dateRender = d3.time.format("%d-%m-%Y");
var vis1 = null;
// SVG drawing area
userchart = function (_parentElement, _data) {
    this.margin = {top: 40, right: 40, bottom: 60, left: 60};

    this.width = 1000 - this.margin.left - this.margin.right,
        this.height = 500 - this.margin.top - this.margin.bottom;

    this.svg = d3.select("#chart-area").append("svg")
        .attr("width", this.width + this.margin.left + this.margin.right)
        .attr("height", this.height + this.margin.top + this.margin.bottom)
        .append("g")
        .attr("transform", "translate(" + this.margin.left + "," + this.margin.top + ")");

    vis1 = this;
    this.value = 0;
    this.label = null;
    this.values = null;

    this.yAxisDOM = null;
    this.xAxisDOM = null;
    this.x = null;
    this.y = null;

    this.lineDOM = null;
    this.line = null;
    this.lineLength = null;

    this.tip = null;

    this.lowFilter = 2;
    this.highFilter = 3;

    this.oldLow = 0;
    this.oldHigh = 0;

// Date parser (https://github.com/mbostock/d3/wiki/Time-Formatting)
    this.formatDate = d3.time.format("%Y-%m-%d");
    this.formatDateUser = d3.time.format("%Y%m");


    this.min = 0;
    this.max = 0;

// Initialize data
    this.loadData();
    this.data = null;


}

//// selectbox
//var select = d3.select("#ranking-type");
//select.on("change", function () {
//
//    updateVisualizationNewUser();
//});

// Load CSV file
userchart.prototype.loadData =
    function () {

        var vis = this;
        queue()
            .defer(d3.csv, "/admin/csv=cats")
            .defer(d3.csv, "/admin/csv=usersMonth")
            .defer(d3.csv, "/admin/csv=age")
            .await(function (error, cat, users, age) {

                cat.forEach(function (d) {
                    d.amount = +d.amount;
                });

                users.forEach(function (d) {
                    // Convert string to 'date object'
                    d.date = vis.formatDateUser.parse(d.date);
                    // Convert numeric values to 'numbers'
                    d.amount = +d.amount;
                });

                age.forEach(function (d) {
                    // Convert string to 'date object'
                    d.cat = +d.cat;
                    // Convert numeric values to 'numbers'
                    d.amount = +d.amount;
                });

                age.sort(function (a, b) {
                    return a.cat - b.cat;
                });

                var newarr = [];
                var prev = 0;
                age.forEach(function (d) {

                    if (prev != 0) {
                        while (prev < d.cat) {
                            newarr.push({"cat": prev, "amount": 0});
                            prev++;
                        }
                    }
                    newarr.push(d);
                    prev = d.cat + 1;
                });

                while (prev < 80) {
                    newarr.push({"cat": prev, "amount": 0});
                    prev++;
                }

                age = newarr;

                // Store csv data in global variable
                vis.data = [
                    {
                        "data": users.sort(function (a, b) {
                            return new Date(a.date) - new Date(b.date);
                        }),
                        "name": "Nieuwe gebruikers afgelopen 12 maanden",
                        "suffix": "nieuwe gebruikers",
                        "info": userinfo,
                        "slider": userinfo,
                        "min": new Date((new Date()).setFullYear(new Date().getFullYear() - 1)),
                        "max": (new Date())
                    },
                    {
                        "data": cat,
                        "name": "Gebruikers op categorie",
                        "suffix": "gebruikers"
                    },
                    {
                        "data": age,
                        "name": "Gebruikers op leeftijd",
                        "suffix": "gebruikers"
                    }];

                vis.setUser();
            });
    };


userchart.prototype.setUser = function () {
    var vis = this;
    vis.initSlider();
    vis.removeBars();
    setTimeout(function () {
        vis.updateVisualizationNewUser();
    }, 500);


};

userchart.prototype.setCategory = function () {
    var vis = this;
    vis.removeSlider();
    vis.removeUsers();
    setTimeout(function () {
        vis.updateVisualizationCategory();
    }, 500);
};

userchart.prototype.setAge = function () {
    var vis = this;
    vis.removeSlider();
    vis.removeUsers();
    setTimeout(function () {
        vis.updateVisualizationAge();
    }, 500);
};


userchart.prototype.addtoday = function (data) {

    if (this.formatDate(data.data[data.data.length - 1].date) != this.formatDate(new Date())) {
        data.data.push({"date": this.formatDate.parse(this.formatDate(new Date())), "amount": 0});
    }

};


function addDays(date, days) {
    var result = new Date(date);
    result.setDate(result.getDate() + days);
    return result;
}

userchart.prototype.initSlider = function () {
    ;
    var vis = this;
    vis.min = vis.data[vis.value].min;
    vis.max = vis.data[vis.value].max;

    vis.lowFilter = vis.min;
    vis.highFilter = vis.max;

    var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds

    var diffDays = Math.round(Math.abs((this.min.getTime() - this.max.getTime()) / (oneDay)));

    $("#dateinfo").text("Datum Bereik: " + dateRender(this.lowFilter) + " - " + dateRender(this.highFilter));

    $("#slideObject").append(' <input class="slider" id="slider" type="text" class="span2">');

    this.slider = $("#slider").slider({
        min: 0,
        max: diffDays,
        value: [0, diffDays],
        focus: true,
        formatter: this.sliderFormat
    });

    this.slider.on('slide', function (inp) {
        vis.oldLow = vis.lowFilter;
        vis.oldHigh = vis.highFilter;

        vis.lowFilter = addDays(vis.min, inp.value[0]);
        vis.highFilter = addDays(vis.min, inp.value[1]);

        $("#dateinfo").text("Datum Bereik: " + dateRender(vis.lowFilter) + " - " + dateRender(vis.highFilter));
    });

    this.slider.on('slideStop', function (inp) {

        vis.updateVisualizationNewUser();
    });
};

userchart.prototype.removeSlider = function () {

    var vis = this;
    vis.min = vis.data[vis.value].min;
    vis.max = vis.data[vis.value].max;

    vis.lowFilter = vis.min;
    vis.highFilter = vis.max;

    var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds

    var diffDays = Math.round(Math.abs((this.min.getTime() - this.max.getTime()) / (oneDay)));

    $("#dateinfo").text("");

    this.slider = null;

    $("#slideObject").empty();

};

userchart.prototype.sliderFormat = function (inp1) {
    return dateRender(addDays(vis1.min, inp1[0])) + " - " + dateRender(addDays(vis1.min, inp1[1]));
};

// Render visualization
userchart.prototype.updateVisualizationNewUser = function () {

    var vis = this;
    this.values = this.data[0].data;


    // remove values outside range boundries
    vis.values = vis.values.filter(function (d) {
        var date = new Date(d.date);
        return (vis.lowFilter <= date && date <= vis.highFilter);
    });


    // create tip
        vis.tip = d3.tip().attr('class', 'd3-tip').html(function (d) {
                return d.amount + " " + vis.data[0].suffix + "<br><br> klik voor meer info";
            })
            .offset([-10, 0]);
        vis.svg.call(vis.tip);


    var yRange = d3.extent(this.values, function (d) {
        return d.amount;
    });

    var upperBound = yRange[1] + (yRange[1] / 100 * 10);
    var lowerBound = yRange[0] - (yRange[1] / 100 * 10);

    if (lowerBound < 0 && yRange[0] >= 0)
        lowerBound = 0;


    this.y = d3.scale.linear()
        .domain([lowerBound, upperBound]) // 10% more and less
        .range([this.height, 0]);

    this.x = d3.time.scale()
        .domain([this.lowFilter, this.highFilter])
        .range([7, this.width]);

    var yAxis = d3.svg.axis()
        .scale(this.y)
        .orient("left");

    var xAxis = d3.svg.axis()
        .scale(this.x)
        .orient("bottom")
        .ticks(4);


    if (this.lineDOM == null) {
        // create lineDOM
        this.lineDOM = this.svg.append("path")
            .attr("class", "line");
    }
    else {
        // remove line
        this.lineDOM.attr("d", null);
    }


    // REMOVE points
    vis.svg.selectAll("circle").data(vis.values, function (d) {
            return d.date
        })
        .exit()
        .transition()
        .duration(500)
        .attr("cx", function (d) {
            if (new Date(d.date) > vis.highFilter)
                return vis.width + 200;
            return -200;
        })
        .remove();


    // CREATE points
    vis.svg.selectAll("circle").data(vis.values, function (d) {
            return d.date
        })
        .enter()
        .append("circle")
        .attr("cx", function (d) {
            if (isNaN(vis.x(d.date)))
                return 0;
            return vis.x(d.date);
        })
        .attr("r", 6)
        .attr("cy", function (d) {
            return -500;
        })
        .attr("class", "tooltip-circle")
        .on("mouseover", function (d) {
            d3.select(this)
                .transition()
                .duration(100)
                .attr("r", 12)
                .attr("cx", function (d) {
                    if (isNaN(vis.x(d.date)))
                        return 0;
                    return vis.x(d.date);
                })
                .attr("cy", function (d) {
                    if (isNaN(vis.y(d.amount)))
                        return 0;
                    return vis.y(d.amount);
                });
            vis.tip.show(d);
        })
        .on("mouseout", function (d) {
            d3.select(this)
                .transition()
                .duration(100)
                .attr("r", 6)
                .attr("cx", function (d) {
                    if (isNaN(vis.x(d.date)))
                        return 0;
                    return vis.x(d.date);
                })
                .attr("cy", function (d) {
                    if (isNaN(vis.y(d.amount)))
                        return 0;
                    return vis.y(d.amount);
                });
            vis.tip.hide(d);
        })
        .on("click", vis.data[0].info);


    // UPDATE points
    vis.svg.selectAll("circle").data(vis.values, function (d) {
            return d.date
        })
        .transition()
        .duration(500)
        .attr("cx", function (d) {
            if (isNaN(vis.x(d.date)))
                return 0;
            return vis.x(d.date);
        })
        .attr("cy", function (d) {
            if (isNaN(vis.y(d.amount)))
                return 0;
            return vis.y(d.amount);
        })
        .each("end", function () {
            vis.drawLine(vis.values)
        });


    // don't update axis if no value is in range
    if (vis.values.length == 0)
        return;

    if (vis.yAxisDOM == null) {
        vis.yAxisDOM = vis.svg.append("g")
            .attr("class", "axis y-axis");
    }
    vis.yAxisDOM
        .transition()
        .duration(500)
        .call(yAxis);


    if (vis.xAxisDOM == null) {
        vis.xAxisDOM = vis.svg.append("g")
            .attr("class", "axis x-axis")
            .attr("transform", "translate(0," + vis.height + ")")

    }

    if (vis.label != null)
        vis.label.remove();

    vis.label = vis.svg.append("text")
        .attr("x", vis.width / 2 - 100)
        .attr("y", 30)
        .html("");

    vis.label.html(vis.data[vis.value].name);


    vis.xAxisDOM
        .transition()
        .duration(500)
        .call(xAxis);


};

userchart.prototype.removeUsers = function () {
    var vis = this;

    if (vis.values == null)
        return;

    vis.svg.selectAll("circle").data(vis.values, function (d) {
            return d.date
        })
        .transition()
        .duration(500)
        .attr("cy", function (d) {
            return -200;
        })
        .remove();

    if (this.lineDOM == null) {
        // create lineDOM
        this.lineDOM = this.svg.append("path")
            .attr("class", "line");
    }
    else {
        // remove line
        this.lineDOM.attr("d", null);
    }

};

userchart.prototype.removeBars = function () {
    var vis = this;

    if (vis.values == null)
        return;

    vis.svg.selectAll("rect").data(vis.values, function (d) {
            return d.cat
        })
        .transition()
        .duration(500)
        .attr("height", 0)
        .attr("y", vis.height)
        .remove();

};


// Render visualization
userchart.prototype.updateVisualizationCategory = function () {


    this.drawBar(1);


};


userchart.prototype.updateVisualizationAge = function () {
    this.drawBar(2);
};


userchart.prototype.drawBar = function (i) {
    var vis = this;
    vis.values = vis.data[i].data;

    // create tip
    vis.tip = d3.tip().attr('class', 'd3-tip').html(function (d) {
            if (i == 1)
                return d.amount + " " + vis.data[i].suffix;
            else
                return d.amount + " " + vis.data[i].suffix + " zijn " + d.cat;
        })
        .offset([-10, 0]);
    vis.svg.call(vis.tip);


    vis.x = d3.scale.ordinal()
        .domain(vis.values.map(function (d) {
            return d.cat;
        }))
        .rangeRoundBands([0, vis.width], .1);


    var storeRange = d3.extent(vis.values, function (d) {
        return d.amount;
    });

    vis.y = d3.scale.linear()
        .domain([0, storeRange[1]])
        .range([vis.height, 0]);

    var yAxis = d3.svg.axis()
        .scale(vis.y)
        .orient("left");

    var xAxis = d3.svg.axis()
        .scale(vis.x)
        .orient("bottom");


    // REMOVE
    this.svg.selectAll("rect").data(vis.values, function (d) {
        return d.cat
    }).exit()
        .transition()
        .duration(500)
        .attr("height", 0)
        .attr("y", vis.height)
        .remove();


    // CREATE


    vis.svg.selectAll("rect").data(vis.values, function (d) {
            return d.cat
        })
        .enter()
        .append("rect")
        .attr("x", function (d) {
            return vis.x(d.cat);
        })
        .attr("height", 0)
        .attr("y", vis.height)
        .attr("width", function () {
            return vis.x.rangeBand();
        })
        .attr("class", "bar")
        .on("mouseover", vis.tip.show)
        .on("mouseout", vis.tip.hide)
        .transition()
        .duration(1100)
        .attr("height", function (d) {
            return vis.height - vis.y(d.amount);
        })
        .attr("y", function (d) {
            return vis.y(d.amount);
        });


    if (vis.yAxisDOM == null) {
        vis.yAxisDOM = vis.svg.append("g")
            .attr("class", "axis y-axis");
    }
    vis.yAxisDOM.transition().duration(500)
        .call(yAxis);

    if (vis.xAxisDOM == null) {
        vis.xAxisDOM = vis.svg.append("g")
            .attr("class", "axis x-axis")
            .attr("transform", "translate(0," + vis.height + ")");
    }


    if (i == 1) {
        vis.xAxisDOM
            .transition()
            .duration(500)
            .call(xAxis)
            .selectAll("text")
            .attr("transform", function (d) {
                return "rotate(-40)translate(-40,-5)";
            });
    }
    else {
        vis.xAxisDOM
            .transition()
            .duration(500)
            .call(xAxis)
            .selectAll("text")
            .attr("transform", function (d) {
                return "rotate(-80)translate(-20,-10)";
            });
    }


    if (vis.label != null)
        vis.label.remove();

    vis.label = vis.svg.append("text")
        .attr("x", vis.width / 2 - 100)
        .attr("y", 30)
        .html("");

    vis.label.html(vis.data[i].name);
};

userchart.prototype.drawLine = function (values) {

    var vis = this;
    // create line structure
    this.line = d3.svg.line()
        .x(function (d) {
            return vis.x(d.date);
        })
        .y(function (d) {
            return vis.y(d.amount);
        })
        .interpolate("linear");

    if (vis.lineDOM == null)
        return;

    this.lineLength = vis.lineDOM.node().getTotalLength();

    // draw line
    this.lineDOM.datum(values)
        .attr("d", this.line)
        .attr("stroke-dasharray", this.lineLength)
        .attr("stroke-dashoffset", this.lineLength)
        .transition()
        .duration(800)
        .attr("stroke-dashoffset", 0);


};



Date.prototype.dateAdd = function (size, value) {
    value = parseInt(value);
    var incr = 0;
    switch (size) {
        case 'day':
            incr = value * 24;
            this.dateAdd('hour', incr);
            break;
        case 'hour':
            incr = value * 60;
            this.dateAdd('minute', incr);
            break;
        case 'week':
            incr = value * 7;
            this.dateAdd('day', incr);
            break;
        case 'minute':
            incr = value * 60;
            this.dateAdd('second', incr);
            break;
        case 'second':
            incr = value * 1000;
            this.dateAdd('millisecond', incr);
            break;
        case 'month':
            value = value + this.getUTCMonth();
            if (value / 12 > 0) {
                this.dateAdd('year', value / 12);
                value = value % 12;
            }
            this.setUTCMonth(value);
            break;
        case 'millisecond':
            this.setTime(this.getTime() + value);
            break;
        case 'year':
            this.setFullYear(this.getUTCFullYear() + value);
            break;
        default:
            throw new Error('Invalid date increment passed');
            break;
    }
};