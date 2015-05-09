/**
 * Created by Aurimas on 2015.05.09.
 */
function autocompleteSearch() {
    var availableTags = [];
    $('#tags').keyup(function () {
        var text = $('#tags').val();
        $.ajax({
            url: Routing.generate('getUsersName'),
            type: "post",
            data: ({'word': text}),
            success: function (data) {
                availableTags = [];
                for (var i = 0; i < data.length; i++) {
                    availableTags.push(data[i]);
                }
                $("#tags").autocomplete({
                    source: availableTags
                });
            },
            error: function () {
                alert("failure");
            }
        });
    });
}
autocompleteSearch();
$('#usernameButton').click(function(){
    //take value
    var text = $('#tags').val();
    //need to return user list
    $.ajax({
        url: Routing.generate('userslist'),
        type: "post",
        data: ({'word': text}),
        success: function (data) {
            $('#userTable').html(data);
        },
        error: function () {
            alert("failure");
        }
    });
});