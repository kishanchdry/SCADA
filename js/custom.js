setInterval(function() { $("#notification").hide(); }, 5000);


$("#unit-select").click(function() {
    $(".MultiSelectList").toggle();
});



$(document).on('click', function(event) {
    $("#MultiSelectList").css('display', "none");
});
$('.button').on('click', function(event) {
    event.stopPropagation();
});




function DateToday() {
    var d = new Date();
    var month = d.getMonth() + 1;
    var day = d.getDate();

    var datetoday = d.getFullYear() + '-' +
        (('' + month).length < 2 ? '0' : '') + month + '-' +
        (('' + day).length < 2 ? '0' : '') + day;

    return datetoday;

}



var export_excel = (function() {
    var uri = 'data:application/vnd.ms-excel;base64,',
        template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
        base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) },
        format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
    return function(table, name) {
        if (!table.nodeType) table = document.getElementById(table)
        var ctx = { worksheet: 'Report_' + DateTime() || 'Report_' + DateTime(), table: table.innerHTML }
        window.location.href = uri + base64(format(template, ctx))
    }
})()




$('table tr th').each(function() {
    $(this).html('<strong>' + $(this).text() + '</strong>')
});


function getPDF(table) {


    var sTable = document.getElementById(table).innerHTML;

    var style = "<style>";
    style = style + "table {width: 100%;font: 17px Calibri;}";
    style = style + "table, th, td {border: solid 1px #DDD; border-collapse: collapse;";
    style = style + "padding: 2px 3px;text-align: center;}";
    style = style + "</style>";

    // CREATE A WINDOW OBJECT.
    var win = window.open('', '', 'height=700,width=700');

    win.document.write('<html><head>');
    //win.document.write("<h3style='text-align:left;'>Report_" + DateToday() + ".pdf</h3>"); // <title> FOR PDF HEADER.
    win.document.write(style); // ADD STYLE INSIDE THE HEAD TAG.
    win.document.write('</head>');
    win.document.write('<body>');
    win.document.write('<br><br><table style="border:none;" class="table table-bordered table-sm" ><tr><td style="text-align:left;border:none;"><img style="width:100px;" src="http://www.balotracetp.com/SCADA/CETP%20Balotra%20Logo.jpg" /></td><td style="border:none;"><h3 style="text-align:center;">Report ' + DateTime() + '</h3></td><td style="text-align:right;border:none;"><img style="width:100px;" src="http://www.balotracetp.com/SCADA/img/logo.jpg" /></td></tr></table><br><br>');
    win.document.write('<table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">' + sTable + '</table>'); // THE TABLE CONTENTS INSIDE THE BODY TAG.
    win.document.write('</body></html>');

    win.document.close(); // CLOSE THE CURRENT WINDOW.

    win.print(); // PRINT THE CONTENTS.
}



/// current date time

function DateTime() {

    var D = new Date();
    var W = '';
    var M = '';


    switch (D.getDay()) {
        case 0:
            W = "Sun";
            break;
        case 1:
            W = "Mon";
            break;
        case 2:
            W = "Tue";
            break;
        case 3:
            W = "Wed";
            break;
        case 4:
            W = "Thu";
            break;
        case 5:
            W = "Fri";
            break;
        case 6:
            W = "Sat";
    }

    switch (D.getMonth()) {
        case 0:
            M = "Jan";
            break;
        case 1:
            M = "Feb";
            break;
        case 2:
            M = "March";
            break;
        case 3:
            M = "April";
            break;
        case 4:
            M = "May";
            break;
        case 5:
            M = "June";
            break;
        case 6:
            M = "July";
            break;
        case 7:
            M = "Aug";
            break;
        case 8:
            M = "Sept";
            break;
        case 9:
            M = "Oct";
            break;
        case 10:
            M = "Nov";
            break;
        case 11:
            M = "Dec";

    }
    var Today = W + ' ' + D.getDate() + ' ' + M + ' ' + D.getFullYear() + '  ' + D.getHours() + ':' + D.getMinutes() + ':' + D.getSeconds();
    return Today;

}

setInterval(function() {

    $("#dateTime").text(DateTime());

}, 1000);


