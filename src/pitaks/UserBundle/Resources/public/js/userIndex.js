/**
 * Created by Aurimas on 2015.05.09.
 */
function autocompleteSearch() {
    var availableTags = [];
    $('#form_name').keyup(function () {
        var text = $('#form_name').val();
        $.ajax({
            url: Routing.generate('getUsersName'),
            type: "post",
            data: ({'word': text}),
            success: function (data) {
                availableTags = [];
                for (var i = 0; i < data.length; i++) {
                    availableTags.push(data[i]);
                }
                $("#form_name").autocomplete({
                    source: availableTags
                });
            },
            error: function () {
            }
        });
    });
}
autocompleteSearch();
