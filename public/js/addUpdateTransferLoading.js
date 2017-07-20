function formSubmitBlock(button) {
    $('input[id=actionForm]').val(button.value);
    $("#" + button.id).attr('disabled', 'disabled');
    $("#formSubmitUpdateUpload").submit();
}

function openClosePanel1() {
    if ($('#Pan1collapse').hasClass('in')) {
        $("#infoPanelLogo").attr('class', 'glyphicon glyphicon-menu-down');
    } else {
        $("#infoPanelLogo").attr('class', 'glyphicon glyphicon-menu-up');
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
$('.btn-number').click(function (e) {
    e.preventDefault();
    var fieldName = $(this).attr('data-field');
    var type = $(this).attr('data-type');
    var input = $("input[name='" + fieldName + "']");
    var currentVal = parseInt(input.val());
    if (!isNaN(currentVal)) {
        if (type == 'minus') {
            if (currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            }
            if (parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }
        } else if (type == 'plus') {
            if (currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if (parseInt(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }
        }
    } else {
        input.val(0);
    }
});
$('.input-number').focusin(function () {
    $(this).data('oldValue', $(this).val());
});
$('.input-number').change(function () {

    minValue = parseInt($(this).attr('min'));
    maxValue = parseInt($(this).attr('max'));
    valueCurrent = parseInt($(this).val());

    name = $(this).attr('name');
    if (valueCurrent >= minValue) {
        $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
    } else {
        alert('Sorry, the minimum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    if (valueCurrent <= maxValue) {
        $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
    } else {
        alert('Sorry, the maximum value was reached');
        $(this).val($(this).data('oldValue'));
    }


});


// PALLETS LOCATION ONLY ///
function updateFieldsNormal() {
    var valueWenzel = 'account-1';
    var textWenzel = 'WENZEL';
    var truckAssociatedId = $('input[id=truckAssociatedId]').val();
    var truckAssociatedName = $('input[id=truckAssociatedName]').val();
    var truckAssociatedLicensePlate = $('input[id=truckAssociatedLicensePlate]').val();
    var valueNumb = $("#palletsNumber").val();

    // //clear select
    // $("#select-debitAccountDWD").empty();
    // $("#input-debitAccountDWD").val('');
    // $("#input-debitAccountDWD").change();
    //
    // $("#input-creditAccountW").val('');
    // $("#input-creditAccountW").change();
    // $("#select-creditAccountW").empty();
    //
    // $("#select-creditAccount2DW").empty();
    // $("#input-creditAccount2DW").val('');
    // $("#input-creditAccount2DW").change();
    // $("#select-debitAccount2DW").empty();
    // $("#input-debitAccount2DW").val('');
    // $("#input-debitAccount2DW").change();
    //
    // $("#select-debitAccount3a").empty();
    // $("#debitAccount3a").val('');
    // $("#debitAccount3a").change();
    // $("#select-creditAccount3b").empty();
    // $("#creditAccount3b").val('');
    // $("#creditAccount3b").change();
    //
    // //deselect options
    // $("#creditAccount3a").find("option:selected").removeAttr("selected");
    // $("#debitAccount3b").find("option:selected").removeAttr("selected");
    // $("#select-debitAccountWDebtOther").find("option:selected").removeAttr("selected");
    // $("#select-creditAccountDW").find("option:selected").removeAttr("selected");
    // $("#select-creditAccountDDebtOther").find("option:selected").removeAttr("selected");
    //
    // //nbr
    // $("#palletsNumber3a").val('');
    // $("#palletsNumber3b").val('');

    //hide fields
    document.getElementById("debt").style.display = "none";
    document.getElementById("debt1").style.display = "none";
    document.getElementById("debt2").style.display = "none";

    if ($("#typeDW").is(":checked")) {
         //clear fields unused
        $("#select-debitAccountWDebtOther").find("option:selected").removeAttr("selected");
        $("#select-creditAccountDDebtOther").find("option:selected").removeAttr("selected");
        $("#input-creditAccountW").val('');
        $("#input-creditAccountW").change();
        $("#select-creditAccountW").empty();

        $("#select-debitAccountDWD").empty();
        $("#select-debitAccount3a").empty();
        $("#select-creditAccount3b").empty();
        $("#select-creditAccount2DW").empty();
        $("#select-debitAccount2DW").empty();
        $("#input-debitAccount2DW").val('');
        var valueCredit = null;
        var textCredit = null;
        var valueAnz = $("#anz").val();
        var valueNumb2 = $("#palletsNumber2DW").val();

        if ($("#notExchanging").is(":checked")) {
            //update fields 1
            $("#select-debitAccountDWD").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
            $("#select-debitAccountDWD").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);

            //update fields 2
            $("#input-creditAccount2DW").val(valueWenzel);
            $("#select-creditAccount2DW").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
            $("#select-creditAccount2DW").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
            valueCredit = $("#select-creditAccountDW").find(":selected").val();
            textCredit = $("#select-creditAccountDW").find(":selected").text();
            $("#input-debitAccount2DW").val(valueCredit);
            $("#select-debitAccount2DW").append($('<option></option>').attr("value", valueCredit).text(textCredit));
            $("#select-debitAccount2DW").find("option[value=\'" + valueCredit + "\']").attr('selected', true);

            var calcul = null;
            if (valueNumb < valueAnz) {
                if (valueNumb2 < valueAnz) {
                    //we don't know who has a debt
                    calcul = valueNumb2 - valueNumb;
                    if (calcul < 0) {
                        document.getElementById("debt").style.display = "block";
                        document.getElementById("debt1").style.display = "none";
                        document.getElementById("debt2").style.display = "block";
                        $("#palletsNumber3b").val(-calcul);
                        if (valueCredit !== '') {
                            $("#debitAccount3b").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                        }
                        $("#creditAccount3b").val(valueWenzel);
                        $("#select-creditAccount3b").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                        $("#select-creditAccount3b").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                        $("#debitAccount3a").val('');
                        $("#creditAccount3a").find("option:selected").removeAttr("selected");
                    } else if (calcul > 0) {
                        document.getElementById("debt").style.display = "block";
                        document.getElementById("debt1").style.display = "block";
                        document.getElementById("debt2").style.display = "none";
                        $("#palletsNumber3a").val(calcul);
                        if (valueCredit !== '') {
                            $("#creditAccount3a").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                        }
                        $("#debitAccount3b").find("option:selected").removeAttr("selected");
                        $("#creditAccount3b").val('');
                        $("#debitAccount3a").val(valueWenzel);
                        $("#select-debitAccount3a").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                        $("#select-debitAccount3a").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                    }
                } else if (valueNumb2 > valueAnz) {
                    //wenzel has a debt
                    calcul = valueNumb2 - valueNumb;
                    document.getElementById("debt").style.display = "block";
                    document.getElementById("debt1").style.display = "block";
                    document.getElementById("debt2").style.display = "none";
                    $("#palletsNumber3a").val(calcul);
                    if (valueCredit !== '') {
                        $("#creditAccount3a").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                    }
                    $("#debitAccount3a").val(valueWenzel);
                    $("#select-debitAccount3a").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                    $("#select-debitAccount3a").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                    $("#debitAccount3b").find("option:selected").removeAttr("selected");
                    $("#creditAccount3b").val('');
                } else if (valueNumb2 === valueAnz) {
                    //wenzel has debt
                    calcul = valueAnz - valueNumb;
                    document.getElementById("debt").style.display = "block";
                    document.getElementById("debt1").style.display = "block";
                    document.getElementById("debt2").style.display = "none";
                    $("#palletsNumber3a").val(calcul);
                    if (valueCredit !== '') {
                        $("#creditAccount3a").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                    }
                    $("#debitAccount3b").find("option:selected").removeAttr("selected");
                    $("#creditAccount3b").val('');
                    $("#debitAccount3a").val(valueWenzel);
                    $("#select-debitAccount3a").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                    $("#select-debitAccount3a").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                }
            } else if (valueNumb > valueAnz) {
                if (valueNumb2 < valueAnz) {
                    //other account has a debt
                    calcul = valueNumb - valueNumb2;
                    document.getElementById("debt").style.display = "block";
                    document.getElementById("debt1").style.display = "none";
                    document.getElementById("debt2").style.display = "block";
                    $("#palletsNumber3b").val(calcul);
                    if (valueCredit !== '') {
                        $("#debitAccount3b").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                    }
                    $("#creditAccount3b").val(valueWenzel);
                    $("#select-creditAccount3b").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                    $("#select-creditAccount3b").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                    $("#debitAccount3a").val('');
                    $("#creditAccount3a").find("option:selected").removeAttr("selected");
                } else if (valueNumb2 > valueAnz) {
                    //we don't know who has a debt
                    calcul = valueNumb - valueNumb2;
                    // document.write(calcul);
                    if (calcul < 0) {
                        document.getElementById("debt").style.display = "block";
                        document.getElementById("debt1").style.display = "none";
                        document.getElementById("debt2").style.display = "block";
                        $("#palletsNumber3b").val(-calcul);
                        if (valueCredit !== '') {
                            $("#debitAccount3b").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                        }
                        $("#creditAccount3a").find("option:selected").removeAttr("selected");
                        $("#debitAccount3a").val('');
                        $("#creditAccount3b").val(valueWenzel);
                        $("#select-creditAccount3b").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                        $("#select-creditAccount3b").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                    } else if (calcul > 0) {
                        document.getElementById("debt").style.display = "block";
                        document.getElementById("debt1").style.display = "block";
                        document.getElementById("debt2").style.display = "none";
                        $("#palletsNumber3a").val(calcul);
                        if (valueCredit !== '') {
                            $("#creditAccount3a").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                        }
                        $("#debitAccount3b").find("option:selected").removeAttr("selected");
                        $("#creditAccount3b").val('');
                        $("#debitAccount3a").val(valueWenzel);
                        $("#select-debitAccount3a").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                        $("#select-debitAccount3a").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                    }
                } else if (valueNumb2 === valueAnz) {
                    //other has a debt
                    calcul = valueNumb - valueAnz;
                    document.getElementById("debt").style.display = "block";
                    document.getElementById("debt1").style.display = "none";
                    document.getElementById("debt2").style.display = "block";
                    $("#palletsNumber3b").val(calcul);
                    if (valueCredit !== '') {
                        $("#debitAccount3b").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                    }
                    $("#creditAccount3a").find("option:selected").removeAttr("selected");
                    $("#debitAccount3a").val('');
                    $("#creditAccount3b").val(valueWenzel);
                    $("#select-creditAccount3b").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                    $("#select-creditAccount3b").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                }
            } else if (valueNumb == valueAnz) {
                if (valueNumb2 < valueAnz) {
                    //other has a debt
                    calcul = valueAnz - valueNumb2;
                    document.getElementById("debt").style.display = "block";
                    document.getElementById("debt1").style.display = "none";
                    document.getElementById("debt2").style.display = "block";
                    $("#palletsNumber3b").val(calcul);
                    if (valueCredit !== '') {
                        $("#debitAccount3b").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                    }
                    $("#creditAccount3a").find("option:selected").removeAttr("selected");
                    $("#debitAccount3a").val('');
                    $("#creditAccount3b").val(valueWenzel);
                    $("#select-creditAccount3b").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                    $("#select-creditAccount3b").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                } else if (valueNumb2 > valueAnz) {
                    //wenzel has debt
                    calcul = valueNumb2 - valueAnz;
                    document.getElementById("debt").style.display = "block";
                    document.getElementById("debt1").style.display = "block";
                    document.getElementById("debt2").style.display = "none";
                    $("#palletsNumber3a").val(calcul);
                    if (valueCredit !== '') {
                        $("#creditAccount3a").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                    }
                    $("#debitAccount3b").find("option:selected").removeAttr("selected");
                    $("#creditAccount3b").val('');
                    $("#debitAccount3a").val(valueWenzel);
                    $("#select-debitAccount3a").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                    $("#select-debitAccount3a").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                }
            }
        } else {
            //fields 3
            document.getElementById("debt").style.display = "none";
            document.getElementById("debt1").style.display = "none";
            document.getElementById("debt2").style.display = "none";
            $("#palletsNumber3b").val();
            $("#palletsNumber3a").val();
            $("#creditAccount3a").find("option:selected").removeAttr("selected");
            $("#debitAccount3a").val('');
            $("#debitAccount3b").find("option:selected").removeAttr("selected");
            $("#creditAccount3b").val('');
            //update fields 1
            $("#select-debitAccountDWD").append($('<option></option>').attr("value", 'truck-' + truckAssociatedId).text(truckAssociatedName + '-' + truckAssociatedLicensePlate));
            $("#select-debitAccountDWD").find("option[value=\'truck-" + truckAssociatedId + "\']").attr('selected', true);

            //update fields 2
            $("#input-creditAccount2DW").val('truck-' + truckAssociatedId);
            $("#select-creditAccount2DW").append($('<option></option>').attr("value", 'truck-' + truckAssociatedId).text(truckAssociatedName + '-' + truckAssociatedLicensePlate));
            $("#select-creditAccount2DW").find("option[value=\'truck-" + truckAssociatedId + "\']").attr('selected', true);
            valueCredit = $("#select-creditAccountDW").find(":selected").val();
            textCredit = $("#select-creditAccountDW").find(":selected").text();
            $("#input-debitAccount2DW").val(valueCredit);
            $("#select-debitAccount2DW").append($('<option></option>').attr("value", valueCredit).text(textCredit));
            $("#select-debitAccount2DW").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
        }
        $("#input-debitAccountDWD").val($("#select-debitAccountDWD").find(":selected").val());
        $("#input-debitAccountDWD").change();
        $("#debitAccount3a").change();
        $("#creditAccount3b").change();
        $("#input-creditAccount2DW").change();
        $("#input-debitAccount2DW").change();
    } else {
        if ($("#typeDonly").is(":checked")) {
            $("#select-debitAccountDWD").empty();
            if ($("#notExchanging").is(":checked")) {
                $("#select-debitAccountDWD").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                $("#select-debitAccountDWD").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                document.getElementById("debt").style.display = "block";
                document.getElementById("debt1").style.display = "none";
                document.getElementById("debt2").style.display = "block";
                var valueCred = $("#select-creditAccountDDebtOther").find(":selected").val();
                $("#palletsNumber3b").val(valueNumb);
                if (valueCred !== '') {
                    $("#debitAccount3b").find("option[value=\'" + valueCred + "\']").attr('selected', true);
                }
                $("#creditAccount3b").val(valueWenzel);
                $("#select-creditAccount3b").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                $("#select-creditAccount3b").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
            } else {
                $("#palletsNumber3b").val();
                $("#select-debitAccountDWD").append($('<option></option>').attr("value", 'truck-' + truckAssociatedId).text(truckAssociatedName + '-' + truckAssociatedLicensePlate));
                $("#select-debitAccountDWD").find("option[value=\'truck-" + truckAssociatedId + "\']").attr('selected', true);
            }
            $("#input-debitAccountDWD").val($("#select-debitAccountDWD").find(":selected").val());
            $("#input-debitAccountDWD").change();
            //clear fields unused
            $("#palletsNumber3a").val();
            $("#creditAccount3a").find("option:selected").removeAttr("selected");
            $("#select-debitAccount3a").empty();
            $("#debitAccount3a").val('');
            $("#debitAccount3a").change();
            $("#select-debitAccountWDebtOther").find("option:selected").removeAttr("selected");
            $("#select-creditAccountDW").find("option:selected").removeAttr("selected");
            $("#input-creditAccountW").val('');
            $("#input-creditAccountW").change();
            $("#select-creditAccountW").empty();
            $("#select-creditAccount2DW").empty();
            $("#input-creditAccount2DW").val('');
            $("#input-creditAccount2DW").change();
            $("#select-debitAccount2DW").empty();
            $("#input-debitAccount2DW").val('');
            $("#input-debitAccount2DW").change();
        } else {
            if ($("#typeWonly").is(":checked")) {
                $("#select-creditAccountW").empty();
                $("#select-debitAccount3a").empty();
                if ($("#notExchanging").is(":checked")) {
                    $("#select-creditAccountW").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                    $("#select-creditAccountW").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                    document.getElementById("debt").style.display = "block";
                    document.getElementById("debt1").style.display = "block";
                    document.getElementById("debt2").style.display = "none";
                    var valueDeb = $("#select-debitAccountWDebtOther").find(":selected").val();
                    if (valueDeb !== '') {
                        $("#creditAccount3a").find("option[value=\'" + valueDeb + "\']").attr('selected', true);
                    }
                    $("#palletsNumber3a").val(valueNumb);
                    $("#debitAccount3a").val(valueWenzel);
                    $("#select-debitAccount3a").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                    $("#select-debitAccount3a").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                } else {
                    $("#palletsNumber3a").val();
                    $("#debitAccount3a").val('');
                    $("#select-creditAccountW").append($('<option></option>').attr("value", 'truck-' + truckAssociatedId).text(truckAssociatedName + '-' + truckAssociatedLicensePlate));
                    $("#select-creditAccountW").find("option[value=\'truck-" + truckAssociatedId + "\']").attr('selected', true);
                }
                $("#input-creditAccountW").val($("#select-creditAccountW").find(":selected").val());
                $("#input-creditAccountW").change();
                $("#debitAccount3a").change();
                //clear fields unused
                $("#palletsNumber3b").val();
                $("#debitAccount3b").find("option:selected").removeAttr("selected");
                $("#select-creditAccount3b").empty();
                $("#creditAccount3b").val('');
                $("#creditAccount3b").change();
                $("#select-creditAccountDW").find("option:selected").removeAttr("selected");
                $("#select-creditAccountDDebtOther").find("option:selected").removeAttr("selected");
                $("#select-debitAccountDWD").empty();
                $("#input-debitAccountDWD").val('');
                $("#input-debitAccountDWD").change();
                $("#select-creditAccount2DW").empty();
                $("#input-creditAccount2DW").val('');
                $("#input-creditAccount2DW").change();
                $("#select-debitAccount2DW").empty();
                $("#input-debitAccount2DW").val('');
                $("#input-debitAccount2DW").change();
            }
        }
    }

    $("#select-debitAccountDWD").selectpicker('refresh');
    $("#select-debitAccountDWD").selectpicker('render');
    $("#select-creditAccountDDebtOther").selectpicker('refresh');
    $("#select-creditAccountDDebtOther").selectpicker('render');
    $("#select-creditAccountDW").selectpicker('refresh');
    $("#select-creditAccountDW").selectpicker('render');
    $("#select-creditAccountW").selectpicker('refresh');
    $("#select-creditAccountW").selectpicker('render');
    $("#select-debitAccountWDebtOther").selectpicker('refresh');
    $("#select-debitAccountWDebtOther").selectpicker('render');
    $('#select-creditAccount2DW').selectpicker('refresh');
    $('#select-creditAccount2DW').selectpicker('render');
    $('#select-debitAccount2DW').selectpicker('refresh');
    $('#select-debitAccount2DW').selectpicker('render');
    $('#creditAccount3a').selectpicker('refresh');
    $('#creditAccount3a').selectpicker('render');
    $('#debitAccount3b').selectpicker('refresh');
    $('#debitAccount3b').selectpicker('render');
    $('#creditAccount3b').selectpicker('refresh');
    $('#creditAccount3b').selectpicker('render');
    $('#debitAccount3a').selectpicker('refresh');
    $('#debitAccount3a').selectpicker('render');
}

function displayFieldsTypeNormal(typeChecked) {
    console.log(typeChecked);
    var valueWenzel = 'account-1';
    var textWenzel = 'WENZEL';
    var truckAssociatedId = $('input[id=truckAssociatedId]').val();
    var truckAssociatedName = $('input[id=truckAssociatedName]').val();
    var truckAssociatedLicensePlate = $('input[id=truckAssociatedLicensePlate]').val();
    var valueNumb = $("#palletsNumber").val();


    //clear select
    // $("#select-debitAccountDWD").empty();
    // $("#input-debitAccountDWD").val('');
    // $("#input-debitAccountDWD").change();

    // $("#input-creditAccountW").val('');
    // $("#input-creditAccountW").change();
    // $("#select-creditAccountW").empty();

    // $("#select-creditAccount2DW").empty();
    // $("#input-creditAccount2DW").val('');
    // $("#input-creditAccount2DW").change();
    // $("#select-debitAccount2DW").empty();
    // $("#input-debitAccount2DW").val('');
    // $("#input-debitAccount2DW").change();

    // $("#select-debitAccount3a").empty();
    // $("#debitAccount3a").val('');
    // $("#debitAccount3a").change();
    // $("#select-creditAccount3b").empty();
    // $("#creditAccount3b").val('');
    // $("#creditAccount3b").change();

    //deselect options
    // $("#creditAccount3a").find("option:selected").removeAttr("selected");
    // $("#debitAccount3b").find("option:selected").removeAttr("selected");
    // $("#select-debitAccountWDebtOther").find("option:selected").removeAttr("selected");
    // $("#select-creditAccountDW").find("option:selected").removeAttr("selected");
    // $("#select-creditAccountDDebtOther").find("option:selected").removeAttr("selected");

    if (typeChecked) {
        if (document.getElementById("typeWonly").value === typeChecked.value) {
            document.getElementById("debitAccountDWD").style.display = "none";
            document.getElementById("debitAccountWDebtOther").style.display = "block";
            document.getElementById("creditAccountDW").style.display = "none";
            document.getElementById("creditAccountDDebtOther").style.display = "none";
            document.getElementById("creditAccountW").style.display = "block";
            document.getElementById("palletsTaken").style.display = "block";
            document.getElementById("palletsGiven").style.display = "none";
            document.getElementById("deposit-withdrawal1").style.display = "none";
            document.getElementById("deposit-withdrawal2").style.display = "none";
            document.getElementById("DW").style.display = "none";

            $("#select-creditAccountW").empty();
            $("#select-debitAccount3a").empty();
            if ($("#notExchanging").is(":checked")) {
                $("#select-creditAccountW").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                $("#select-creditAccountW").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                document.getElementById("debt").style.display = "block";
                document.getElementById("debt1").style.display = "block";
                document.getElementById("debt2").style.display = "none";
                // var valueDeb = $("#select-debitAccountWDebtOther").find(":selected").val();
                // if (valueDeb !== '') {
                //     $("#creditAccount3a").find("option[value=\'" + valueDeb + "\']").attr('selected', true);
                // }
                $("#palletsNumber3a").val(valueNumb);
                $("#debitAccount3a").val(valueWenzel);
                $("#select-debitAccount3a").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                $("#select-debitAccount3a").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
            } else {
                $("#palletsNumber3a").val();
                $("#debitAccount3a").val('');
                $("#select-creditAccountW").append($('<option></option>').attr("value", 'truck-' + truckAssociatedId).text(truckAssociatedName + '-' + truckAssociatedLicensePlate));
                $("#select-creditAccountW").find("option[value=\'truck-" + truckAssociatedId + "\']").attr('selected', true);
            }
            $("#input-creditAccountW").val($("#select-creditAccountW").find(":selected").val());
            $("#input-creditAccountW").change();
            $("#debitAccount3a").change();
            //clear fields unused
            $("#palletsNumber3b").val();
            $("#debitAccount3b").find("option:selected").removeAttr("selected");
            $("#select-creditAccount3b").empty();
            $("#creditAccount3b").val('');
            $("#creditAccount3b").change();
            $("#select-creditAccountDW").find("option:selected").removeAttr("selected");
            $("#select-creditAccountDDebtOther").find("option:selected").removeAttr("selected");
            $("#select-debitAccountDWD").empty();
            $("#input-debitAccountDWD").val('');
            $("#input-debitAccountDWD").change();
            $("#select-creditAccount2DW").empty();
            $("#input-creditAccount2DW").val('');
            $("#input-creditAccount2DW").change();
            $("#select-debitAccount2DW").empty();
            $("#input-debitAccount2DW").val('');
            $("#input-debitAccount2DW").change();
        } else {
            if (document.getElementById("typeDonly").value === typeChecked.value) {
                document.getElementById("debitAccountDWD").style.display = "block";
                document.getElementById("debitAccountWDebtOther").style.display = "none";
                document.getElementById("creditAccountDW").style.display = "none";
                document.getElementById("creditAccountDDebtOther").style.display = "block";
                document.getElementById("creditAccountW").style.display = "none";

                document.getElementById("palletsTaken").style.display = "none";
                document.getElementById("palletsGiven").style.display = "block";

                document.getElementById("deposit-withdrawal1").style.display = "none";
                document.getElementById("deposit-withdrawal2").style.display = "none";
                document.getElementById("DW").style.display = "none";

                $("#select-debitAccountDWD").empty();
                if ($("#notExchanging").is(":checked")) {
                    $("#select-debitAccountDWD").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                    $("#select-debitAccountDWD").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                    document.getElementById("debt").style.display = "block";
                    document.getElementById("debt1").style.display = "none";
                    document.getElementById("debt2").style.display = "block";
                    // var valueCred = $("#select-creditAccountDDebtOther").find(":selected").val();
                    $("#palletsNumber3b").val(valueNumb);
                    // if (valueCred !== '') {
                    //     $("#debitAccount3b").find("option[value=\'" + valueCred + "\']").attr('selected', true);
                    // }
                    $("#creditAccount3b").val(valueWenzel);
                    $("#creditAccount3b").change();
                    $("#select-creditAccount3b").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                    $("#select-creditAccount3b").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                } else {
                    $("#palletsNumber3b").val();
                    $("#select-debitAccountDWD").append($('<option></option>').attr("value", 'truck-' + truckAssociatedId).text(truckAssociatedName + '-' + truckAssociatedLicensePlate));
                    $("#select-debitAccountDWD").find("option[value=\'truck-" + truckAssociatedId + "\']").attr('selected', true);
                }
                $("#input-debitAccountDWD").val($("#select-debitAccountDWD").find(":selected").val());
                $("#input-debitAccountDWD").change();
                //clear fields unused
                $("#palletsNumber3a").val();
                $("#creditAccount3a").find("option:selected").removeAttr("selected");
                $("#select-debitAccount3a").empty();
                $("#debitAccount3a").val('');
                $("#debitAccount3a").change();
                $("#select-debitAccountWDebtOther").find("option:selected").removeAttr("selected");
                $("#select-creditAccountDW").find("option:selected").removeAttr("selected");
                $("#input-creditAccountW").val('');
                $("#input-creditAccountW").change();
                $("#select-creditAccountW").empty();
                $("#select-creditAccount2DW").empty();
                $("#input-creditAccount2DW").val('');
                $("#input-creditAccount2DW").change();
                $("#select-debitAccount2DW").empty();
                $("#input-debitAccount2DW").val('');
                $("#input-debitAccount2DW").change();
            } else {
                if (document.getElementById("typeDW").value === typeChecked.value) {
                    document.getElementById("debitAccountDWD").style.display = "block";
                    document.getElementById("debitAccountWDebtOther").style.display = "none";
                    document.getElementById("creditAccountDW").style.display = "block";
                    document.getElementById("creditAccountDDebtOther").style.display = "none";
                    document.getElementById("creditAccountW").style.display = "none";

                    document.getElementById("palletsTaken").style.display = "none";
                    document.getElementById("palletsGiven").style.display = "block";

                    document.getElementById("deposit-withdrawal1").style.display = "block";
                    document.getElementById("deposit-withdrawal2").style.display = "block";
                    document.getElementById("DW").style.display = "block";

                    //clear fields unused
                    $("#select-debitAccountWDebtOther").find("option:selected").removeAttr("selected");
                    $("#select-creditAccountDDebtOther").find("option:selected").removeAttr("selected");
                    $("#input-creditAccountW").val('');
                    $("#input-creditAccountW").change();
                    $("#select-creditAccountW").empty();

                    $("#select-debitAccountDWD").empty();
                    $("#select-debitAccount3a").empty();
                    $("#select-creditAccount3b").empty();
                    $("#select-creditAccount2DW").empty();
                    $("#select-debitAccount2DW").empty();
                    $("#input-debitAccount2DW").val('');
                    // var valueCredit = null;
                    // var textCredit = null;
                    var valueAnz = $("#anz").val();
                    var valueNumb2 = $("#palletsNumber2DW").val();

                    if ($("#notExchanging").is(":checked")) {
                        //update fields 1
                        $("#select-debitAccountDWD").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                        $("#select-debitAccountDWD").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);

                        //update fields 2
                        $("#input-creditAccount2DW").val(valueWenzel);
                        $("#select-creditAccount2DW").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                        $("#select-creditAccount2DW").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                        // valueCredit = $("#select-creditAccountDW").find(":selected").val();
                        // textCredit = $("#select-creditAccountDW").find(":selected").text();
                        // $("#input-debitAccount2DW").val(valueCredit);
                        // $("#input-debitAccount2DW").change();
                        // $("#select-debitAccount2DW").append($('<option></option>').attr("value", valueCredit).text(textCredit));
                        // $("#select-debitAccount2DW").find("option[value=\'" + valueCredit + "\']").attr('selected', true);

                        var calcul = null;
                        if (valueNumb < valueAnz) {
                            if (valueNumb2 < valueAnz) {
                                //we don't know who has a debt
                                calcul = valueNumb2 - valueNumb;
                                if (calcul < 0) {
                                    document.getElementById("debt").style.display = "block";
                                    document.getElementById("debt1").style.display = "none";
                                    document.getElementById("debt2").style.display = "block";
                                    $("#palletsNumber3b").val(-calcul);
                                    // if (valueCredit !== '') {
                                    //     $("#debitAccount3b").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                                    // }
                                    $("#creditAccount3b").val(valueWenzel);
                                    $("#select-creditAccount3b").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                                    $("#select-creditAccount3b").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                                    $("#debitAccount3a").val('');
                                    $("#creditAccount3a").find("option:selected").removeAttr("selected");
                                } else if (calcul > 0) {
                                    document.getElementById("debt").style.display = "block";
                                    document.getElementById("debt1").style.display = "block";
                                    document.getElementById("debt2").style.display = "none";
                                    $("#palletsNumber3a").val(calcul);
                                    // if (valueCredit !== '') {
                                    //     $("#creditAccount3a").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                                    // }
                                    $("#debitAccount3b").find("option:selected").removeAttr("selected");
                                    $("#creditAccount3b").val('');
                                    $("#debitAccount3a").val(valueWenzel);
                                    $("#select-debitAccount3a").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                                    $("#select-debitAccount3a").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                                }
                            } else if (valueNumb2 > valueAnz) {
                                //wenzel has a debt
                                calcul = valueNumb2 - valueNumb;
                                document.getElementById("debt").style.display = "block";
                                document.getElementById("debt1").style.display = "block";
                                document.getElementById("debt2").style.display = "none";
                                $("#palletsNumber3a").val(calcul);
                                // if (valueCredit !== '') {
                                //     $("#creditAccount3a").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                                // }
                                $("#debitAccount3a").val(valueWenzel);
                                $("#select-debitAccount3a").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                                $("#select-debitAccount3a").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                                $("#debitAccount3b").find("option:selected").removeAttr("selected");
                                $("#creditAccount3b").val('');
                            } else if (valueNumb2 === valueAnz) {
                                //wenzel has debt
                                calcul = valueAnz - valueNumb;
                                document.getElementById("debt").style.display = "block";
                                document.getElementById("debt1").style.display = "block";
                                document.getElementById("debt2").style.display = "none";
                                $("#palletsNumber3a").val(calcul);
                                // if (valueCredit !== '') {
                                //     $("#creditAccount3a").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                                // }
                                $("#debitAccount3b").find("option:selected").removeAttr("selected");
                                $("#creditAccount3b").val('');
                                $("#debitAccount3a").val(valueWenzel);
                                $("#select-debitAccount3a").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                                $("#select-debitAccount3a").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                            }
                        } else if (valueNumb > valueAnz) {
                            if (valueNumb2 < valueAnz) {
                                //other account has a debt
                                calcul = valueNumb - valueNumb2;
                                document.getElementById("debt").style.display = "block";
                                document.getElementById("debt1").style.display = "none";
                                document.getElementById("debt2").style.display = "block";
                                $("#palletsNumber3b").val(calcul);
                                // if (valueCredit !== '') {
                                //     $("#debitAccount3b").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                                // }
                                $("#creditAccount3b").val(valueWenzel);
                                $("#select-creditAccount3b").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                                $("#select-creditAccount3b").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                                $("#debitAccount3a").val('');
                                $("#creditAccount3a").find("option:selected").removeAttr("selected");
                            } else if (valueNumb2 > valueAnz) {
                                //we don't know who has a debt
                                calcul = valueNumb - valueNumb2;
                                if (calcul < 0) {
                                    document.getElementById("debt").style.display = "block";
                                    document.getElementById("debt1").style.display = "none";
                                    document.getElementById("debt2").style.display = "block";
                                    $("#palletsNumber3b").val(-calcul);
                                    // if (valueCredit !== '') {
                                    //     $("#debitAccount3b").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                                    // }
                                    $("#creditAccount3a").find("option:selected").removeAttr("selected");
                                    $("#debitAccount3a").val('');
                                    $("#creditAccount3b").val(valueWenzel);
                                    $("#select-creditAccount3b").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                                    $("#select-creditAccount3b").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                                } else if (calcul > 0) {
                                    document.getElementById("debt").style.display = "block";
                                    document.getElementById("debt1").style.display = "block";
                                    document.getElementById("debt2").style.display = "none";
                                    $("#palletsNumber3a").val(calcul);
                                    // if (valueCredit !== '') {
                                    //     $("#creditAccount3a").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                                    // }
                                    $("#debitAccount3b").find("option:selected").removeAttr("selected");
                                    $("#creditAccount3b").val('');
                                    $("#debitAccount3a").val(valueWenzel);
                                    $("#select-debitAccount3a").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                                    $("#select-debitAccount3a").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                                }
                            } else if (valueNumb2 === valueAnz) {
                                //other has a debt
                                calcul = valueNumb - valueAnz;
                                document.getElementById("debt").style.display = "block";
                                document.getElementById("debt1").style.display = "none";
                                document.getElementById("debt2").style.display = "block";
                                $("#palletsNumber3b").val(calcul);
                                // if (valueCredit !== '') {
                                //     $("#debitAccount3b").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                                // }
                                $("#creditAccount3a").find("option:selected").removeAttr("selected");
                                $("#debitAccount3a").val('');
                                $("#creditAccount3b").val(valueWenzel);
                                $("#select-creditAccount3b").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                                $("#select-creditAccount3b").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                            }
                        } else if (valueNumb == valueAnz) {
                            if (valueNumb2 < valueAnz) {
                                //other has a debt
                                calcul = valueAnz - valueNumb2;
                                document.getElementById("debt").style.display = "block";
                                document.getElementById("debt1").style.display = "none";
                                document.getElementById("debt2").style.display = "block";
                                $("#palletsNumber3b").val(calcul);
                                // if (valueCredit !== '') {
                                //     $("#debitAccount3b").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                                // }
                                $("#creditAccount3a").find("option:selected").removeAttr("selected");
                                $("#debitAccount3a").val('');
                                $("#creditAccount3b").val(valueWenzel);
                                $("#select-creditAccount3b").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                                $("#select-creditAccount3b").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                            } else if (valueNumb2 > valueAnz) {
                                //wenzel has debt
                                calcul = valueNumb2 - valueAnz;
                                document.getElementById("debt").style.display = "block";
                                document.getElementById("debt1").style.display = "block";
                                document.getElementById("debt2").style.display = "none";
                                $("#palletsNumber3a").val(calcul);
                                // if (valueCredit !== '') {
                                //     $("#creditAccount3a").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                                // }
                                $("#debitAccount3b").find("option:selected").removeAttr("selected");
                                $("#creditAccount3b").val('');
                                $("#debitAccount3a").val(valueWenzel);
                                $("#select-debitAccount3a").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
                                $("#select-debitAccount3a").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
                            }
                        }
                    } else {
                        //fields 3
                        document.getElementById("debt").style.display = "none";
                        document.getElementById("debt1").style.display = "none";
                        document.getElementById("debt2").style.display = "none";
                        $("#palletsNumber3b").val();
                        $("#palletsNumber3a").val();
                        $("#creditAccount3a").find("option:selected").removeAttr("selected");
                        $("#debitAccount3a").val('');
                        $("#debitAccount3b").find("option:selected").removeAttr("selected");
                        $("#creditAccount3b").val('');
                        //update fields 1
                        $("#select-debitAccountDWD").append($('<option></option>').attr("value", 'truck-' + truckAssociatedId).text(truckAssociatedName + '-' + truckAssociatedLicensePlate));
                        $("#select-debitAccountDWD").find("option[value=\'truck-" + truckAssociatedId + "\']").attr('selected', true);

                        //update fields 2
                        $("#input-creditAccount2DW").val('truck-' + truckAssociatedId);
                        $("#select-creditAccount2DW").append($('<option></option>').attr("value", 'truck-' + truckAssociatedId).text(truckAssociatedName + '-' + truckAssociatedLicensePlate));
                        $("#select-creditAccount2DW").find("option[value=\'truck-" + truckAssociatedId + "\']").attr('selected', true);
                        // valueCredit = $("#select-creditAccountDW").find(":selected").val();
                        // textCredit = $("#select-creditAccountDW").find(":selected").text();
                        // $("#input-debitAccount2DW").val(valueCredit);
                        // $("#input-debitAccount2DW").change();
                        // $("#select-debitAccount2DW").empty();
                        // $("#select-debitAccount2DW").append($('<option></option>').attr("value", valueCredit).text(textCredit));
                        // $("#select-debitAccount2DW").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
                    }
                    $("#input-debitAccountDWD").val($("#select-debitAccountDWD").find(":selected").val());
                    $("#input-debitAccountDWD").change();
                    $("#debitAccount3a").change();
                    $("#creditAccount3b").change();
                    $("#input-creditAccount2DW").change();
                    // $("#input-debitAccount2DW").change();
                }
            }
        }
    // } else {
    //     document.getElementById("debitAccountDWD").style.display = "block";
    //     document.getElementById("debitAccountWDebtOther").style.display = "none";
    //     document.getElementById("creditAccountDW").style.display = "block";
    //     document.getElementById("creditAccountDDebtOther").style.display = "none";
    //     document.getElementById("creditAccountW").style.display = "none";
    //
    //     document.getElementById("palletsTaken").style.display = "none";
    //     document.getElementById("palletsGiven").style.display = "block";
    //
    //     document.getElementById("deposit-withdrawal1").style.display = "block";
    //     document.getElementById("deposit-withdrawal2").style.display = "block";
    //     document.getElementById("DW").style.display = "block";
    //
    //     var valueCredit = null;
    //     var textCredit = null;
    //     var valueAnz = $("#anz").val();
    //     var valueNumb2 = $("#palletsNumber2DW").val();
    //
    //     if ($("#notExchanging").is(":checked")) {
    //         //update fields 1
    //         $("#select-debitAccountDWD").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
    //         $("#select-debitAccountDWD").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
    //         $("#input-debitAccountDWD").val(valueWenzel);
    //         $("#input-debitAccountDWD").change();
    //         //update fields 2
    //         $("#input-creditAccount2DW").val(valueWenzel);
    //         $("#input-creditAccount2DW").change();
    //         $("#select-creditAccount2DW").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
    //         $("#select-creditAccount2DW").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
    //         valueCredit = $("#select-creditAccountDW").find(":selected").val();
    //         textCredit = $("#select-creditAccountDW").find(":selected").text();
    //         $("#input-debitAccount2DW").val(valueCredit);
    //         $("#input-debitAccount2DW").change();
    //         $("#select-debitAccount2DW").append($('<option></option>').attr("value", valueCredit).text(textCredit));
    //         $("#select-debitAccount2DW").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
    //
    //         var calcul = null;
    //         if (valueNumb < valueAnz) {
    //             if (valueNumb2 < valueAnz) {
    //                 //we don't know who has a debt
    //                 calcul = valueNumb2 - valueNumb;
    //                 if (calcul < 0) {
    //                     document.getElementById("debt").style.display = "block";
    //                     document.getElementById("debt1").style.display = "none";
    //                     document.getElementById("debt2").style.display = "block";
    //                     $("#palletsNumber3b").val(-calcul);
    //                     if (valueCredit !== '') {
    //                         $("#debitAccount3b").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
    //                     }
    //                     $("#creditAccount3b").val(valueWenzel);
    //                     $("#creditAccount3b").change();
    //                     $("#select-creditAccount3b").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
    //                     $("#select-creditAccount3b").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
    //                 } else if (calcul > 0) {
    //                     document.getElementById("debt").style.display = "block";
    //                     document.getElementById("debt1").style.display = "block";
    //                     document.getElementById("debt2").style.display = "none";
    //                     $("#palletsNumber3a").val(calcul);
    //                     if (valueCredit !== '') {
    //                         $("#creditAccount3a").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
    //                     }
    //                     $("#debitAccount3a").val(valueWenzel);
    //                     $("#debitAccount3a").change();
    //                     $("#select-debitAccount3a").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
    //                     $("#select-debitAccount3a").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
    //                 }
    //             } else if (valueNumb2 > valueAnz) {
    //                 //wenzel has a debt
    //                 calcul = valueNumb2 - valueNumb;
    //                 document.getElementById("debt").style.display = "block";
    //                 document.getElementById("debt1").style.display = "block";
    //                 document.getElementById("debt2").style.display = "none";
    //                 $("#palletsNumber3a").val(calcul);
    //                 if (valueCredit !== '') {
    //                     $("#creditAccount3a").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
    //                 }
    //                 $("#debitAccount3a").val(valueWenzel);
    //                 $("#debitAccount3a").change();
    //                 $("#select-debitAccount3a").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
    //                 $("#select-debitAccount3a").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
    //
    //             } else if (valueNumb2 === valueAnz) {
    //                 //wenzel has debt
    //                 calcul = valueAnz - valueNumb;
    //                 document.getElementById("debt").style.display = "block";
    //                 document.getElementById("debt1").style.display = "block";
    //                 document.getElementById("debt2").style.display = "none";
    //                 $("#palletsNumber3a").val(calcul);
    //                 if (valueCredit !== '') {
    //                     $("#creditAccount3a").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
    //                 }
    //                 $("#debitAccount3a").val(valueWenzel);
    //                 $("#debitAccount3a").change();
    //                 $("#select-debitAccount3a").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
    //                 $("#select-debitAccount3a").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
    //             }
    //         } else if (valueNumb > valueAnz) {
    //             if (valueNumb2 < valueAnz) {
    //                 //other account has a debt
    //                 calcul = valueNumb - valueNumb2;
    //
    //                 document.getElementById("debt").style.display = "block";
    //                 document.getElementById("debt1").style.display = "none";
    //                 document.getElementById("debt2").style.display = "block";
    //                 $("#palletsNumber3b").val(calcul);
    //                 if (valueCredit !== '') {
    //                     $("#debitAccount3b").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
    //                 }
    //                 $("#creditAccount3b").val(valueWenzel);
    //                 $("#creditAccount3b").change();
    //                 $("#select-creditAccount3b").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
    //                 $("#select-creditAccount3b").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
    //
    //             } else if (valueNumb2 > valueAnz) {
    //                 //we don't know who has a debt
    //                 calcul = valueNumb - valueNumb;
    //                 if (calcul < 0) {
    //                     document.getElementById("debt").style.display = "block";
    //                     document.getElementById("debt1").style.display = "none";
    //                     document.getElementById("debt2").style.display = "block";
    //                     $("#palletsNumber3b").val(-calcul);
    //                     if (valueCredit !== '') {
    //                         $("#debitAccount3b").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
    //                     }
    //                     $("#creditAccount3b").val(valueWenzel);
    //                     $("#creditAccount3b").change();
    //                     $("#select-creditAccount3b").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
    //                     $("#select-creditAccount3b").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
    //                 } else if (calcul > 0) {
    //                     document.getElementById("debt").style.display = "block";
    //                     document.getElementById("debt1").style.display = "block";
    //                     document.getElementById("debt2").style.display = "none";
    //                     $("#palletsNumber3a").val(calcul);
    //                     if (valueCredit !== '') {
    //                         $("#creditAccount3a").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
    //                     }
    //                     $("#debitAccount3a").val(valueWenzel);
    //                     $("#debitAccount3a").change();
    //                     $("#select-debitAccount3a").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
    //                     $("#select-debitAccount3a").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
    //                 }
    //             } else if (valueNumb2 === valueAnz) {
    //                 //other has a debt
    //                 calcul = valueNumb - valueAnz;
    //                 document.getElementById("debt").style.display = "block";
    //                 document.getElementById("debt1").style.display = "none";
    //                 document.getElementById("debt2").style.display = "block";
    //                 $("#palletsNumber3b").val(calcul);
    //                 if (valueCredit !== '') {
    //                     $("#debitAccount3b").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
    //                 }
    //                 $("#creditAccount3b").val(valueWenzel);
    //                 $("#creditAccount3b").change();
    //                 $("#select-creditAccount3b").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
    //                 $("#select-creditAccount3b").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
    //             }
    //         } else if (valueNumb == valueAnz) {
    //             if (valueNumb2 < valueAnz) {
    //                 //other has a debt
    //                 calcul = valueAnz - valueNumb2;
    //                 document.getElementById("debt").style.display = "block";
    //                 document.getElementById("debt1").style.display = "none";
    //                 document.getElementById("debt2").style.display = "block";
    //                 $("#palletsNumber3b").val(calcul);
    //                 if (valueCredit !== '') {
    //                     $("#debitAccount3b").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
    //                 }
    //                 $("#creditAccount3b").val(valueWenzel);
    //                 $("#creditAccount3b").change();
    //                 $("#select-creditAccount3b").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
    //                 $("#select-creditAccount3b").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
    //             } else if (valueNumb2 > valueAnz) {
    //                 //wenzel has debt
    //                 calcul = valueNumb2 - valueAnz;
    //                 document.getElementById("debt").style.display = "block";
    //                 document.getElementById("debt1").style.display = "block";
    //                 document.getElementById("debt2").style.display = "none";
    //                 $("#palletsNumber3a").val(calcul);
    //                 if (valueCredit !== '') {
    //                     $("#creditAccount3a").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
    //                 }
    //                 $("#debitAccount3a").val(valueWenzel);
    //                 $("#debitAccount3a").change();
    //                 $("#select-debitAccount3a").append($('<option></option>').attr("value", valueWenzel).text(textWenzel));
    //                 $("#select-debitAccount3a").find("option[value=\'" + valueWenzel + "\']").attr('selected', true);
    //             }
    //         }
    //     } else {
    //         document.getElementById("debt").style.display = "none";
    //         document.getElementById("debt1").style.display = "none";
    //         document.getElementById("debt2").style.display = "none";
    //         $("#palletsNumber3b").val();
    //         $("#palletsNumber3a").val();
    //         //update fields 1
    //         $("#select-debitAccountDWD").append($('<option></option>').attr("value", 'truck-' + truckAssociatedId).text(truckAssociatedName + '-' + truckAssociatedLicensePlate));
    //         $("#select-debitAccountDWD").find("option[value=\'truck-" + truckAssociatedId + "\']").attr('selected', true);
    //         $("#input-debitAccountDWD").val('truck-' + truckAssociatedId);
    //         $("#input-debitAccountDWD").change();
    //         //update fields 2
    //         $("#input-creditAccount2DW").val('truck-' + truckAssociatedId);
    //         $("#input-creditAccount2DW").change();
    //         $("#select-creditAccount2DW").append($('<option></option>').attr("value", 'truck-' + truckAssociatedId).text(truckAssociatedName + '-' + truckAssociatedLicensePlate));
    //         $("#select-creditAccount2DW").find("option[value=\'truck-" + truckAssociatedId + "\']").attr('selected', true);
    //         valueCredit = $("#select-creditAccountDW").find(":selected").val();
    //         textCredit = $("#select-creditAccountDW").find(":selected").text();
    //         $("#input-debitAccount2DW").val(valueCredit);
    //         $("#input-debitAccount2DW").change();
    //         $("#select-debitAccount2DW").empty();
    //         $("#select-debitAccount2DW").append($('<option></option>').attr("value", valueCredit).text(textCredit));
    //         $("#select-debitAccount2DW").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
    //     }
    }
    $("#select-debitAccountDWD").selectpicker('refresh');
    $("#select-debitAccountDWD").selectpicker('render');
    $("#select-creditAccountDDebtOther").selectpicker('refresh');
    $("#select-creditAccountDDebtOther").selectpicker('render');
    $("#select-creditAccountDW").selectpicker('refresh');
    $("#select-creditAccountDW").selectpicker('render');
    $("#select-creditAccountW").selectpicker('refresh');
    $("#select-creditAccountW").selectpicker('render');
    $("#select-debitAccountWDebtOther").selectpicker('refresh');
    $("#select-debitAccountWDebtOther").selectpicker('render');
    $('#select-creditAccount2DW').selectpicker('refresh');
    $('#select-creditAccount2DW').selectpicker('render');
    $('#select-debitAccount2DW').selectpicker('refresh');
    $('#select-debitAccount2DW').selectpicker('render');
    $('#creditAccount3a').selectpicker('refresh');
    $('#creditAccount3a').selectpicker('render');
    $('#debitAccount3b').selectpicker('refresh');
    $('#debitAccount3b').selectpicker('render');
    $('#creditAccount3b').selectpicker('refresh');
    $('#creditAccount3b').selectpicker('render');
    $('#debitAccount3a').selectpicker('refresh');
    $('#debitAccount3a').selectpicker('render');
}

function displayFieldsTypeCorrecting(typeChecked) {
    console.log(typeChecked);
    if (typeChecked) {
        if (document.getElementById("typePS").value === typeChecked.value) {
            document.getElementById("creditAccountPS").style.display = "block";
            document.getElementById("debitAccountPS").style.display = "block";
            document.getElementById("creditAccountDDebtOther").style.display = "none";
            document.getElementById("debitAccountWDebtOther").style.display = "none";

            document.getElementById("purchase-sale1").style.display = "block";
            document.getElementById("purchase-sale2").style.display = "block";
            document.getElementById("PS").style.display = "block";

            document.getElementById("palletsBought").style.display = "block";
            document.getElementById("pallets").style.display = "none";
        } else {
            if (document.getElementById("typeDebt").value === typeChecked.value || document.getElementById("typeOther").value === typeChecked.value) {
                //other and debt
                document.getElementById("creditAccountDDebtOther").style.display = "block";
                document.getElementById("debitAccountWDebtOther").style.display = "block";
                document.getElementById("pallets").style.display = "block";

                document.getElementById("creditAccountPS").style.display = "none";
                document.getElementById("debitAccountPS").style.display = "none";
                document.getElementById("purchase-sale1").style.display = "none";
                document.getElementById("purchase-sale2").style.display = "none";
                document.getElementById("PS").style.display = "none";
                document.getElementById("palletsBought").style.display = "none";
            }
        }
    }
    else {
        document.getElementById("creditAccountPS").style.display = "block";
        document.getElementById("debitAccountPS").style.display = "block";
        document.getElementById("creditAccountDDebtOther").style.display = "none";
        document.getElementById("debitAccountWDebtOther").style.display = "none";

        document.getElementById("purchase-sale1").style.display = "block";
        document.getElementById("purchase-sale2").style.display = "block";
        document.getElementById("PS").style.display = "block";

        document.getElementById("palletsBought").style.display = "block";
        document.getElementById("pallets").style.display = "none";
    }
}

// function inverseAccounts(numberTransfer){
//     if ($("#typeDW").is(":checked")) {
//         var valueDebit=$("#select-debitAccountDWD").find(":selected").val();
//         var textDebit=$("#select-debitAccountDWD").find(":selected").text();
//         var valueCredit=$("#select-creditAccountDW").find(":selected").val();
//         var textCredit = $("#select-creditAccountDW").find(":selected").text();
//
//         $("#select-debitAccountDWD").empty();
//         $("#select-debitAccountDWD").append($('<option></option>').attr("value", valueCredit).text(textCredit));
//         $("#select-debitAccountDWD").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
//         $("#select-creditAccountDW").empty();
//         $("#select-creditAccountDW").append($('<option></option>').attr("value", valueDebit).text(textDebit));
//         $("#select-creditAccountDW").find("option[value=\'" + valueDebit + "\']").attr('selected', true);
//
//         $('#select-debitAccountDWD').selectpicker('refresh');
//         $('#select-debitAccountDWD').selectpicker('render');
//         $('#select-creditAccountDW').selectpicker('refresh');
//         $('#select-creditAccountDW').selectpicker('render');
//     }else{
//         if($("#typePS").is(":checked")){
//             if(numberTransfer.attr('name')==='inverseAccount1'){
//                 var valueDebit=$("#select-debitAccountPS").find(":selected").val();
//                 var textDebit=$("#select-debitAccountPS").find(":selected").text();
//                 var valueCredit=$("#select-creditAccountPS").find(":selected").val();
//                 var textCredit = $("#select-creditAccountPS").find(":selected").text();
//
//                 $("#select-debitAccountPS").empty();
//                 $("#select-debitAccountPS").append($('<option></option>').attr("value", valueCredit).text(textCredit));
//                 $("#select-debitAccountPS").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
//                 $("#select-creditAccountPS").empty();
//                 $("#select-creditAccountPS").append($('<option></option>').attr("value", valueDebit).text(textDebit));
//                 $("#select-creditAccountPS").find("option[value=\'" + valueDebit + "\']").attr('selected', true);
//
//                 $('#select-debitAccountPS').selectpicker('refresh');
//                 $('#select-debitAccountPS').selectpicker('render');
//                 $('#select-creditAccountPS').selectpicker('refresh');
//                 $('#select-creditAccountPS').selectpicker('render');
//             }else{
//                 if(numberTransfer.attr('name')==='inverseAccount2'){
//                     var valueDebit=$("#select-debitAccount2PS").find(":selected").val();
//                     var textDebit=$("#select-debitAccount2PS").find(":selected").text();
//                     var valueCredit=$("#select-creditAccount2PS").find(":selected").val();
//                     var textCredit = $("#select-creditAccount2PS").find(":selected").text();
//
//                     $("#select-debitAccount2PS").empty();
//                     $("#select-debitAccount2PS").append($('<option></option>').attr("value", valueCredit).text(textCredit));
//                     $("#select-debitAccount2PS").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
//                     $("#select-creditAccount2PS").empty();
//                     $("#select-creditAccount2PS").append($('<option></option>').attr("value", valueDebit).text(textDebit));
//                     $("#select-creditAccount2PS").find("option[value=\'" + valueDebit + "\']").attr('selected', true);
//
//                     $('#select-debitAccount2PS').selectpicker('refresh');
//                     $('#select-debitAccount2PS').selectpicker('render');
//                     $('#select-creditAccount2PS').selectpicker('refresh');
//                     $('#select-creditAccount2PS').selectpicker('render');
//                 }
//             }
//         }else{
//             if($("#typeDebt").is(":checked") || $("#typeOther").is(":checked")){
//                 var valueDebit=$("#select-debitAccountWDebtOther").find(":selected").val();
//                 var textDebit=$("#select-debitAccountWDebtOther").find(":selected").text();
//                 var valueCredit=$("#select-creditAccountDDebtOther").find(":selected").val();
//                 var textCredit = $("#select-creditAccountDDebtOther").find(":selected").text();
//
//                 $("#select-debitAccountWDebtOther").empty();
//                 $("#select-debitAccountWDebtOther").append($('<option></option>').attr("value", valueCredit).text(textCredit));
//                 $("#select-debitAccountWDebtOther").find("option[value=\'" + valueCredit + "\']").attr('selected', true);
//                 $("#select-creditAccountDDebtOther").empty();
//                 $("#select-creditAccountDDebtOther").append($('<option></option>').attr("value", valueDebit).text(textDebit));
//                 $("#select-creditAccountDDebtOther").find("option[value=\'" + valueDebit + "\']").attr('selected', true);
//
//                 $('#select-debitAccountWDebtOther').selectpicker('refresh');
//                 $('#select-debitAccountWDebtOther').selectpicker('render');
//                 $('#select-creditAccountDDebtOther').selectpicker('refresh');
//                 $('#select-creditAccountDDebtOther').selectpicker('render');
//             }
//         }
//     }
// }

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

