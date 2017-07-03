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
            document.getElementById("sale-purchase1L").style.display = "block";
            document.getElementById("sale-purchase2L").style.display = "block";
            document.getElementById("purchase-sale1L").style.display = "none";
            document.getElementById("purchase-sale2L").style.display = "none";
            document.getElementById("SPL").style.display = "block";
        }
        else {
            if (document.getElementById("Purchase-SaleOptionL").value == typeSelected.value) {
                document.getElementById("sale-purchase1L").style.display = "none";
                document.getElementById("sale-purchase2L").style.display = "none";
                document.getElementById("purchase-sale1L").style.display = "block";
                document.getElementById("purchase-sale2L").style.display = "block";
                document.getElementById("SPL").style.display = "block";
            }
            else {
                document.getElementById("sale-purchase1L").style.display = "none";
                document.getElementById("sale-purchase2L").style.display = "none";
                document.getElementById("purchase-sale1L").style.display = "none";
                document.getElementById("purchase-sale2L").style.display = "none";
                document.getElementById("SPL").style.display = "none";
            }
        }
    } else {
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


function displayFieldsTypeIdNormal(typeSelected, typeId) {
    var id = typeId.substr(4);
    console.log(typeSelected);
    if (typeSelected) {
        document.getElementById("creditAccount1" + id).style.display = "block";
        document.getElementById("creditAccount2" + id).style.display = "block";
        document.getElementById("debitAccount1" + id).style.display = "block";
        document.getElementById("debitAccount2" + id).style.display = "block";
    }
    else {
        document.getElementById("creditAccount1" + id).style.display = "none";
        document.getElementById("creditAccount2" + id).style.display = "none";
        document.getElementById("debitAccount1" + id).style.display = "none";
        document.getElementById("debitAccount2" + id).style.display = "none";
    }
}
function displayFieldsTypeIdCorrecting(typeSelected, typeId) {
    var id = typeId.substr(4);
    console.log(typeSelected);
    if (typeSelected) {
        document.getElementById("creditAccount1" + id).style.display = "block";
        document.getElementById("creditAccount2" + id).style.display = "block";
        document.getElementById("debitAccount1" + id).style.display = "block";
        document.getElementById("debitAccount2" + id).style.display = "block";
    }
    else {
        document.getElementById("creditAccount1" + id).style.display = "none";
        document.getElementById("creditAccount2" + id).style.display = "none";
        document.getElementById("debitAccount1" + id).style.display = "none";
        document.getElementById("debitAccount2" + id).style.display = "none";
    }
}

// function displayFieldsTypeCorrecting(typeSelected, typeId) {
//     var id = typeId.substr(14);
//     console.log(typeSelected);
//     if (typeSelected) {
//         document.getElementById("creditAccount1C"+id).style.display = "block";
//         document.getElementById("creditAccount2C"+id).style.display = "block";
//         document.getElementById("debitAccount1C"+id).style.display = "block";
//         document.getElementById("debitAccount2Correcting"+id).style.display = "block";
//
//         if(document.getElementById("Sale-PurchaseOption"+id).value == typeSelected.value){
//             document.write('test');
//             document.getElementById("sale-purchase1"+id).style.display = "block";
//             document.getElementById("sale-purchase2"+id).style.display = "block";
//             document.getElementById("purchase-sale1"+id).style.display = "none";
//             document.getElementById("purchase-sale2"+id).style.display = "none";
//             document.getElementById("PS"+id).style.display = "block";
//         }
//         else{
//             if(document.getElementById("Purchase-SaleOption"+id).value == typeSelected.value){
//                 document.getElementById("sale-purchase1"+id).style.display = "none";
//                 document.getElementById("sale-purchase2"+id).style.display = "none";
//                 document.getElementById("purchase-sale1"+id).style.display = "block";
//                 document.getElementById("purchase-sale2"+id).style.display = "block";
//                 document.getElementById("PS"+id).style.display = "block";
//             }else{
//                 document.getElementById("sale-purchase1"+id).style.display = "none";
//                 document.getElementById("sale-purchase2"+id).style.display = "none";
//                 document.getElementById("purchase-sale1"+id).style.display = "none";
//                 document.getElementById("purchase-sale2"+id).style.display = "none";
//                 document.getElementById("PS"+id).style.display = "none";
//             }
//         }
//     }
//     else {
//         document.getElementById("creditAccount1C"+id).style.display = "none";
//         document.getElementById("creditAccount2C"+id).style.display = "none";
//         document.getElementById("debitAccount1C"+id).style.display = "none";
//         document.getElementById("debitAccount2C"+id).style.display = "none";
//         // document.getElementById("deposit-withdrawal1"+id).style.display = "none";
//         // document.getElementById("deposit-withdrawal2"+id).style.display = "none";
//         // document.getElementById("withdrawal-deposit1"+id).style.display = "none";
//         // document.getElementById("withdrawal-deposit2"+id).style.display = "none";
//         // document.getElementById("DW"+id).style.display = "none";
//     }
// }