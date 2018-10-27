$(document).ready(function () {

    $(document).on('click', '#btnSearch', function (e) {
        e.preventDefault();
        var to_date = $("#to_date").val(),
            past_date = $("#past_date").val(),
            currency_id = $("#currency_id").val(),
            count_record = $("#count_record").val(),
            page_a = "",
            page = Number($(".jsPagination").find('.active').text());

        // console.log(to_date);
        // console.log(past_date);
        // console.log(currency_id);
        // console.log(count_record);
        // console.log(page);
        query_ajax(to_date, past_date, currency_id, count_record, page,page_a);

    });

    $(document).on('click', '.jsPagination a', function (e) {
        e.preventDefault();
        var to_date = $("#to_date").val(),
            past_date = $("#past_date").val(),
            currency_id = $("#currency_id").val(),
            count_record = $("#count_record").val(),
            page_a = Number($(".jsPagination").find('.active').text()),
            page = Number($(this).text());

        if (isNaN(page)) {
            page = $(this).data('act');
            if(page=="Next"){
                page_a = "next";
                page = $(this).parent().prev().find('a').text() ;
            }
            if(page=="Previous"){
                page_a = "previous";
                page = $(this).parent().next().find('a').text() ;
            }
        }

        // console.log(to_date);
        // console.log(past_date);
        // console.log(currency_id);
        // console.log(count_record);
        // console.log(page);
        // console.log(page_a);
        query_ajax(to_date, past_date, currency_id, count_record, page,page_a);
    });

    function query_ajax(to_date, past_date, currency_id, count_record, page,page_a) {
        $.ajax({
            type: "POST",
            url: "functions.php",
            data: {
                "ajax": "queryDateDb",
                "to_date": to_date,
                "past_date": past_date,
                "currency_id": currency_id,
                "count_record": count_record,
                "page": page,
                "page_a": page_a //последний или первый в шкале
            },
            success: function (response) {
                // console.log("** " + response);
                var obj = $.parseJSON(response);
                $("#myfirstchart").replaceWith(obj['gr']);
                $("#tbl_currenty").replaceWith(obj['tbl']);
                $(".jsPagination").replaceWith(obj['pagination']);

            }
        });
    }

});//end

