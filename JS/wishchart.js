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
var formatDateUser = d3.time.format("%Y%m");
var dateRender = d3.time.format("%d-%m-%Y");

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
        .defer(d3.csv, "/admin/csv=usersMonth")
        .await(function (error, wishes, users) {

            wishes.forEach(function (d) {
                // Convert string to 'date object'
                d.date = formatDate.parse(d.date);
                // Convert numeric values to 'numbers'
                d.amount = +d.amount;
            });

            users.forEach(function (d) {
                // Convert string to 'date object'
                d.date = formatDateUser.parse(d.date);
                // Convert numeric values to 'numbers'
                d.amount = +d.amount;
            });

            // Store csv data in global variable
            data = [
                {
                    "data": wishes.sort(function (a, b) {
                        return new Date(a.date) - new Date(b.date);
                    }),
                    "name": "Aantal nieuwe wensen",
                    "suffix": "nieuwe wensen",
                    "info": wishinfo,
                    "slider": wishinfo,
                    "min": wishes[0].date,
                    "max":wishes[wishes.length - 1].date,
                },
                {
                    "data": users.sort(function (a, b) {
                        return new Date(a.date) - new Date(b.date);
                    }),
                    "name": "Nieuwe gebruikers afgelopen 12 maanden",
                    "suffix": "nieuwe gebruikers",
                    "info": userinfo,
                    "slider": wishinfo,
                    "min": new Date((new Date()).setFullYear(new Date().getFullYear() - 1)),
                    "max":(new Date())
                }];

            addtoday(data[0]);



            initSlider();
            // Draw the visualization for the first time
            updateVisualization();
        });
}


function addtoday(data) {

    if (formatDate(data.data[data.data.length - 1].date) != formatDate(new Date())) {
        data.data.push({"date": formatDate.parse(formatDate(new Date())), "amount": 0});
    }

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

        if (values != data[value].data) {
            values = [];

            // reset path
            if (lineDOM == null) {
                // create lineDOM
                lineDOM = svg.append("path")
                    .attr("class", "line");
            }
            else {
                // remove line
                lineDOM.attr("d", null);
            }

            // move points offscreen
            svg.selectAll("circle").data(values, function (d) {
                    return d.date
                })
                .exit()
                .transition()
                .duration(500)
                .attr("cy", function (d) {
                    return -200;
                })
                .remove()
                .each("end", function () {

                    initSlider();
                    updateVisualization();
                });
        }
    }

}

function addDays(date, days) {
    var result = new Date(date);
    result.setDate(result.getDate() + days);
    return result;
}

function initSlider() {

    min = data[value].min;
    max = data[value].max;

    lowFilter = min;
    highFilter = max;

    var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds

    var diffDays = Math.round(Math.abs((min.getTime() - max.getTime()) / (oneDay)));

    $("#mindate").text(dateRender(lowFilter));
    $("#maxdate").text(dateRender(highFilter));

    slider = $("#slider").slider({min: 0, max: diffDays, value: [0, diffDays], focus: true, formatter: sliderFormat});
    slider.on('slideStop', function (inp) {
        oldLow = lowFilter;
        oldHigh = highFilter;

        lowFilter = addDays(min, inp.value[0]);
        highFilter = addDays(min, inp.value[1]);

        $("#mindate").text(dateRender(lowFilter));
        $("#maxdate").text(dateRender(highFilter));
        updateVisualization();
    });
}

function sliderFormat(inp1) {
    return dateRender(addDays(min, inp1[0])) + " - " + dateRender(addDays(min, inp1[1]));
}

var value = 0;
var label;
var values;
// Render visualization
function updateVisualization() {


    values = data[value].data;


    // remove values outside range boundries
    values = values.filter(function (d) {
        var date = new Date(d.date);
        return (lowFilter <= date && date <= highFilter);
    });


    // create tip
    if (tip == null) {
        tip = d3.tip().attr('class', 'd3-tip').html(function (d) {
                return d.amount + " " + data[value].suffix + "<br><br> klik voor meer info";
            })
            .offset([-10, 0]);
        svg.call(tip);
    }


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
        .domain([lowFilter, highFilter])
        .range([7, width]);

    var yAxis = d3.svg.axis()
        .scale(y)
        .orient("left");

    var xAxis = d3.svg.axis()
        .scale(x)
        .orient("bottom")
        .ticks(4);


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
        })
        .exit()
        .transition()
        .duration(500)
        .attr("cx", function (d) {
            if (new Date(d.date) > highFilter)
                return width + 200;
            return -200;
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
            return -500;
        })
        .attr("class", "tooltip-circle")
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
            tip.show(d);
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
            tip.hide(d);
        })
        .on("click", data[value].info);


    // UPDATE points
    svg.selectAll("circle").data(values, function (d) {
            return d.date
        })
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
        .each("end", function () {
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
        .duration(500)
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
        .duration(500)
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

function wishinfo(d) {
    var panel = $('#slide-panel');
    if (panel.hasClass("visible")) {
        panel.removeClass('visible').animate({'margin-right': '-500px'});
        $('#content').css({'margin-left': '0px'});
    } else {
        panel.addClass('visible').animate({'margin-right': '0px'});
        $('#content').css({'margin-left': '-500px'});
    }
    return false;
}

function userinfo(d) {
    var panel = $('#slide-panel');
    if (panel.hasClass("visible")) {
        panel.removeClass('visible').animate({'margin-right': '-500px'});
        $('#content').css({'margin-left': '0px'});
    } else {
        panel.addClass('visible').animate({'margin-right': '0px'});
        $('#content').css({'margin-left': '-500px'});
    }
    return false;
}


Date.prototype.dateAdd = function(size,value) {
    value = parseInt(value);
    var incr = 0;
    switch (size) {
        case 'day':
            incr = value * 24;
            this.dateAdd('hour',incr);
            break;
        case 'hour':
            incr = value * 60;
            this.dateAdd('minute',incr);
            break;
        case 'week':
            incr = value * 7;
            this.dateAdd('day',incr);
            break;
        case 'minute':
            incr = value * 60;
            this.dateAdd('second',incr);
            break;
        case 'second':
            incr = value * 1000;
            this.dateAdd('millisecond',incr);
            break;
        case 'month':
            value = value + this.getUTCMonth();
            if (value/12>0) {
                this.dateAdd('year',value/12);
                value = value % 12;
            }
            this.setUTCMonth(value);
            break;
        case 'millisecond':
            this.setTime(this.getTime() + value);
            break;
        case 'year':
            this.setFullYear(this.getUTCFullYear()+value);
            break;
        default:
            throw new Error('Invalid date increment passed');
            break;
    }
}