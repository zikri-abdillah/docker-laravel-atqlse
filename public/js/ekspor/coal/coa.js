$(document).ready(function () {
    get_list_coa();
});

function get_list_coa() {
    var searchParam = JSON.stringify($('#frm-tracking-coa').serializeArray());

    if ($.fn.DataTable.isDataTable('#coa-list')) {
        $('#coa-list').DataTable().destroy();
    }

    new DataTable('#coa-list', {
        ajax: {
            url: baseurl + 'ekspor/coa/list',
            type: 'POST',
            data: { searchParam: searchParam, csrf_appls: csrfName }
        },
        columns: [
            { className: "text-center text-nowrap align-top" },
            { className: "text-nowrap align-top" },
            { className: "text-nowrap align-top" },
            { className: "text-nowrap align-top" },
            { className: "align-top" },
            { className: "text-center align-top" },
            { className: "text-center align-top" }
        ],
        searching: false,
        ordering: false,
        processing: true,
        serverSide: true,
        scrollX: true,
        autoWidth: true,
    });
}

$('#btn-reset-coal').click(function () {
    $('#frm-tracking-coa')[0].reset();
    get_list_coa();
});

$('#btn-tracking-coal').click(function () {
    get_list_coa();
});

function edit(id) {
    $.redirect(baseurl + 'ekspor/coa/edit', { id: id, csrf_appls: csrfName }, "POST", "_self");
}

function viewData(id) {
    $.redirect(baseurl + 'ekspor/coa/view', { id: id, csrf_appls: csrfName }, "POST", "_self");
}

function del(id) {
    const callback = function (resp) {
        showAlert(resp);
        get_list_coa();
    }

    swal_confirm('Konfirmasi hapus', 'Hapus data COA ini?', function (confirm) {
        if (confirm) {
            var formData = new FormData();
            formData.append("id", id);
            postAjax(baseurl + "ekspor/coa/delete", formData, callback);
        }
    });
}

function sendCoa(id) {
    const callback = function (resp) {
        const hasRawResponse = resp && resp.data && typeof resp.data === 'object' && resp.data.raw_response;
        const alertInstance = showAlert(resp);

        if (hasRawResponse && alertInstance && typeof alertInstance.then === 'function') {
            alertInstance.then(function () {
                get_list_coa();
            });
            return;
        }

        get_list_coa();
    };

    swal_confirm('Konfirmasi kirim', 'Kirim data COA ini ke Inatrade?', function (confirm) {
        if (confirm) {
            var formData = new FormData();
            formData.append("id", id);
            postAjax(baseurl + "ekspor/coa/send", formData, callback);
        }
    });
}

function cancelCoa(id) {
    swal_confirm('Konfirmasi pembatalan', 'Kirim pembatalan untuk data COA ini ke Inatrade?', function (confirm) {
        if (confirm) {
            var formData = new FormData();
            formData.append("id", id);
            postAjax(baseurl + "ekspor/coa/pembatalan", formData, function (resp) {
                const hasRawResponse = resp && resp.data && typeof resp.data === 'object' && resp.data.raw_response;
                const alertInstance = showAlert(resp);

                if (hasRawResponse && alertInstance && typeof alertInstance.then === 'function') {
                    alertInstance.then(function () {
                        get_list_coa();
                    });
                    return;
                }

                get_list_coa();
            });
        }
    });
}
