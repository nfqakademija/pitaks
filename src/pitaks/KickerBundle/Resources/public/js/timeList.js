/**
 * Created by Aurimas on 2015.05.09.
 */
    var timeValue = $('#dayValueFromCalendar').val();
var verte;
var verteEnd;
$("input[name='timeListValue']").change(function(){
    verteEnd = $(this).val();
    verte = $(this).parent().attr('value');
    $("#save").prop("disabled",false);
    $("#challengeOk").prop("disabled",false);
    $("#challengeTeamOk").prop("disabled",false);
});
