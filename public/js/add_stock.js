$(document).ready(function () {
    //Prevent enter key function except texarea
    $("form").on("keyup keypress", function (e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13 && e.target.tagName != "TEXTAREA") {
            e.preventDefault();
            return false;
        }
    });

    //Add products
    if ($("#search_product").length > 0) {
        $("#search_product")
            .autocomplete({
                source: function (request, response) {
                    $.getJSON(
                        "/purchase-order/get-products?is_raw_material=" +
                            $("#is_raw_material").val(),
                        { term: request.term },
                        response
                    );
                },
                minLength: 2,
                response: function (event, ui) {
                    if (ui.content.length == 1) {
                        ui.item = ui.content[0];
                        $(this)
                            .data("ui-autocomplete")
                            ._trigger("select", "autocompleteselect", ui);
                        $(this).autocomplete("close");
                    } 
                    // else if (ui.content.length == 0) {
                    //     swal("Product not found");
                    // }
                },
                focus: function (event, ui) {},
                select: function (event, ui) {
                    $(this).val(null);
                    get_label_product_row(
                        ui.item.product_id,
                        ui.item.variation_id
                    );
                },
            })
            .autocomplete("instance")._renderItem = function (ul, item) {
            var html = `<li><div>`;
            if (item.image != "" && item.image != null) {
                html += `<img src="${item.image}" width="50px" height="50px"/> ${item.text}</div>`;
            }
            return $(html).appendTo(ul);
        };
    }
});

$(document).on("change", "#store_id", function () {
    if ($("form#edit_stock_form").length == 0) {
        getCurrencyDropDown();
    }
});
$(document).on("click", ".add_bounce_btn", function () {
    var index_id=$(this).attr('index_id');
    console.log(index_id);
    let bounce_details_td = $(".bounce_details_td_"+index_id),
        add_qty_bounce_dive = $(".add_qty_bounce_dive_"+index_id);
    if(add_qty_bounce_dive.hasClass('hide') && bounce_details_td.hasClass('hide')){
        add_qty_bounce_dive.removeClass('hide');
        bounce_details_td.removeClass('hide');
    }else{
        add_qty_bounce_dive.addClass('hide');
        bounce_details_td.addClass('hide');
    }

});
$(document).on("click", ".remove_batch_row", function () {
    old_qty= parseInt($(".current_stock"+$(this).data('id')).val());
    new_qty= old_qty-parseInt($(".batch_quantity"+$(this).data('id')).val());
    $(".current_stock"+$(this).data('id'))
    .val(__currency_trans_from_en(new_qty, false));
    $("span.current_stock_text"+$(this).data('id'))
        .text(__currency_trans_from_en(new_qty, false));
    $(this).closest("tr").remove();
    calculate_sub_totals();
});
$(document).on("click", "#addBatch", function () {
    $product=$(this).data("product");
    var index=$(this).data('index');
    var store_id = $("#store_id").val();
    let currency_id = $('#paying_currency_id').val();
    var batch_count = parseInt($("#batch_count").val());
    $("#batch_count").val(batch_count + 1);
    if($('.stockId'+index).prop('checked')){
        $('.stockId'+index).prop('checked', false);
    }
    $.ajax({
        method: "GET",
        url: "/add-stock/add-product-batch-row",
        dataType: "html",
        data: {
            product_id: $product.id,
            variation_id: $product.variation_id,
            store_id:store_id,
            index:index,
            currency_id:currency_id,
            batch_count:batch_count
        },
        success: function (result) {
            // console.log(result);
            $('.bounce_details_td_'+index).after(result);
            
            if($('.stockId'+index).prop('checked')){
                $('.stockId'+index).prop('checked', false);
            }
            calculate_sub_totals();
        },
    });
});
// $(document).on("click", "#addBatch", function () {
//     $('#addNewBatch').modal('show');
    
//     var store_id = $("#store_id").val();
//     let currency_id = $('#paying_currency_id').val()
//     $product=$(this).data("product");

//     $.ajax({
//         method: "GET",
//         url: "/add-stock/add-product-different-batch-row",
//         dataType: "html",
//         async: false,
//         data: {
//             product_id: $product.id,
//             variation_id: $product.variation_id,
//             currency_id: currency_id,
//             store_id:store_id
//         },
//         success: function (result) {
//             $("table#product_batch_table tbody").html(result);
//             $("input#search_product").val("");
//             $("input#search_product").focus();
//             calculate_sub_totals();
//             reset_row_numbering();
//         },
//     });
// });
// $(document).on("click", ".addProductBatchBtn", function () {
//     var productId=$('.productbatch_id').val();
//     var variationId=$('.variationbatch_id').val();

//     get_label_product_row(productId,variationId,true);
// });
function getCurrencyDropDown() {
    let store_id = $("#store_id").val();
    let default_currency_id = $("#default_currency_id").val();

    $.ajax({
        method: "get",
        url: "/exchange-rate/get-currency-dropdown",
        data: { store_id: store_id },
        success: function (result) {
            $("#paying_currency_id").html(result);
            $("#paying_currency_id").val(default_currency_id);
            $("#paying_currency_id").change();
            $("#paying_currency_id").selectpicker("refresh");
        },
    });
}

$(document).on("change", "select#paying_currency_id", function () {
    let currency_id = $(this).val();
    let store_id = $("#store_id").val();
    $.ajax({
        method: "GET",
        url: "/exchange-rate/get-exchange-rate-by-currency",
        data: {
            store_id: store_id,
            currency_id: currency_id,
        },
        success: function (result) {
            $("#exchange_rate").val(result.conversion_rate);
            $("#exchange_rate").change();
        },
    });
});
function get_label_multipe_product_row(product_selected) {
    //Get item addition method
    var store_id = $("#store_id").val();
    var qty;
    var all_row_count=[0];
    //Search for variation id in each row of pos table
        $("#product_table tbody")
            .find(".product_row")
            .each(function () {
                var row_v_id = $(this).find(".variation_id").val();
                var row_p_id = $(this).find(".product_id").val();
                const isFound = product_selected.some(element => {
                all_row_count.push( __read_number($(this).find(".row_count")));
                if (element.product_id == row_p_id && element.variation_id==row_v_id) {
                    // return true;
                    var index=$(this).find(".row_count").val()
                    qty_element = $(this).find(".quantity");
                    qty = __read_number(qty_element);
                    qty+=1;
                    element.qty=qty;
                    calculate_sub_totals();
                    $("input#search_product").val("");
                    $("input#search_product").focus();
                    //remove if exist
                    $(this).closest("tr").remove();
                    $('.row_details_'+index).remove();
                    $('.bounce_details_td_'+index).remove();
                    // $(this).closest("tr").next().next().show();
                    // $(this).closest("tr").next().next().remove();
                }
                return false;
            });
        });
        row_count=Math.max(...all_row_count);
        let currency_id = $('#paying_currency_id').val()
        $("#row_count").val(row_count + product_selected.length);
        $.ajax({
            method: "GET",
            url: "/add-stock/add-multiple-product-row",
            dataType: "html",
            async: false,
            data: {
                row_count: row_count==null||row_count==0?0:row_count,
                store_id: store_id,
                currency_id: currency_id,
                product_selected:product_selected
            },
            success: function (result) {
                $("#product_table tbody").prepend(result);
                $("input#search_product").val("");
                $("input#search_product").focus();
                calculate_sub_totals();
                reset_row_numbering();
            },
        });
}

