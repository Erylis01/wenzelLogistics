function formSubmitBlock(button) {
    $('input[id=actionForm]').val(button.value);
    $("#" + button.id).attr('disabled', 'disabled');
    $("#formSubmitUpdateUpload").submit();
}

function openClosePanel1() {
    if ($('#Pan1collapse').hasClass('in')) {
        $("#infoPanelLogo").attr('class', 'glyphicon glyphicon-menu-down');
    } else {
        $("#infoPanelLogo").attr('class', 'glyphicon glyphicon-menu-up');
    }
}

function openClosePanelSub1() {
    if ($('#PanSub1collapse').hasClass('in')) {
        $("#generalPanelLogo").attr('class', 'glyphicon glyphicon-menu-down');
    } else {
        $("#generalPanelLogo").attr('class', 'glyphicon glyphicon-menu-up');
    }
}

function openClosePanelSub2() {
    if ($('#PanSub2collapse').hasClass('in')) {
        $("#loadingPanelLogo").attr('class', 'glyphicon glyphicon-menu-down');
        $("#PanSub3collapse").removeClass('panel-collapse collapse in');
        $("#PanSub3collapse").addClass('panel-collapse collapse');
        $("#offloadingPanelLogo").attr('class', 'glyphicon glyphicon-menu-down');
    } else {
        $("#loadingPanelLogo").attr('class', 'glyphicon glyphicon-menu-up');
        // $("#PanSub3collapse").collapse('show');
        $("#PanSub3collapse").removeClass('panel-collapse collapse');
        $("#PanSub3collapse").addClass('panel-collapse collapse in');
        $("#offloadingPanelLogo").attr('class', 'glyphicon glyphicon-menu-up');
    }
}

function openClosePanelSub3() {
    if ($('#PanSub3collapse').hasClass('in')) {
        $("#offloadingPanelLogo").attr('class', 'glyphicon glyphicon-menu-down');
        $("#PanSub2collapse").removeClass('panel-collapse collapse in');
        $("#PanSub2collapse").addClass('panel-collapse collapse');
        $("#loadingPanelLogo").attr('class', 'glyphicon glyphicon-menu-down');
    } else {
        $("#offloadingPanelLogo").attr('class', 'glyphicon glyphicon-menu-up');
        $("#PanSub2collapse").removeClass('panel-collapse collapse');
        $("#PanSub2collapse").addClass('panel-collapse collapse in');
        $("#loadingPanelLogo").attr('class', 'glyphicon glyphicon-menu-up');
    }


    if ($('#PanSub3collapse').hasClass('in')) {
        $("#offloadingPanelLogo").attr('class', 'glyphicon glyphicon-menu-down');
    } else {
        $("#offloadingPanelLogo").attr('class', 'glyphicon glyphicon-menu-up');
    }
}

function openClosePanel2() {
    if ($('#Pan2collapse').hasClass('in')) {
        $("#palletsPanelLogo").attr('class', 'glyphicon glyphicon-menu-down');
    } else {
        $("#palletsPanelLogo").attr('class', 'glyphicon glyphicon-menu-up');
    }
}

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


// PALLETS LOCATION ONLY ///
function selectAccount() {
    var valueDebit2 = null;
    var textDebit2 = null;
    var valueCredit2 = null;
    var textCredit2 = null;
    if ($("#typeDW").is(":checked")) {
        valueCredit2 = $("#select-debitAccountDWD").find(":selected").val();
        textCredit2 = $("#select-debitAccountDWD").find(":selected").text();
        // $('input[id=creditAccount2DW]').val(valueCredit2);
        $("#input-creditAccount2DW").val(valueCredit2);
        $("#input-creditAccount2DW").change();
        $("#select-creditAccount2DW").empty();
        $("#select-creditAccount2DW").append($('<option></option>').attr("value", valueCredit2).text(textCredit2));
        $("#select-creditAccount2DW").find("option[value=\'" + valueCredit2 + "\']").attr('selected', true);

        valueDebit2 = $("#select-creditAccountDW").find(":selected").val();
        textDebit2 = $("#select-creditAccountDW").find(":selected").text();
        // $('input[id=debitAccount2DW]').val(valueCredit2);
        $("#input-debitAccount2DW").val(valueDebit2);
        $("#input-debitAccount2DW").change();
        $("#select-debitAccount2DW").empty();
        $("#select-debitAccount2DW").append($('<option></option>').attr("value", valueDebit2).text(textDebit2));
        $("#select-debitAccount2DW").find("option[value=\'" + valueDebit2 + "\']").attr('selected', true);
    } else {
        $("#select-creditAccount2DW").empty();
        $("#select-creditAccount2DW").append($('<option></option>').attr("value", "").text());
        $("#select-debitAccount2DW").empty();
        $("#select-debitAccount2DW").append($('<option></option>').attr("value", "").text());
        $("#select-creditAccount2DW").find("option[value=\'" + valueCredit2 + "\']").attr('selected', false);
        $("#select-debitAccount2DW").find("option[value=\'" + valueDebit2 + "\']").attr('selected', false);
    }
    $('#select-creditAccount2DW').selectpicker('refresh');
    $('#select-creditAccount2DW').selectpicker('render');
    $('#select-debitAccount2DW').selectpicker('refresh');
    $('#select-debitAccount2DW').selectpicker('render');

}

