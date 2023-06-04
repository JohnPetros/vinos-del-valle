const charts = document.querySelectorAll(".chart");

function renderChart(id, data, categories, color) {
  const options = {
    series: [
      {
        data: data,
      },
    ],
    chart: {
      height: 350,
      type: "bar",
    },
    plotOptions: {
      bar: {
        columnWidth: "40%",
        distributed: true,
        borderRadius: 4,
      },
    },
    dataLabels: {
      enabled: false,
    },
    legend: {
      show: false,
    },
    theme: {
      mode: "light",
      palette: "palette1",
      monochrome: {
        enabled: true,
        color: color,
        shadeTo: "light",
        shadeIntensity: 0.65,
      },
    },
    xaxis: {
      categories: categories,
      labels: {
        style: {
          fontSize: "12px",
        },
      },
    },
    yaxis: {
      title: {
        text: "Quantidade",
      },
    },
  };

  const chart = new ApexCharts(document.querySelector(`#${id}-chart`), options);
  chart.render();
}

function setChart(chart) {
  const id = chart.id;
  const data = chart.dataset.data.split(";");
  const categories = chart.dataset.categories.split(";");
  const color = chart.dataset.color;
  renderChart(id, data, categories, color);
}

charts.forEach(setChart);
