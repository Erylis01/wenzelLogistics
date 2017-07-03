var lastDebitAccount = null;
var lastCreditAccount = null;

function selectAccount(accountSelected) {
    if (lastDebitAccount !== accountSelected) {
        $("#select-credit option[value=\'" + accountSelected + "\']").hide().prop('disabled', true);
        if (lastDebitAccount !== null) {
            $("#select-credit option[value=\'" + lastDebitAccount + "\']").show().prop('disabled', false);
        }
    }
    lastDebitAccount = accountSelected;
}

function creditaccount(accountSelected) {
    if (lastCreditAccount !== accountSelected) {
        $("#select-debit option[value=\'" + accountSelected + "\']").hide().prop('disabled', true);
        if (lastCreditAccount !== null) {
            $("#select-debit option[value=\'" + lastCreditAccount + "\']").show().prop('disabled', false);
        }
    }
    lastCreditAccount = accountSelected;
}
var lastDebitAccount2 = null;
var lastCreditAccount2 = null;

function selectAccount2(accountSelected) {
    if (lastDebitAccount2 !== accountSelected) {
        $("#select-credit2 option[value=\'" + accountSelected + "\']").hide().prop('disabled', true);
        if (lastDebitAccount !== null) {
            $("#select-credit2 option[value=\'" + lastDebitAccount2 + "\']").show().prop('disabled', false);
        }
    }
    lastDebitAccount2 = accountSelected;
}

function creditaccount2(accountSelected) {
    if (lastCreditAccount2 !== accountSelected) {
        $("#select-debit2 option[value=\'" + accountSelected + "\']").hide().prop('disabled', true);
        if (lastCreditAccount !== null) {
            $("#select-debit2 option[value=\'" + lastCreditAccount2 + "\']").show().prop('disabled', false);
        }
    }
    lastCreditAccount2 = accountSelected;
}

