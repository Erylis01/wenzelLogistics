function displayFields(typeSelected) {
    console.log(typeSelected);
    if (typeSelected) {
        networkOptionValue = document.getElementById("networkOption").value;
        carrierOptionValue = document.getElementById("carrierOption").value;
        otherOptionValue = document.getElementById("otherOption").value;
        if (carrierOptionValue == typeSelected.value) {
            document.getElementById("trucksAssociated").style.display = "block";
            document.getElementById("warehousesAssociated").style.display = "none";
            document.getElementById("realNumberPallets1").style.display = "none";
            document.getElementById("realNumberPallets2").style.display = "none";
        }
        else {
            if (networkOptionValue == typeSelected.value) {
                document.getElementById("warehousesAssociated").style.display = "block";
                document.getElementById("trucksAssociated").style.display = "none";
                document.getElementById("realNumberPallets1").style.display = "block";
                document.getElementById("realNumberPallets2").style.display = "block";
            }else{
                if(otherOptionValue == typeSelected.value){
                    document.getElementById("warehousesAssociated").style.display = "none";
                    document.getElementById("trucksAssociated").style.display = "none";
                    document.getElementById("realNumberPallets1").style.display = "block";
                    document.getElementById("realNumberPallets2").style.display = "block";
                }else{
                    document.getElementById("warehousesAssociated").style.display = "none";
                    document.getElementById("trucksAssociated").style.display = "none";
                    document.getElementById("realNumberPallets1").style.display = "none";
                    document.getElementById("realNumberPallets2").style.display = "none";
                }
            }
        }
    }
    else {
        document.getElementById("warehousesAssociated").style.display = "none";
        document.getElementById("trucksAssociated").style.display = "none";
        document.getElementById("realNumberPallets1").style.display = "none";
        document.getElementById("realNumberPallets2").style.display = "none";
    }
}

function formAddSubmitBlock(button) {
    $('input[id=actionAddForm]').val(button.value);
    $("#"+button.id).attr('disabled','disabled');
    $("#formAddPalletsaccount").submit();
}

function formUpdateSubmitBlock(button) {
    $('input[id=actionUpdateForm]').val(button.value);
    $("#"+button.id).attr('disabled','disabled');
    $("#formUpdatePalletsaccount").submit();
}

function formDeleteSubmitBlock(button) {
    $('input[id=actionDeleteForm]').val(button.value);
    $("#"+button.id).attr('disabled','disabled');
    $("#formDeletePalletsaccount").submit();
}

function formClearSubmitBlock(button) {
    $('input[id=actionClearForm]').val(button.value);
    $("#"+button.id).attr('disabled','disabled');
    $("#formClearPalletsaccount").submit();
}

//plugin bootstrap minus and plus
$('.btn-number').click(function(e){
    e.preventDefault();
    var fieldName = $(this).attr('data-field');
    var type      = $(this).attr('data-type');
    var input = $("input[name='"+fieldName+"']");
    var currentVal = parseInt(input.val());
    if (!isNaN(currentVal)) {
        if(type == 'minus') {
            if(currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            }
            if(parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }
        } else if(type == 'plus') {
            if(currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if(parseInt(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }
        }
    } else {
        input.val(0);
    }
});
$('.input-number').focusin(function(){
    $(this).data('oldValue', $(this).val());
});
$('.input-number').change(function() {

    minValue =  parseInt($(this).attr('min'));
    maxValue =  parseInt($(this).attr('max'));
    valueCurrent = parseInt($(this).val());

    name = $(this).attr('name');
    if(valueCurrent >= minValue) {
        $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the minimum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    if(valueCurrent <= maxValue) {
        $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the maximum value was reached');
        $(this).val($(this).data('oldValue'));
    }


});