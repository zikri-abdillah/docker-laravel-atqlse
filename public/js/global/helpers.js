let csrfName = $('meta[name="APPLS-CSRF-TOKEN"]').attr("content"); 

function postAjax(url,postData,callbacks,dataType='json',processData=false) {
  $("#global-loader").show();
  postData.append('csrf_appls', csrfName);
  let jqxhr = $.ajax({
      url: url,
      method: 'POST',
      data: postData,
      dataType: dataType,
      processData: processData,
      contentType: false,
  })
  .done(function(resp) {
      callbacks(resp)
  })
  .fail(function(xhr, status, error) {
      console.log(error)
  })
  .always(function() {
    $("#global-loader").hide();
  });
}

function getAjax(url,postData,callbacks,dataType='json',processData=false) {
  $("#global-loader").show();
  postData.append('csrf_appls', csrfName);
  let jqxhr = $.ajax({
      url: url,
      method: 'DELETE',
      data: postData,
      dataType: dataType,
      processData: processData,
      contentType: false,
  })
  .done(function(resp) {
      callbacks(resp)
  })
  .fail(function(xhr, status, error) {
      console.log(error)
  })
  .always(function() {
    $("#global-loader").hide();
  });
}

function escapeHtml(value) {
  if (value === null || value === undefined) {
    return '';
  }

  return String(value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

function buildAlertHtml(resp) {
  let html = resp && resp.msg ? resp.msg : '';
  const hasRawResponse = resp && resp.data && typeof resp.data === 'object' && resp.data.raw_response !== undefined && resp.data.raw_response !== null;

  if (!hasRawResponse) {
    return html;
  }

  let metaInfo = '';
  if (resp.data.response_code !== undefined && resp.data.response_code !== null && resp.data.response_code !== '') {
    metaInfo += '<div><strong>Response code:</strong> ' + escapeHtml(resp.data.response_code) + '</div>';
  }

  if (resp.data.http_status !== undefined && resp.data.http_status !== null && resp.data.http_status !== '') {
    metaInfo += '<div><strong>HTTP status:</strong> ' + escapeHtml(resp.data.http_status) + '</div>';
  }

  html += '<hr>';
  html += '<div style="text-align:left;"><strong>Raw response Inatrade</strong></div>';
  html += metaInfo;
  html += '<pre style="text-align:left;max-height:320px;overflow:auto;white-space:pre-wrap;word-break:break-word;background:#f8f9fa;border:1px solid #dee2e6;border-radius:6px;padding:12px;margin-top:8px;">' + escapeHtml(resp.data.raw_response) + '</pre>';

  return html;
}

function showAlert(resp,times=5000) {
  $("#global-loader").hide();

  var type = '';
  if(resp.code == '00')
      type = 'success';
  else if(resp.code == '55')
      type = 'warning';
  else if(resp.code == '99')
      type = 'error';

  if(type != '')
  {
    const hasRawResponse = resp && resp.data && typeof resp.data === 'object' && resp.data.raw_response !== undefined && resp.data.raw_response !== null;
    const alertOptions = {
      title: resp.text,
      html: buildAlertHtml(resp),
      icon: type,
      confirmButtonColor: '#57a94f'
    };

    if (!hasRawResponse && typeof times === 'number' && times > 0) {
      alertOptions.timer = times;
    } else {
      alertOptions.confirmButtonText = 'Tutup';
      alertOptions.showCloseButton = true;
      alertOptions.width = '60rem';
    }

    return Swal.fire(alertOptions);
  }
}

function alertRedirect(resp,times=5000,url)
{
  // console.log(resp);
  $("#global-loader").hide();

  var type = '';
  if(resp.code == '00')
      type = 'success';
  else if(resp.code == '55')
      type = 'warning';
  else if(resp.code == '99')
      type = 'error';

  Swal.fire({
    title: resp.text,
    html: resp.msg,
    timer: times,
    showCancelButton: true,
    confirmButtonText: "Ok",
    cancelButtonText: 'Tetap Disini'
  }).then((result) => {
    //window.location.replace(url);
    if (result.isConfirmed) {
      window.location.replace(url);
    }
  })
}


function swal_confirm(title='Konfirmasi',msg='Lanjutkan ?',callback)
{
  Swal.fire({
    title: title,
    html: msg,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      callback('111111');
    }
    else{
      Swal.fire(
        'Dibatalkan!',
        'Aksi dibatalkan',
        'success'
      )
    }
  })
}

$(document).on("click", ".btn-back", function (e) { // <-- see second argument of .on
  e.preventDefault();
  $.redirect(baseurl+'back', {csrf_appls: csrfName}, "POST", "_self");
});

function GoBack(event) {
    if ('referrer' in document) {
        window.location = document.referrer;
    } else {
        window.history.back();
    }
}


function getBulanIndonesia(bulan) {
    const bulanIndo = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    return (bulan >= 1 && bulan <= 12) ? bulanIndo[bulan - 1] : "Bulan tidak valid. Silakan masukkan angka antara 1-12.";
}


function reverseDate(tanggal) {
  if(tanggal)
  {
    const [tahun, bulan, hari] = tanggal.split("-");
    return `${hari}-${bulan}-${tahun}`;
  }
}
