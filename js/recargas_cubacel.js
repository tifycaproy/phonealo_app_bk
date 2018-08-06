$(document).ready( function () {
    //alert('Hello');
    $('#selectprod').change( function () {
        var option = $(this).find('option:selected');

        if (!option.attr('data-infoprod')) {
            $('#btnrecarga').html('Recargar');
            $('#btnrecarga').addClass('disabled');
        } else {
            $('#btnrecarga').removeClass('disabled');
            $('#prodinfo').html(option.attr('data-infoprod'));
            $('#pricerecarga').val(option.attr('data-price'));
            $('#btnrecarga').html('Recargar ' + option.attr('data-price') + ' €');
        }

    });
    $('#selectprod').change();

    $('#btnagentes').click( function () {
        $('.blockusers').hide();
        $('#blockagentes').show(100);
        $('#btnrecarga').hide();
        $('#btnagentes').hide();
        $('#btnsessionagent').show();

        $('#clave').attr('required', 'required');
        $('#agent').attr('required', 'required');

        return false;
    });

    $('#agent').focus( function () {
        $(this).removeClass('bg-red');
        $('#clave').removeClass('bg-red');
    });

    $('#btnsessionagent').click( function () {
        $.ajax({
            type: "POST",
            url: base_path + 'recharge/ajax',
            cache: "false",
            async: false,
            data: $('#form_recarga').serialize(),
            dataType: "json",
            success: function (result) {

                if (result.login == 'ok') {
                    window.location.href = base_path + "/recharge/cubacel";
                } else {
                    $('#agent').addClass('bg-red');
                    $('#clave').addClass('bg-red');
                }

            }
        });

        return false;

    });

    $('#mobile').change(function () {
        if ($(this).val() != '') {
            if ($('#country').val() > 0) {
                loaduserbyphone($(this).val(), $('#country').val());
            }
        } else {
            $('#mobile').removeClass('bg-red');
        }
    });

    $('#country').change( function () {
        if ($(this).val() > 0) {
            if ($('#mobile').val() != '') {
                loaduserbyphone($('#mobile').val(), $(this).val());
            }
        } else {
            loaduserbyphone($('#mobile').val(), $(this).val());
            $('#country').removeClass('bg-red');
        }
    });

    $('#dopay').click(function () {
        $('#okayform').val(1);
        $('#form_recarga').submit();
    });

    $('#form_recarga').submit(function () {
        var cansubmit = false;


        /*
        [correoe] => juan@lasperras.com
            [mobilecu] => 5353665454
            [service] => 10 a 14 Julio - 20 EUR
            [mobile] =>
        [country] => 0
            [agent] =>
        [clave] =>
        [freeforcall53] => 0
            [okayform] => 1


*/
        $('#confirm_correoe').html($('#correoe').val());
        $('#confirm_telcubacel').html($('#mobilecu').val());
        $('#confirm_inforecibe').html($('#prodinfo').html());
        $('#confirm_importe').html($('#pricerecarga').val() + ' EUR');
        if ($('#freeforcall53').val() == 1) {
            $('#confirm_usucall53').show();
            $('#confirm_usu').html('<strong>' + $('#name_user2min').html() + ' ' + $('#country').val() + $('#mobile').val() + ' recibe 2 minutos gratis en su cuenta CALL53 </strong>' );
        } else {
            $('#confirm_usucall53').hide();
        }

        $('#basic').modal('show');
        if ($('#okayform').val() == 1) {
            cansubmit = true;
        }
        return cansubmit;
    });

    function loaduserbyphone(phone, country) {

        $.ajax({
            type: "GET",
            url: base_path + 'recharge/ajax',
            cache: "false",
            async: false,
            data: {
                action: 'loadbymobile',
                mobile: phone,
                countryprefix: country
            },
            dataType: "json",
            success: function (result) {

                if (result.uname == 'nouser') {
                    $('#country').addClass('bg-red');
                    $('#mobile').addClass('bg-red');
                    $('#name_user2min').html('');
                    $('#user2min').hide();
                    $('#freeforcall53').val(0);

                } else {
                    $('#country').removeClass('bg-red');
                    $('#mobile').removeClass('bg-red');
                    $('#name_user2min').html(result.uname);
                    $('#user2min').show(200);
                    $('#freeforcall53').val(1);
                }

            }
        });


    }

});
