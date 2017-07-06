var lastDebitAccount = null;
var lastCreditAccount = null;

function selectAccount(accountSelected, index) {
    if (lastDebitAccount !== accountSelected) {
        $("#select-credit"+index+" option[value=\'" + accountSelected + "\']").hide().prop('disabled', true);
        if (lastDebitAccount !== null) {
            $("#select-credit"+index+" option[value=\'" + lastDebitAccount + "\']").show().prop('disabled', false);
        }
    }
    lastDebitAccount = accountSelected;
}

function creditaccount(accountSelected, index) {
    if (lastCreditAccount !== accountSelected) {
        $("#select-debit"+index+" option[value=\'" + accountSelected + "\']").hide().prop('disabled', true);
        if (lastCreditAccount !== null) {
            $("#select-debit"+index+" option[value=\'" + lastCreditAccount + "\']").show().prop('disabled', false);
        }
    }
    lastCreditAccount = accountSelected;
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
            }
            else {
                if(document.getElementById("Withdrawal_OnlyOptionL").value == typeSelected.value){
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
                }else{
                    if(document.getElementById("Deposit_OnlyOptionL").value == typeSelected.value){
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
                    }else{
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
    }
}


function displayFieldsTypeCorrecting(typeSelected) {
    console.log(typeSelected);
    if (typeSelected) {
        // if (document.getElementById("Sale-PurchaseOptionL").value == typeSelected.value) {
        //      // $('#select-credit').find("option[value='account-6']").attr('selected',true);
        //      // $('#select-credit').change();
        //      // $('#select-debit').find("option[value='account-6']").attr('selected',false);
        //      // $('#select-debit').change();
        //      // $('#select-credit2').find("option[value='account-6']").attr('selected',false);
        //      // $('#select-credit2').change();
        //      // $('#select-debit2').find("option[value='account-6']").attr('selected',true);
        //      // $('#select-debit2').change();
        //      document.getElementById("sale-purchase1L").style.display = "block";
        //      document.getElementById("sale-purchase2L").style.display = "block";
        //      document.getElementById("purchase-sale1L").style.display = "none";
        //      document.getElementById("purchase-sale2L").style.display = "none";
        //      document.getElementById("SPL").style.display = "block";
        //  }
        //  else {
        if (document.getElementById("Purchase-SaleOptionL").value == typeSelected.value) {
            document.getElementById("creditAccount0").style.display = "none";
            document.getElementById("debitAccount0").style.display = "none";
            document.getElementById("creditAccount4").style.display = "block";
            document.getElementById("debitAccount4").style.display = "block";
            document.getElementById("creditAccount3").style.display = "none";
            document.getElementById("debitAccount3").style.display = "none";
            // document.getElementById("sale-purchase1L").style.display = "none";
            // document.getElementById("sale-purchase2L").style.display = "none";
            document.getElementById("purchase-sale1L").style.display = "block";
            document.getElementById("purchase-sale2L").style.display = "block";
            document.getElementById("SPL").style.display = "block";
            $('#select-debit4b').find("option[value='account-1']").attr('selected',true);
            $('#select-debit4b').change();
            $('#select-credit4b').find("option[value='account-1']").attr('selected',false);
            $('#select-credit4b').change();
            $('#select-credit2').find("option[value='account-1']").attr('selected',true);
            $('#select-credit2').change();
            $('#select-debit2').find("option[value='account-1']").attr('selected',false);
            $('#select-debit2').change();
        }
        else {
            document.getElementById("creditAccount0").style.display = "none";
            document.getElementById("debitAccount0").style.display = "none";
            document.getElementById("creditAccount3").style.display = "block";
            document.getElementById("debitAccount3").style.display = "block";
            document.getElementById("creditAccount4").style.display = "none";
            document.getElementById("debitAccount4").style.display = "none";
            // document.getElementById("sale-purchase1L").style.display = "none";
            // document.getElementById("sale-purchase2L").style.display = "none";
            document.getElementById("purchase-sale1L").style.display = "none";
            document.getElementById("purchase-sale2L").style.display = "none";
            document.getElementById("SPL").style.display = "none";
            $('#select-credit4b').find("option[value='account-1']").attr('selected',false);
            $('#select-credit4b').change();
            $('#select-debit4b').find("option[value='account-1']").attr('selected',false);
            $('#select-debit4b').change();
            $('#select-credit2').find("option[value='account-1']").attr('selected',false);
            $('#select-credit2').change();
            $('#select-debit2').find("option[value='account-1']").attr('selected',false);
            $('#select-debit2').change();
        }
        // }
    } else {
        document.getElementById("creditAccount0").style.display = "block";
        document.getElementById("debitAccount0").style.display = "block";
        document.getElementById("creditAccount3").style.display = "none";
        document.getElementById("debitAccount3").style.display = "none";
        document.getElementById("creditAccount4").style.display = "none";
        document.getElementById("debitAccount4").style.display = "none";
        // document.getElementById("sale-purchase1L").style.display = "none";
        // document.getElementById("sale-purchase2L").style.display = "none";
        document.getElementById("purchase-sale1L").style.display = "none";
        document.getElementById("purchase-sale2L").style.display = "none";
        document.getElementById("SPL").style.display = "none";
        $('#select-credit4b').find("option[value='account-1']").attr('selected',false);
        $('#select-credit4b').change();
        $('#select-debit4b').find("option[value='account-1']").attr('selected',false);
        $('#select-debit4b').change();
        $('#select-credit2').find("option[value='account-1']").attr('selected',false);
        $('#select-credit2').change();
        $('#select-debit2').find("option[value='account-1']").attr('selected',false);
        $('#select-debit2').change();
    }
}



// --------------------------------------

var lastDebitAccountK = null;
var lastCreditAccountK = null;
var id=null;
function selectAccountK(accountSelected, idSelected) {
    id = idSelected.substr(12);
    if (lastDebitAccountK !== accountSelected) {
        $("#select-credit"+id+" option[value=\'" + accountSelected + "\']").hide().prop('disabled', true);
        if (lastDebitAccountK !== null) {
            $("#select-credit"+id+" option[value=\'" + lastDebitAccountK + "\']").show().prop('disabled', false);
        }
    }
    lastDebitAccountK = accountSelected;
}

function creditaccountK(accountSelected, idSelected) {
    id = idSelected.substr(12);
    if (lastCreditAccountK !== accountSelected) {
        $("#select-debit"+id+" option[value=\'" + accountSelected + "\']").hide().prop('disabled', true);
        if (lastCreditAccountK !== null) {
            $("#select-debit"+id+" option[value=\'" + lastCreditAccountK + "\']").show().prop('disabled', false);
        }
    }
    lastCreditAccountK = accountSelected;
}

// function displayFieldsTypeIdNormal(typeSelected, typeId) {
//     var id = typeId.substr(4);
//     console.log(typeSelected);
//     if (typeSelected) {
//         document.getElementById("creditAccount1" + id).style.display = "block";
//         document.getElementById("creditAccount2" + id).style.display = "block";
//         document.getElementById("debitAccount1" + id).style.display = "block";
//         document.getElementById("debitAccount2" + id).style.display = "block";
//     }
//     else {
//         document.getElementById("creditAccount1" + id).style.display = "none";
//         document.getElementById("creditAccount2" + id).style.display = "none";
//         document.getElementById("debitAccount1" + id).style.display = "none";
//         document.getElementById("debitAccount2" + id).style.display = "none";
//     }
// }
//
function displayFieldsTypeIdCorrecting(typeSelected, typeId) {
    var id = typeId.substr(4);
    console.log(typeSelected);
    if (typeSelected) {
        if (document.getElementById("Purchase-SaleOption" + id).value == typeSelected.value) {
            $('#select-debit' + id).find("option[value='account-1']").attr('selected', true);
            $('#select-debit' + id).change();
            $('#select-credit' + id).find("option[value='account-1']").attr('selected', false);
            $('#select-credit' + id).change();
        } else {
            if (document.getElementById("Sale-PurchaseOption" + id).value == typeSelected.value) {
                $('#select-debit' + id).find("option[value='account-1']").attr('selected', false);
                $('#select-debit' + id).change();
                $('#select-credit' + id).find("option[value='account-1']").attr('selected', true);
                $('#select-credit' + id).change();
            }
            else {
                $('#select-debit' + id).find("option[value='account-1']").attr('selected', false);
                $('#select-debit' + id).change();
                $('#select-credit' + id).find("option[value='account-1']").attr('selected', false);
                $('#select-credit' + id).change();
            }
        }
    }
    else {
        $('#select-debit' + id).find("option[value='account-1']").attr('selected', false);
        $('#select-debit' + id).change();
        $('#select-credit' + id).find("option[value='account-1']").attr('selected', false);
        $('#select-credit' + id).change();
    }
}