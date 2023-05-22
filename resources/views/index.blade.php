<html>
<head>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
</head>

<button id="btn-create">등록</button>
<table border="1">
    <thead>
        <tr>
            <td>loan amount</td>
            <td>annual interest rate</td>
            <td>loan term</td>
            <td>effective interest rate</td>
            <td>repayment</td>
        </tr>
    </thead>
    <tbody>
        @foreach( $loans as $loan )
        <tr>
            <td>{{$loan->amount}}</td>
            <td>{{$loan->rate}}</td>
            <td>{{$loan->term}}</td>
            <td>{{$loan->effective_interest_rate}}</td>
            <td><button data-loan-id="{{$loan->id}}" style="cursor:pointer">repayment</button></td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
    $( function(e) {
        $("[data-loan-id]").on("click", (e) => {
            location.href="/repayment/" + $(e.currentTarget).attr("data-loan-id");
        });

        $("#btn-create").on("click", (e) => {
            location.href="/create";
        })
    });
</script>
</html>
