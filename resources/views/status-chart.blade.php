<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Status by Department</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f4f8;
        }
        .container {
            width: 80%;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }
        .chart-container {
            margin-bottom: 20px;
            height: 400px;
        }
        .print-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .print-btn:hover {
            background-color: #2980b9;
        }
        .statistics {
            margin-top: 20px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .statistics h2 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .statistics ul {
            list-style-type: none;
            padding: 0;
        }
        .statistics li {
            margin: 5px 0;
            color: #34495e;
        }
        @media print {
            body {
                background-color: white;
            }
            .container {
                width: 100%;
                box-shadow: none;
            }
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    @include('partials.nav')
    <div class="container">
        <h1>Pending Status by Department</h1>
        <div class="chart-container">
            <canvas id="statusChart"></canvas>
        </div>
        <div class="statistics">
            <h2>Statistics</h2>
            <ul id="statsList"></ul>
        </div>
        <button class="print-btn" onclick="window.print()">Download Report</button>
    </div>

    <script>
        var ctx = document.getElementById('statusChart').getContext('2d');
        var labels = <?php echo json_encode($pendingStatusesByDepartment->pluck('dep_name')) ;?>;
        var data = <?php echo json_encode($pendingStatusesByDepartment->pluck('count'));?>;
        var statusChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pending Status Count',
                    data: data,
                    backgroundColor: 'rgba(52, 152, 219, 0.6)',
                    borderColor: 'rgba(52, 152, 219, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Count of Pending Status',
                            color: '#7f8c8d',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            color: '#7f8c8d',
                            font: {
                                size: 12
                            },
                            stepSize: 1,
                            callback: function(value) {
                                return value.toFixed(0);
                            }
                        },
                        grid: {
                            color: 'rgba(127, 140, 141, 0.2)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Department',
                            color: '#7f8c8d',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            color: '#7f8c8d',
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 12
                        },
                        padding: 10,
                        cornerRadius: 4
                    }
                }
            }
        });

        // Calculate and display statistics
        function calculateStats() {
            const total = data.reduce((sum, count) => sum + parseInt(count), 0);
            const average = total / data.length;
            const highest = Math.max(...data);
            const lowest = Math.min(...data);
            const highestDept = labels[data.indexOf(highest.toString())];
            const lowestDept = labels[data.indexOf(lowest.toString())];

            const statsList = document.getElementById('statsList');
            statsList.innerHTML = `
                <li><strong>Total Departments:</strong> ${labels.length}</li>
                <li><strong>Total Pending Status:</strong> ${total}</li>
                <li><strong>Average Pending Status:</strong> ${average.toFixed(2)}</li>
                <li><strong>Highest Pending Status:</strong> ${highest} (${highestDept})</li>
                <li><strong>Lowest Pending Status:</strong> ${lowest} (${lowestDept})</li>
            `;
        }

        calculateStats();
    </script>
</body>
</html>