
function showAll() {
    $('.dataBlock').show();
    $('#menu-button:visible').trigger("click");
}

function showDesign() {
    $('.dataBlock').hide();
    $('#dataDesign').show();
    $('#menu-button:visible').trigger("click");
}

function showProducts() {
    $('.dataBlock').hide();
    $('#dataProducts').show();
    $('#menu-button:visible').trigger("click");
}

function showCustomers() {
    $('.dataBlock').hide();
    $('#dataCustomers').show();
    $('#menu-button:visible').trigger("click");
}

function showOrders() {
    $('.dataBlock').hide();
    $('#dataOrders').show();
    $('#menu-button:visible').trigger("click");
}

function showStatistics() {
    $('.dataBlock').hide();
    $('#dataStatistics').show();
    $('#menu-button:visible').trigger("click");
}


function search() {
    let text = $('#search').val().toUpperCase();

    $('.accordion-button ').each(function (index, element) {
        if (!$(this).html().toUpperCase().includes(text)) {
                $(this).hide();
        } else {
                $(this).show();
        }
        
    });
}