function displayFieldsTypeNormal(typeChecked) {
    console.log(typeChecked);
    var valueWenzel = 'account-1';
    var textWenzel = 'WENZEL';
    var truckAssociatedId = $('input[id=truckAssociatedId]').val();
    var truckAssociatedName = $('input[id=truckAssociatedName]').val();
    var truckAssociatedLicensePlate = $('input[id=truckAssociatedLicensePlate]').val();

    if (typeChecked) {
        if (document.getElementById("typeWonly").value === typeChecked.value) {
            document.getElementById("debitAccountDWD").style.display = "none";
            document.getElementById("debitAccountWDebtOther").style.display = "block";
            document.getElementById("creditAccountDW").style.display = "none";
            document.getElementById("creditAccountDDebtOther").style.display = "none";
            document.getElementById("creditAccountW").style.display = "block";

            document.getElementById("palletsTaken").style.display = "block";
            document.getElementById("palletsGiven").style.display = "none";

            document.getElementById("deposit-withdrawal1").style.display = "none";
            document.getElementById("deposit-withdrawal2").style.display = "none";
            document.getElementById("DW").style.display = "none";

            if ($("#notExchanging").is(":checked")) {
                $("#select-creditAccountW").empty();
                $("#select-creditAccountW").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                $("#select-creditAccountW").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                document.getElementById("debt").style.display = "block";
                document.getElementById("debt1").style.display = "block";
                document.getElementById("debt2").style.display = "none";
                $("#palletsNumber3a").val($("#palletsNumber").val());
            } else {
                $("#select-creditAccountW").empty();
                $("#select-creditAccountW").append($('<option></option>').attr("value", 'truck-' + truckAssociatedId).text(truckAssociatedName + '-' + truckAssociatedLicensePlate));
                $("#select-creditAccountW").find("option[value=\'truck-" + truckAssociatedId + "\']").attr('selected', true);
                document.getElementById("debt").style.display = "none";
                document.getElementById("debt1").style.display = "none";
                document.getElementById("debt2").style.display = "none";
                $("#palletsNumber3a").val();
            }
        } else {
            if (document.getElementById("typeDonly").value === typeChecked.value) {
                document.getElementById("debitAccountDWD").style.display = "block";
                document.getElementById("debitAccountWDebtOther").style.display = "none";
                document.getElementById("creditAccountDW").style.display = "none";
                document.getElementById("creditAccountDDebtOther").style.display = "block";
                document.getElementById("creditAccountW").style.display = "none";

                document.getElementById("palletsTaken").style.display = "none";
                document.getElementById("palletsGiven").style.display = "block";

                document.getElementById("deposit-withdrawal1").style.display = "none";
                document.getElementById("deposit-withdrawal2").style.display = "none";
                document.getElementById("DW").style.display = "none";

                if ($("#notExchanging").is(":checked")) {
                    $("#select-debitAccountDWD").empty();
                    $("#select-debitAccountDWD").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                    $("#select-debitAccountDWD").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                    document.getElementById("debt").style.display = "block";
                    document.getElementById("debt1").style.display = "none";
                    document.getElementById("debt2").style.display = "block";
                    $("#palletsNumber3b").val($("#palletsNumber").val());
                } else {
                    $("#select-debitAccountDWD").empty();
                    $("#select-debitAccountDWD").append($('<option></option>').attr("value", 'truck-' + truckAssociatedId).text(truckAssociatedName + '-' + truckAssociatedLicensePlate));
                    $("#select-debitAccountDWD").find("option[value=\'truck-" + truckAssociatedId + "\']").attr('selected', true);
                    document.getElementById("debt").style.display = "block";
                    document.getElementById("debt1").style.display = "none";
                    document.getElementById("debt2").style.display = "none";
                    $("#palletsNumber3b").val();
                }
            } else {
                if (document.getElementById("typeDW").value === typeChecked.value) {
                    document.getElementById("debitAccountDWD").style.display = "block";
                    document.getElementById("debitAccountWDebtOther").style.display = "none";
                    document.getElementById("creditAccountDW").style.display = "block";
                    document.getElementById("creditAccountDDebtOther").style.display = "none";
                    document.getElementById("creditAccountW").style.display = "none";

                    document.getElementById("palletsTaken").style.display = "none";
                    document.getElementById("palletsGiven").style.display = "block";

                    document.getElementById("deposit-withdrawal1").style.display = "block";
                    document.getElementById("deposit-withdrawal2").style.display = "block";
                    document.getElementById("DW").style.display = "block";

                    if ($("#notExchanging").is(":checked")) {
                        $("#select-debitAccountDWD").empty();
                        $("#select-debitAccountDWD").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                        $("#select-debitAccountDWD").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                        var valueAnz = $("#anz").val();
                        var valueNumb = $("#palletsNumber").val();
                        var valueNumb2 = $("#palletsNumber2DW").val();
                        if (valueNumb < valueAnz) {
                            document.getElementById("debt").style.display = "block";
                            document.getElementById("debt1").style.display = "block";
                            document.getElementById("debt2").style.display = "none";
                            $("#palletsNumber3a").val(valueAnz - valueNumb);
                        } else {
                            if (valueNumb2 < valueAnz) {
                                document.getElementById("debt").style.display = "block";
                                document.getElementById("debt1").style.display = "none";
                                document.getElementById("debt2").style.display = "block";
                                $("#palletsNumber3b").val(valueAnz - valueNumb2);
                            } else {
                                document.getElementById("debt").style.display = "none";
                                document.getElementById("debt1").style.display = "none";
                                document.getElementById("debt2").style.display = "none";
                                $("#palletsNumber3a").val();
                                $("#palletsNumber3b").val();
                            }
                        }
                    } else {
                        $("#select-debitAccountDWD").empty();
                        $("#select-debitAccountDWD").append($('<option></option>').attr("value", 'truck-' + truckAssociatedId).text(truckAssociatedName + '-' + truckAssociatedLicensePlate));
                        $("#select-debitAccountDWD").find("option[value=\'truck-" + truckAssociatedId + "\']").attr('selected', true);
                        document.getElementById("debt").style.display = "none";
                        document.getElementById("debt1").style.display = "none";
                        document.getElementById("debt2").style.display = "none";
                    }
                }
            }
        }
    }
    else {
        document.getElementById("debitAccountDWD").style.display = "block";
        document.getElementById("debitAccountWDebtOther").style.display = "none";
        document.getElementById("creditAccountDW").style.display = "block";
        document.getElementById("creditAccountDDebtOther").style.display = "none";
        document.getElementById("creditAccountW").style.display = "none";

        document.getElementById("palletsTaken").style.display = "none";
        document.getElementById("palletsGiven").style.display = "block";

        document.getElementById("deposit-withdrawal1").style.display = "block";
        document.getElementById("deposit-withdrawal2").style.display = "block";
        document.getElementById("DW").style.display = "block";

        if ($("#notExchanging").is(":checked")) {
            $("#select-debitAccountDWD").empty();
            $("#select-debitAccountDWD").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
            $("#select-debitAccountDWD").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
            var valueAnz = $("#anz").val();
            var valueNumb = $("#palletsNumber").val();
            var valueNumb2 = $("#palletsNumber2DW").val();
            if (valueNumb < valueAnz) {
                document.getElementById("debt").style.display = "block";
                document.getElementById("debt1").style.display = "block";
                document.getElementById("debt2").style.display = "none";
                $("#palletsNumber3a").val(valueAnz - valueNumb);
            } else {
                if (valueNumb2 < valueAnz) {
                    document.getElementById("debt").style.display = "block";
                    document.getElementById("debt1").style.display = "none";
                    document.getElementById("debt2").style.display = "block";
                    $("#palletsNumber3b").val(valueAnz - valueNumb2);
                } else {
                    document.getElementById("debt").style.display = "none";
                    document.getElementById("debt1").style.display = "none";
                    document.getElementById("debt2").style.display = "none";
                    $("#palletsNumber3a").val();
                    $("#palletsNumber3b").val();
                }
            }
        } else {
            $("#select-debitAccountDWD").empty();
            $("#select-debitAccountDWD").append($('<option></option>').attr("value", 'truck-' + truckAssociatedId).text(truckAssociatedName + '-' + truckAssociatedLicensePlate));
            $("#select-debitAccountDWD").find("option[value=\'truck-" + truckAssociatedId + "\']").attr('selected', true);
            document.getElementById("debt").style.display = "none";
            document.getElementById("debt1").style.display = "none";
            document.getElementById("debt2").style.display = "none";
        }
    }
    $('#select-creditAccountW').selectpicker('refresh');
    $('#select-creditAccountW').selectpicker('render');
    $('#select-debitAccountDWD').selectpicker('refresh');
    $('#select-debitAccountDWD').selectpicker('render');
}

