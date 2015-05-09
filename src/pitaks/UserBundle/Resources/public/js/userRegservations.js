/**
 * Created by Aurimas on 2015.05.09.
 */
$('.removeRegisteredReservation').click(function(){
    var reservationId = $(this).attr('value');

    $.ajax({
        url:  Routing.generate('userDeleteRegisteredReservation'),
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