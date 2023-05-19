<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
</head>
<form id="form01" method="post" action="/submit">
    {{ csrf_field() }}
    Loan amount <input type="text" name="amount" /> <br />
    Annual interest rate <input type="text" name="rate" /> <br />
    Loan term <input type="text" name="term" /> <br />
    Monthly fixed extra payment <input type="text" name="extra_payment" /> <br />

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
