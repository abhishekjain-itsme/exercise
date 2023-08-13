@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Results for {{ $company[1] }} - {{ $company[0] }}</h1>
    <p>Date range: {{ $startDate }} to {{ $endDate }}</p>
    <canvas id="stockChart" width="400" height="200"></canvas>
</div>
@endsection

@section('scripts')
<script>
    // Assuming $data is made available to the view as a JSON string
    var data = @json($data);

    var dates = data.prices.map(function(price) {
        // Convert the Unix timestamp to a human-readable format
        console.log(price.date);
        var date = new Date(price.date * 1000);
        return date.toISOString().slice(0, 10); // returns YYYY-MM-DD format
    });

    var opens = data.prices.map(function(price) {
        return price.open;
    });

    var closes = data.prices.map(function(price) {
        return price.close;
    });

    var ctx = document.getElementById('stockChart').getContext('2d');
    var stockChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Open',
                data: opens,
                borderColor: 'rgb(255, 99, 132)',
                fill: false
            }, {
                label: 'Close',
                data: closes,
                borderColor: 'rgb(54, 162, 235)',
                fill: false
            }]
        },
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day'
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection