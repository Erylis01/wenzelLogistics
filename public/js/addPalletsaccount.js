function displayFields(typeSelected) {
    console.log(typeSelected);
    if (typeSelected) {
        networkOptionValue = document.getElementById("networkOption").value;
        carrierOptionValue = document.getElementById("carrierOption").value;
        if (carrierOptionValue == typeSelected.value) {
            document.getElementById("trucksAssociated").style.display = "block";
            document.getElementById("warehousesAssociated").style.display = "none";
        }
        else {
            if (networkOptionValue == typeSelected.value) {
                document.getElementById("warehousesAssociated").style.display = "block";
                document.getElementById("trucksAssociated").style.display = "none";
            }else{
                document.getElementById("warehousesAssociated").style.display = "none";
                document.getElementById("trucksAssociated").style.display = "none";
            }
        }
    }
    else {
        document.getElementById("warehousesAssociated").style.display = "none";
        document.getElementById("trucksAssociated").style.display = "none";
    }
}