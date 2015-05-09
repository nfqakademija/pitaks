/**
 * Created by Aurimas on 2015.05.09.
 */
$('.acceptRegisteredReservation').click(function(){
    var reservationId = $(this).attr('value');
    alert(reservationId);
    $.ajax({
        url:  Routing.generate('userConfirmRegisteredReservation'),
        type: "post",
        data: ({reservationId: reservationId}),
        success: function(data){
            alert(data);
            window.location.reload(true);
        },
        error:function(){
            alert("Where was some errors while accepting");
        }
    });
});
$('.removeRegisteredReservation').click(function(){
    var reservationId = $(this).attr('value');
    alert(reservationId);
    $.ajax({
        url: Routing.generate('userDeleteRegisteredReservation'),
        type: "post",
        data: ({reservationId: reservationId}),
        success: function(data){
            alert(data);
            window.location.reload(true);
        },
        error:function(){
            alert("Where was some errors while deleting");
        }
    });
});
