/**
 * Created by Aurimas on 2015.05.09.
 */

$('#anotherTable').click(function()
{
    var friendId = $('#friendTeamId').val();
    document.location.href = friendId;

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


