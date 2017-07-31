function displayFields(typeSelected) {
    console.log(typeSelected);
    if (typeSelected) {
        networkOptionValue = document.getElementById("networkOption").value;
        carrierOptionValue = document.getElementById("carrierOption").value;
        otherOptionValue = document.getElementById("otherOption").value;
        if (carrierOptionValue == typeSelected.value) {
            document.getElementById("contactInfos").style.display = "block";
            document.getElementById("warehouse").style.display = "none";
            document.getElementById("createWarehouse").style.display = "none";
            $("#oneWarehouse").prop('checked', false);
        }
        else {
            if (networkOptionValue == typeSelected.value) {
                document.getElementById("warehouse").style.display = "block";
                document.getElementById("createWarehouse").style.display = "block";
                if ($("#oneWarehouse").is(":checked")) {
                    document.getElementById("contactInfos").style.display = "block";
                } else {
                    document.getElementById("contactInfos").style.display = "none";
                }

            } else {
                if (otherOptionValue == typeSelected.value) {
                    document.getElementById("warehouse").style.display = "none";
                    document.getElementById("contactInfos").style.display = "none";
                    document.getElementById("createWarehouse").style.display = "block";
                    $("#oneWarehouse").prop('checked', false);
                } else {
                    document.getElementById("warehouse").style.display = "none";
                    document.getElementById("contactInfos").style.display = "none";
                    document.getElementById("createWarehouse").style.display = "none";
                    $("#oneWarehouse").prop('checked', false);
                }
            }
        }
    }
    else {
        document.getElementById("warehouse").style.display = "none";
        document.getElementById("contactInfos").style.display = "none";
        document.getElementById("createWarehouse").style.display = "none";
        $("#oneWarehouse").prop('checked', false);
    }
}

function hideWarehousesAssociated() {
    if ($("#oneWarehouse").is(":checked")) {
        document.getElementById("warehousesAssociated").style.display = "none";
        document.getElementById("contactInfos").style.display = "block";
    } else {
        document.getElementById("warehousesAssociated").style.display = "block";
        document.getElementById("contactInfos").style.display = "none";
    }
}

function writeNickname(nameWritten) {
    $('input[id=nickname]').val(nameWritten.value);
    $('input[id=nickname]').change();
}

function formAddSubmitBlock(button) {
    $('input[id=actionAddForm]').val(button.value);
    $("#" + button.id).attr('disabled', 'disabled');
    $("#formAddPalletsaccount").submit();
}

//update
function formUpdateSubmitBlock(button) {
    $('input[id=actionUpdateForm]').val(button.value);
    $("#" + button.id).attr('disabled', 'disabled');
    $("#formUpdatePalletsaccount").submit();
}
function formClearSubmitBlock(button) {
    $('input[id=actionClearForm]').val(button.value);
    $("#" + button.id).attr('disabled', 'disabled');
    $("#formClearPalletsaccount").submit();
}

function displayFieldsUpdate(typeSelected) {
    console.log(typeSelected);
    if (typeSelected) {
        networkOptionValue = document.getElementById("networkOption").value;
        carrierOptionValue = document.getElementById("carrierOption").value;
        otherOptionValue = document.getElementById("otherOption").value;
        if (carrierOptionValue == typeSelected.value) {
            document.getElementById("trucksAssociated").style.display = "block";
            document.getElementById("warehousesAssociated").style.display = "none";
            document.getElementById("truckAsso").style.display = "block";
            document.getElementById("warehouseAsso").style.display = "none";
            document.getElementById("buttonClearTrucks").style.display = "block";
            $("#select-warehouses :selected").each(function(){
               $(this).prop('selected', false);
            });
            $("#select-warehouses").selectpicker('refresh');
            $("#select-warehouses").selectpicker('render');
        } else {
            if (networkOptionValue == typeSelected.value) {
                document.getElementById("warehousesAssociated").style.display = "block";
                document.getElementById("trucksAssociated").style.display = "none";
                document.getElementById("truckAsso").style.display = "none";
                document.getElementById("warehouseAsso").style.display = "block";
                document.getElementById("buttonClearTrucks").style.display = "none";
            } else {
                if (otherOptionValue == typeSelected.value) {
                    document.getElementById("warehousesAssociated").style.display = "none";
                    document.getElementById("trucksAssociated").style.display = "none";
                    document.getElementById("truckAsso").style.display = "none";
                    document.getElementById("warehouseAsso").style.display = "none";
                    document.getElementById("buttonClearTrucks").style.display = "none";
                    $("#select-warehouses").find(':selected').removeAttr('selected');
                } else {
                    document.getElementById("warehousesAssociated").style.display = "none";
                    document.getElementById("trucksAssociated").style.display = "none";
                    document.getElementById("truckAsso").style.display = "none";
                    document.getElementById("buttonClearTrucks").style.display = "none";
                    $("#select-warehouses").find(':selected').removeAttr('selected');
                }
            }
        }
    }
    else {
        document.getElementById("warehousesAssociated").style.display = "none";
        document.getElementById("trucksAssociated").style.display = "none";
        document.getElementById("truckAsso").style.display = "none";
        document.getElementById("warehouseAsso").style.display = "none";
        document.getElementById("buttonClearTrucks").style.display = "none";
        $("#select-warehouses").find(':selected').removeAttr('selected');
    }
}

