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


function displayFieldsType(typeSelected) {
    if (typeSelected) {
        if (document.getElementById("Purchase_ExtOption").value == typeSelected.value) {
            document.getElementById("creditAccount1").style.display = "block";
            document.getElementById("creditAccount2").style.display = "block";
            document.getElementById("debitAccount1").style.display = "none";
            document.getElementById("debitAccount2").style.display = "none";
            document.getElementById("deposit-withdrawal1").style.display = "none";
            document.getElementById("deposit-withdrawal2").style.display = "none";
            document.getElementById("withdrawal-deposit1").style.display = "none";
            document.getElementById("withdrawal-deposit2").style.display = "none";
            document.getElementById("DW").style.display = "none";
            document.getElementById("atrnr").style.display = "none";
            $("#loading_atrnrSelect").prop('disabled', true);
            document.getElementById("normalTransferAssociated").style.display = "none";
        } else {
            if (document.getElementById("Sale_ExtOption").value == typeSelected.value) {
                document.getElementById("creditAccount1").style.display = "none";
                document.getElementById("creditAccount2").style.display = "none";
                document.getElementById("debitAccount1").style.display = "block";
                document.getElementById("debitAccount2").style.display = "block";
                document.getElementById("deposit-withdrawal1").style.display = "none";
                document.getElementById("deposit-withdrawal2").style.display = "none";
                document.getElementById("withdrawal-deposit1").style.display = "none";
                document.getElementById("withdrawal-deposit2").style.display = "none";
                document.getElementById("DW").style.display = "none";
                document.getElementById("atrnr").style.display = "none";
                $("#loading_atrnrSelect").prop('disabled', true);
                document.getElementById("normalTransferAssociated").style.display = "none";
            } else {
                if (document.getElementById("Deposit-WithdrawalOption").value == typeSelected.value) {

                    document.getElementById("creditAccount1").style.display = "block";
                    document.getElementById("creditAccount2").style.display = "block";
                    document.getElementById("debitAccount1").style.display = "block";
                    document.getElementById("debitAccount2").style.display = "block";
                    document.getElementById("deposit-withdrawal1").style.display = "block";
                    document.getElementById("deposit-withdrawal2").style.display = "block";
                    document.getElementById("withdrawal-deposit1").style.display = "none";
                    document.getElementById("withdrawal-deposit2").style.display = "none";
                    document.getElementById("DW").style.display = "block";
                    document.getElementById("atrnr").style.display = "inline-block";
                    $("#loading_atrnrSelect").prop('disabled', false);
                    document.getElementById("normalTransferAssociated").style.display = "none";
                }
                else {
                    if (document.getElementById("Withdrawal-DepositOption").value == typeSelected.value) {
                        document.getElementById("creditAccount1").style.display = "block";
                        document.getElementById("creditAccount2").style.display = "block";
                        document.getElementById("debitAccount1").style.display = "block";
                        document.getElementById("debitAccount2").style.display = "block";
                        document.getElementById("deposit-withdrawal1").style.display = "none";
                        document.getElementById("deposit-withdrawal2").style.display = "none";
                        document.getElementById("withdrawal-deposit1").style.display = "block";
                        document.getElementById("withdrawal-deposit2").style.display = "block";
                        document.getElementById("DW").style.display = "block";
                        document.getElementById("atrnr").style.display = "inline-block";
                        $("#loading_atrnrSelect").prop('disabled', false);
                        document.getElementById("normalTransferAssociated").style.display = "none";
                    }
                    else {
                        if (document.getElementById("Deposit_OnlyOption").value == typeSelected.value) {
                            document.getElementById("creditAccount1").style.display = "block";
                            document.getElementById("creditAccount2").style.display = "block";
                            document.getElementById("debitAccount1").style.display = "block";
                            document.getElementById("debitAccount2").style.display = "block";
                            document.getElementById("deposit-withdrawal1").style.display = "none";
                            document.getElementById("deposit-withdrawal2").style.display = "none";
                            document.getElementById("withdrawal-deposit1").style.display = "none";
                            document.getElementById("withdrawal-deposit2").style.display = "none";
                            document.getElementById("DW").style.display = "none";
                            document.getElementById("atrnr").style.display = "inline-block";
                            $("#loading_atrnrSelect").prop('disabled', false);
                            document.getElementById("normalTransferAssociated").style.display = "none";
                        }
                        else {
                            if (document.getElementById("Withdrawal_OnlyOption").value == typeSelected.value) {
                                document.getElementById("creditAccount1").style.display = "block";
                                document.getElementById("creditAccount2").style.display = "block";
                                document.getElementById("debitAccount1").style.display = "block";
                                document.getElementById("debitAccount2").style.display = "block";
                                document.getElementById("deposit-withdrawal1").style.display = "none";
                                document.getElementById("deposit-withdrawal2").style.display = "none";
                                document.getElementById("withdrawal-deposit1").style.display = "none";
                                document.getElementById("withdrawal-deposit2").style.display = "none";
                                document.getElementById("DW").style.display = "none";
                                document.getElementById("atrnr").style.display = "inline-block";
                                $("#loading_atrnrSelect").prop('disabled', false);
                                document.getElementById("normalTransferAssociated").style.display = "none";
                            } else {
                                //case purchase-sale sale-purchase other
                                document.getElementById("creditAccount1").style.display = "block";
                                document.getElementById("creditAccount2").style.display = "block";
                                document.getElementById("debitAccount1").style.display = "block";
                                document.getElementById("debitAccount2").style.display = "block";
                                document.getElementById("deposit-withdrawal1").style.display = "none";
                                document.getElementById("deposit-withdrawal2").style.display = "none";
                                document.getElementById("withdrawal-deposit1").style.display = "none";
                                document.getElementById("withdrawal-deposit2").style.display = "none";
                                document.getElementById("DW").style.display = "none";
                                document.getElementById("atrnr").style.display = "none";
                                $("#loading_atrnrSelect").prop('disabled', false);
                                document.getElementById("normalTransferAssociated").style.display = "block";
                            }
                        }
                    }
                }
            }
        }
    }
    else {
        document.getElementById("creditAccount1").style.display = "none";
        document.getElementById("creditAccount2").style.display = "none";
        document.getElementById("debitAccount1").style.display = "none";
        document.getElementById("debitAccount2").style.display = "none";
        document.getElementById("deposit-withdrawal1").style.display = "none";
        document.getElementById("deposit-withdrawal2").style.display = "none";
        document.getElementById("withdrawal-deposit1").style.display = "none";
        document.getElementById("withdrawal-deposit2").style.display = "none";
        document.getElementById("DW").style.display = "none";
        document.getElementById("atrnr").style.display = "none";
        $("#loading_atrnrSelect").prop('disabled', false);
        document.getElementById("normalTransferAssociated").style.display = "none";
    }
}

function displayFieldsAtrnr(atrnrSelected) {
    console.log(atrnrSelected);
    if (atrnrSelected) {
        document.getElementById("loading_atrnrLink").style.display = "block";
    }
    else {
        document.getElementById("loading_atrnrLink").style.display = "none";
    }
}

