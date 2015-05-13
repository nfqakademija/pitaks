/**
 * Created by Aurimas on 2015.05.09.
 */
$('#mygtukas').click(function(){
        var duration = $('#selectDuration').val();
        var startTime = $('#timepicker').val();
        var tableId =  $('#tableName').attr('value');
        $("#save").prop("disabled",true);
        $("#challengeOk").prop("disabled",true);
        $("#challengeTeamOk").prop("disabled",true);
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
        var datele = $('#dayValueFromCalendar').val();
        $.ajax({
            url: Routing.generate('saveReservation'),
            type: "post",
            data: ({dateValue: datele, tableId: tableId, startValue: verte, endValue: verteEnd }),
            success: function(data){
                alert(data);
                window.location.reload(true);
            },
            error:function(){
            }
        });
    }
);
function saveChallenge() {
    $('#challengeOk').click(function () {
            var userUsername = $('#UserUsername').val();
            var tableId = $('#timesTable').attr('value');
            var datele = $('#dayValueFromCalendar').val();
            $.ajax({
                url: Routing.generate('userChallengeSave', {'username': userUsername }),
                type: "post",
                data: ({dateValue: datele, tableId: tableId, startValue: verte, endValue: verteEnd }),
                success: function (data) {
                    modalAlert(data,'/profile');
                },
                error: function () {
                }
            });
        }
    );
}
saveChallenge();

function saveTeamChallenges() {
    $('#challengeTeamOk').click(function () {
            var tableId = $('#timesTable').attr('value');
            var datele = $('#dayValueFromCalendar').val();
            var myId = $('#myteamId').val();
            var friendId = $('#friendTeamId').val();
            $.ajax({
                url: Routing.generate('saveTeamChallenge', {'teamId':  myId, 'anotherTeamId': friendId  }),
                type: "post",
                data: ({dateValue: datele, tableId: tableId, startValue: verte, endValue: verteEnd }),
                success: function (data) {
                    alert(data);
                    window.location.reload(true);
                },
                error: function () {
                    alert("failure");
                }
            });
        }
    );
}
saveTeamChallenges();
