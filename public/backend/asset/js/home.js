// Revenue Chart (Line)
      const ctxRev = document.getElementById('revenueChart').getContext('2d');
      new Chart(ctxRev, {
        type: 'line',
        data: {
          labels: window.chartData.revenue.labels,
          datasets: [{
            label: 'Doanh thu (Triệu VNĐ)',
            data: window.chartData.revenue.data,
            borderColor: '#dc2626',
            backgroundColor: 'rgba(220, 38, 38, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false }
          },
          scales: {
            y: { beginAtZero: true }
          }
        }
      });

      // User Structure Chart (Doughnut)
      const ctxUser = document.getElementById('userChart').getContext('2d');
      new Chart(ctxUser, {
        type: 'doughnut',
        data: {
          labels: window.chartData.userStruct.labels,
          datasets: [{
            data: window.chartData.userStruct.data,
            backgroundColor: ['#dc2626', '#f59e0b', '#3b82f6', '#9ca3af'],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          cutout: '70%',
          plugins: {
            legend: { position: 'bottom' }
          }
        }
      });

      // User Registration Chart (Bar)
      const ctxUserReg = document.getElementById('userRegistrationChart').getContext('2d');
      new Chart(ctxUserReg, {
        type: 'bar',
        data: {
          labels: window.chartData.userReg.labels,
          datasets: [{
            label: 'Học viên mới',
            data: window.chartData.userReg.data,
            backgroundColor: '#3b82f6',
            borderRadius: 6,
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false }
          },
          scales: {
            y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
            x: { grid: { display: false } }
          }
        }
      });
    