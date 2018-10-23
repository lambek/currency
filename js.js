$(document).ready(function () {

    // $(document).on('click', '#btn', function (e) {
    $('#btn').on('click', function (e) {
        e.preventDefault();
        var login = $("#reg_email").val(),
            pas = $("#reg_password").val();

        $.ajax({
            type: "POST",
            url:"functions.php",
            data: {
                "reg":"1",
                "log":login,
                "pas":pas
            },
            success: function (response) {
                console.log(response);
                $('#signup_div_wrapper').html(response);//
            }
        });
    });

});//end

