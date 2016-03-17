/**
 * Created by Marius on 1-3-2016.
 */

// SVG drawing area

var margin = {top: 40, right: 40, bottom: 60, left: 60};

var width = 600 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;

var svg = d3.select("#chart-area").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
    .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");


// Date parser (https://github.com/mbostock/d3/wiki/Time-Formatting)
var formatDate = d3.time.format("%Y-%m-%d");

var min = 0;
var max = 0;

// Initialize data
loadData();


// FIFA world cup
var data;

//// selectbox
//var select = d3.select("#ranking-type");
//select.on("change", function () {
//
//    updateVisualization();
//});


// Load CSV file
function loadData() {
    queue()
        .defer(d3.csv, "/admin/csv=wishes")
        .defer(d3.csv, "/admin/csv=users")
        .await(function (error, wishes, users) {

            wishes.forEach(function (d) {
                // Convert string to 'date object'
                d.date = formatDate.parse(d.date);
                // Convert numeric values to 'numbers'
                d.amount = +d.amount;
            });

            users.forEach(function (d) {
                // Convert string to 'date object'
                d.date = formatDate.parse(d.date);
                // Convert numeric values to 'numbers'
                d.amount = +d.amount;
            });

            // Store csv data in global variable
            data = [{"data": wishes, "name": "Aantal nieuwe wensen"}, {
                "data": users,
                "name": "Aantal nieuwe gebruikers"
            }];

            console.log(data);

            data[0].data.sort(function (a, b) {
                return new Date(a.date) - new Date(b.date);
            });

            data[1].data.sort(function (a, b) {
                return new Date(a.date) - new Date(b.date);
            });

            // todo: if today not set, set it to 0

            var yearRange = d3.extent(data[0], function (d) {
                return formatDate(d.date);
            });

            min = +yearRange[0];
            max = +yearRange[1];
            lowFilter = min;
            highFilter = max;
            initSlider();
            // Draw the visualization for the first time
            updateVisualization();
        });
}

var yAxisDOM;
var xAxisDOM;
var x;
var y;

var lineDOM;
var line;
var lineLength;

var tip;

var lowFilter = 2;
var highFilter = 3;

var oldLow = 0;
var oldHigh = 0;

function setXaxis(val) {

    if (value != val) {
        value = val;
        updateVisualization();
    }

}

function initSlider() {
    var slider = $("#slider").slider({min: min, max: max, value: [min, max], focus: true});

    slider.on('slideStop', function (inp) {
        oldLow = lowFilter;
        oldHigh = highFilter;

        lowFilter = inp.value[0];
        highFilter = inp.value[1];
        updateVisualization();
    });
}