function displayFieldsTypeCorrecting(typeChecked) {
    console.log(typeChecked);

    if (typeChecked) {
        if (document.getElementById("typePS").value === typeChecked.value) {
            document.getElementById("creditAccountPS").style.display = "block";
            document.getElementById("debitAccountPS").style.display = "block";
            document.getElementById("creditAccountDDebtOther").style.display = "none";
            document.getElementById("debitAccountWDebtOther").style.display = "none";

            document.getElementById("purchase-sale1").style.display = "block";
            document.getElementById("purchase-sale2").style.display = "block";
            document.getElementById("PS").style.display = "block";

            document.getElementById("palletsBought").style.display = "block";
            document.getElementById("pallets").style.display = "none";
        } else {
            if (document.getElementById("typeDebt").value === typeChecked.value || document.getElementById("typeOther").value === typeChecked.value) {
                //other and debt
                document.getElementById("creditAccountDDebtOther").style.display = "block";
                document.getElementById("debitAccountWDebtOther").style.display = "block";
                document.getElementById("pallets").style.display = "block";

                document.getElementById("creditAccountPS").style.display = "none";
                document.getElementById("debitAccountPS").style.display = "none";
                document.getElementById("purchase-sale1").style.display = "none";
                document.getElementById("purchase-sale2").style.display = "none";
                document.getElementById("PS").style.display = "none";
                document.getElementById("palletsBought").style.display = "none";
            }
        }
    }
    else {
        document.getElementById("creditAccountPS").style.display = "block";
        document.getElementById("debitAccountPS").style.display = "block";
        document.getElementById("creditAccountDDebtOther").style.display = "none";
        document.getElementById("debitAccountWDebtOther").style.display = "none";

        document.getElementById("purchase-sale1").style.display = "block";
        document.getElementById("purchase-sale2").style.display = "block";
        document.getElementById("PS").style.display = "block";

        document.getElementById("palletsBought").style.display = "block";
        document.getElementById("pallets").style.display = "none";
    }
}

