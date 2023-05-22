<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.13.6/underscore-min.js" integrity="sha512-2V49R8ndaagCOnwmj8QnbT1Gz/rie17UouD9Re5WxbzRVUGoftCu5IuqqtAM9+UC3fwfHCSJR1hkzNQh/2wdtg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
        <tbody id="preview-container">
        </tbody>
    </table>

<script type="text/tmpl-loan-calculator" id="tmpl-preview-row">
    <tr>
       <td> <%=idx%> </td>
        <td> <%=ym%> </td>
        <td> <%=starting_balance%> </td>
        <td> <%=monthly_payment%> </td>
        <td> <%=principal_component%> </td>
        <td> <%=interest_component%> </td>
        <td> <%=ending_balance%> </td>
        <td> <%=remaining_loan_term%> </td>
    </tr>
</script>
<script>
    var rowTmpl = _.template($("#tmpl-preview-row").text());
    $( function() {
        // var rowTmpl = _.template($("#tmpl-preview-row").text());

        $("input[name='amount'],[name='rate'],[name='term'],[name='extra_payment']").on("change", (e) => {
            console.log("input changed");

            $.get( "/preview-schedules", $("#form01").serializeArray() )
                .done(function(data) {
                    console.log( "second success", data );
                    $("#preview-container").empty();
                    $(data).each((k,v) => {
                        $("#preview-container").append( rowTmpl(v));
                    });
                })
                .fail(function() {
                    console.error( "error" );
                })
                .always(function() {
                    console.log( "finished" );
                });
            ;

        });

        $("#form01").on("submit", (e) => {
            $(e.currentTarget).submit();
        })
    });
</script>
</html>
