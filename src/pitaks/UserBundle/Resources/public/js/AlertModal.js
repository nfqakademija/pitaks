/**
 * Created by Aurimas on 2015.05.09.
 */
function modalAlert(text,redirect) {
    $('body').prepend(
        '<div class="myModal modal fade bs-example-modal-sm " tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">'+
        '<div class="modal-dialog modal-sm  " >'+
        '<div class=" modal-content "  style="padding: 5%; font-size: 20pt">'
        +text+'<div style="text-align: right">'+
        '<button type="button" class="btn btn-default" data-dismiss="modal" align="right">OK</button>'+'</div>'+'</div>'+
        '</div>');
    $('.myModal').modal('show');
    if(redirect !="") {
        $('.myModal').on('hidden.bs.modal', function (e) {
            document.location.href = redirect;
        });
    }
}