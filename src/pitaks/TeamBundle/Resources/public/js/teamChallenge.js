/**
 * Created by Aurimas on 2015.05.09.
 */
$('#anotherTable').click(function()
{
    $('#tableListForReservation').css("display","block");
    $('#anotherTable').css("display","none");
    $("#teamChallengeMenu").html("");
});
function showResults(){
    $('.reservation').click(function(){
            $.ajax({
                url:  Routing.generate('viewRes'),
                type: "post",
                data: ({ tableId: $(this).val()}),
                success: function(data){
                    $('#tableListForReservation').css("display","none");
                    $('#anotherTable').css("display","block");
                    $("#teamChallengeMenu").html(data);
                },
                error:function(){
                    alert("something wrong happends");
                }
            });
        }
    );
}
showResults();

function saveChallenge() {
    $('#challengeOk').click(function () {
            var tableId = $('#timesTable').attr('value');
            var datele = $('#datetimepicker').val();
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
saveChallenge();
