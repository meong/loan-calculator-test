<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
</head>
<form id="form01" method="post" action="/repayment">
    {{ csrf_field() }}
    Loan amount1 <input type="text" name="amount" value="{{$loan->amount}}" disabled="disabled" /> <br />
    Annual interest rate <input type="text" name="rate" value="{{$loan->rate}}" disabled="disabled" /> <br />
    Loan term <input type="text" name="term" value="{{$loan->term}}" disabled="disabled" /> <br />
    Monthly fixed extra payment <input type="text" name="extra_payment" value="{{$loan->extra_payment}}" disabled="disabled" /> <br />

    extra repayments <input type="text" name="extra_repayments" /><br />

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <input type="submit" value="submit" />
</form>
<script>
    $( function() {
        $("#form01").on("submit", (e) => {
            $(e.currentTarget).submit();
        })
    });
</script>
</html>
