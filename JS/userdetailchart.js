/**
 * Created by Marius on 1-3-2016.
 */
// SVG drawing area
detailchart = function (_parentElement, _data) {
    this.margin = {top: 40, right: 40, bottom: 40, left: 40};

    this.width = 300 - this.margin.left - this.margin.right,
        this.height = 300 - this.margin.top - this.margin.bottom;

    this.svg = d3.select("#detailchart").append("svg")
        .attr("width", this.width + this.margin.left + this.margin.right)
        .attr("height", this.height + this.margin.top + this.margin.bottom)
        .append("g")
        .attr("transform", "translate(" + this.margin.left + "," + this.margin.top + ")");

    this.tip = null;
    this.arcs = null;


};

detailchart.prototype.removeVis = function () {
    var vis = this;
    if (vis.arcs != null)
        vis.arcs.remove();
};

// Render visualization
detailchart.prototype.updateVisualization = function (dataset, date) {

    var vis = this;
    //Width and height
    //var dataset = [ {"value" : 5, "cat" : "elder"}, {"value" : 5, "cat" : "young"}, {"value" : 5, "cat" : "parent"},  ];

    vis.tip = d3.tip().attr('class', 'd3-tip').html(function (d, i) {
            return d.data.name + ": " + d3.format("%")(d.value / total);
        })
        .offset([-10, 0]);
    vis.svg.call(vis.tip);


    var total = 0;
    $.each(dataset, function () {
        total += this.value;
    });


    if (vis.arcs != null)
        vis.arcs.remove();

    if (total == 0) {
        return;
    }

    var outerRadius = vis.width / 2;
    var innerRadius = 0;
    var arc = d3.svg.arc()
        .innerRadius(innerRadius)
        .outerRadius(outerRadius);

    var pie = d3.layout.pie().value(function (d) {
        return d.value;
    });

    //Easy colors accessible via a 10-step ordinal scale
    var color = d3.scale.category10();

    var olddata = dataset;
    dataset = pie(dataset);
    //Set up groups
    vis.arcs = vis.svg.selectAll("g.arc")
        .data(dataset)
        .enter()
        .append("g")
        .attr("class", "arc")
        .attr("transform", "translate(" + outerRadius + "," + outerRadius + ")")
        .on("mouseover", function (d) {
            vis.tip.show
        })
        .on("mouseout", vis.tip.hide);


    //Draw arc paths
    vis.arcs.append("path")
        .attr("fill", function (d, i) {
            return color(i);
        })
        .attr("d", arc)
        .on("mouseover", vis.tip.show)
        .on("mouseout", vis.tip.hide);


    this.setText(olddata, date);

};
detailchart.prototype.setText = function (dataset, date) {

    $("#Handicap").text("0");
    $("#Kind").text("0");
    $("#Volwassen").text("0");
    $("#Ouderen").text("0");
    $("#Bedrijven").text("0");

    var text = "Dit zijn alle nieuwe gebruikers voor " + d3.time.format("%m-%Y")(date);
    $("#detailText").text(text);

    dataset.forEach(function (d) {
        $("#" + d.name.split(" ")[0]).text(d.value);
    });

}