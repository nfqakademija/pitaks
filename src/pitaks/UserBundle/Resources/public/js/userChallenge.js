/**
 * Created by Aurimas on 2015.05.09.
 */

$('#anotherTable').click(function()
{    document.location.href = $('#UserUsername').val();
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