function displayRowsTable() {
    if ($("#debt").is(":checked")) {
        $('.debt').show();
    } else {
        $('.debt').hide();
    }
}

function openClosePanelWarehousesActivated() {
    if ($('#warehousesActivated').hasClass('in')) {
        $("#warehousesActivatedPanelLogo").attr('class', 'glyphicon glyphicon-menu-down');
    } else {
        $("#warehousesActivatedPanelLogo").attr('class', 'glyphicon glyphicon-menu-up');
    }
}
function openClosePanelWarehousesInactivated() {
    if ($('#warehousesInactivated').hasClass('in')) {
        $("#warehousesInactivatedPanelLogo").attr('class', 'glyphicon glyphicon-menu-down');
    } else {
        $("#warehousesInactivatedPanelLogo").attr('class', 'glyphicon glyphicon-menu-up');
    }
}

function openClosePanelTrucksActivated() {
    if ($('#trucksActivated').hasClass('in')) {
        $("#trucksActivatedPanelLogo").attr('class', 'glyphicon glyphicon-menu-down');
    } else {
        $("#trucksActivatedPanelLogo").attr('class', 'glyphicon glyphicon-menu-up');
    }
}
function openClosePanelTrucksInactivated() {
    if ($('#trucksInactivated').hasClass('in')) {
        $("#trucksInactivatedPanelLogo").attr('class', 'glyphicon glyphicon-menu-down');
    } else {
        $("#trucksInactivatedPanelLogo").attr('class', 'glyphicon glyphicon-menu-up');
    }
}
function openClosePanelContactInfos() {
    if ($('#contactInfos').hasClass('in')) {
        $("#contactInfosPanelLogo").attr('class', 'glyphicon glyphicon-menu-down');
    } else {
        $("#contactInfosPanelLogo").attr('class', 'glyphicon glyphicon-menu-up');
    }
}


// function formDeleteSubmitBlock(button) {
//     $('input[id=actionDeleteForm]').val(button.value);
//     $("#"+button.id).attr('disabled','disabled');
//     $("#formDeletePalletsaccount").submit();
// }


//plugin bootstrap minus and plus
$('.btn-number').click(function (e) {
    e.preventDefault();
    var fieldName = $(this).attr('data-field');
    var type = $(this).attr('data-type');
    var input = $("input[name='" + fieldName + "']");
    var currentVal = parseInt(input.val());
    if (!isNaN(currentVal)) {
        if (type == 'minus') {
            if (currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            }
            if (parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }
        } else if (type == 'plus') {
            if (currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if (parseInt(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }
        }
    } else {
        input.val(0);
    }
});
$('.input-number').focusin(function () {
    $(this).data('oldValue', $(this).val());
});
$('.input-number').change(function () {

    minValue = parseInt($(this).attr('min'));
    maxValue = parseInt($(this).attr('max'));
    valueCurrent = parseInt($(this).val());

    name = $(this).attr('name');
    if (valueCurrent >= minValue) {
        $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
    } else {
        alert('Sorry, the minimum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    if (valueCurrent <= maxValue) {
        $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
    } else {
        alert('Sorry, the maximum value was reached');
        $(this).val($(this).data('oldValue'));
    }


});