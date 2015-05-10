/**
 * Created by Aurimas on 2015.05.10.
 */
$( document ).ready(function() {
        window.setInterval(function(){
            timeDown();
        }, 1000);
    }
);
function timeDown() {
    var reservationTime = $('#NextReservationTime').val();
    // alert(new Date(reservationTime).getTime());
    var dt = new Date();
    var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
    var day = dt.getFullYear() + "-" + (dt.getMonth() + 1) + "-" + dt.getDate() + " ";
    var day2 = new Date(day + reservationTime);
    var milliseconds = (day2.getTime()-dt.getTime());
    if(milliseconds > 0){
        var mydate = new Date(milliseconds);
        var sec =mydate.getUTCSeconds();
        var min =  mydate.getUTCMinutes();
        var hours = mydate.getUTCHours();
        if(sec < 10){
            sec = "0"+sec;
        }
        if(min<10){
            min = "0"+min;
        }
        if(hours<10){
            hours="0"+hours;
        }
        var humandate = hours + " : " + min+ " : " + sec;
        $('#nextTime').html('<h3>'+"Next Reservation: "+ humandate +'</h3>');
    }
}