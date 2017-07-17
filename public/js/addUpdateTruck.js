function formAddSubmitBlock(button) {
    $('input[id=actionAddForm]').val(button.value);
    $("#"+button.id).attr('disabled','disabled');
    $("#formAddTruck").submit();
}

function formUpdateSubmitBlock(button) {
    $('input[id=actionUpdateForm]').val(button.value);
    $("#"+button.id).attr('disabled','disabled');
    $("#formUpdateTruck").submit();
}

function formDeleteSubmitBlock(button) {
    $('input[id=actionDeleteForm]').val(button.value);
    $("#"+button.id).attr('disabled','disabled');
    $("#formDeleteTruck").submit();
}