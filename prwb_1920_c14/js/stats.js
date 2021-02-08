$(function() {
    var ctx = $('#graph');
    
    var graphe = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: '# of Votes',
                data: [],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            'onClick' : function (evt, item) {
                var index = item[0]._index;
                var name = graphe.data.labels[index];
                console.log(name);
                $.post("user/usersActivity", {UserName: name, period: $("#period").val(), temp: $("#temp").val()}, function(data) {
                    datas = jQuery.parseJSON(data);
                    console.log(name);
                    var history;
                    showActivity(datas);
                })
            }            
        }
        
    });

    initData(graphe);
    actionsInput(graphe);
});

function initData(graphe) {
    $.post("user/showGraphJson", {period: $("#period").val(), temp: $("#temp").val()}, function(data) {
        datas = jQuery.parseJSON(data);

        datas.users.sort(function (a, b) {
            return b.nbActivity - a.nbActivity;
        });

        addData(graphe, datas.limit, datas.users);
    })
}

function actionsInput(graphe) {
    $('#period').change(function() {
        $.post("user/showGraphJson", {period: $("#period").val(), temp: $("#temp").val()}, function(data) {
            datas = jQuery.parseJSON(data);
    
            datas.users.sort(function (a, b) {
                return b.nbActivity - a.nbActivity;
            });

            removeData(graphe, datas.limit);
            addData(graphe, datas.limit, datas.users);
        });
    });

    $('#temp').change(function() {
        $.post("user/showGraphJson", {period: $("#period").val(), temp: $("#temp").val()}, function(data) {
            datas = jQuery.parseJSON(data);
    
            datas.users.sort(function (a, b) {
                return b.nbActivity - a.nbActivity;
            });

            removeData(graphe, datas.limit);
            addData(graphe, datas.limit, datas.users);
        });
    });
    
}

function addData(chart, limit, users) {
    for(var i = 0; i < limit; ++i) {
        chart.data.labels.push(users[i].UserName);
        chart.data.datasets.forEach((dataset) => {
            dataset.data.push(users[i].nbActivity);
        });
    }
    
    chart.update();
}

function removeData(chart, limit) {
    for(var i = 0; i < limit; ++i) {
        chart.data.labels.pop();
        chart.data.datasets.forEach((dataset) => {
            dataset.data.pop();
        });
    }
    
    chart.update();
}

function showActivity(datas) {console.log(datas);

    $("#details").html("");
    var table = "";
    
        table = "<p>Détails des activités </p>";
        table += '<table class="table table-hover">';
        table += '<thead>';
        table += '<tr>';
        table += '<th scope="col">Moment</th>';
        table += '<th scope="col">Type</th>';
        table += '<th scope="col">Question</th>';
        table += '</tr>';
        table += "</thead>";
        table += "<tbody>";
        for(var t = 0; t < datas.length; ++t) {
            table += '<tr class="table-primary">';
            table += '<td>' + datas[t].time + '</td>';
            table += '<td>' + datas[t].type + '</td>'
            table += '<td>' + datas[t].Title + '</td>'
            table += '</tr>'

        }
        table += '</tBody>';
        table += '</table>';
    
    $("#details").append(table);
}