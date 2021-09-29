

$(document).ready(async function()
{
  getWeather();
  let doughnutPieData =await dataChart();
  let doughnutPieOptions = {
      responsive: true,
      animation: {
        animateScale: true,
        animateRotate: true
      }
  };

  let pieChartCanvas = $("#pieChart").get(0).getContext("2d");
  let pieChart = new Chart(pieChartCanvas, {
      type: 'pie',
      data: doughnutPieData,
      options: doughnutPieOptions
  });

});
async function getWeather()
{
    let path = 'https://api.openweathermap.org/data/2.5/find?q=hanoi&units=metric&lang=vi&appid=01fddd75d5907a45fc558a4a6db56ae9';
    if(navigator.geolocation)
    {
        let res = await new Promise(function(resolve, reject) {
            navigator.geolocation.getCurrentPosition(resolve, reject);
        });
        lat = res.coords.latitude;
        long = res.coords.longitude;
        path = `https://api.openweathermap.org/data/2.5/find?lat=${lat}&lon=${long}&units=metric&cnt=1&lang=vi&appid=01fddd75d5907a45fc558a4a6db56ae9`;
    }
    $.get(path,
        function (data) {
            $('.ml-2 .font-weight-normal').text(data.list[0].sys.country);
            $('.location.font-weight-normal').text(data.list[0].name);
            $('#weather-deg').text(data.list[0].main.temp);
        },
        "json"
    );
};

async function dataChart()
{
  let res = await (await fetch('/ajaxAdmin/getChart')).json();
  let data = [];
  if(res.status == 200)
  {
    let obj = res['data'];
    $('#users').text(obj.users);
    $('#files').text(obj.files);
    $('#donates').text(obj.donates);
    $('#amounts').text(new Intl.NumberFormat('ja-JP').format(obj.totalAmount) + ' VND');
    data = [obj.users,obj.files,obj.donates];
  }
  return {
    datasets: [{
      data: data,
      backgroundColor: [
        'rgba(255, 99, 132, 0.5)',
        'rgba(54, 162, 235, 0.5)',
        'rgba(255, 206, 86, 0.5)',
        'rgba(75, 192, 192, 0.5)',
        'rgba(153, 102, 255, 0.5)',
        'rgba(255, 159, 64, 0.5)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
    }],

    // These labels appear in the legend and in the tooltips when hovering different arcs
    labels: [
      'Users',
      'Files',
      'Donates',
    ]
  };
}