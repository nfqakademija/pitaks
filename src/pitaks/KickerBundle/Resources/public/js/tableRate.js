/**
 * Created by Aurimas on 2015.05.09.
 */
$( "#spinner" ).spinner({
    max: 5,
    min: 0
});

$('#saveRate').click(function(){
    var tableId= $('#tableId').val();
    var ratingValue = $('#spinner').attr('aria-valuenow');
    if(ratingValue>=0 && ratingValue<6) {
        $.ajax({
            url: Routing.generate('saveTableRate', {'tableId': tableId}),
            type: "post",
            data: ({rating: ratingValue}),
            success: function (data) {
                $('#rateErrors').html("");
                $('#spinnerTab').html("");
                $('#rateErrors').html("<h3>" + "Rate saved"+"</h3>" + "</br>" + "please close" );
            },
            error: function () {
            }
        });
    }
    else{
        $('#rateErrors').html("");
        $('#rateErrors').text("Choose value from spinner")
    }

});