var value = 0;
var label;
// Render visualization
function updateVisualization() {

    var values = data[value].data;


    // remove values outside range boundries
    //values = values.filter(function (d) {
    //    var year = formatDate(d.date);
    //    return (lowFilter <= year && year <= highFilter);
    //});


    // create tip
    //if (tip == null) {
    //    tip = d3.tip().attr('class', 'd3-tip').html(function (d) {
    //            return d.EDITION + "<br>" + $("#ranking-type option:selected").html() + " " + d[value];
    //        })
    //        .offset([-10, 0]);
    //    svg.call(tip);
    //}

    // create ranges/scales/axis
    var yearRange = d3.extent(values, function (d) {
        return d.date;
    });


    var yRange = d3.extent(values, function (d) {
        return d.amount;
    });

    var upperBound = yRange[1] + (yRange[1] / 100 * 10);
    var lowerBound = yRange[0] - (yRange[1] / 100 * 10);

    if (lowerBound < 0 && yRange[0] >= 0)
        lowerBound = 0;

    y = d3.scale.linear()
        .domain([lowerBound, upperBound]) // 10% more and less
        .range([height, 0]);

    x = d3.time.scale()
        .domain([yearRange[0], yearRange[1]])
        .range([7, width]);

    var yAxis = d3.svg.axis()
        .scale(y)
        .orient("left");

    var xAxis = d3.svg.axis()
        .scale(x)
        .orient("bottom");


    if (lineDOM == null) {
        // create lineDOM
        lineDOM = svg.append("path")
            .attr("class", "line");
    }
    else {
        // remove line
        lineDOM.attr("d", null);
    }


    // REMOVE points
    svg.selectAll("circle").data(values, function (d) {
        return d.date
    }).exit()
        .transition()
        .duration(500)
        .attr("cx", function (d) {
            if (isNaN(x(d.date)))
                return 0;
            return x(d.date);
        })
        .attr("cy", function (d) {
            if (isNaN(y(d.amount)))
                return 0;
            return y(d.amount);
        })
        .remove();

    // CREATE points
    svg.selectAll("circle").data(values, function (d) {
            return d.date
        })
        .enter()
        .append("circle")
        .attr("cx", function (d) {
            if (isNaN(x(d.date)))
                return 0;
            return x(d.date);
        })
        .attr("r", 6)
        .attr("cy", function (d) {
            if (isNaN(y(d.amount)))
                return 0;
            return y(d.amount);
        })
        .attr("class", "tooltip-circle")
        .on("click", showEdition)
        .on("mouseover", function (d) {
            d3.select(this)
                .transition()
                .duration(100)
                .attr("r", 12)
                .attr("cx", function (d) {
                    if (isNaN(x(d.date)))
                        return 0;
                    return x(d.date);
                })
                .attr("cy", function (d) {
                    if (isNaN(y(d.amount)))
                        return 0;
                    return y(d.amount);
                });
            //tip.show(d);
        })
        .on("mouseout", function (d) {
            d3.select(this)
                .transition()
                .duration(100)
                .attr("r", 6)
                .attr("cx", function (d) {
                    if (isNaN(x(d.date)))
                        return 0;
                    return x(d.date);
                })
                .attr("cy", function (d) {
                    if (isNaN(y(d.amount)))
                        return 0;
                    return y(d.amount);
                });
            //tip.hide(d);
        })
        .transition()
        .duration(800)
        .attr("cx", function (d) {
            if (isNaN(x(d.date)))
                return 0;
            return x(d.date);
        });

    // UPDATE points
    svg.selectAll("circle").data(values, function (d) {
            return d.date
        })
        .transition()
        .duration(800)
        .attr("cx", function (d) {
            if (isNaN(x(d.date)))
                return 0;
            return x(d.date);
        })
        .attr("cy", function (d) {
            if (isNaN(y(d.amount)))
                return 0;
            return y(d.amount);
        }).each("end", function () {
        drawLine(values)
    });

    // don't update axis if no value is in range
    if (values.length == 0)
        return;

    if (yAxisDOM == null) {
        yAxisDOM = svg.append("g")
            .attr("class", "axis y-axis");
    }
    yAxisDOM
        .transition()
        .duration(800)
        .call(yAxis);

    if (xAxisDOM == null) {
        xAxisDOM = svg.append("g")
            .attr("class", "axis x-axis")
            .attr("transform", "translate(0," + height + ")")

        label = svg.append("text")
            .attr("x", width / 2 - 40)
            .attr("y", 30)
            .html("");
    }
    label.html(data[value].name);
    xAxisDOM
        .transition()
        .duration(800)
        .call(xAxis);


}

function drawLine(values) {

    // create line structure
    line = d3.svg.line()
        .x(function (d) {
            return x(d.date);
        })
        .y(function (d) {
            return y(d.amount);
        })
        .interpolate("linear");

    if (lineDOM == null)
        return;

    lineLength = lineDOM.node().getTotalLength();

    // draw line
    lineDOM.datum(values)
        .attr("d", line)
        .attr("stroke-dasharray", lineLength)
        .attr("stroke-dashoffset", lineLength)
        .transition()
        .duration(800)
        .attr("stroke-dashoffset", 0);

}

//// Show details for a specific FIFA World Cup
function showEdition(d) {


    if ($("#title").text() == d.EDITION) {
        $("#info").toggleClass("hidden")
    }
    else
        $("#info").removeClass("hidden");

    $("#title").text(d.EDITION);
    $("#winner").text(d.WINNER);
    $("#year").text(formatDate(d.YEAR));
    $("#goals").text(d.GOALS);
    $("#avgGoals").text(d.AVERAGE_GOALS);
    $("#matches").text(d.MATCHES);
    $("#teams").text(d.TEAMS);
    $("#avgAttendance").text(d.AVERAGE_ATTENDANCE);
    $("#location").text(d.LOCATION);

}
