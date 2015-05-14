/**
 * Created by Aurimas on 2015.05.09.
 */
$('.acceptRegisteredReservation').click(function(){
    var reservationId = $(this).attr('value');
    console.log(reservationId);
    $.ajax({
        url:  Routing.generate('userConfirmRegisteredReservation'),
        type: "post",
        data: ({reservationId: reservationId}),
        success: function(data){
            window.location.reload(true);
        },
        error:function(){
            console.log("Where was some errors while accepting");
        }
    });
});
$('.removeRegisteredReservation').click(function(){
    var reservationId = $(this).attr('value');
    console.log(reservationId);
    $.ajax({
        url: Routing.generate('userDeleteRegisteredReservation'),
        type: "post",
        data: ({reservationId: reservationId}),
        success: function(data){
            window.location.reload(true);
        },
        error:function(){
            console.log("Where was some errors while deleting");
        }
    });
});
