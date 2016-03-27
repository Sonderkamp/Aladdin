/**
 * Created by Marius on 27-3-2016.
 */

var wish = new userchart();
var details = new detailchart();
var prevdate = "";



function newUsers() {
    wish.setUser();
}

function perCat() {
    wish.setCategory();

}
function perAge() {
    wish.setAge();

}


function userinfo(d) {

    console.log(d);
    var panel = $('#slide-panel');

    if (d != null) {
        var month = d.date.getMonth() + 1;
        if (month < 10) {
            month = "0" + month;
        }
        var date = d.date.getFullYear() + "" + month;

        if(date == prevdate)
            return;

        if (panel.hasClass("visible")) {
            panel.removeClass('visible').animate({'right': '-300px'});
            $('#content').css({'left': '0px'});
        }

        prevdate = date;
        d3.csv("/admin/csv=monthly/month=" + date, function (data) {

            data.forEach(function (d) {
                d.value = +d.value;
            });

            console.log(data);

            details.updateVisualization(data, d.date);



            if (!panel.hasClass("visible")) {
                panel.addClass('visible').animate({'right': '0px'});
                $('#content').css({'left': '-300px'});
            }


        })
    }
    else if (panel.hasClass("visible")) {
        panel.removeClass('visible').animate({'right': '-300px'});
        $('#content').css({'left': '0px'});
        prevdate = "";
    }
    return false;
}