function get_label_product_row(product_id, variation_id,is_batch=false) {
    //Get item addition method
    var add_via_ajax = true;
    var store_id = $("#store_id").val();
    var is_added = false;
    var qty;
    //Search for variation id in each row of pos table
        $("#product_table tbody")
            .find("tr")
            .each(function () {
                var row_v_id = $(this).find(".variation_id").val();
                if (row_v_id == variation_id && !is_added) {
                    add_via_ajax = false;
                    is_added = true;
                    //Increment product quantity
                    //get product qty
                    var index=$(this).find(".row_count").val()
                    qty_element = $(this).find(".quantity");
                    qty = __read_number(qty_element);
                    qty+=1;
                    calculate_sub_totals();
                    $("input#search_product").val("");
                    $("input#search_product").focus();
                    //remove if exist
                    $(this).closest("tr").remove();
                    $('.row_details_'+index).remove();
                    $('.bounce_details_td_'+index).remove();
                }
            });
    // }
    // if (add_via_ajax) {
        var row_count = parseInt($("#row_count").val());
        let currency_id = $('#paying_currency_id').val()
        $("#row_count").val(row_count + 1);
        $.ajax({
            method: "GET",
            url: "/add-stock/add-product-row",
            dataType: "html",
            async: false,
            data: {
                product_id: product_id,
                row_count: row_count,
                variation_id: variation_id,
                store_id: store_id,
                currency_id: currency_id,
                qty:qty,
                is_batch:is_batch,
            },
            success: function (result) {
                $("#product_table tbody").prepend(result);
                $("input#search_product").val("");
                $("input#search_product").focus();
                calculate_sub_totals();
                reset_row_numbering();
            },
        });
    // }
}
function calculate_sub_totals() {
    var total = 0;
    $("#product_table > tbody > .product_row").each((ele, tr) => {
        let quantity = __read_number($(tr).find(".quantity"));
        let productId = $(tr).find(".product_id").val();
        let purchase_price = __read_number($(tr).find(".purchase_price"));
        let sub_total = purchase_price * quantity;
        let hasBatchQuantity = false;

        $("#product_table > tbody > .row_batch_details").each((ele, td) => {
            let batchProductId = $(td).find(".batch_product_id").val();
            if (batchProductId === productId) {
                let batch_quantity = __read_number($(td).find(".batch_quantity" + productId));
                let batch_purchase_price = __read_number($(td).find(".batch_purchase_price" + productId));
                if (batch_quantity) {
                    sub_total += batch_quantity * batch_purchase_price;
                    hasBatchQuantity = true;
                }
            }
        });

        if (!hasBatchQuantity) {
            sub_total = purchase_price * quantity;
        }

        __write_number($(tr).find(".sub_total"), sub_total);
        $(tr).find(".sub_total_span").text(__currency_trans_from_en(sub_total, false));
        total += sub_total;
    });

    // Calculate total with other expenses, discount, and other payments
    var other_expenses = __read_number($("#other_expenses"));
    var discount_amount = __read_number($("#discount_amount"));
    var other_payments = __read_number($("#other_payments"));

    total = total + other_expenses - discount_amount + other_payments;
    
    // Update grand total and final total
    __write_number($("#grand_total"), total);
    __write_number($("#final_total"), total);
    __write_number($("#amount"), total);
    $(".final_total_span").text(__currency_trans_from_en(total, false));

    calculate_final_cost_for_products();
}

$(document).on("change", "#amount", function () {
    let amount = __read_number($("#amount"));
    let final_total = __read_number($("#final_total"));

    let due_amount = final_total - amount;
    if (due_amount > 0) {
        $(".due_amount_div").removeClass("hide");
    } else {
        $(".due_amount_div").addClass("hide");
    }
    $(".due_amount_span").text(__currency_trans_from_en(due_amount, false));
});