// function inverseAccounts(numberTransfer){
//     if ($("#typeDW").is(":checked")) {
//         var valueDebit=$("#select-debitAccountDWD").find(":selected").val();
//         var textDebit=$("#select-debitAccountDWD").find(":selected").text();
//         var valueCredit=$("#select-creditAccountDW").find(":selected").val();
//         var textCredit = $("#select-creditAccountDW").find(":selected").text();
//
//         $("#select-debitAccountDWD").empty();
//         $("#select-debitAccountDWD").append($('<option></option>').attr("value", valueCredit).text(textCredit));
//         $("#select-debitAccountDWD").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
//         $("#select-creditAccountDW").empty();
//         $("#select-creditAccountDW").append($('<option></option>').attr("value", valueDebit).text(textDebit));
//         $("#select-creditAccountDW").find("option[value=\'" + valueDebit + "\']").attr('selected', true);
//
//         $('#select-debitAccountDWD').selectpicker('refresh');
//         $('#select-debitAccountDWD').selectpicker('render');
//         $('#select-creditAccountDW').selectpicker('refresh');
//         $('#select-creditAccountDW').selectpicker('render');
//     }else{
//         if($("#typePS").is(":checked")){
//             if(numberTransfer.attr('name')==='inverseAccount1'){
//                 var valueDebit=$("#select-debitAccountPS").find(":selected").val();
//                 var textDebit=$("#select-debitAccountPS").find(":selected").text();
//                 var valueCredit=$("#select-creditAccountPS").find(":selected").val();
//                 var textCredit = $("#select-creditAccountPS").find(":selected").text();
//
//                 $("#select-debitAccountPS").empty();
//                 $("#select-debitAccountPS").append($('<option></option>').attr("value", valueCredit).text(textCredit));
//                 $("#select-debitAccountPS").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
//                 $("#select-creditAccountPS").empty();
//                 $("#select-creditAccountPS").append($('<option></option>').attr("value", valueDebit).text(textDebit));
//                 $("#select-creditAccountPS").find("option[value=\'" + valueDebit + "\']").attr('selected', true);
//
//                 $('#select-debitAccountPS').selectpicker('refresh');
//                 $('#select-debitAccountPS').selectpicker('render');
//                 $('#select-creditAccountPS').selectpicker('refresh');
//                 $('#select-creditAccountPS').selectpicker('render');
//             }else{
//                 if(numberTransfer.attr('name')==='inverseAccount2'){
//                     var valueDebit=$("#select-debitAccount2PS").find(":selected").val();
//                     var textDebit=$("#select-debitAccount2PS").find(":selected").text();
//                     var valueCredit=$("#select-creditAccount2PS").find(":selected").val();
//                     var textCredit = $("#select-creditAccount2PS").find(":selected").text();
//
//                     $("#select-debitAccount2PS").empty();
//                     $("#select-debitAccount2PS").append($('<option></option>').attr("value", valueCredit).text(textCredit));
//                     $("#select-debitAccount2PS").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
//                     $("#select-creditAccount2PS").empty();
//                     $("#select-creditAccount2PS").append($('<option></option>').attr("value", valueDebit).text(textDebit));
//                     $("#select-creditAccount2PS").find("option[value=\'" + valueDebit + "\']").attr('selected', true);
//
//                     $('#select-debitAccount2PS').selectpicker('refresh');
//                     $('#select-debitAccount2PS').selectpicker('render');
//                     $('#select-creditAccount2PS').selectpicker('refresh');
//                     $('#select-creditAccount2PS').selectpicker('render');
//                 }
//             }
//         }else{
//             if($("#typeDebt").is(":checked") || $("#typeOther").is(":checked")){
//                 var valueDebit=$("#select-debitAccountWDebtOther").find(":selected").val();
//                 var textDebit=$("#select-debitAccountWDebtOther").find(":selected").text();
//                 var valueCredit=$("#select-creditAccountDDebtOther").find(":selected").val();
//                 var textCredit = $("#select-creditAccountDDebtOther").find(":selected").text();
//
//                 $("#select-debitAccountWDebtOther").empty();
//                 $("#select-debitAccountWDebtOther").append($('<option></option>').attr("value", valueCredit).text(textCredit));
//                 $("#select-debitAccountWDebtOther").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
//                 $("#select-creditAccountDDebtOther").empty();
//                 $("#select-creditAccountDDebtOther").append($('<option></option>').attr("value", valueDebit).text(textDebit));
//                 $("#select-creditAccountDDebtOther").find("option[value=\'" + valueDebit + "\']").attr('selected', true);
//
//                 $('#select-debitAccountWDebtOther').selectpicker('refresh');
//                 $('#select-debitAccountWDebtOther').selectpicker('render');
//                 $('#select-creditAccountDDebtOther').selectpicker('refresh');
//                 $('#select-creditAccountDDebtOther').selectpicker('render');
//             }
//         }
//     }
// }

