function displayFields(typeSelected) {
    console.log(typeSelected);
    if (typeSelected) {
        networkOptionValue = document.getElementById("networkOption").value;
        carrierOptionValue = document.getElementById("carrierOption").value;
        otherOptionValue = document.getElementById("otherOption").value;
        if (carrierOptionValue == typeSelected.value) {
            document.getElementById("trucksAssociated").style.display = "block";
            document.getElementById("warehousesAssociated").style.display = "none";
            document.getElementById("realNumberPallets1").style.display = "none";
            document.getElementById("realNumberPallets2").style.display = "none";
        }
        else {
            if (networkOptionValue == typeSelected.value) {
                document.getElementById("warehousesAssociated").style.display = "block";
                document.getElementById("trucksAssociated").style.display = "none";
                document.getElementById("realNumberPallets1").style.display = "block";
                document.getElementById("realNumberPallets2").style.display = "block";
            }else{
                if(otherOptionValue == typeSelected.value){
                    document.getElementById("warehousesAssociated").style.display = "none";
                    document.getElementById("trucksAssociated").style.display = "none";
                    document.getElementById("realNumberPallets1").style.display = "block";
                    document.getElementById("realNumberPallets2").style.display = "block";
                }else{
                    document.getElementById("warehousesAssociated").style.display = "none";
                    document.getElementById("trucksAssociated").style.display = "none";
                    document.getElementById("realNumberPallets1").style.display = "none";
                    document.getElementById("realNumberPallets2").style.display = "none";
                }
            }
        }
    }
    else {
        document.getElementById("warehousesAssociated").style.display = "none";
        document.getElementById("trucksAssociated").style.display = "none";
        document.getElementById("realNumberPallets1").style.display = "none";
        document.getElementById("realNumberPallets2").style.display = "none";
    }
}