function calculate_final_cost_for_products() {
    var total_qauntity = 0;
    var item_count = 0;
    $("#product_table > tbody  > .product_row").each((ele, tr) => {
        let quantity = __read_number($(tr).find(".quantity"));
        total_qauntity += quantity;
        if($(tr).find(".quantity").val()){
            item_count++;
        }
    });

    $('.items_count_span').text(item_count);
    $('.items_quantity_span').text(total_qauntity);
    let unit_other_expenses =
        __read_number($("#other_expenses")) / total_qauntity;
    let unit_discount_amount =
        __read_number($("#discount_amount")) / total_qauntity;
    let unit_other_payments =
        __read_number($("#other_payments")) / total_qauntity;

    $("#product_table > tbody  > tr").each((ele, tr) => {
        let purchase_price = __read_number($(tr).find(".purchase_price"));
        let final_cost =
            purchase_price +
            unit_other_expenses -
            unit_discount_amount +
            unit_other_payments;
        __write_number($(tr).find(".final_cost"), final_cost);
    });
}
$(document).on(
    "change",
    "#other_expenses, #discount_amount, #other_payments",
    function () {
        calculate_sub_totals();
    }
);
$(document).on('focus','.quantity', function(){
    $(this).data('val', $(this).val());
})
$(document).on("change", ".purchase_price", function () {
    calculate_sub_totals();
});
$(document).on("change", ".quantity", function () {
    let tr = $(this).closest("tr");
    let old_qty=parseInt($(this).data('val'));
    let number_vs_base_unit = __read_number($(tr).find("#number_vs_base_unit"));
    let current_stock = __read_number($(tr).find(".current_stock"));
    let qty = __read_number($(tr).find(".quantity"));
    let is_service = parseInt($(tr).find(".is_service").val());
    let purchase_price = __read_number($(tr).find(".purchase_price"));
    qty=qty * number_vs_base_unit;
    old_qty=old_qty * number_vs_base_unit;
    let new_qty =0;
    if(current_stock==0){
        new_qty=current_stock + qty;
    }else{
        if(old_qty){
            new_qty=current_stock + qty-old_qty;
        }else{
            new_qty=current_stock + qty;
        }
    }
    if (is_service) {
        new_qty = 0;
    }
    $(tr)
    .find(".current_stock")
    .val(__currency_trans_from_en(new_qty, false));
    $(tr)
        .find("span.current_stock_text")
        .text(__currency_trans_from_en(new_qty, false));
    calculate_sub_totals();

});
$(document).on("change",".batch_purchase_price", function () {
    calculate_sub_totals();
});
$(document).on('focus','.batch_quantity', function(){
    $(this).data('val', $(this).val());
});
$(document).on("change", ".batch_quantity", function () {
    let tr = $(this).closest("tr");
    let productId=$(this).data('id');
    let current_stock = parseInt($(".current_stock"+productId).val());
    let qty = parseInt($(this).val());
    let old_qty=parseInt($(this).data('val'));
    let new_qty = current_stock + qty-old_qty;
    console.log(new_qty)

    $(".current_stock"+productId)
    .val(__currency_trans_from_en(new_qty, false));
    $("span.current_stock_text"+productId)
        .text(__currency_trans_from_en(new_qty, false));
    calculate_sub_totals();
});
$(document).on("click", ".remove_row", function () {
    let index = $(this).data("index");

    $(this).closest("tr").remove();
    $(".row_details_" + index).remove();
    $(".bounce_details_td_" + index).remove();
    $(".row_batch_details_"+index).remove();
    calculate_sub_totals();
    reset_row_numbering();
});
function reset_row_numbering() {
    $("#product_table > tbody  > .product_row").each((ele, tr) => {
        $(tr)
            .find(".row_number")
            .text(ele + 2 - 1);
    });
}
$(document).on("change", ".bounce_qty,.quantity ,.purchase_price ,.selling_price", function () {

    index_id = $(this).attr('index_id');
     console.log(index_id);
    if($(".quantity_"+index_id).val() != null){
    let quantity = parseInt($(".quantity_"+index_id).val()),
        purchase_price = parseInt($(".purchase_price_"+index_id).val()),
        sell_price = parseInt($(".selling_price_"+index_id).val()),
        all_ty = parseInt($('.bounce_qty_'+index_id).val()) + quantity;


            let bounce_purchase_price_val = (purchase_price * quantity ) / all_ty ;
            let bounce_profit_val = sell_price - bounce_purchase_price_val;
            $(".bounce_purchase_price_"+index_id).val(bounce_purchase_price_val.toFixed(2));
            $(".bounce_profit_"+index_id).val( bounce_profit_val.toFixed(2));
        }

});
$(document).on("click", "#clear_all_input_form", function () {
    var value = $('#clear_all_input_form').is(':checked')?1:0;
    $.ajax({
        method: "get",
        url: "/create-or-update-system-property/clear_all_input_stock_form/"+value,
        contentType: "html",
        success: function (result) {
            if (result.success) {
                swal("Success", response.msg, "success");
            }
        },
    });
});
function    checkAddStock(){
    let willDelete = 2;
    let namePurchasePrice='';
    let nameSellingPrice='';
    let checkPrice = '';
    let checkQty=0;
    $("#product_table tbody")
        .find(".product_row")
        .each(function() {
            if ($(this).find('.quantity').val() == 0) {
                $(this).find('.quantity').css('border', '2px solid red');
                swal("Error", LANG.qty_msg, "error");
                willDelete = 3;
                checkQty=3
            }
            ///
            if ($(this).find('.purchase_price').val() == 0) {
                    $(this).find('.purchase_price').css('border', '2px solid #6f42c1');
                    willDelete = 1;
                    namePurchasePrice = 'purchase_price';
            }
            ////
            if ($(this).find('.selling_price').val() == 0) {
                    $(this).find('.selling_price').css('border', '2px solid #6f42c1');
                    willDelete = 1;
                    nameSellingPrice = 'selling_price';
            }

            if(parseFloat($(this).find('.purchase_price').val()) >= parseFloat($(this).find('.selling_price').val())) {
                console.log($(this).find('.purchase_price').val());
                console.log($(this).find('.selling_price').val());
                $(this).find('.selling_price').css('border', '2px solid #6f42c1');
                $(this).find('.purchase_price').css('border', '2px solid #6f42c1');
                willDelete = 1;
                checkPrice = 'purchase_price>selling_price';
            }
        });
        return [willDelete,namePurchasePrice,nameSellingPrice,checkQty,checkPrice];
}
$(document).on('click', '#submit-edit-save', function(e) {
    // e.preventDefault();
    let data=checkAddStock();
    console.log(data)
    if (data[0]=="1" && data[3]!="3") {
        
        let title = '';
        let check = '';
        if(data[4] != ''){
            title = LANG.purchase_price_more_than_sell_price;
                   swal({
                title: title,
                text: LANG.continue,
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,
                confirmButtonText: 'Save',
            })
            .then((isConfirm) => {
                if (isConfirm) {
                    if (data[1] != '' && data[2] != '') {
                        title = LANG.purchase_price_and_sell_price_equal_to_zero;
                        check = 'no'
                    } else if (data[1] == '' && data[2] != '') {
                        title = LANG.sell_price_equal_to_zero;
                        check = 'no'
                    } else if (data[1] != '' && data[2] == '') {
                        title = LANG.purchase_price_equal_to_zero;
                        check = 'no'
                    }
                    if(check != ''){
                        $(this).find('.purchase_price_submit').val('0');
                        $(this).find('.selling_price_submit').val('0')
                        swal("warning", title, "warning");
                    }else{
                        $('form#edit_stock_form').valid();
                        $('form#edit_stock_form').submit();
                    }
                } else { $(this).find('.purchase_price_submit').val('0');
                    $(this).find('.selling_price_submit').val('0')
                }
            });
        }else{
            if (data[1] != '' && data[2] != '') {
                title = LANG.purchase_price_and_sell_price_equal_to_zero;
            } else if (data[1] == '' && data[2] != '') {
                title = LANG.sell_price_equal_to_zero;
            } else if (data[1] != '' && data[2] == '') {
                title = LANG.purchase_price_equal_to_zero;
            }
    
            // swal({
            //         title: title,
            //         text: LANG.continue,
            //         icon: "warning",
            //         buttons: true,
            //         dangerMode: true,
            //         showCancelButton: true,
            //         confirmButtonText: 'Save',
            //     })
            //     .then((isConfirm) => {
            //         if (isConfirm) {
            //             $('form#add_stock_form').valid();
            //             $('form#add_stock_form').submit();
            //         } else {
                        $(this).find('.purchase_price_submit').val('0');
                        $(this).find('.selling_price_submit').val('0')
            //         }
            //     });
            swal("warning", title, "warning");
        }
   
    } else if(data[0]=="2") {
        console.log('test');
        $('form#edit_stock_form').valid();
        $('form#edit_stock_form').submit();
    }
});
$(document).on('click', '#submit-save', function(e) {
    e.preventDefault();
    let data=checkAddStock();
    console.log(data)
    if (data[0]=="1" && data[3]!="3") {
        
        let title = '';
        let check = '';
        if(data[4] != ''){
            title = LANG.purchase_price_more_than_sell_price;
                   swal({
                title: title,
                text: LANG.continue,
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,
                confirmButtonText: 'Save',
            })
            .then((isConfirm) => {
                if (isConfirm) {
                    if (data[1] != '' && data[2] != '') {
                        title = LANG.purchase_price_and_sell_price_equal_to_zero;
                        check = 'no'
                    } else if (data[1] == '' && data[2] != '') {
                        title = LANG.sell_price_equal_to_zero;
                        check = 'no'
                    } else if (data[1] != '' && data[2] == '') {
                        title = LANG.purchase_price_equal_to_zero;
                        check = 'no'
                    }
            
                    // swal({
                    //         title: title,
                    //         text: LANG.continue,
                    //         icon: "warning",
                    //         buttons: true,
                    //         dangerMode: true,
                    //         showCancelButton: true,
                    //         confirmButtonText: 'Save',
                    //     })
                    //     .then((isConfirm) => {
                    //         if (isConfirm) {
                    //             $('form#add_stock_form').valid();
                    //             $('form#add_stock_form').submit();
                    //         } else {
                              
                    //         }
                    //     });
                    if(check != ''){
                        $(this).find('.purchase_price_submit').val('0');
                        $(this).find('.selling_price_submit').val('0')
                        swal("warning", title, "warning");
                    }else{
                        $('form#add_stock_form').valid();
                        $('form#add_stock_form').submit();
                    }
                } else { $(this).find('.purchase_price_submit').val('0');
                    $(this).find('.selling_price_submit').val('0')
                }
            });
        }else{
            if (data[1] != '' && data[2] != '') {
                title = LANG.purchase_price_and_sell_price_equal_to_zero;
            } else if (data[1] == '' && data[2] != '') {
                title = LANG.sell_price_equal_to_zero;
            } else if (data[1] != '' && data[2] == '') {
                title = LANG.purchase_price_equal_to_zero;
            }
    
            // swal({
            //         title: title,
            //         text: LANG.continue,
            //         icon: "warning",
            //         buttons: true,
            //         dangerMode: true,
            //         showCancelButton: true,
            //         confirmButtonText: 'Save',
            //     })
            //     .then((isConfirm) => {
            //         if (isConfirm) {
            //             $('form#add_stock_form').valid();
            //             $('form#add_stock_form').submit();
            //         } else {
                        $(this).find('.purchase_price_submit').val('0');
                        $(this).find('.selling_price_submit').val('0')
            //         }
            //     });
            swal("warning", title, "warning");
        }
   
    } else if(data[0]=="2") {
        if ($('form#add_stock_form').length) {
            $('form#add_stock_form').valid();
            $('form#add_stock_form').submit();
        }else if ($('form#edit_stock_form').length) {
            $('form#edit_stock_form').valid();
            $('form#edit_stock_form').submit();
        }
    }
});

$(document).on('change','.quantity,.purchase_price,.selling_price',function(){
    $(this).css('border','1px solid grey');
});