function displayFieldsNotExchanging() {
    if ($("#notExchanging").is(":checked")) {
        $("#select-debitAccountDWD").empty();
        var valueWenzel = 'account-1';
        var textWenzel = 'WENZEL';
        $("#select-debitAccountDWD").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
        $("#select-debitAccountDWD").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
        var valueAnz = $("#anz").val();
        var valueNumb = $("#palletsNumber").val();
        var valueNumb2 = $("#palletsNumber2DW").val();

        if ($("#typeDW").is(":checked")) {
            if (valueNumb < valueAnz) {
                document.getElementById("debt").style.display = "block";
                document.getElementById("debt1").style.display = "block";
                document.getElementById("debt2").style.display = "none";
                $("#palletsNumber3a").val(valueAnz - valueNumb);
            } else {
                if (valueNumb2 < valueAnz) {
                    document.getElementById("debt").style.display = "block";
                    document.getElementById("debt1").style.display = "none";
                    document.getElementById("debt2").style.display = "block";
                    $("#palletsNumber3b").val(valueAnz - valueNumb2);
                } else {
                    document.getElementById("debt").style.display = "none";
                    document.getElementById("debt1").style.display = "none";
                    document.getElementById("debt2").style.display = "none";
                    $("#palletsNumber3a").val();
                    $("#palletsNumber3b").val();
                }
            }
        } else {
            if ($("#typeDonly").is(":checked")) {
                document.getElementById("debt").style.display = "block";
                document.getElementById("debt1").style.display = "none";
                document.getElementById("debt2").style.display = "block";
                $("#palletsNumber3b").val(valueNumb);
            } else {
                if ($("#typeWonly").is(":checked")) {
                    document.getElementById("debt").style.display = "block";
                    document.getElementById("debt1").style.display = "block";
                    document.getElementById("debt2").style.display = "none";
                    $("#palletsNumber3a").val(valueNumb);
                }
            }
        }


    } else {
        var truckAssociatedId = $('input[id=truckAssociatedId]').val();
        var truckAssociatedName = $('input[id=truckAssociatedName]').val();
        var truckAssociatedLicensePlate = $('input[id=truckAssociatedLicensePlate]').val();
        $("#select-debitAccountDWD").empty();
        $("#select-debitAccountDWD").append($('<option></option>').attr("value", 'truck-' + truckAssociatedId).text(truckAssociatedName + '-' + truckAssociatedLicensePlate));
        $("#select-debitAccountDWD").find("option[value=\'truck-" + truckAssociatedId + "\']").attr('selected', true);
        document.getElementById("debt").style.display = "none";
        document.getElementById("debt1").style.display = "none";
        document.getElementById("debt2").style.display = "none";
    }
    $('#select-creditAccountW').selectpicker('refresh');
    $('#select-creditAccountW').selectpicker('render');
    $('#select-debitAccountDWD').selectpicker('refresh');
    $('#select-debitAccountDWD').selectpicker('render');
}

