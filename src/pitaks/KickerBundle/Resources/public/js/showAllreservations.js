/**
 * Created by Aurimas on 2015.05.10.
 */
$('#showReservations').click(function(){
        var tableId =  $('#tableId').val();
        var verte = $('#datepicker').val();
        if(verte!=""){
            $.ajax({
                url: Routing.generate('tableEveryDayReservationsList', {'tableId': tableId}),
                type: "post",
                data: ({dateValue: verte}),
                success: function(data){
                    $("#reservationList").html("");
                    $("#reservationList").html(data);
                },
                error:function(){
                    $("#reservationList").html('There is error while submit');
                }
            });
        }
        else{
            $("#laukas").text("Choose Data from the calendar");
        }
    }
);
$('#datepicker').datetimepicker({
    defaultDate:0,
    lang:'lt',
    format:'Y-m-d',
    minTime:0,
    minDate:0,
    timepicker:false
});
