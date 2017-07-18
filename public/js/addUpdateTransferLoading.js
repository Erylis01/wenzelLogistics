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
    if($("#typeDW").is(":checked")){
        valueCredit2 = $("#select-debitAccountDWD").find(":selected").val();
        textCredit2 = $("#select-debitAccountDWD").find(":selected").text();
        $("#select-creditAccount2DW").empty();
        $("#select-creditAccount2DW").append($('<option></option>').attr("value", valueCredit2).text(textCredit2));
        $("#select-creditAccount2DW").find("option[value=\'" + valueCredit2 + "\']").attr('selected',true);

        valueDebit2 = $("#select-creditAccountDW").find(":selected").val();
        textDebit2 = $("#select-creditAccountDW").find(":selected").text();
        $("#select-debitAccount2DW").empty();
        $("#select-debitAccount2DW").append($('<option></option>').attr("value", valueDebit2).text(textDebit2));
        $("#select-debitAccount2DW").find("option[value=\'" + valueDebit2 + "\']").attr('selected',true);
    }else{
        $("#select-creditAccount2DW").empty();
        $("#select-creditAccount2DW").append($('<option></option>').attr("value", "").text());
        $("#select-debitAccount2DW").empty();
        $("#select-debitAccount2DW").append($('<option></option>').attr("value", "").text());
        ("#select-creditAccount2DW").find("option[value=\'" + valueCredit2 + "\']").attr('selected',false);
        $("#select-debitAccount2DW").find("option[value=\'" + valueDebit2 + "\']").attr('selected',false);
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
            document.getElementById("debitAccountW").style.display = "block";
            document.getElementById("creditAccountDW").style.display = "none";
            document.getElementById("creditAccountD").style.display = "none";
            document.getElementById("creditAccountW").style.display = "block";

            document.getElementById("palletsTaken").style.display = "block";
            document.getElementById("palletsGiven").style.display = "none";

            document.getElementById("deposit-withdrawal1").style.display = "none";
            document.getElementById("deposit-withdrawal2").style.display = "none";
            document.getElementById("DW").style.display = "none";

            document.getElementById("exchanging").style.display = "block";

            if ($("#notExchanging").is(":checked")) {
                $("#select-creditAccountW").empty();

                $("#select-creditAccountW").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                document.getElementById("debt").style.display = "block";
                document.getElementById("debt1").style.display = "block";
                document.getElementById("debt2").style.display = "none";
                $("#palletsNumber3a").val($("#palletsNumber").val());
            } else {
                $("#select-creditAccountW").empty();
                $("#select-creditAccountW").append($('<option></option>').attr("value", 'truck-' + truckAssociatedId).text(truckAssociatedName + '-' + truckAssociatedLicensePlate));
                document.getElementById("debt").style.display = "none";
                document.getElementById("debt1").style.display = "none";
                document.getElementById("debt2").style.display = "none";
                $("#palletsNumber3a").val();
            }
        } else {
            if (document.getElementById("typeDonly").value === typeChecked.value) {
                document.getElementById("debitAccountDWD").style.display = "block";
                document.getElementById("debitAccountW").style.display = "none";
                document.getElementById("creditAccountDW").style.display = "none";
                document.getElementById("creditAccountD").style.display = "block";
                document.getElementById("creditAccountW").style.display = "none";

                document.getElementById("palletsTaken").style.display = "none";
                document.getElementById("palletsGiven").style.display = "block";

                document.getElementById("deposit-withdrawal1").style.display = "none";
                document.getElementById("deposit-withdrawal2").style.display = "none";
                document.getElementById("DW").style.display = "none";

                document.getElementById("exchanging").style.display = "block";

                if ($("#notExchanging").is(":checked")) {
                    $("#select-debitAccountDWD").empty();
                    $("#select-debitAccountDWD").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                    document.getElementById("debt").style.display = "block";
                    document.getElementById("debt1").style.display = "none";
                    document.getElementById("debt2").style.display = "block";
                    $("#palletsNumber3b").val($("#palletsNumber").val());
                } else {
                    $("#select-debitAccountDWD").empty();
                    $("#select-debitAccountDWD").append($('<option></option>').attr("value", 'truck-' + truckAssociatedId).text(truckAssociatedName + '-' + truckAssociatedLicensePlate));
                    document.getElementById("debt").style.display = "block";
                    document.getElementById("debt1").style.display = "none";
                    document.getElementById("debt2").style.display = "none";
                    $("#palletsNumber3b").val();
                }
            } else {
                document.getElementById("debitAccountDWD").style.display = "block";
                document.getElementById("debitAccountW").style.display = "none";
                document.getElementById("creditAccountDW").style.display = "block";
                document.getElementById("creditAccountD").style.display = "none";
                document.getElementById("creditAccountW").style.display = "none";

                document.getElementById("palletsTaken").style.display = "none";
                document.getElementById("palletsGiven").style.display = "block";

                document.getElementById("deposit-withdrawal1").style.display = "block";
                document.getElementById("deposit-withdrawal2").style.display = "block";
                document.getElementById("DW").style.display = "block";

                document.getElementById("exchanging").style.display = "block";

                if ($("#notExchanging").is(":checked")) {
                    $("#select-debitAccountDWD").empty();
                    $("#select-debitAccountDWD").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                    var valueAnz = $("#anz").val();
                    var valueNumb = $("#palletsNumber").val();
                    var valueNumb2 = $("#palletsNumber2").val();
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
                    document.getElementById("debt").style.display = "none";
                    document.getElementById("debt1").style.display = "none";
                    document.getElementById("debt2").style.display = "none";
                }
            }
        }
    }
    else {
        document.getElementById("debitAccountDWD").style.display = "block";
        document.getElementById("debitAccountW").style.display = "none";
        document.getElementById("creditAccountDW").style.display = "block";
        document.getElementById("creditAccountD").style.display = "none";
        document.getElementById("creditAccountW").style.display = "none";

        document.getElementById("palletsTaken").style.display = "none";
        document.getElementById("palletsGiven").style.display = "block";

        document.getElementById("deposit-withdrawal1").style.display = "block";
        document.getElementById("deposit-withdrawal2").style.display = "block";
        document.getElementById("DW").style.display = "block";

        document.getElementById("exchanging").style.display = "block";
        if ($("#notExchanging").is(":checked")) {
            $("#select-debitAccountDWD").empty();
            $("#select-debitAccountDWD").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
            var valueAnz = $("#anz").val();
            var valueNumb = $("#palletsNumber").val();
            var valueNumb2 = $("#palletsNumber2").val();
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

function displayFieldsTypeCorrecting(typeSelected) {
    console.log(typeSelected);
    var associated = $("#normalTransferAssociated").find(":selected").val();
    var valueCred = $("#creditAccount" + associated).val();
    var valueDeb = $("#debitAccount" + associated).val();
    var type = $("#type" + associated).val();
    if (typeSelected) {
        if (document.getElementById("Purchase-SaleOptionL").value === typeSelected.value) {
            document.getElementById("creditAccount0").style.display = "none";
            document.getElementById("debitAccount0").style.display = "none";
            document.getElementById("creditAccount4").style.display = "block";
            document.getElementById("debitAccount4").style.display = "block";
            document.getElementById("creditAccount3").style.display = "none";
            document.getElementById("debitAccount3").style.display = "none";
            // document.getElementById("debt1L").style.display = "none";
            // document.getElementById("debt2L").style.display = "none";
            document.getElementById("purchase-sale1L").style.display = "block";
            document.getElementById("purchase-sale2L").style.display = "block";
            document.getElementById("SPL").style.display = "block";
            // $('#select-debit4b').find("option[value=\'" + valueDeb + "\']").attr('selected', false);
            // $('#select-debit4b').change();
            $('#select-debit4b').find("option[value='account-1']").attr('selected', true);
            $('#select-debit4b').change();
            // $('#select-credit4b').find("option[value=\'" + valueCred + "\']").attr('selected', false);
            // $('#select-credit4b').change();
            $('#select-credit4b').find("option[value='account-1']").attr('selected', false);
            $('#select-credit4b').change();
            $('#select-credit2').find("option[value='account-1']").attr('selected', true);
            $('#select-credit2').change();
            $('#select-debit2').find("option[value='account-1']").attr('selected', false);
            $('#select-debit2').change();
        } else {
            if (document.getElementById("DebtOptionL").value === typeSelected.value) {
                document.getElementById("creditAccount0").style.display = "none";
                document.getElementById("debitAccount0").style.display = "none";
                document.getElementById("creditAccount4").style.display = "block";
                document.getElementById("debitAccount4").style.display = "block";
                document.getElementById("creditAccount3").style.display = "none";
                document.getElementById("debitAccount3").style.display = "none";
                // document.getElementById("debt1L").style.display = "none";
                // document.getElementById("debt2L").style.display = "none";
                document.getElementById("purchase-sale1L").style.display = "none";
                document.getElementById("purchase-sale2L").style.display = "none";
                document.getElementById("SPL").style.display = "none";
                if (type === 'Deposit_Only') {
                    // $('#select-debit4b').find("option[value=\'" + valueDeb + "\']").attr('selected', false);
                    // $('#select-debit4b').change();
                    $('#select-debit4b').find("option[value='account-1']").attr('selected', true);
                    $('#select-debit4b').change();
                    $('#select-credit4b').find("option[value='account-1']").attr('selected', false);
                    $('#select-credit4b').change();
                    // $('#select-credit4b').find("option[value=\'" + valueCred + "\']").attr('selected', true);
                    // $('#select-credit4b').change();
                    $('#select-credit2').find("option[value='account-1']").attr('selected', false);
                    $('#select-credit2').change();
                    $('#select-debit2').find("option[value='account-1']").attr('selected', false);
                    $('#select-debit2').change();
                } else {
                    if (type === 'Withdrawal_Only') {
                        $('#select-debit4b').find("option[value='account-1']").attr('selected', false);
                        $('#select-debit4b').change();
                        // $('#select-debit4b').find("option[value=\'" + valueDeb + "\']").attr('selected', true);
                        // $('#select-debit4b').change();
                        // $('#select-credit4b').find("option[value=\'" + valueCred + "\']").attr('selected', false);
                        // $('#select-credit4b').change();
                        $('#select-credit4b').find("option[value='account-1']").attr('selected', true);
                        $('#select-credit4b').change();
                        $('#select-credit2').find("option[value='account-1']").attr('selected', false);
                        $('#select-credit2').change();
                        $('#select-debit2').find("option[value='account-1']").attr('selected', false);
                        $('#select-debit2').change();
                    }
                }
            } else {
                document.getElementById("creditAccount0").style.display = "none";
                document.getElementById("debitAccount0").style.display = "none";
                document.getElementById("creditAccount3").style.display = "block";
                document.getElementById("debitAccount3").style.display = "block";
                document.getElementById("creditAccount4").style.display = "none";
                document.getElementById("debitAccount4").style.display = "none";
                // document.getElementById("debt1L").style.display = "none";
                // document.getElementById("debt2L").style.display = "none";
                document.getElementById("purchase-sale1L").style.display = "none";
                document.getElementById("purchase-sale2L").style.display = "none";
                document.getElementById("SPL").style.display = "none";
                // $('#select-debit4b').find("option[value=\'" + valueDeb + "\']").attr('selected', false);
                // $('#select-debit4b').change();
                $('#select-debit4b').find("option[value='account-1']").attr('selected', false);
                $('#select-debit4b').change();
                $('#select-credit4b').find("option[value='account-1']").attr('selected', false);
                $('#select-credit4b').change();
                // $('#select-credit4b').find("option[value=\'" + valueCred + "\']").attr('selected', false);
                // $('#select-credit4b').change();
                $('#select-credit2').find("option[value='account-1']").attr('selected', false);
                $('#select-credit2').change();
                $('#select-debit2').find("option[value='account-1']").attr('selected', false);
                $('#select-debit2').change();
            }
        }
    } else {
        document.getElementById("creditAccount0").style.display = "block";
        document.getElementById("debitAccount0").style.display = "block";
        document.getElementById("creditAccount3").style.display = "none";
        document.getElementById("debitAccount3").style.display = "none";
        document.getElementById("creditAccount4").style.display = "none";
        document.getElementById("debitAccount4").style.display = "none";
        // document.getElementById("debt1L").style.display = "none";
        // document.getElementById("debt2L").style.display = "none";
        document.getElementById("purchase-sale1L").style.display = "none";
        document.getElementById("purchase-sale2L").style.display = "none";
        document.getElementById("SPL").style.display = "none";
        // $('#select-debit4b').find("option[value=\'" + valueDeb + "\']").attr('selected', false);
        // $('#select-debit4b').change();
        $('#select-debit4b').find("option[value='account-1']").attr('selected', false);
        $('#select-debit4b').change();
        $('#select-credit4b').find("option[value='account-1']").attr('selected', false);
        $('#select-credit4b').change();
        // $('#select-credit4b').find("option[value=\'" + valueCred + "\']").attr('selected', false);
        // $('#select-credit4b').change();
        $('#select-credit2').find("option[value='account-1']").attr('selected', false);
        $('#select-credit2').change();
        $('#select-debit2').find("option[value='account-1']").attr('selected', false);
        $('#select-debit2').change();
    }
}

function displayFieldsNotExchanging() {
    if ($("#notExchanging").is(":checked")) {
        $("#select-debitAccountDWD").empty();
        var valueWenzel = 'account-1';
        var textWenzel = 'WENZEL';
        $("#select-debitAccountDWD").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
        var valueAnz = $("#anz").val();
        var valueNumb=$("#palletsNumber").val();
        var valueNumb2 = $("#palletsNumber2").val();

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
        }else {
            if($("#typeDonly").is(":checked")){
                document.getElementById("debt").style.display = "block";
                document.getElementById("debt1").style.display = "none";
                document.getElementById("debt2").style.display = "block";
                $("#palletsNumber3b").val(valueNumb);
            }else{
                if($("#typeWonly").is(":checked")){
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
        document.getElementById("debt").style.display = "block";
        if ($("#typeW").is(":checked")) {
            document.getElementById("debt1").style.display = "block";
            document.getElementById("debt2").style.display = "none";
        } else {
            if ($("#typeD").is(":checked")) {
                document.getElementById("debt1").style.display = "none";
                document.getElementById("debt2").style.display = "none";
            } else {
                var valueAnz = $("#anz").val();
                var valueNumb = $("#palletsNumber").val();
                var valueNumb2 = $("#palletsNumber2").val();
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
                }else {
                    if($("#typeDonly").is(":checked")){
                        document.getElementById("debt").style.display = "block";
                        document.getElementById("debt1").style.display = "none";
                        document.getElementById("debt2").style.display = "block";
                        $("#palletsNumber3b").val(valueNumb);
                    }else {
                        if($("#typeWonly").is(":checked")){
                            document.getElementById("debt").style.display = "block";
                            document.getElementById("debt1").style.display = "block";
                            document.getElementById("debt2").style.display = "none";
                            $("#palletsNumber3a").val(valueNumb);
                        }
                    }
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