function updateFieldsPalletsNumber() {
    if ($("#notExchanging").is(":checked")) {
        var valueAnz = $("#anz").val();
        var valueNumb = $("#palletsNumber").val();
        var valueNumb2 = $("#palletsNumber2DW").val();
        if ($("#typeDW").is(":checked")) {
            if (valueNumb < valueAnz) {
                document.getElementById("debt").style.display = "block";
                document.getElementById("debt1").style.display = "block";
                document.getElementById("debt2").style.display = "none";
                $("#palletsNumber3a").val(valueAnz - valueNumb);
            } else {
                if (valueNumb2 < valueAnz) {
                    document.getElementById("debt").style.display = "block";
                    document.getElementById("debt1").style.display = "none";
                    document.getElementById("debt2").style.display = "block";
                    $("#palletsNumber3b").val(valueAnz - valueNumb2);
                } else {
                    document.getElementById("debt").style.display = "none";
                    document.getElementById("debt1").style.display = "none";
                    document.getElementById("debt2").style.display = "none";
                    $("#palletsNumber3a").val();
                    $("#palletsNumber3b").val();
                }
            }
        } else {
            if ($("#typeDonly").is(":checked")) {
                document.getElementById("debt").style.display = "block";
                document.getElementById("debt1").style.display = "none";
                document.getElementById("debt2").style.display = "block";
                $("#palletsNumber3b").val(valueNumb);
            } else {
                if ($("#typeWonly").is(":checked")) {
                    document.getElementById("debt").style.display = "block";
                    document.getElementById("debt1").style.display = "block";
                    document.getElementById("debt2").style.display = "none";
                    $("#palletsNumber3a").val(valueNumb);
                } else {
                    document.getElementById("debt").style.display = "none";
                    document.getElementById("debt1").style.display = "none";
                    document.getElementById("debt2").style.display = "none";
                    $("#palletsNumber3a").val();
                    $("#palletsNumber3b").val();
                }
            }
        }
        $('#select-creditAccountW').selectpicker('refresh');
        $('#select-creditAccountW').selectpicker('render');
        $('#select-debitAccountDWD').selectpicker('refresh');
        $('#select-debitAccountDWD').selectpicker('render');
    }
}

