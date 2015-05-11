/**
 * Created by Aurimas on 2015.05.09.
 */

$('#anotherTable').click(function()
{
    $('#tableListForReservation').css("display","block");
    $('#anotherTable').css("display","none");
    $("#challengeMenu").html("");
});
var friend = $('#friendUsername').val();

function showResults(){
    $('.rezervation').click(function(){
            $.ajax({
                url:  Routing.generate('viewRes'),
                type: "post",
                data: ({ tableId: $(this).val()}),
                success: function(data){
                    $("#challengeMenu").html(data);
                    $('#anotherTable').css("display","block");
                    $('#tableListForReservation').css("display","none");
                },
                error:function(){
                }
            });
        }
    );
}
showResults();
function saveChallenge() {
    $('#challengeOk').click(function () {
            var userUsername = $('#UserUsername').val();
            var tableId = $('#timesTable').attr('value');
            var datele = $('#datetimepicker').val();
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