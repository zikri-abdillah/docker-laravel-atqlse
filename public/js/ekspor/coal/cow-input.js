let cowCommodityCounter = 0;
let cowLsSuggestionTimer = null;

$(document).ready(function () {
    bootstrapCowForm();
});

function bootstrapCowForm() {
    initCommoditySatuanSelect();

    var nestedData = Array.isArray(window.cowNestedData) ? window.cowNestedData : [];

    nestedData.forEach(function (item) {
        addKomoditas({
            commodityKey: generateCommodityKey(),
            ur_barang: item.ur_barang || '',
            spesifikasi: item.spesifikasi || '',
            jml_barang: item.jml_barang || '',
            satuan: item.satuan || '',
            satuan_label: item.satuan_label || item.satuan || ''
        });
    });

    resetCommodityForm();
    refreshCommodityTable();
    refreshEmptyState();
    initLsAutocomplete();
}

function initCommoditySatuanSelect() {
    initselectdua('#commodity_satuan', baseurl + 'select/satuan', '', 2);
}

function generateCommodityKey() {
    cowCommodityCounter += 1;
    return 'commodity-' + cowCommodityCounter;
}

function addKomoditas(data) {
    var commodity = $.extend({
        commodityKey: generateCommodityKey(),
        ur_barang: '',
        spesifikasi: '',
        jml_barang: '',
        satuan: '',
        satuan_label: ''
    }, data || {});

    var $row = $('#komoditas-table-body').find('tr[data-commodity-key="' + commodity.commodityKey + '"]');
    if (!$row.length) {
        $row = $('<tr class="komoditas-row"></tr>').attr('data-commodity-key', commodity.commodityKey);
        $('#komoditas-table-body').append($row);
    }

    $row.data('commodity', commodity);
    renderCommodityRow($row);
}

function renderCommodityRow($row) {
    var commodity = getCommodityRowData($row);

    $row.html(
        '<td class="commodity-seri text-center"></td>' +
        '<td>' + nl2br(escapeHtml(commodity.ur_barang || '-')) + '</td>' +
        '<td>' + nl2br(escapeHtml(commodity.spesifikasi || '-')) + '</td>' +
        '<td>' + escapeHtml(commodity.jml_barang || '-') + '</td>' +
        '<td>' + escapeHtml(commodity.satuan_label || commodity.satuan || '-') + '</td>' +
        '<td class="text-nowrap">' +
        '<button type="button" class="btn btn-sm btn-warning me-1 btn-edit-komoditas"><i class="fa fa-edit"></i></button>' +
        '<button type="button" class="btn btn-sm btn-danger btn-remove-komoditas"><i class="fa fa-trash"></i></button>' +
        '</td>'
    );
}

function getCommodityRowData($row) {
    return $.extend({
        commodityKey: $row.data('commodity-key'),
        ur_barang: '',
        spesifikasi: '',
        jml_barang: '',
        satuan: '',
        satuan_label: ''
    }, $row.data('commodity') || {});
}

function collectCommodityForm() {
    var $satuan = $('#commodity_satuan');
    var satuanData = $.isFunction($.fn.select2) ? ($satuan.select2('data') || []) : [];
    var satuanValue = ($satuan.val() || '').trim();
    var satuanLabel = '';

    if (satuanData.length > 0) {
        satuanLabel = (satuanData[0].text || '').trim();
    }

    if (!satuanLabel) {
        satuanLabel = ($satuan.find('option:selected').text() || '').trim();
    }

    return {
        commodityKey: ($('#commodity-edit-key').val() || '').trim(),
        ur_barang: ($('#commodity_ur_barang').val() || '').trim(),
        spesifikasi: ($('#commodity_spesifikasi').val() || '').trim(),
        jml_barang: ($('#commodity_jml_barang').val() || '').trim(),
        satuan: satuanValue,
        satuan_label: satuanLabel
    };
}

function setCommodityForm(data) {
    $('#commodity-edit-key').val(data.commodityKey || '');
    $('#commodity_ur_barang').val(data.ur_barang || '');
    $('#commodity_spesifikasi').val(data.spesifikasi || '');
    $('#commodity_jml_barang').val(data.jml_barang || '');
    setCommoditySatuanValue(data.satuan || '', data.satuan_label || data.satuan || '');
    $('#btn-save-komoditas').html('<i class="bx bx-save me-1"></i>Update Komoditas');
}

function resetCommodityForm() {
    $('#commodity-edit-key').val('');
    $('#commodity_ur_barang').val('');
    $('#commodity_spesifikasi').val('');
    $('#commodity_jml_barang').val('');
    $('#commodity_satuan').val(null).trigger('change');
    $('#btn-save-komoditas').html('<i class="bx bx-save me-1"></i>Simpan Komoditas');
}

