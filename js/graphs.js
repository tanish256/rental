fetch('graphdata.php?MIG')  // Replace with actual PHP endpoint
  .then(response => response.json())
  .then(data => {
      var optionsMIG = {
          series: [{
              name: "Million",
              data: data  // Dynamically loaded data
          }],
          chart: {
              height: 200,
              type: 'line',
              zoom: { enabled: false }
          },
          dataLabels: { enabled: false },
          stroke: { curve: 'smooth' },
          title: {
              text: 'Monthly Income Growth (2025)',
              align: 'left'
          },
          grid: {
              row: {
                  colors: ['#f3f3f3', 'transparent'],
                  opacity: 0.5
              },
          },
          xaxis: {
              categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
          },
          fill: {
            type: 'gradient',
            gradient: {
              shade: 'dark',
              gradientToColors: ['#228B22', '#228B22', '#228B22'], // Custom gradient colors
              shadeIntensity: 1,
              type: 'vertical',  // Change to 'vertical' for a vertical gradient
              opacityFrom: 0.7,  // Adjust starting opacity
              opacityTo: 0.8,  // Adjust ending opacity
              stops: [0, 70, 100]  // Stops at the different points in the gradient
            },
          }
      };

      var chart = new ApexCharts(document.querySelector("#chart"), optionsMIG);
      chart.render();
  })
  .catch(error => console.error('Error fetching data:', error));

   fetch('graphdata.php?MCP')  // Replace with actual PHP endpoint
  .then(response => response.json())
  .then(data => {
      var optionsMCP = {
          series: [{
              name: "Million",
              data: data  // Dynamically loaded data
          }],
          chart: {
              height: 200,
              type: 'bar',
              zoom: { enabled: false }
          },
          plotOptions: {
              bar: {
                  distributed: true,  // Distribute different colors for each bar
                  dataLabels: {
                      position: 'top'  // Optional: positions the data labels on top of each bar
                  }
              }
          },
          colors: [
              '#008FFB', '#00E396', '#FEB019', '#FF4560', 
              '#775DD0', '#3F51B5', '#546E7A', '#D4526E', 
              '#8D5B4C', '#F86624', '#D7263D', '#1B998B'
          ],
          dataLabels: { enabled: false },
          stroke: { curve: 'smooth' },
          title: {
              text: 'Monthly Collection Performance (2025)',
              align: 'left'
          },
          grid: {
              row: {
                  colors: ['#f3f3f3', 'transparent'],
                  opacity: 0.5
              }
          },
          xaxis: {
              categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
          },
          legend: {
              show: false  // Disable the legend
          }
      };

      var chart2 = new ApexCharts(document.querySelector("#chart2"), optionsMCP);
      chart2.render();
  })
  .catch(error => console.error('Error fetching data:', error));