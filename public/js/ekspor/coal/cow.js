$(document).ready(function () {
    get_list_cow();
});

function get_list_cow() {
    var searchParam = JSON.stringify($('#frm-tracking-cow').serializeArray());

    if ($.fn.DataTable.isDataTable('#cow-list')) {
        $('#cow-list').DataTable().destroy();
    }

    new DataTable('#cow-list', {
        ajax: {
            url: baseurl + 'ekspor/cow/list',
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

$('#btn-reset-cow').click(function () {
    $('#frm-tracking-cow')[0].reset();
    get_list_cow();
});

$('#btn-tracking-cow').click(function () {
    get_list_cow();
});

function edit(id) {
    $.redirect(baseurl + 'ekspor/cow/edit', { id: id, csrf_appls: csrfName }, "POST", "_self");
}

function viewData(id) {
    $.redirect(baseurl + 'ekspor/cow/view', { id: id, csrf_appls: csrfName }, "POST", "_self");
}

function del(id) {
    const callback = function (resp) {
        showAlert(resp);
        get_list_cow();
    };

    swal_confirm('Konfirmasi hapus', 'Hapus data COW ini?', function (confirm) {
        if (confirm) {
            var formData = new FormData();
            formData.append("id", id);
            postAjax(baseurl + "ekspor/cow/delete", formData, callback);
        }
    });
}

function sendCow(id) {
    const callback = function (resp) {
        const hasRawResponse = resp && resp.data && typeof resp.data === 'object' && resp.data.raw_response;
        const alertInstance = showAlert(resp);

        if (hasRawResponse && alertInstance && typeof alertInstance.then === 'function') {
            alertInstance.then(function () {
                get_list_cow();
            });
            return;
        }

        get_list_cow();
    };

    swal_confirm('Konfirmasi kirim', 'Kirim data COW ini ke Inatrade?', function (confirm) {
        if (confirm) {
            var formData = new FormData();
            formData.append("id", id);
            postAjax(baseurl + "ekspor/cow/send", formData, callback);
        }
    });
}

function cancelCow(id) {
    swal_confirm('Konfirmasi pembatalan', 'Kirim pembatalan untuk data COW ini ke Inatrade?', function (confirm) {
        if (confirm) {
            var formData = new FormData();
            formData.append("id", id);
            postAjax(baseurl + "ekspor/cow/pembatalan", formData, function (resp) {
                const hasRawResponse = resp && resp.data && typeof resp.data === 'object' && resp.data.raw_response;
                const alertInstance = showAlert(resp);

                if (hasRawResponse && alertInstance && typeof alertInstance.then === 'function') {
                    alertInstance.then(function () {
                        get_list_cow();
                    });
                    return;
                }

                get_list_cow();
            });
        }
    });
}