function validateCommodityForm(data) {
    if (!data.ur_barang) {
        return 'Uraian barang komoditas wajib diisi.';
    }

    if (!data.satuan) {
        return 'Satuan komoditas wajib diisi.';
    }

    return true;
}

function refreshCommodityTable() {
    $('#komoditas-table-body .komoditas-row').each(function (index) {
        renderCommodityRow($(this));
        $(this).find('.commodity-seri').text(index + 1);
    });
}

function refreshEmptyState() {
    if ($('#komoditas-table-body .komoditas-row').length > 0) {
        $('#komoditas-empty-row').hide();
    } else {
        $('#komoditas-empty-row').show();
    }
}

function initLsAutocomplete() {
    $('#i_no_ls').on('input', function () {
        var keyword = ($(this).val() || '').trim();
        clearTimeout(cowLsSuggestionTimer);
        hideLsFeedback();

        if (keyword.length < 2) {
            $('#cow-ls-suggestions').empty();
            return;
        }

        cowLsSuggestionTimer = setTimeout(function () {
            $.ajax({
                url: baseurl + 'ekspor/cow/ls-suggestions',
                type: 'POST',
                dataType: 'json',
                data: {
                    q: keyword,
                    csrf_appls: csrfName
                }
            }).done(function (resp) {
                var options = (resp && Array.isArray(resp.data)) ? resp.data : [];
                var html = options.map(function (item) {
                    return '<option value="' + escapeHtml(item.value || '') + '"></option>';
                }).join('');
                $('#cow-ls-suggestions').html(html);
            });
        }, 250);
    });

    $('#i_no_ls').on('change blur', function () {
        fetchLsReference();
    });
}

function fetchLsReference() {
    var noLs = ($('#i_no_ls').val() || '').trim();
    if (!noLs) {
        return;
    }

    $.ajax({
        url: baseurl + 'ekspor/cow/ls-reference',
        type: 'POST',
        dataType: 'json',
        data: {
            no_ls: noLs,
            csrf_appls: csrfName
        }
    }).done(function (resp) {
        if (resp && resp.code === '00' && resp.data) {
            $('#i_tgl_ls').val(resp.data.tgl_ls || '');
            $('#i_kode_ls').val(resp.data.kode_ls || '');
            $('#i_nib').val(resp.data.nib || '');
            $('#i_npwp').val(resp.data.npwp || '');
            $('#i_nitku').val(resp.data.nitku || '');
            $('#i_nama_perusahaan').val(resp.data.nama_perusahaan || '');
            showLsFeedback('success', resp.msg || 'Data LS ditemukan dan field terisi otomatis.');
        } else if (resp) {
            showLsFeedback('warning', resp.msg || 'Nomor LS tidak ditemukan. Anda tetap bisa mengisi manual.');
        }
    }).fail(function () {
        showLsFeedback('danger', 'Gagal mengambil referensi LS. Anda tetap bisa mengisi manual.');
    });
}

function showLsFeedback(type, message) {
    var $feedback = $('#ls-reference-feedback');
    $feedback.removeClass('alert-success alert-warning alert-danger').addClass('alert-' + type).html(message).show();
}

function hideLsFeedback() {
    $('#ls-reference-feedback').hide().removeClass('alert-success alert-warning alert-danger').html('');
}

function collectHeaderData() {
    return {
        idData: ($('#idData').val() || '').trim(),
        jns_penerbitan: ($('#i_jns_penerbitan').val() || '').trim(),
        nib: ($('#i_nib').val() || '').trim(),
        npwp: ($('#i_npwp').val() || '').trim(),
        nitku: ($('#i_nitku').val() || '').trim(),
        nama_perusahaan: ($('#i_nama_perusahaan').val() || '').trim(),
        no_ls: ($('#i_no_ls').val() || '').trim(),
        tgl_ls: ($('#i_tgl_ls').val() || '').trim(),
        kode_ls: ($('#i_kode_ls').val() || '').trim(),
        nomor_cow: ($('#i_nomor_cow').val() || '').trim(),
        tgl_cow: ($('#i_tgl_cow').val() || '').trim(),
        tgl_periksa: ($('#i_tgl_periksa').val() || '').trim()
    };
}

function collectKomoditasData() {
    var komoditas = [];
    $('#komoditas-table-body .komoditas-row').each(function () {
        komoditas.push(getCommodityRowData($(this)));
    });
    return komoditas;
}

function buildPayload() {
    var payload = collectHeaderData();
    payload.komoditas = collectKomoditasData().map(function (item) {
        return {
            ur_barang: item.ur_barang || '',
            spesifikasi: item.spesifikasi || '',
            jml_barang: item.jml_barang || '',
            satuan: item.satuan || ''
        };
    });

    return payload;
}