function userFunction(_this) {
    if ($("#select-all").is(':checked')) {
        $('#MultiSelectList :input').not(_this).attr('checked', true);
        //$('#MultiSelectList :input').prop("checked", true);
    } else {
        $('#MultiSelectList :input').not(_this).attr('checked', false);
        //$('#MultiSelectList :input').prop("checked", false);
        $('#MultiSelectList input:checked').not(_this).prop("checked", false);
        // $("#MultiSelectList").load("#MultiSelectList");
    }

    var units = [];
    $("input[name='unit']:checked").each(function() {
        value = $(this).val();
        units.push(value);
    });
    if (units) {
        var table, tr, td, i, txtValue, r;
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                var result = units.includes(txtValue);
                if (result) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
}






////////////////    db-functions

function liveSearch(txt) {
    $.ajax({
        type: 'post',
        url: "./live-search.php",
        data: '_txt=' + txt,
        success: function(result) {

            $(".live-result").empty().append(result);
        }
    });

}

function setID(id, txt) {
    $("#o_id").val(id);
    $("#txt").val(txt);
    $(".live-result").empty();
}


function liveAgent(txt) {
    $.ajax({
        type: 'post',
        url: "./live-agent.php",
        data: '_txt=' + txt,
        success: function(result) {

            $(".live-result").empty().append(result);
        }
    });

}







$(document).ready(function() {
    $('#dataTable1').after('<div id="pg-nav"></div>');
    var rowsShown = 10;
    var rowsTotal = $('#dataTable1 tbody tr').length;
    var numPages = rowsTotal / rowsShown;
    for (i = 0; i < numPages; i++) {
        var pageNum = i + 1;
        $('#pg-nav').append('<a href="#" rel="' + i + '">' + pageNum + '</a> ');
    }
    $('#dataTable1 tbody tr').hide();
    $('#dataTable1 tbody tr').slice(0, rowsShown).show();
    $('#pg-nav a:first').addClass('active');
    $('#pg-nav a').bind('click', function() {

        $('#pg-nav a').removeClass('active');
        $(this).addClass('active');
        var currPage = $(this).attr('rel');
        var startItem = currPage * rowsShown;
        var endItem = startItem + rowsShown;
        $('#dataTable1 tbody tr').css('opacity', '0.0').hide().slice(startItem, endItem).
        css('display', 'table-row').animate({
            opacity: 1
        }, 300);
    });
});


function acknowledge(alarm_id) {
    $.ajax({
        type: "POST",
        url: 'acknowledge_alarm.php',
        data: { a_id: alarm_id }, // serializes the form's elements.
        success: function(result) {
            alert(result);

        }
    });
}


function updatePhase(phase_id) {

    var phase_state = $("#phase_state" + phase_id).prop('checked');
    var phase_limit = $("#phase_limit" + phase_id).val();;

    if (phase_state == true) {
        phase_state = 1;
    } else {
        phase_state = 0;
    }
    var confirmation = confirm('Are you sure, You want to update phase !');
    if (confirmation == true) {
        $.ajax({
            type: "POST",
            url: 'update-phase.php',
            data: { pid: phase_id, pl: phase_limit, phs: phase_state }, // serializes the form's elements.
            success: function(result) {
                if (result == 'ok') {
                    $('<div id="notification" class="card bg-success text-white shadow mb-4"> <div class = "card-body" >Updated: <div class = "text-white-80 small" > Location Updated! </div> </div> </div>').insertBefore('#top');

                    window.location.reload();

                }

            }
        });
    }

}


function get_not_zone(phase) {
    $.ajax({
        type: "POST",
        url: 'get_zone_notin_phase.php',
        data: { phase_id: phase }, // serializes the form's elements.
        success: function(result) {
            $('#not-zones').empty().append(result);
            get_phase_zone(phase);
        }

    });
}

function get_phase_zone(phase) {
    $.ajax({
        type: "POST",
        url: 'get_phase_zone_data.php',
        data: { phase_id: phase }, // serializes the form's elements.
        success: function(result) {
            $('#zones_data').empty().append(result);
        }
    });
}




function updateZone(zoneid) {

    var zonelimit = $("#zone_limit" + zoneid).val();
    var state = $("#zone_state" + zoneid).prop('checked');
    var plc = $("#zone_plc_reset" + zoneid).prop('checked');
    var phaseid = $("#phaseid" + zoneid).val();

    if (state == true) {
        var zonestate = 1;
    } else {
        var zonestate = 0;
    }

    if (plc == true) {
        var zoneplc = 1;
    } else {
        var zoneplc = 0;
    }
    var confirmation = confirm('Are you sure, You want to update zone !');
    if (confirmation == true) {
        $.ajax({
            url: "reports/updateZone.php",
            type: "POST",
            data: { phase_id: phaseid, zone_id: zoneid, zone_limit: zonelimit, zone_state: zonestate, zone_plc: zoneplc },
            success: function(result) {
                if (result == 'ok') {
                    $('<div id="notification" class="card bg-success text-white shadow mb-4"> <div class = "card-body" >Updated: <div class = "text-white-80 small" > Location Updated! </div> </div> </div>').insertBefore('#top');

                    window.location.reload();
                }

            }
        });
    }


}


function get_zone(phase) {

    $.ajax({
        type: "POST",
        url: 'get_zones.php',
        data: { phase_id: phase }, // serializes the form's elements.
        success: function(result) {
            $('#zones').empty().append('<option value="">----Select Zone----</option>').append(result);
        }

    });


}


function get_users(zone, phase) {

    var zone, filter, table, tr, td, i, txtValue, ptd, ptxtValue;
    zonen = document.getElementById("zones");
    let phases = document.getElementById("phase");
    filter = zonen.value.toUpperCase();
    let pfilter = zonen.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = $("#myTable tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[3];
        ptd = tr[i].getElementsByTagName("td")[2];
        if (td && ptd) {
            txtValue = td.textContent || td.innerText;
            ptxtValue = ptd.textContent || ptd.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1 && ptxtValue.toUpperCase().indexOf(pfilter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }

    $.ajax({
        type: "POST",
        url: 'get_users.php',
        data: { phase_id: phase, zone_id: zone }, // serializes the form's elements.
        success: function(result) {
            $('#users').empty().append('<option value="">----Select User----</option>').append(result);
        }

    });

    $.ajax({
        type: "POST",
        url: 'get_user_with_select.php',
        data: { phase_id: phase, zone_id: zone }, // serializes the form's elements.
        success: function(result) {
            $("#MultiSelectList").empty();
            $("#MultiSelectList").append('<div class="form-check"><label class="form-check-label" for="check2"><input type="checkbox" class="form-check-input" id="select-all" name="select-all" value="0" onclick="userFunction()"><b>Select All</b> </label> </div>');
            $("#MultiSelectList").append(result);

        }

    });

}

function selectAll() {
    var check = $('#select-all').prop('checked');
    if (check == true) {
        $('.selected').prop('checked', 'checked');
    } else {
        $('.selected').prop('checked', '');
    }
}


function deleteAll(user) {
    var check = [user];
    $('input:checkbox.selected:checked').each(function() {
        check.push($(this).val());
    });

    var jsonString = JSON.stringify(check);

    var confirmation = confirm('Are you sure, You want to delete all selected data !');
    if (confirmation == true) {
        $.ajax({
            type: "POST",
            url: 'deleteData.php',
            data: { dates: jsonString }, // serializes the form's elements.
            success: function() {
                alert('Selected data deleted!');
            }
        });
    }
}



function monthelySum() {


    var colmn = $("#myTable tr th").length;


    var sum_7 = 0;
    var sum_8 = 0;
    var sum_9 = 0;
    var sum_10 = 0;
    var sum_11 = 0;
    var sum_12 = 0;
    var sum_13 = 0;
    var sum_14 = 0;
    var sum_15 = 0;
    var sum_16 = 0;
    var sum_17 = 0;
    var sum_18 = 0;
    var sum_19 = 0;
    var sum_20 = 0;
    var sum_21 = 0;
    var sum_22 = 0;
    var sum_23 = 0;
    var sum_24 = 0;
    var sum_25 = 0;
    var sum_26 = 0;
    var sum_27 = 0;
    var sum_28 = 0;
    var sum_29 = 0;
    var sum_30 = 0;
    var sum_31 = 0;
    var sum_32 = 0;
    var sum_33 = 0;
    var sum_34 = 0;
    var sum_35 = 0;
    var sum_36 = 0;
    var sum_37 = 0;


    $("#myTable tr").not(':first').each(function() {
        if ($(this).css('display') != 'none') {
            sum_7 += getnum($(this).find("td:eq(7)").text());
            sum_8 += getnum($(this).find("td:eq(8)").text());
            sum_9 += getnum($(this).find("td:eq(9)").text());
            sum_10 += getnum($(this).find("td:eq(10)").text());
            sum_11 += getnum($(this).find("td:eq(11)").text());
            sum_12 += getnum($(this).find("td:eq(12)").text());
            sum_13 += getnum($(this).find("td:eq(13)").text());
            sum_14 += getnum($(this).find("td:eq(14)").text());
            sum_15 += getnum($(this).find("td:eq(15)").text());
            sum_16 += getnum($(this).find("td:eq(16)").text());
            sum_17 += getnum($(this).find("td:eq(17)").text());
            sum_18 += getnum($(this).find("td:eq(18)").text());
            sum_19 += getnum($(this).find("td:eq(19)").text());
            sum_20 += getnum($(this).find("td:eq(20)").text());
            sum_21 += getnum($(this).find("td:eq(21)").text());
            sum_22 += getnum($(this).find("td:eq(22)").text());
            sum_23 += getnum($(this).find("td:eq(23)").text());
            sum_24 += getnum($(this).find("td:eq(24)").text());
            sum_25 += getnum($(this).find("td:eq(25)").text());
            sum_26 += getnum($(this).find("td:eq(26)").text());
            sum_27 += getnum($(this).find("td:eq(27)").text());
            sum_28 += getnum($(this).find("td:eq(28)").text());
            sum_29 += getnum($(this).find("td:eq(29)").text());
            sum_30 += getnum($(this).find("td:eq(30)").text());
            sum_31 += getnum($(this).find("td:eq(31)").text());
            sum_32 += getnum($(this).find("td:eq(32)").text());
            sum_33 += getnum($(this).find("td:eq(33)").text());
            sum_34 += getnum($(this).find("td:eq(34)").text());
            sum_35 += getnum($(this).find("td:eq(35)").text());
            sum_36 += getnum($(this).find("td:eq(36)").text());
            sum_37 += getnum($(this).find("td:eq(37)").text());

            function getnum(t) {
                if (isNumeric(t)) {
                    return parseFloat(t, 10);
                }
                return 0;

                function isNumeric(n) {
                    return !isNaN(parseFloat(n)) && isFinite(n);
                }
            }
        }
    });

    var row = "<tr id='total'><th></th><th colspan='6'> Total</th></tr>";

    var arr = [];

    arr.push(parseFloat(sum_7).toFixed(2));
    arr.push(parseFloat(sum_8).toFixed(2));
    arr.push(parseFloat(sum_9).toFixed(2));
    arr.push(parseFloat(sum_10).toFixed(2));
    arr.push(parseFloat(sum_11).toFixed(2));
    arr.push(parseFloat(sum_12).toFixed(2));
    arr.push(parseFloat(sum_13).toFixed(2));
    arr.push(parseFloat(sum_14).toFixed(2));
    arr.push(parseFloat(sum_15).toFixed(2));
    arr.push(parseFloat(sum_16).toFixed(2));
    arr.push(parseFloat(sum_17).toFixed(2));
    arr.push(parseFloat(sum_18).toFixed(2));
    arr.push(parseFloat(sum_19).toFixed(2));
    arr.push(parseFloat(sum_20).toFixed(2));
    arr.push(parseFloat(sum_22).toFixed(2));
    arr.push(parseFloat(sum_23).toFixed(2));
    arr.push(parseFloat(sum_24).toFixed(2));
    arr.push(parseFloat(sum_24).toFixed(2));
    arr.push(parseFloat(sum_25).toFixed(2));
    arr.push(parseFloat(sum_26).toFixed(2));
    arr.push(parseFloat(sum_27).toFixed(2));
    arr.push(parseFloat(sum_28).toFixed(2));
    arr.push(parseFloat(sum_29).toFixed(2));
    arr.push(parseFloat(sum_30).toFixed(2));
    arr.push(parseFloat(sum_31).toFixed(2));
    arr.push(parseFloat(sum_32).toFixed(2));
    arr.push(parseFloat(sum_33).toFixed(2));
    arr.push(parseFloat(sum_34).toFixed(2));
    arr.push(parseFloat(sum_35).toFixed(2));
    arr.push(parseFloat(sum_36).toFixed(2));
    arr.push(parseFloat(sum_37).toFixed(2));


    $("#myTable").append(row);
    for (var i = 0; i < (colmn - 7); i++) {
        $("#myTable #total").append("<th>" + arr[i] + "</th>");
    }

}


$(document).ready(function() {
    $('.dataTable_length').append('<option value="200">200</option><option value="400">400</option><option value="600">600</option><option value="800">800</option><option value="1000">1000</option>');
});