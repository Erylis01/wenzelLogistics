// function formUpdateSubmitBlock(button) {
//     $('input[id=actionUpdateForm]').val(button.value);
//     $("#"+button.id).attr('disabled','disabled');
//     $("#formUpdateProfile").submit();
// }

function formDeleteSubmitBlock(button) {
    $('input[id=actionDeleteForm]').val(button.value);
    $("#"+button.id).attr('disabled','disabled');
    $("#formDeleteProfile").submit();
}