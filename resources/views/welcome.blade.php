@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 t-4" style="margin-top:50px">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title"></div>
                </div>  
                <div class="panel-body">
                    <form action="{{ route('submit') }}" method="post" class="form-horizontal" role="form">
                        @csrf
                        <div id="signupalert" style="display:none" class="alert alert-danger">
                            <p>Error:</p>
                            <span></span>
                        </div>
                            
                        <div class="form-group">
                            <label for="company" class="col-md-3 control-label">Company</label>
                            <div class="col-md-9">
                                <select name="company" id="company" class="form-control">
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $company)
                                    <option value="{{ $company['Symbol'] }} - {{ $company['Company Name'] }}">{{ $company['Company Name'] }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('company'))
                                    <span class="text-danger">{{ $errors->first('company') }}</span>
                                @endif
                            </div>
                        </div>
                            
                        <div class="form-group">
                            <label for="start_date" class="col-md-3 control-label">Start Date</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control datepicker" placeholder="Enter Start Date" id="start_date" name="start_date" autocomplete="off">
                                @if ($errors->has('start_date'))
                                    <span class="text-danger">{{ $errors->first('start_date') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="end_date" class="col-md-3 control-label">End Date</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control datepicker" placeholder="Enter End Date" id="end_date" name="end_date" autocomplete="off">
                                @if ($errors->has('end_date'))
                                    <span class="text-danger">{{ $errors->first('end_date') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-md-3 control-label">Email</label>
                            <div class="col-md-9">
                                <input type="email" class="form-control" placeholder="Enter Your Email" id="email" name="email">
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-info"> Submit </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$(document).ready(function () {
    var startDate = $("#start_date").datepicker({
        maxDate: 0, // Disables future dates
        changeYear: true, // Allows changing the year directly
        yearRange: "-100:+0", // Allows selecting from 100 years ago to current year
        // dateFormat: "yy-mm-dd", // Format of the selected date
        onSelect: function (selectedDate) {
            // Set minDate for end date to start date
            $("#end_date").datepicker("option", "minDate", selectedDate);
        }
    });

    $("#end_date").datepicker({
        maxDate: 0, // Disables future dates
        changeYear: true, // Allows changing the year directly
        yearRange: "-100:+0", // Allows selecting from 100 years ago to current year
        // dateFormat: "yy-mm-dd", // Format of the selected date
        onSelect: function (selectedDate) {
            // Set maxDate for start date to end date
            startDate.datepicker("option", "maxDate", selectedDate);
        }
    });
});
</script>
@endsection