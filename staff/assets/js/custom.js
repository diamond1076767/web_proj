$(document).ready(function () {

    alertify.set('notifier', 'position', 'top-right');

    // Increase quantity button click
    $(document).on('click', '.increment', function () {
        var $quantityInput = $(this).closest('.qtyBox').find('.qty');
        var productId = $(this).closest('.qtyBox').find('.prodId').val();
        var currentValue = parseInt($quantityInput.val());
        var maxQty = $(this).closest('.qtyBox').find('.maxQty').val();

        if (!isNaN(currentValue) && currentValue < maxQty) {
            var qtyVal = currentValue + 1;
            $quantityInput.val(qtyVal);
            quantityIncDec(productId, qtyVal);
        }
    });

    // Decrease quantity button click
    $(document).on('click', '.decrement', function () {
        var $quantityInput = $(this).closest('.qtyBox').find('.qty');
        var productId = $(this).closest('.qtyBox').find('.prodId').val();
        var currentValue = parseInt($quantityInput.val());

        if (!isNaN(currentValue) && currentValue > 1) {
            var qtyVal = currentValue - 1;
            $quantityInput.val(qtyVal);
            quantityIncDec(productId, qtyVal);
        }
    });

    // Function to handle quantity increase/decrease AJAX
    function quantityIncDec(prodId, qty) {
        $.ajax({
            type: "POST",
            url: "order-code.php",
            data: {
                'productIncDec': true,
                'product_id': prodId,
                'quantity': qty,
            },
            success: function (response) {
                var res = JSON.parse(response);

                if (res.status == 200) {
                    location.reload();
                    alertify.success(res.message);
                } else {
                    alertify.error(res.message);
                }
            }
        });
    }

    // Proceed to place order button click
    $(document).on('click', '.proceedToPlace', function () {
        var cphone = $("#cphone").val();
        var payment_mode = $("#payment_mode").val();

        if (payment_mode == '') {
            swal("Select Payment Mode", "Select your payment mode", "warning");
            return false;
        }

        if (cphone == '' || !$.isNumeric(cphone)) {
            swal("Enter Phone Number", "Enter Valid Phone Number", "warning");
            return false;
        }

        var data = {
            'proceedToPlaceBtn': true,
            'cphone': cphone,
            'payment_mode': payment_mode,
        };

        // AJAX request to proceed with the order placement
        $.ajax({
            type: "POST",
            url: "order-code.php",
            data: data,
            success: function (response) {
                var res = JSON.parse(response);
                if (res.status == 200) {
                    window.location.href = 'order-request-summary.php';
                } else if (res.status == 404) {
                    // If customer not found, prompt to add customer
                    swal(res.message, res.message, res.status_type, {
                        buttons: {
                            catch: {
                                text: "Add Customer",
                                value: "catch"
                            },
                            cancel: "Cancel"
                        }
                    }).then((value) => {
                        switch (value) {
                            case "catch":
                                $('#c_phone').val(cphone);
                                $('#addCustomerModal').modal('show');
                                break;
                            default:
                        }
                    });
                } else {
                    swal(res.message, res.message, res.status_type);
                }
            }
        });
    });

    // Save customer button click
    $(document).on('click', '.saveCustomer', function () {
        var c_name = $('#c_name').val();
        var c_company = $('#c_company').val();
        var c_phone = $('#c_phone').val();
        var c_email = $('#c_email').val();

        if (c_name !== '' && c_company !== '' && c_phone !== '' && c_email !== '') {

            if (!isAlphabeticName(c_name)){
                swal("Please enter alphabetic name", "", "warning");
            }

            if ($.isNumeric(c_phone)) {
                checkExistingPhone(c_phone);
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailRegex.test(c_email)) {
                    checkExistingEmail(c_email);
                    var data = {
                        'saveCustomerBtn': true,
                        'name': $('#c_name').val(),
                        'company': $('#c_company').val(),
                        'phone': c_phone,
                        'email': c_email,
                    };
                    // AJAX request to save customer
                    $.ajax({
                        type: "POST",
                        url: "order-code.php",
                        data: data,
                        success: function (response) {
                            var res = JSON.parse(response);
                            if (res.status == 200) {
                                swal(res.message, res.message, res.status_type);
                                $('#addCustomerModal').modal('hide');
                            } else if (res.status == 422) {
                                swal(res.message, res.message, res.status_type);
                            } else {
                                swal(res.message, res.message, res.status_type);
                            }
                        }
                    });
                } else {
                    swal("Enter Valid Email Address", "", "warning");
                }
            } else {
                swal("Enter Valid Phone Number", "", "warning");
            }
        } else {
            swal("Please Fill Required Fields", "", "warning");
        }
    });

    // Function to check if email already exists
    function checkExistingEmail(email) {
        $.ajax({
            type: "POST",
            url: "order-code.php",
            data: {
                'checkExistingEmail': true,
                'email': email,
            },
            success: function (response) {
                var res = JSON.parse(response);
                if (res.status == 409) {
                    swal(res.message, res.message, res.status_type);
                } else if (res.status == 200) {
                    saveCustomer();
                } else {
                    swal(res.message, res.message, res.status_type);
                }
            }
        });
    }

    // Function to check if phone already exists
    function checkExistingPhone(phone) {
        $.ajax({
            type: "POST",
            url: "order-code.php",
            data: {
                'checkExistingPhone': true,
                'phone': phone,
            },
            success: function (response) {
                var res = JSON.parse(response);
                if (res.status == 409) {
                    swal(res.message, res.message, res.status_type);
                } else if (res.status == 200) {
                    saveCustomer();
                } else {
                    swal(res.message, res.message, res.status_type);
                }
            }
        });
    }

    // Function to save customer details
    function saveCustomer() {
        $.ajax({
            type: "POST",
            url: "order-code.php",
            data: {
                'saveCustomerBtn': true,
                'name': $('#c_name').val(),
                'company': $('#c_company').val(),
                'phone': $('#c_phone').val(),
                'email': $('#c_email').val(),
            },
            success: function (response) {
                var res = JSON.parse(response);
                if (res.status == 200) {
                    swal(res.message, res.message, res.status_type);
                    $('#addCustomerModal').modal('hide');
                } else if (res.status == 422) {
                    swal(res.message, res.message, res.status_type);
                } else {
                    swal(res.message, res.message, res.status_type);
                }
            }
        });
    }

    // Click event for saving the order
    $(document).on("click", "#saveOrder", function () {
        $.ajax({
            type: "POST",
            url: "order-code.php",
            data: {
                'saveOrder': true
            },
            success: function (response) {
                var res = JSON.parse(response);
                if (res.status == 200) {
                    swal(res.message, res.message, res.status_type);
                    $('#orderPlaceSuccessMessage').text(res.message);
                    $('#orderSuccessModal').modal('show');
                } else {
                    swal(res.message, res.message, res.status_type);
                }
            }
        });
    });

});

// Function to print billing area
function printMyBillingArea() {
    var divContents = document.getElementById("myBillingArea").innerHTML;
    var a = window.open('', '');
    a.document.write('<html><title>Sales Inventory Management</title>');
    a.document.write('<body style="font-family: fangsong;">');
    a.document.write(divContents);
    a.document.write('</body></html>');
    a.document.close();
    a.print();
}

window.jsPDF = window.jspdf.jsPDF;
var docPDF = new jsPDF();

// Function to download PDF
function downloadPDF(invoiceNo) {
    var elementHTML = document.querySelector("#myBillingArea");
    docPDF.html(elementHTML, {
        callback: function () {
            docPDF.save(invoiceNo + '.pdf');
        },
        x: 15,
        y: 15,
        width: 170,
        windowWidth: 650
    });
}

function isAlphabeticName(name) {
    // Regular expression to check if the name contains only alphabetic characters
    var nameRegex = /^[a-zA-Z]+$/;

    // Test the name against the regular expression
    return nameRegex.test(name);
}
