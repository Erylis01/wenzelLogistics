// var lastDebitAccount = null;
// var lastCreditAccount = null;
//
// function selectAccount(accountSelected) {
//     if (lastDebitAccount !== accountSelected) {
//         $("#select-credit option[value=\'" + accountSelected + "\']").hide().prop('disabled', true);
//         if (lastDebitAccount !== null) {
//             $("#select-credit option[value=\'" + lastDebitAccount + "\']").show().prop('disabled', false);
//         }
//     }
//     lastDebitAccount = accountSelected;
// }
//
// function creditaccount(accountSelected) {
//     if (lastCreditAccount !== accountSelected) {
//         $("#select-debit option[value=\'" + accountSelected + "\']").hide().prop('disabled', true);
//         if (lastCreditAccount !== null) {
//             $("#select-debit option[value=\'" + lastCreditAccount + "\']").show().prop('disabled', false);
//         }
//     }
//     lastCreditAccount = accountSelected;
// }

$(document).ready(function () {
    $('#input-debitAccount').typeahead({
        source: function (query, process) {
            return $.get("{{ route('autocompleteAccount') }}", {query: query}, function (data) {
                return process(data);
            });
        }
    });
});

//     // Defining the local dataset
//     var cars = ['Audi', 'BMW', 'Bugatti', 'Ferrari', 'Ford', 'Lamborghini', 'Mercedes Benz', 'Porsche', 'Rolls-Royce', 'Volkswagen'];
//
//     // Constructing the suggestion engine
//     var cars = new Bloodhound({
//         datumTokenizer: Bloodhound.tokenizers.whitespace,
//         queryTokenizer: Bloodhound.tokenizers.whitespace,
//         local: cars
//     });
// document.write('res');
//     // Initializing the typeahead
//     $('input[id=input-debitAccount]').typeahead({
//             hint: true,
//             highlight: true, /* Enable substring highlighting */
//             minLength: 1 /* Specify minimum characters required for showing suggestions */
//         },
//         {
//             name: 'cars',
//             source: cars
//         });






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




//details transfer
function formSubmitBlock(button) {
    $('input[id=actionForm]').val(button.value);
    $("#" + button.id).attr('disabled', 'disabled');
    $("#formUpdateUpload").submit();
}

function formDeleteSubmitBlock(button) {
    $('input[id=actionFormDelete]').val(button.value);
    $("#"+button.id).attr('disabled','disabled');
    $("#formDelete").submit();
}


//add transfer
function formAddSubmitBlock(button) {
    $('input[id=actionForm]').val(button.value);
    $("#"+button.id).attr('disabled','disabled');
    $("#formAddPalletstransfer").submit();
}

function displayFieldsType(typeSelected) {
    if (typeSelected) {
        if (document.getElementById("typePext").value === typeSelected.value) {
            document.getElementById("creditAccount").style.display = "block";
            document.getElementById("debitAccount").style.display = "none";
            document.getElementById("creditAccountLegend").style.display = "block";
            document.getElementById("debitAccountLegend").style.display = "none";
        } else {
            if (document.getElementById("typeSext").value === typeSelected.value) {
                document.getElementById("creditAccount").style.display = "none";
                document.getElementById("debitAccount").style.display = "block";
                document.getElementById("creditAccountLegend").style.display = "none";
                document.getElementById("debitAccountLegend").style.display = "block";
            } else {
                document.getElementById("creditAccount").style.display = "block";
                document.getElementById("debitAccount").style.display = "block";
                document.getElementById("creditAccountLegend").style.display = "block";
                document.getElementById("debitAccountLegend").style.display = "block";
            }
        }
    }
    else {
        document.getElementById("creditAccount").style.display = "block";
        document.getElementById("debitAccount").style.display = "block";
        document.getElementById("creditAccountLegend").style.display = "block";
        document.getElementById("debitAccountLegend").style.display = "block";
    }
}

// function displayFieldsAtrnr(atrnrSelected) {
//     console.log(atrnrSelected);
//     if (atrnrSelected) {
//         document.getElementById("loading_atrnrLink").style.display = "block";
//     }
//     else {
//         document.getElementById("loading_atrnrLink").style.display = "none";
//     }
// }

