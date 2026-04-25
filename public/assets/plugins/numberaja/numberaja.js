/**
 * numberaja.js
 * A simple plugin to provide simple number formatting.
 * For now just add a 'numberaja' class in your input. The input must have an id.
 * Copyright 2018
 *
 * @author	gung.wxyz@gmail.com
 * Version: 1.1
 */


(function($) {
    $.fn.flash_message = function(options) {
        options = $.extend({
            time: 1000,
            class_name: 'flash_message'
        }, options);
        return $(this).each(function() {
            if ($(this).parent().find('.flash_message').get(0))
                return;
            var message = $('<span />', {
            	'style': 'color:red;',
                'class': options.class_name,
                text: options.text
            }).hide().fadeIn('fast');
            $(this)[options.how](message);
            message.delay(options.time).fadeOut('normal', function() {
                var removeid = $(this).parent().find('.numberaja_msg').parent().attr('id');
                $(this).parent().remove();
                $(this).remove();
                $('#' + removeid).remove();
                // $(".numberaja_msg").remove();
            });

        });
    };

})(jQuery);

$(document).ready(function(){
	initnumberaja();
});

function formatangka(angka) {
    if(angka){
        var number_string = angka.replace(/[^,\d]/g, '').toString();
        split = number_string.split(',');
        var split0 = parseInt(split[0]) * 1;
        if (!isNaN(split0))
            split[0] = split[0] * 1;
        else if (typeof(split[1]) != "undefined")
            split[0] = 0;
        split[0] = split[0].toString();
        sisa = split[0].length % 3;
        hasil = split[0].substr(0, sisa);
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        if (ribuan) {
            separator = sisa ? '.' : '';
            hasil += separator + ribuan.join('.');
        }
        hasil = split[1] != undefined ? hasil + ',' + split[1] : hasil;
        if(hasil == 0)
            return '';
        else
            return hasil;
    }
}

function formatangka_us(angka) {
    if (angka) {
        var str = angka.toString();
        var trimmed = str.trim();

        // cek: apakah minus di paling depan
        var negative = trimmed.charAt(0) === '-';

        // buang semua minus dulu (nanti kita pasang lagi di depan)
        trimmed = trimmed.replace(/-/g, '');

        // sisakan hanya angka dan titik
        var number_string = trimmed.replace(/[^.\d]/g, '').toString();

        // 🔴 KUNCI: kalau tidak ada angka sama sekali
        // tapi ada tanda minus di depan, kembalikan "-" saja
        if (number_string === '') {
            return negative ? '-' : '';
        }

        var split = number_string.split('.');
        var split0 = parseInt(split[0]) * 1;

        if (!isNaN(split0))
            split[0] = split0;
        else if (typeof(split[1]) != "undefined")
            split[0] = 0;

        split[0] = split[0].toString();

        var sisa = split[0].length % 3;
        var hasil = split[0].substr(0, sisa);
        var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            var separator = sisa ? ',' : '';
            hasil += separator + ribuan.join(',');
        }

        hasil = split[1] != undefined ? hasil + '.' + split[1] : hasil;

        if (negative && hasil)
            hasil = '-' + hasil;

        return hasil;
    }

    // fallback kalau angka falsy
    return '';
}


function flashalert(idtarget) {
    $('#' + idtarget).flash_message({
        text: 'Hanya angka dan titik untuk desimal',
        how: 'append'
    });
}

function initnumberaja()
{
    $(".numberaja").map(function() {
        var value = $(this).val();
        // if(value)
        //     value = value.replace(",", ".");

        var fnilai = formatangka_us(value);
        $(this).val(fnilai);
    });

    $('.numberaja').on('keyup blur', function(e) {
        var value = $(this).val();
        var fnilai = formatangka_us(value);
        $(this).val(fnilai);
    });

    $(".numberaja").keydown(function(e) {
        $(".numberaja_msg").remove();
        var elemenid = $(this).attr('id');
        var idtarget = elemenid + '_alert';
        $('#' + elemenid).after('<span class="numberaja_msg" id="' + idtarget + '">');

        $disableKeys = 188; 
        if ($.inArray(e.keyCode, [8,9,35,36,37,39,46,116]) !== -1 || (e.ctrlKey === true || e.metaKey === true)) {
            return;
        }
        if (e.keyCode === 189 || e.keyCode === 109) { // 189 = -, 109 = numpad -
            var val = $(this).val();
            var cursorPos = this.selectionStart;

            if (cursorPos !== 0 || val.indexOf('-') !== -1) {
                e.preventDefault();
                return;
            }
            return;
        }

        if ((e.keyCode > 64 && e.keyCode < 91) || e.keyCode == 188) {
            e.preventDefault();
            flashalert(idtarget);
        }
    });

}