<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSFR token for ajax call -->
        <meta name="_token" content="{{ csrf_token() }}"/>

        <title>Google Analytics</title>
        
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" type="text/css">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="col-md-12">
            <h3 class="text-center">Google Analytics - Website Traffic</h3>
            <p class="text-center"><a href="https://www.google.com/analytics/" target="_blank">Link to Google Analytics</a></p>
            <br/>
            <div class="text-center">
                <button class="btn btn-success send-btn">Get data</button>
            </div>
            <br />
            <div id="analytics_title" class="text-center"></div><br />
            <table class="table" id="analytics_table"></table><br />
            <div id="analytics_country"></div>
        </div><!-- /.col-md-12 -->

        <div id="loader" class="text-center hidden"><img src="{{ asset('ajax-loader.gif') }}" /></div>

    </body>
</html>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.1/js/bootstrap.min.js"></script>

<script>
    $.ajaxSetup({
       headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    $(document).ready(function(){
        $(".send-btn").click(function() {
            var start = new Date().getTime();
            $(".send-btn").attr("disabled", true);
            $("#loader").removeClass("hidden");
            $.ajax({
                url: "getAnalyticsData",
                type: 'POST',
                success: function(data) {
                    var end = new Date().getTime();

                    // General info (title)
                    title = document.getElementById("analytics_title");
                    title.innerHTML = "<b>Property: </b>" + data[0][1] + " <b>| View: </b>" + data[0][0] + " <b>| Elasped time: </b>" + (end-start)/1000 + " seconds to retrieve data";

                    // Main data (First table)
                    table1 = document.getElementById("analytics_table");
                    var numbers = data[1];
                    table1.innerHTML = "<thead><tr><th></th><th>Sessions</th><th>Users</th><th>New Users</th><th>Average session duration (min)</th><th>Bounce rate</th></thead>";
                    var timeframes = ['Last 7 days', '2 weeks ago', '3 weeks ago', '4 weeks ago', ''];
                    var colors = ['success', 'danger', 'info', 'warning'];
                    function myFunction(item, index) {
                        if (item['rows'] !== null) {
                            table1.innerHTML = table1.innerHTML + "<tr class='" + colors[index] + "'><th>" + timeframes[index] + "</th><td>" + item['rows'][0][0] + "</td><td>" + item['rows'][0][1] + "</td><td>" +item['rows'][0][2] + "</td><td>" + ((item['rows'][0][3])/60).toFixed(2) + "</td><td>" + parseFloat(item['rows'][0][4]).toFixed(2) + "</td></tr>";
                        }
                    }
                    numbers.forEach(myFunction);

                    // Data by country (Second table)
                    var table2 = document.createElement('table'), thead, tbody, th, tr, td, row, cell;
                    table2.className = "table";
                    var timeframes = ['Last 7 days', '2 weeks ago', '3 weeks ago', '4 weeks ago'];
                    var colors = ['success', 'danger', 'info', 'warning'];
                    var headers = ['', '#1 Sessions', '#2 Sessions', '#3 Sessions', '#4 Sessions', '#5 Sessions'];
                    var byCountry = data[2];
                    thead = document.createElement('thead');
                    tr = document.createElement('tr');

                    for (i = 0; i < headers.length; i++) {
                        th = document.createElement('th');
                        thead.appendChild(tr);
                        tr.appendChild(th);
                        th.innerHTML = headers[i];
                    }
                    table2.appendChild(thead);

                    tbody = document.createElement('tbody');
                    for (row = 0; row < byCountry.length; row++) {
                        if (byCountry[row]["rows"] !== null) {
                            var dataByTimeframe = byCountry[row]["rows"];
                            var dataByTimeframeLength = dataByTimeframe.length;

                            tr = document.createElement('tr');
                            tr.className = colors[row];
                            tbody.appendChild(tr);
                            th = document.createElement('th');
                            tr.appendChild(th);
                            th.innerHTML = timeframes[row];
                            for (cell = 0; cell < dataByTimeframeLength; cell++) {
                                td = document.createElement('td');
                                tr.appendChild(td);
                                td.innerHTML = dataByTimeframe[cell][0] + ": " + dataByTimeframe[cell][1];
                            }
                            table2.appendChild(tbody);
                        }      
                    }
                    document.getElementById('analytics_country').appendChild(table2);

                    $('#loader').hide();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    // empty
                }
            });
        });
    });
</script>