function saveCow() {
    var payload = buildPayload();
    if (!payload.no_ls) {
        showAlert({ code: '99', msg: 'Nomor LS wajib diisi.', type: 'failed', text: 'Error' });
        return;
    }

    var formData = new FormData();
    formData.append('postdata', JSON.stringify(payload));

    var fileInput = $('#i_file_cow')[0];
    if (fileInput && fileInput.files && fileInput.files[0]) {
        var fileValidation = validateUploadFile(fileInput.files[0], 'COW');
        if (fileValidation !== true) {
            showAlert({ code: '99', msg: fileValidation, type: 'failed', text: 'Error' });
            return;
        }
        formData.append('file_cow', fileInput.files[0]);
    }

    var callback = function (resp) {
        showAlert(resp);

        if (resp && resp.code === '00' && resp.data && resp.data.id) {
            $.redirect(baseurl + 'ekspor/cow/edit', { id: resp.data.id, csrf_appls: csrfName }, "POST", "_self");
        }
    };

    postAjax(baseurl + 'ekspor/cow/save', formData, callback);
}

function toggleCowFileInput(showUpload) {
    if (showUpload) {
        $('#span-view-cow').addClass('d-none');
        $('#span-file-cow').removeClass('d-none');
        $('#i_file_cow').val('');
    } else {
        $('#span-view-cow').removeClass('d-none');
        $('#span-file-cow').addClass('d-none');
    }
}

$('#btn-save-komoditas').on('click', function () {
    var data = collectCommodityForm();
    var validation = validateCommodityForm(data);

    if (validation !== true) {
        showAlert({ code: '99', msg: validation, type: 'failed', text: 'Error' });
        return;
    }

    if (!data.commodityKey) {
        data.commodityKey = generateCommodityKey();
    }

    addKomoditas(data);
    refreshCommodityTable();
    refreshEmptyState();
    resetCommodityForm();
});

$('#btn-reset-komoditas').on('click', function () {
    resetCommodityForm();
});

$(document).on('click', '.btn-edit-komoditas', function () {
    var data = getCommodityRowData($(this).closest('tr'));
    setCommodityForm(data);
    var tab = document.querySelector('a[href="#cowKomoditasTab"]');
    if (tab && window.bootstrap && bootstrap.Tab) {
        bootstrap.Tab.getOrCreateInstance(tab).show();
    }
});

$(document).on('click', '.btn-remove-komoditas', function () {
    $(this).closest('tr').remove();
    refreshCommodityTable();
    refreshEmptyState();
    resetCommodityForm();
});

$(document).on('click', '.btn-delete-file-cow', function () {
    var idData = ($('#idData').val() || '').trim();

    if (!idData) {
        toggleCowFileInput(true);
        return;
    }

    swal_confirm('Konfirmasi hapus', 'Hapus file COW yang sudah diupload?', function (confirm) {
        if (!confirm) {
            return;
        }

        var formData = new FormData();
        formData.append('id', idData);

        postAjax(baseurl + 'ekspor/cow/delete-file', formData, function (resp) {
            showAlert(resp);
            if (resp && resp.code === '00') {
                toggleCowFileInput(true);
            }
        });
    });
});

$('#btn-save-cow').on('click', function () {
    saveCow();
});

$('#btn-back-cow').on('click', function () {
    window.location.href = baseurl + 'ekspor/cow';
});

function escapeHtml(value) {
    return $('<div>').text(value || '').html();
}

function nl2br(value) {
    return (value || '').replace(/\n/g, '<br>');
}

function setCommoditySatuanValue(value, label) {
    var $select = $('#commodity_satuan');
    var selectedValue = (value || '').trim();

    if (!selectedValue) {
        $select.val(null).trigger('change');
        return;
    }

    if (!$select.find('option[value="' + selectedValue + '"]').length) {
        $select.append(new Option(label || selectedValue, selectedValue, true, true));
    }

    $select.val(selectedValue).trigger('change');
}

function validateUploadFile(file, label) {
    if (!file) {
        return true;
    }

    var allowedMimeTypes = [
        'application/pdf',
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/webp',
        'image/gif'
    ];
    var allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'gif'];
    var fileName = (file.name || '').toLowerCase();
    var extension = fileName.indexOf('.') !== -1 ? fileName.split('.').pop() : '';
    var maxBytes = 5 * 1024 * 1024;

    if (file.size > maxBytes) {
        return 'Ukuran file ' + label + ' maksimal 5 MB.';
    }

    if (allowedMimeTypes.indexOf((file.type || '').toLowerCase()) === -1 && allowedExtensions.indexOf(extension) === -1) {
        return 'File ' + label + ' harus berupa image atau PDF dengan format JPG/JPEG/PNG/WEBP/GIF/PDF.';
    }

    return true;
}
