var wordCloudData;

$(document).ready(function() {

    if(wordCloudData) {

        var sessionIdentifier = $("meta[name=sessionIdentifier]").attr("content");
        var sessionQuestionID = $("meta[name=sessionQuestionID]").attr("content");

        // Construct URL for API communication
        var url = baseUrl + "api/session/" + sessionIdentifier + "/question/" + sessionQuestionID + "/analysis/";

        // Make an api request
        $.getJSON(url, function(data) {

            $(".analysis-loading").addClass("display-none");

            if(data["error"]) {
                $(".no-analysis-error").removeClass("display-none");
                return;
            }

            var dataFormatted = [];

            data.forEach(function(item) {

                var cluster = parseInt(item.cluster);

                if(!(cluster in dataFormatted)) {
                    dataFormatted[cluster] = [];
                }

                dataFormatted[cluster].push({
                    "x": item.x,
                    "y": item.y,
                    "responseID": item.responseID,
                    "response": item.response,
                    "cluster_label": item.cluster_label,
                    "r": 7.5
                });
            });

            var analysisData = {
                datasets: []
            };

            var analysisLabels = [];
            var i = 0;

            barLabels = [];
            barData = [];
            bgColors = [];
            borColors = [];

            dataFormatted.forEach(function(cluster) {

                if(!(i in analysisLabels))
                    analysisLabels[i] = [];

                barLabels.push(cluster[0].cluster_label);
                barData.push(cluster.length);
                bgColors.push(getColour(backgroundColours, i));
                borColors.push(getColour(backgroundColours, i+3));

                if(!(i in analysisData.datasets))
                    analysisData.datasets[i] = {
                        label: [cluster[0].cluster_label],
                        data: [],
                        backgroundColor: getColour(backgroundColours, i)
                    };


                cluster.forEach(function(item) {
                    analysisData.datasets[i].data.push(item);

                    const responseLength = 100;
                    var responseText = item.response;

                    if(responseText.length > responseLength) {
                        responseText = responseText.substr(0, responseLength) + "...";
                    }

                    analysisLabels[i].push(responseText);
                });

                i++;
            });


            initAnalysisChart("analysis-chart", analysisData, analysisLabels);
            initBarChart("bar-analysis", barLabels, barData, bgColors, borColors);
        });
    }
});

/**
 * When the button to display usernames is clicked
 */
$("#display-personal").click(function() {
    $(".username").css("display", "table-cell");
    $(".fullname").css("display", "table-cell");
    $(this).css("display", "none");
    $("#hide-personal").css("display", "inline")
});

/**
 * When the button to hide usernames is clicked
 */
$("#hide-personal").click(function() {
    $(".username").css("display", "none");
    $(".fullname").css("display", "none");
    $(this).css("display", "none");
    $("#display-personal").css("display", "inline")
});

function initBarChartSection() {
    initBarChart("bar-chart", labels, data, backgroundColor, borderColor);
}

function initPieChartSection() {
    initPieChart("pie-chart", labels, data, backgroundColor, borderColor);
}

function initWordCloudSection(json) {
    wordCloudData = JSON.parse(json);
    initWordCloud();
}

function initBarChart(id, labels, data, backgroundColor, borderColor) {
    var ctx = document.getElementById(id).getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: backgroundColor,
                borderColor: borderColor,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        fontSize:20,
                        beginAtZero:true,
                        autoSkip: true
                    }
                }],
                xAxes: [{
                    ticks: {
                        fontSize:20,
                        autoSkip: true
                    }
                }]
            },
            legend: {
                display: false
            }
        }
    });
}

function initPieChart(id, labels, data, backgroundColor, borderColor) {
    var ctx = document.getElementById(id).getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: backgroundColor,
                borderColor: borderColor,
                borderWidth: 1
            }]
        }
    });
}

function initAnalysisChart(id, analysisData, analysisLabels) {
    var ctx = document.getElementById(id).getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bubble',
        data: analysisData,
        options: {
            tooltips: {
                callbacks: {
                    title: function() {
                        return '';
                    },
                    label: function(item, data) {
                        return analysisLabels[item.datasetIndex][item.index];
                    }
                }
            },
            legend: {
                display: true,
                labels: {
                    fontSize: 20
                }
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        drawBorder: false,
                        display:false
                    },
                    display: false
                }],
                yAxes: [{
                    gridLines: {
                        drawBorder:false,
                        display:false
                    },
                    display: false
                }],
            },
        }
    });
}

function initWordCloud() {
    var data = wordCloudData;
    var id = "wordcloud";

    d3.wordcloud()
        .size([$("main .container").width(), 500])
        .fill(d3.scale.ordinal().range(["#884400", "#448800", "#888800", "#444400"]))
        .words(data)
        .onwordclick(function (d, i) {
            //if (d.href) { window.location = d.href; }
            if (d.alert) {
                //alert(d.alert);
                //initWordCloud(id, data);
            }
        })
        .start();
}
