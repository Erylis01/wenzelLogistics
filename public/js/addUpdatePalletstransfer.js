// function blockOption(accountSelected){
//     console.log(accountSelected);
//     document.write();
//     if(accountSelected){
//         document.getElementById("creditAccount").style.display = "none";
//     }
// }
function displayFieldsType(typeSelected) {
    console.log(typeSelected);
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
        }else{
            if(document.getElementById("Sale_ExtOption").value==typeSelected.value){
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
            }else{
                if(document.getElementById("Deposit-WithdrawalOption").value == typeSelected.value){
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
                }
                else{
                    if(document.getElementById("Withdrawal-DepositOption").value == typeSelected.value){
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
                    }
                    else{
                        // if(document.getElementById("Deposit_OnlyOption").value == typeSelected.value){
                        //     document.getElementById("creditAccount1").style.display = "none";
                        //     document.getElementById("creditAccount2").style.display = "none";
                        //     document.getElementById("debitAccount1").style.display = "block";
                        //     document.getElementById("debitAccount2").style.display = "block";
                        //     document.getElementById("deposit-withdrawal1").style.display = "none";
                        //     document.getElementById("deposit-withdrawal2").style.display = "none";
                        //     document.getElementById("withdrawal-deposit1").style.display = "none";
                        //     document.getElementById("withdrawal-deposit2").style.display = "none";
                        //     document.getElementById("DW").style.display = "none";
                        // }
                        // else{
                        //     if(document.getElementById("Withdrawal_OnlyOption").value == typeSelected.value){
                        //         document.getElementById("creditAccount1").style.display = "block";
                        //         document.getElementById("creditAccount2").style.display = "block";
                        //         document.getElementById("debitAccount1").style.display = "none";
                        //         document.getElementById("debitAccount2").style.display = "none";
                        //         document.getElementById("deposit-withdrawal1").style.display = "none";
                        //         document.getElementById("deposit-withdrawal2").style.display = "none";
                        //         document.getElementById("withdrawal-deposit1").style.display = "none";
                        //         document.getElementById("withdrawal-deposit2").style.display = "none";
                        //         document.getElementById("DW").style.display = "none";
                        //     }else{
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
                        //     }
                        // }
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

// function accountOrder(typeSelected){
//     // document.write('lala');
//     console.log(typeSelected);
//     // document.write(document.getElementById("Deposit").value);
//     if (typeSelected) {
//         if (document.getElementById("Deposit").value == typeSelected.value) {
//             document.getElementById("creditAccount").style.display = "block";
//             document.getElementById("debitAccount").style.display = "none";
//         }
//         else {
//             if (document.getElementById("Withdrawal").value == typeSelected.value) {
//                 document.getElementById("creditAccount").style.display = "none";
//                 document.getElementById("debitAccount").style.display = "block";
//             }else{
//                 document.getElementById("creditAccount").style.display = "block";
//                 document.getElementById("debitAccount").style.display = "block";
//             }
//         }
//     }
//     else {
//         document.getElementById("creditAccount").style.display = "block";
//         document.getElementById("debitAccount").style.display = "block";
//     }
// }

