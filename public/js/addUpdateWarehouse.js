function formAddSubmitBlock(button) {
    $('input[id=actionAddForm]').val(button.value);
    $("#"+button.id).attr('disabled','disabled');
    $("#formAddWarehouse").submit();
}

function formUpdateSubmitBlock(button) {
    $('input[id=actionUpdateForm]').val(button.value);
    $("#"+button.id).attr('disabled','disabled');
    $("#formUpdateWarehouse").submit();
}

function formDeleteSubmitBlock(button) {
    $('input[id=actionDeleteForm]').val(button.value);
    $("#"+button.id).attr('disabled','disabled');
    $("#formDeleteWarehouse").submit();
}

function writeNickname(nameWritten){
    $('input[id=nickname]').val(nameWritten.value);
    $('input[id=nickname]').change();
}