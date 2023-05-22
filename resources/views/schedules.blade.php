<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
</head>

    {{ csrf_field() }}
    Loan amount1 <input type="text" name="amount" value="{{$loan->amount}}" disabled="disabled" /> <br />
    Annual interest rate <input type="text" name="rate" value="{{$loan->rate}}" disabled="disabled" /> <br />
    Loan term <input type="text" name="term" value="{{$loan->term}}" disabled="disabled" /> <br />
    Monthly fixed extra payment <input type="text" name="extra_payment" value="{{$loan->extra_payment}}" disabled="disabled" /> <br />
    repayment amount <input type="text" name="repayment_amount" value="{{$loan->repayment_amount}}" disabled="disabled" /> <br />

    <table border="1px">
        <thead>
            <tr>
                <td>idx</td>
                <td>yyyy-mm</td>
                <td> starting balance </td>
                <td> monthly payment </td>
                <td> principal component </td>
                <td> interest component </td>
                <td> ending balance </td>
                <td> remaining loan term </td>
            </tr>
        </thead>
        <tbody>
            @foreach( $loan->schedules() as $schedule )
                <tr>
                    <td>{{ $schedule->idx }}</td>
                    <td>{{ $schedule->ym }}</td>
                    <td>{{ $schedule->starting_balance }}</td>
                    <td>{{ $schedule->monthly_payment }}</td>
                    <td>{{ $schedule->principal_component }}</td>
                    <td>{{ $schedule->interest_component }}</td>
                    <td>{{ $schedule->ending_balance }}</td>
                    <td>{{ $schedule->remaining_loan_term }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
<script>
</script>
</html>
