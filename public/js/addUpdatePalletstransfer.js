function displayFields(atrnrSelected) {
    console.log(atrnrSelected);
    if (atrnrSelected) {
        document.getElementById("loading_atrnrLink").style.display = "block";
    }
    else {
        document.getElementById("loading_atrnrLink").style.display = "none";
    }
}