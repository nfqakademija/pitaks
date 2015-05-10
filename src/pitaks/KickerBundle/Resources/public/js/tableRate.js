/**
 * Created by Aurimas on 2015.05.09.
 */
$( "#spinner" ).spinner({
    max: 5,
    min: 0
});

//end data to database
$('#saveRate').click(function(){
    var tableId= $('#tableId').val();
    var ratingValue = $('#spinner').attr('aria-valuenow');
    $.ajax({
        url:  Routing.generate('saveTableRate', {'tableId': tableId}),
        type: "post",
        data: ({rating: ratingValue}),
        success: function(data){
        },
        error:function(){
        }
    });

});