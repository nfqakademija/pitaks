/**
 * Created by Aurimas on 2015.05.09.
 */
$('#mygtukas').click(function(){
        var duration = $('#selectDuration').val();
        var startTime = $('#timepicker').val();
        alert(startTime);
        var tableId =  $('#tableName').attr('value');
        $("#save").prop("disabled",true);
        var verte = $('#datetimepicker').val();
        if(verte!=""){
            $.ajax({
                url:  Routing.generate('timelist'),
                type: "post",
                data: ({dateValue: verte, tableId: tableId, startDate: startTime, duration: duration }),
                success: function(data){
                    $("#laukas").html("");
                    $("#laukas").html(data);
                    alert('success');
                },
                error:function(){
                    alert("failure");
                    $("#laukas").html('There is error while submit');
                }
            });
        }
        else{
            $("#laukas").text("Choose Data from the calendar");
        }
    }
);

$('#datetimepicker').datetimepicker({
    defaultDate:0,
    lang:'lt',
    format:'Y-m-d',
    minTime:0,
    minDate:0,
    timepicker:false
});
$('#timepicker').datetimepicker({
    lang:'lt',
    format:'H:i',
    datepicker:false
});

$('#save').click(function(){
        var tableId= $('#timesTable').attr('value');
        var datele = $('#datetimepicker').val();
        $.ajax({
            url: Routing.generate('saveReservation'),
            type: "post",
            data: ({dateValue: datele, tableId: tableId, startValue: verte, endValue: verteEnd }),
            success: function(data){
                alert(data);
                window.location.reload(true);
            },
            error:function(){
                alert("failure");
            }
        });
    }
);
