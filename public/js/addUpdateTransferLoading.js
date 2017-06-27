function displayFieldsType(typeSelected) {
    console.log(typeSelected);
    if (typeSelected) {
        if (document.getElementById("Purchase_ExtOptionL").value == typeSelected.value) {
            document.getElementById("creditAccount1L").style.display = "block";
            document.getElementById("creditAccount2L").style.display = "block";
            document.getElementById("debitAccount1L").style.display = "none";
            document.getElementById("debitAccount2L").style.display = "none";
            document.getElementById("deposit-withdrawal1L").style.display = "none";
            document.getElementById("deposit-withdrawal2L").style.display = "none";
            document.getElementById("withdrawal-deposit1L").style.display = "none";
            document.getElementById("withdrawal-deposit2L").style.display = "none";
            document.getElementById("DWL").style.display = "none";
        }else{
            if(document.getElementById("Sale_ExtOptionL").value==typeSelected.value){
                document.getElementById("creditAccount1L").style.display = "none";
                document.getElementById("creditAccount2L").style.display = "none";
                document.getElementById("debitAccount1L").style.display = "block";
                document.getElementById("debitAccount2L").style.display = "block";
                document.getElementById("deposit-withdrawal1L").style.display = "none";
                document.getElementById("deposit-withdrawal2L").style.display = "none";
                document.getElementById("withdrawal-deposit1L").style.display = "none";
                document.getElementById("withdrawal-deposit2L").style.display = "none";
                document.getElementById("DWL").style.display = "none";
            }else{
                if(document.getElementById("Deposit-WithdrawalOptionL").value == typeSelected.value){
                    document.getElementById("creditAccount1L").style.display = "block";
                    document.getElementById("creditAccount2L").style.display = "block";
                    document.getElementById("debitAccount1L").style.display = "block";
                    document.getElementById("debitAccount2L").style.display = "block";
                    document.getElementById("deposit-withdrawal1L").style.display = "block";
                    document.getElementById("deposit-withdrawal2L").style.display = "block";
                    document.getElementById("withdrawal-deposit1L").style.display = "none";
                    document.getElementById("withdrawal-deposit2L").style.display = "none";
                    document.getElementById("DWL").style.display = "block";
                }
                else{
                    if(document.getElementById("Withdrawal-DepositOptionL").value == typeSelected.value){
                        document.getElementById("creditAccount1L").style.display = "block";
                        document.getElementById("creditAccount2L").style.display = "block";
                        document.getElementById("debitAccount1L").style.display = "block";
                        document.getElementById("debitAccount2L").style.display = "block";
                        document.getElementById("deposit-withdrawal1L").style.display = "none";
                        document.getElementById("deposit-withdrawal2L").style.display = "none";
                        document.getElementById("withdrawal-deposit1L").style.display = "block";
                        document.getElementById("withdrawal-deposit2L").style.display = "block";
                        document.getElementById("DWL").style.display = "block";
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
                        document.getElementById("creditAccount1L").style.display = "block";
                        document.getElementById("creditAccount2L").style.display = "block";
                        document.getElementById("debitAccount1L").style.display = "block";
                        document.getElementById("debitAccount2L").style.display = "block";
                        document.getElementById("deposit-withdrawal1L").style.display = "none";
                        document.getElementById("deposit-withdrawal2L").style.display = "none";
                        document.getElementById("withdrawal-deposit1L").style.display = "none";
                        document.getElementById("withdrawal-deposit2L").style.display = "none";
                        document.getElementById("DWL").style.display = "none";
                        //     }
                        // }
                    }
                }
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

