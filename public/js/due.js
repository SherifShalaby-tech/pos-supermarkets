var updatepay_customer_due_formClicked = false;
$(document).on("submit", "form#pay_customer_due_form", function (e) {
    e.preventDefault();
    let url = $(this).attr("action");
    let data = $(this).serialize();
    let submitButton = $("#pay_due"); 
    if (!updatepay_customer_due_formClicked) {
        console.log('tstt');
        // Set the flag to true to indicate the button has been clicked
        updatepay_customer_due_formClicked = true;
        $.ajax({
            method: "POST",
            url: url,
            data: data,
            dataType: "json",
            success: function (result) {
                if (result.success) {
                    swal("Success!", result.msg, "success");
                    $(".view_modal").modal("hide");
                    $("#customer_id").change();
                } else {
                    swal("Error!", result.msg, "error");
                }
            },
        });
        // Disable the button after it has been clicked
        submitButton.prop('disabled', true);
    }
 
});