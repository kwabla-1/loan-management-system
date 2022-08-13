google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawStuff);

      function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Money Given out',     11],
          ['Money Pending',      2],
          ['Total Chronic',  1],
          ['Total Interest', 2]
        ]);

        var options = {
          title: 'Statistics',
          backgroundColor: {stroke: 'red'},
          // width: 670,
          legend: { position: 'none' },
          
          bars: 'vertical', // Required for Material Bar Charts.
          axes: {
            x: {
              0: { side: 'top', label: 'Percentage'} // Top x-axis.
            }
          },
          bar: { groupWidth: "70%" }
        };

        var chart = new google.charts.Bar(document.getElementById('top_x_div'));
        chart.draw(data, options);
      };