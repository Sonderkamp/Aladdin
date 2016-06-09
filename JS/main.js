/**
 * Created by Marius on 27-3-2016.
 */

var wish = new userchart();
var details = new detailchart();
var matchmap = new matchmap();
var prevdate = "";
var usermap = new userMap();

function newUsers() {
    wish.setUser();
}

function perCat() {
    wish.setCategory();

}
function perAge() {
    wish.setAge();

}

function toggleLines() {
    matchmap.showlines = !matchmap.showlines;
    matchmap.zoomed();
}


function userinfo(d) {

    var panel = $('#slide-panel');


    if (d != null) {

        var month = d.date.getMonth() + 1;
        if (month < 10) {
            month = "0" + month;
        }
        var date = d.date.getFullYear() + "" + month;

        if (date == prevdate)
            return;

        if (panel.hasClass("visible")) {
            panel.removeClass('visible').animate({'right': '-300px'});
            $('#content').css({'left': '0px'});
        }

        prevdate = date;
        d3.csv("/admin/csv=monthly/month=" + date, function (data) {

            var found = false;
            data.forEach(function (d) {
                d.value = +d.value;

                if (d.value > 0)
                    found = true;
            });


            if (found === false) {
                $("#Handicap").text("0");
                $("#Kind").text("0");
                $("#Volwassen").text("0");
                $("#Ouderen").text("0");

                var text = "Geen data voor: " + d3.time.format("%m-%Y")(d.date);
                $("#detailText").text(text);
                details.removeVis();

            }
            else {
                details.updateVisualization(data, d.date);
            }


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


var checkbrowser = function () {
    // Opera 8.0+
    var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
    // Firefox 1.0+
    var isFirefox = typeof InstallTrigger !== 'undefined';
    // At least Safari 3+: "[object HTMLElementConstructor]"
    var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
    // Internet Explorer 6-11
    var isIE = /*@cc_on!@*/false || !!document.documentMode;
    // Chrome 1+
    var isChrome = !!window.chrome && !!window.chrome.webstore;
    // Blink engine detection

    var ua = navigator.userAgent;
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Mobile|mobile/i.test(ua)) {
        swal(
            {
                type: "error",
                title: "Deze pagina is gebouwd voor desktop.",
                text: "Deze pagina werkt het beste op een desktop computer. Je mag de site natuurlijk wel openenen op telefoon.",
                showConfirmButton: true
            }
        );
    }
    else if (!isChrome && !isFirefox && !isOpera && !isSafari) {
        swal(
            {
                type: "error",
                title: "Deze pagina is gebouwd voor andere browsers.",
                text: "Deze pagina werkt het beste op een andere browser. Probeer Google Chrome, Mozilla Firefox of Opera. Je mag de site natuurlijk wel openenen op deze browser.",
                showConfirmButton: true
            }
        );
    }

};

checkbrowser();