

google.charts.load("current", {packages:["corechart"]});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {
var data = google.visualization.arrayToDataTable([
    ['Task', 'Hours per Day'],
    ['Money Given out',     11],
    ['Money Pending',      2],
    ['Total Chronic',  1],
    ['Total Interest', 2]
]);

var options = {
    title: 'Transactions Statistics',
    backgroundColor: 'burlywood',
    chartArea:{left:60,top:50,width:'90%',height:'80%'},
    is3D: true,
    legend: {position: 'bottom'}
};

var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
chart.draw(data, options);
}