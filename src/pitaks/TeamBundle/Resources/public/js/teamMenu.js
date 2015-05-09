/**
 * Created by Aurimas on 2015.05.09.
 */
function challenges()
{
    var id = $('#teamId').val();
    $("#challengeMaker").click(function()
        {
            $.ajax({
                url:  Routing.generate('teamSearchForm',{'teamId': id}),
                type: "get",
                success: function(data){
                    $('#challengeView').html(data);
                },
                error:function(){
                    $('#challengeView').html("Where was some errors while loading challenge form");
                }
            });

        }
    );
}
challenges();