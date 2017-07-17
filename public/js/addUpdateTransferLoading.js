function formSubmitBlock(button) {
    $('input[id=actionForm]').val(button.value);
    $("#"+button.id).attr('disabled','disabled');
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


// PALLETS LOCATION ONLY ///
function selectAccount(index) {
    if (index !== '3') {
        $("#select-debit3").empty();
        $("#select-credit3").empty();
        if ($("#notExchanging").is(":checked")) {
            if (index == '1b') {
                var value1 = $("#select-debit1b").find(":selected").val();
                var text1 = $("#select-debit1b").find(":selected").text();
                $("#select-credit3").append($('<option></option>').attr("value", value1).text(text1));
                $("#select-debit3").append($('<option></option>').attr("value", value1).text(text1));
                var value2 = $("#select-credit2b").find(":selected").val();
                var text2 = $("#select-credit2b").find(":selected").text();
                $("#select-debit3").append($('<option></option>').attr("value", value2).text(text2));
                $("#select-credit3").append($('<option></option>').attr("value", value2).text(text2));
            } else {
                if (index == '2b') {
                    var value1 = $("#select-debit2b").find(":selected").val();
                    var text1 = $("#select-debit2b").find(":selected").text();
                    $("#select-credit3").append($('<option></option>').attr("value", value1).text(text1));
                    $("#select-debit3").append($('<option></option>').attr("value", value1).text(text1));
                    var value2 = $("#select-credit1b").find(":selected").val();
                    var text2 = $("#select-credit1b").find(":selected").text();
                    $("#select-debit3").append($('<option></option>').attr("value", value2).text(text2));
                    $("#select-credit3").append($('<option></option>').attr("value", value2).text(text2));
                }
            }
            var value3 = 'account-1';
            var text3 = 'WENZEL';
            $("#select-debit3").append($('<option></option>').attr("value", value3).text(text3));
            $("#select-credit3").append($('<option></option>').attr("value", value3).text(text3));
        }
        $('#select-debit3').selectpicker('refresh');
        $('#select-debit3').selectpicker('render');
        $('#select-credit3').selectpicker('refresh');
        $('#select-credit3').selectpicker('render');

        if ($("#palletsNumber").val() !== 0 && $("#palletsNumber2").val() == 0 && $("#notExchanging").is(":checked")) {
            $('#select-debit3').find("option[value=\'" + value3 + "\']").attr('selected', true);
            $('#select-debit3').change();
            $('#select-credit3').find("option[value=\'" + value2 + "\']").attr('selected', true);
            $('#select-credit3').change();
        } else {
            if ($("#palletsNumber").val() == 0 && $("#palletsNumber2").val() !== 0 && $("#notExchanging").is(":checked")) {
                $('#select-debit3').find("option[value=\'" + value2 + "\']").attr('selected', true);
                $('#select-debit3').change();
                $('#select-credit3').find("option[value=\'" + value3 + "\']").attr('selected', true);
                $('#select-credit3').change();
            }
        }
    }
}

function creditaccount(index) {
    if (index !== '3') {
        $("#select-debit3").empty();
        $("#select-credit3").empty();
        if ($("#notExchanging").is(":checked")) {
            if (index == '2b') {
                var value1 = $("#select-debit1b").find(":selected").val();
                var text1 = $("#select-debit1b").find(":selected").text();
                $("#select-credit3").append($('<option></option>').attr("value", value1).text(text1));
                $("#select-debit3").append($('<option></option>').attr("value", value1).text(text1));
                var value2 = $("#select-credit2b").find(":selected").val();
                var text2 = $("#select-credit2b").find(":selected").text();
                $("#select-debit3").append($('<option></option>').attr("value", value2).text(text2));
                $("#select-credit3").append($('<option></option>').attr("value", value2).text(text2));
            } else {
                if (index == '1b') {
                    var value1 = $("#select-debit2b").find(":selected").val();
                    var text1 = $("#select-debit2b").find(":selected").text();
                    $("#select-credit3").append($('<option></option>').attr("value", value1).text(text1));
                    $("#select-debit3").append($('<option></option>').attr("value", value1).text(text1));
                    var value2 = $("#select-credit1b").find(":selected").val();
                    var text2 = $("#select-credit1b").find(":selected").text();
                    $("#select-debit3").append($('<option></option>').attr("value", value2).text(text2));
                    $("#select-credit3").append($('<option></option>').attr("value", value2).text(text2));
                }
            }
            var value3 = 'account-1';
            var text3 = 'WENZEL';
            $("#select-debit3").append($('<option></option>').attr("value", value3).text(text3));
            $("#select-credit3").append($('<option></option>').attr("value", value3).text(text3));
        }
        $('#select-debit3').selectpicker('refresh');
        $('#select-debit3').selectpicker('render');
        $('#select-credit3').selectpicker('refresh');
        $('#select-credit3').selectpicker('render');

        if ($("#palletsNumber").val() !== 0 && $("#palletsNumber2").val() == 0 && $("#notExchanging").is(":checked")) {
            $('#select-debit3').find("option[value=\'" + value3 + "\']").attr('selected', true);
            $('#select-debit3').change();
            $('#select-credit3').find("option[value=\'" + value2 + "\']").attr('selected', true);
            $('#select-credit3').change();
        } else {
            if ($("#palletsNumber").val() == 0 && $("#palletsNumber2").val() !== 0 && $("#notExchanging").is(":checked")) {
                $('#select-debit3').find("option[value=\'" + value2 + "\']").attr('selected', true);
                $('#select-debit3').change();
                $('#select-credit3').find("option[value=\'" + value3 + "\']").attr('selected', true);
                $('#select-credit3').change();
            }
        }
    }
}

function displayFieldsTypeNormal(typeSelected) {
    console.log(typeSelected);
    if (typeSelected) {
        if (document.getElementById("Deposit-WithdrawalOptionL").value == typeSelected.value) {
            document.getElementById("creditAccount0").style.display = "none";
            document.getElementById("creditAccount1").style.display = "none";
            document.getElementById("creditAccount2").style.display = "block";
            document.getElementById("creditAccount3").style.display = "none";
            document.getElementById("debitAccount0").style.display = "none";
            document.getElementById("debitAccount1").style.display = "block";
            document.getElementById("debitAccount2").style.display = "none";
            document.getElementById("debitAccount3").style.display = "none";
            document.getElementById("debitAccount4").style.display = "none";

            document.getElementById("deposit-withdrawal1L").style.display = "block";
            document.getElementById("deposit-withdrawal2L").style.display = "block";
            document.getElementById("withdrawal-deposit1L").style.display = "none";
            document.getElementById("withdrawal-deposit2L").style.display = "none";
            document.getElementById("DWL").style.display = "block";
            document.getElementById("exchangingL").style.display = "block";
            // $('#select-debit1b').find("option[value='account-1']").attr('selected',true);
            // $('#select-debit1b').change();
            // $('#select-credit1b').find("option[value='account-1']").attr('selected',false);
            // $('#select-credit1b').change();
            if ($("#notExchanging").is(":checked")) {
                if ($("#palletsNumber").val() == 0 || $("#palletsNumber2").val() == 0) {
                    document.getElementById("debt").style.display = "block";
                } else {
                    document.getElementById("debt").style.display = "none";
                }
            } else {
                document.getElementById("debt").style.display = "none";
            }
        }
        else {
            if (document.getElementById("Withdrawal-DepositOptionL").value == typeSelected.value) {
                document.getElementById("creditAccount0").style.display = "none";
                document.getElementById("creditAccount1").style.display = "block";
                document.getElementById("creditAccount2").style.display = "none";
                document.getElementById("creditAccount3").style.display = "none";
                document.getElementById("debitAccount0").style.display = "none";
                document.getElementById("debitAccount1").style.display = "none";
                document.getElementById("debitAccount2").style.display = "block";
                document.getElementById("debitAccount3").style.display = "none";

                document.getElementById("deposit-withdrawal1L").style.display = "none";
                document.getElementById("deposit-withdrawal2L").style.display = "none";
                document.getElementById("withdrawal-deposit1L").style.display = "block";
                document.getElementById("withdrawal-deposit2L").style.display = "block";
                document.getElementById("DWL").style.display = "block";
                document.getElementById("exchangingL").style.display = "block";
                if ($("#notExchanging").is(":checked")) {
                    if ($("#palletsNumber").val() == 0 || $("#palletsNumber2").val() == 0) {
                        document.getElementById("debt").style.display = "block";
                    } else {
                        document.getElementById("debt").style.display = "none";
                    }
                } else {
                    document.getElementById("debt").style.display = "none";
                }
            }
            else {
                if (document.getElementById("Withdrawal_OnlyOptionL").value == typeSelected.value) {
                    document.getElementById("creditAccount0").style.display = "none";
                    document.getElementById("creditAccount1").style.display = "block";
                    document.getElementById("creditAccount2").style.display = "none";
                    document.getElementById("creditAccount3").style.display = "none";
                    document.getElementById("debitAccount0").style.display = "none";
                    document.getElementById("debitAccount1").style.display = "none";
                    document.getElementById("debitAccount2").style.display = "none";
                    document.getElementById("debitAccount3").style.display = "block";

                    document.getElementById("deposit-withdrawal1L").style.display = "none";
                    document.getElementById("deposit-withdrawal2L").style.display = "none";
                    document.getElementById("withdrawal-deposit1L").style.display = "none";
                    document.getElementById("withdrawal-deposit2L").style.display = "none";
                    document.getElementById("DWL").style.display = "none";
                    document.getElementById("exchangingL").style.display = "none";
                    document.getElementById("debt").style.display = "none";
                } else {
                    if (document.getElementById("Deposit_OnlyOptionL").value == typeSelected.value) {
                        document.getElementById("creditAccount0").style.display = "none";
                        document.getElementById("creditAccount1").style.display = "none";
                        document.getElementById("creditAccount2").style.display = "none";
                        document.getElementById("creditAccount3").style.display = "block";
                        document.getElementById("debitAccount0").style.display = "none";
                        document.getElementById("debitAccount1").style.display = "block";
                        document.getElementById("debitAccount2").style.display = "none";
                        document.getElementById("debitAccount3").style.display = "none";

                        document.getElementById("deposit-withdrawal1L").style.display = "none";
                        document.getElementById("deposit-withdrawal2L").style.display = "none";
                        document.getElementById("withdrawal-deposit1L").style.display = "none";
                        document.getElementById("withdrawal-deposit2L").style.display = "none";
                        document.getElementById("DWL").style.display = "none";
                        document.getElementById("exchangingL").style.display = "none";
                        document.getElementById("debt").style.display = "none";
                    } else {
                        document.getElementById("creditAccount0").style.display = "block";
                        document.getElementById("creditAccount1").style.display = "none";
                        document.getElementById("creditAccount2").style.display = "none";
                        document.getElementById("creditAccount3").style.display = "none";
                        document.getElementById("debitAccount0").style.display = "block";
                        document.getElementById("debitAccount1").style.display = "none";
                        document.getElementById("debitAccount2").style.display = "none";
                        document.getElementById("debitAccount3").style.display = "none";

                        document.getElementById("deposit-withdrawal1L").style.display = "none";
                        document.getElementById("deposit-withdrawal2L").style.display = "none";
                        document.getElementById("withdrawal-deposit1L").style.display = "none";
                        document.getElementById("withdrawal-deposit2L").style.display = "none";
                        document.getElementById("DWL").style.display = "none";
                        document.getElementById("exchangingL").style.display = "none";
                        document.getElementById("debt").style.display = "none";
                    }
                }
            }
        }
    }
    else {
        document.getElementById("creditAccount0").style.display = "block";
        document.getElementById("creditAccount1").style.display = "none";
        document.getElementById("creditAccount2").style.display = "none";
        document.getElementById("creditAccount3").style.display = "none";
        document.getElementById("debitAccount0").style.display = "block";
        document.getElementById("debitAccount1").style.display = "none";
        document.getElementById("debitAccount2").style.display = "none";
        document.getElementById("debitAccount3").style.display = "none";
        document.getElementById("deposit-withdrawal1L").style.display = "none";
        document.getElementById("deposit-withdrawal2L").style.display = "none";
        document.getElementById("withdrawal-deposit1L").style.display = "none";
        document.getElementById("withdrawal-deposit2L").style.display = "none";
        document.getElementById("DWL").style.display = "none";
        document.getElementById("exchangingL").style.display = "none";
        document.getElementById("debt").style.display = "none";
    }
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

function displayFieldsDebt(notExchangingChecked) {
    if ($("#notExchanging").is(":checked")) {
        document.getElementById("debt").style.display = "block";
        $("#palletsNumber3").val($("#palletsNumber").val());
    } else {
        document.getElementById("debt").style.display = "none";
        $("#palletsNumber3").val();
    }
}

function updateFieldsPalletsNumber(palNumber) {
    if ($("#notExchanging").is(":checked")) {
        //     $("#palletsNumber3").val($("#palletsNumber").val());
        //     $("#palletsNumber2").val($("#palletsNumber").val());
        //     document.getElementById("debt").style.display = "none";
        // }else {
        if ($("#palletsNumber").val() == 0) {
            document.getElementById("debt").style.display = "block";
            $("#palletsNumber3").val($("#palletsNumber2").val());
        } else {
            if ($("#palletsNumber2").val() == 0) {
                document.getElementById("debt").style.display = "block";
                $("#palletsNumber3").val($("#palletsNumber").val());
            } else {
                document.getElementById("debt").style.display = "none";
                $("#palletsNumber3").val();
            }
        }
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