// ------------------------------PANELS---------

// var lastDebitAccountK = null;
// var lastCreditAccountK = null;
// var id=null;
// function selectAccountK(accountSelected, idSelected) {
//     id = idSelected.substr(12);
//     if (lastDebitAccountK !== accountSelected) {
//         $("#select-credit"+id+" option[value=\'" + accountSelected + "\']").hide().prop('disabled', true);
//         if (lastDebitAccountK !== null) {
//             $("#select-credit"+id+" option[value=\'" + lastDebitAccountK + "\']").show().prop('disabled', false);
//         }
//     }
//     lastDebitAccountK = accountSelected;
// }
//
// function creditaccountK(accountSelected, idSelected) {
//     id = idSelected.substr(12);
//     if (lastCreditAccountK !== accountSelected) {
//         $("#select-debit"+id+" option[value=\'" + accountSelected + "\']").hide().prop('disabled', true);
//         if (lastCreditAccountK !== null) {
//             $("#select-debit"+id+" option[value=\'" + lastCreditAccountK + "\']").show().prop('disabled', false);
//         }
//     }
//     lastCreditAccountK = accountSelected;
// }
//
// // function displayFieldsTypeIdNormal(typeSelected, typeId) {
// //     var id = typeId.substr(4);
// //     console.log(typeSelected);
// //     if (typeSelected) {
// //         document.getElementById("creditAccount1" + id).style.display = "block";
// //         document.getElementById("creditAccount2" + id).style.display = "block";
// //         document.getElementById("debitAccount1" + id).style.display = "block";
// //         document.getElementById("debitAccount2" + id).style.display = "block";
// //     }
// //     else {
// //         document.getElementById("creditAccount1" + id).style.display = "none";
// //         document.getElementById("creditAccount2" + id).style.display = "none";
// //         document.getElementById("debitAccount1" + id).style.display = "none";
// //         document.getElementById("debitAccount2" + id).style.display = "none";
// //     }
// // }
// //
// function displayFieldsTypeIdCorrecting(typeSelected, typeId) {
//     var id = typeId.substr(4);
//     console.log(typeSelected);
//     if (typeSelected) {
//         if (document.getElementById("Purchase-SaleOption" + id).value == typeSelected.value) {
//             $('#select-debit' + id).find("option[value='account-1']").attr('selected', true);
//             $('#select-debit' + id).change();
//             $('#select-credit' + id).find("option[value='account-1']").attr('selected', false);
//             $('#select-credit' + id).change();
//         } else {
//             if (document.getElementById("Sale-PurchaseOption" + id).value == typeSelected.value) {
//                 $('#select-debit' + id).find("option[value='account-1']").attr('selected', false);
//                 $('#select-debit' + id).change();
//                 $('#select-credit' + id).find("option[value='account-1']").attr('selected', true);
//                 $('#select-credit' + id).change();
//             }
//             else {
//                 $('#select-debit' + id).find("option[value='account-1']").attr('selected', false);
//                 $('#select-debit' + id).change();
//                 $('#select-credit' + id).find("option[value='account-1']").attr('selected', false);
//                 $('#select-credit' + id).change();
//             }
//         }
//     }
//     else {
//         $('#select-debit' + id).find("option[value='account-1']").attr('selected', false);
//         $('#select-debit' + id).change();
//         $('#select-credit' + id).find("option[value='account-1']").attr('selected', false);
//         $('#select-credit' + id).change();
//     }
// }

