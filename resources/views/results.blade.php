@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Results for {{ $company[1] }} - {{ $company[0] }}</h1>
    <p>Date range: {{ $startDate }} to {{ $endDate }}</p>
    
    @if($data['prices'])
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#section1">Row Data</a></li>
        <li><a data-toggle="tab" href="#section2">Chart</a></li>
    </ul>
    <div class="tab-content">
        <div id="section1" class="tab-pane fade in active">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Open</th>
                        <th>High</th>
                        <th>Low</th>
                        <th>Close</th>
                        <th>Volume</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['prices'] as $row)
                        <tr>
                            <td>{{ Carbon\Carbon::parse($row['date'])->format('Y-m-d') }}</td>
                            <td>{{ @$row['open'] }}</td> 
                            <td>{{ @$row['high'] }}</td>
                            <td>{{ @$row['low'] }}</td>
                            <td>{{ @$row['close'] }}</td>
                            <td>{{ @$row['volume'] }}</td>
                        </tr>
                        <!-- use @ before render data because in the API response at some places data is not coming, only getting dates -->
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="section2" class="tab-pane fade">
            <canvas id="stockChart" width="400" height="200"></canvas>
        </div>
    </div>
    @else
    <p>Record Not Found</p>
    @endif
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