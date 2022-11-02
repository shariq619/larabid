$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('click', '.user_type', function () {
    if ($("#property").val() == '0') {
        alert('Please select property');
        $(".user_type").prop("checked", false);
    } else {
        if ($(this).val() == 'buyer') {
            $('.buyer_container').show();
        } else {
            $('.buyer_container').hide();
        }

        if ($(this).val() == 'broker') {
            $('.broker_container').show();
            $('#property_id').val($('#property').val());
        } else {
            $('.broker_container').hide();
        }
    }
});
$(document).ajaxStart(function () {
    $("#loader").show();
});

$(document).ajaxStop(function () {
    $("#loader").hide();
});

$('.show-property-detail-form').submit(function (event) {
    $("#loader").show();
    event.preventDefault();
    $.ajax({
        type: 'post',
        url: 'show-property-detail',
        data: $(this).serializeArray(),
        success: function (result) {

            if (result.success) {
                $("#loader").hide();
                let url = 'biddable-properties/' + result.data.slug
                window.location.href = url;

            }
        }

    })

});


function printErrorMsg(keys, error, formId) {
    $("label.error").remove();
    error.forEach(function (value, i) {
        if (typeof (keys[i]) != 'undefined') {
            if (keys[i].includes('.')) {

                /** Array input fields handling */
                var input = keys[i].split('.')
                let element = $('#' + formId + ' [name="' + input[0] + '[]"]')
                if (element.length) {

                    /** Array inputs without index mentioned */
                    if (element.parent().hasClass('input-group')) {
                        element.eq(input[1]).parent().after(
                            '<div class="error text-danger">' + value + "</div>"
                        )
                    } else {
                        element.eq(input[1]).after(
                            '<div class="error text-danger">' + value + "</div>"
                        );
                    }
                } else {

                    /** Array with index mentioned */
                    $('[name="' + input[0] + '[' + input[1] + ']"]').after(
                        '<div class="error text-danger">' + value + "</div>"
                    )
                }
            } else {

                /** Without Array input fields handling */
                let element = $('#' + formId + " [name=" + keys[i] + "]")
                if (element.hasClass('select2')) {
                    element.parent().append(
                        '<div class="error text-danger">' + value + "</div>"
                    );
                } else {
                    element.parent().after(
                        '<label id="password-error" class="error text-danger" for="password">' + value + '</label>'
                    );
                }
            }
        }
    });
}

$('.register-form').submit(function (event) {
    $("#loader").show();
    event.preventDefault();

    $('#property_id').val($('#property').val());
    $.ajax({
        type: 'post',
        url: 'register',
        data: $(this).serializeArray(),
        success: function (result) {
            if (result.success) {
                $("#loader").hide();
                $("#result").html("<div class='alert alert-success'>An Email has been sent to you for verification</div>");
                $('.error ').remove();
            }
        },
        statusCode: {
            500: function () {
                // Server error
                $("#loader").hide();
            },
            422: function (info) { // laravel and node form exception
                console.clear();
                if (Object.keys(info.responseJSON.errors).length > 0) {
                    let error = [];
                    let keys = Object.keys(info.responseJSON.errors);
                    $.each(info.responseJSON.errors, function (i, val) {
                        if (val.length > 0) {
                            $.each(val, function (index, entry) {
                                error.push(entry);
                            });
                        }
                    });
                    printErrorMsg(keys, error, 'register-form');
                    $("#loader").hide();
                }
            }
        }
    })
});