function displayFieldsTypeNormal(typeSelected) {
    console.log(typeSelected);
    if (typeSelected) {
        document.getElementById("creditAccount1L").style.display = "block";
        document.getElementById("creditAccount2L").style.display = "block";
        document.getElementById("debitAccount1L").style.display = "block";
        document.getElementById("debitAccount2L").style.display = "block";

        if (document.getElementById("Deposit-WithdrawalOptionL").value == typeSelected.value) {
            document.getElementById("deposit-withdrawal1L").style.display = "block";
            document.getElementById("deposit-withdrawal2L").style.display = "block";
            document.getElementById("withdrawal-deposit1L").style.display = "none";
            document.getElementById("withdrawal-deposit2L").style.display = "none";
            document.getElementById("DWL").style.display = "block";
        }
        else {
            if (document.getElementById("Withdrawal-DepositOptionL").value == typeSelected.value) {
                document.getElementById("deposit-withdrawal1L").style.display = "none";
                document.getElementById("deposit-withdrawal2L").style.display = "none";
                document.getElementById("withdrawal-deposit1L").style.display = "block";
                document.getElementById("withdrawal-deposit2L").style.display = "block";
                document.getElementById("DWL").style.display = "block";
            }
            else {
                document.getElementById("deposit-withdrawal1L").style.display = "none";
                document.getElementById("deposit-withdrawal2L").style.display = "none";
                document.getElementById("withdrawal-deposit1L").style.display = "none";
                document.getElementById("withdrawal-deposit2L").style.display = "none";
                document.getElementById("DWL").style.display = "none";
            }
        }
    }
    else {
        document.getElementById("creditAccount1L").style.display = "none";
        document.getElementById("creditAccount2L").style.display = "none";
        document.getElementById("debitAccount1L").style.display = "none";
        document.getElementById("debitAccount2L").style.display = "none";
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
        document.getElementById("creditAccount1L").style.display = "block";
        document.getElementById("creditAccount2L").style.display = "block";
        document.getElementById("debitAccount1L").style.display = "block";
        document.getElementById("debitAccount2L").style.display = "block";

        if (document.getElementById("Sale-PurchaseOptionL").value == typeSelected.value) {
            $('#select-credit').find("option[value='account-6']").attr('selected',true);
            $('#select-credit').change();
            $('#select-debit').find("option[value='account-6']").attr('selected',false);
            $('#select-debit').change();
            $('#select-credit2').find("option[value='account-6']").attr('selected',false);
            $('#select-credit2').change();
            $('#select-debit2').find("option[value='account-6']").attr('selected',true);
            $('#select-debit2').change();
            document.getElementById("sale-purchase1L").style.display = "block";
            document.getElementById("sale-purchase2L").style.display = "block";
            document.getElementById("purchase-sale1L").style.display = "none";
            document.getElementById("purchase-sale2L").style.display = "none";
            document.getElementById("SPL").style.display = "block";
        }
        else {
            if (document.getElementById("Purchase-SaleOptionL").value == typeSelected.value) {
                $('#select-debit').find("option[value='account-6']").attr('selected',true);
                $('#select-debit').change();
                $('#select-credit').find("option[value='account-6']").attr('selected',false);
                $('#select-credit').change();
                $('#select-credit2').find("option[value='account-6']").attr('selected',true);
                $('#select-credit2').change();
                $('#select-debit2').find("option[value='account-6']").attr('selected',false);
                $('#select-debit2').change();
                document.getElementById("sale-purchase1L").style.display = "none";
                document.getElementById("sale-purchase2L").style.display = "none";
                document.getElementById("purchase-sale1L").style.display = "block";
                document.getElementById("purchase-sale2L").style.display = "block";
                document.getElementById("SPL").style.display = "block";
            }
            else {
                $('#select-credit').find("option[value='account-6']").attr('selected',false);
                $('#select-credit').change();
                $('#select-debit').find("option[value='account-6']").attr('selected',false);
                $('#select-debit').change();
                $('#select-credit2').find("option[value='account-6']").attr('selected',false);
                $('#select-credit2').change();
                $('#select-debit2').find("option[value='account-6']").attr('selected',false);
                $('#select-debit2').change();
                document.getElementById("sale-purchase1L").style.display = "none";
                document.getElementById("sale-purchase2L").style.display = "none";
                document.getElementById("purchase-sale1L").style.display = "none";
                document.getElementById("purchase-sale2L").style.display = "none";
                document.getElementById("SPL").style.display = "none";
            }
        }
    } else {
        $('#select-credit').find("option[value='account-6']").attr('selected',false);
        $('#select-credit').change();
        $('#select-debit').find("option[value='account-6']").attr('selected',false);
        $('#select-debit').change();
        $('#select-credit2').find("option[value='account-6']").attr('selected',false);
        $('#select-credit2').change();
        $('#select-debit2').find("option[value='account-6']").attr('selected',false);
        $('#select-debit2').change();
        document.getElementById("creditAccount1L").style.display = "none";
        document.getElementById("creditAccount2L").style.display = "none";
        document.getElementById("debitAccount1L").style.display = "none";
        document.getElementById("debitAccount2L").style.display = "none";
        document.getElementById("sale-purchase1L").style.display = "none";
        document.getElementById("sale-purchase2L").style.display = "none";
        document.getElementById("purchase-sale1L").style.display = "none";
        document.getElementById("purchase-sale2L").style.display = "none";
        document.getElementById("SPL").style.display = "none";
    }
}

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
            $('#select-debit' + id).find("option[value='account-6']").attr('selected', true);
            $('#select-debit' + id).change();
            $('#select-credit' + id).find("option[value='account-6']").attr('selected', false);
            $('#select-credit' + id).change();
        } else {
            if (document.getElementById("Sale-PurchaseOption" + id).value == typeSelected.value) {
                $('#select-debit' + id).find("option[value='account-6']").attr('selected', false);
                $('#select-debit' + id).change();
                $('#select-credit' + id).find("option[value='account-6']").attr('selected', true);
                $('#select-credit' + id).change();
            }
            else {
                $('#select-debit' + id).find("option[value='account-6']").attr('selected', false);
                $('#select-debit' + id).change();
                $('#select-credit' + id).find("option[value='account-6']").attr('selected', false);
                $('#select-credit' + id).change();
            }
        }
    }
    else {
        $('#select-debit' + id).find("option[value='account-6']").attr('selected', false);
        $('#select-debit' + id).change();
        $('#select-credit' + id).find("option[value='account-6']").attr('selected', false);
        $('#select-credit' + id).change();
    }
}