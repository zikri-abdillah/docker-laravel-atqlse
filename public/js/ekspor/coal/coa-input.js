let coaCommodityCounter = 0;
let coaGroupCounter = 0;
let coaRowCounter = 0;
let coaLsSuggestionTimer = null;

$(document).ready(function () {
    bootstrapCoaForm();
});

function bootstrapCoaForm() {
    initCommoditySatuanSelect();

    var nestedData = Array.isArray(window.coaNestedData) ? window.coaNestedData : [];
    var normalized = normalizeNestedData(nestedData);

    normalized.komoditas.forEach(function (komoditas) {
        addKomoditas(komoditas);
    });

    refreshCommodityPanels(false);

    normalized.groups.forEach(function (group) {
        addGroup(group);
    });

    resetCommodityForm();
    refreshCommodityPanels();
    refreshTitles();
    refreshEmptyStates();
    expandAllCommodityPanels();
    expandAllGroupPanels();
}

function normalizeNestedData(nestedData) {
    var komoditas = [];
    var groups = [];

    nestedData.forEach(function (item) {
        var commodityKey = generateCommodityKey();

        komoditas.push({
            commodityKey: commodityKey,
            ur_barang: item.ur_barang || '',
            spesifikasi: item.spesifikasi || '',
            jml_barang: item.jml_barang || '',
            satuan: item.satuan || '',
            satuan_label: item.satuan_label || item.satuan || ''
        });

        (item.coa || []).forEach(function (group) {
            groups.push({
                groupKey: generateGroupKey(),
                commodityKey: commodityKey,
                spec: Array.isArray(group.spec) ? group.spec : [],
                param: Array.isArray(group.param) ? group.param : []
            });
        });
    });

    return { komoditas: komoditas, groups: groups };
}

function initCommoditySatuanSelect() {
    initselectdua('#commodity_satuan', baseurl + 'select/satuan', '', 2);
}

function generateCommodityKey() {
    coaCommodityCounter += 1;
    return 'commodity-' + coaCommodityCounter;
}

function generateGroupKey() {
    coaGroupCounter += 1;
    return 'group-' + coaGroupCounter;
}

function generateRowKey(prefix) {
    coaRowCounter += 1;
    return prefix + '-' + coaRowCounter;
}

function cloneTemplate(id) {
    return $($('#' + id).html());
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
    var commodityKey = commodity.commodityKey || $row.data('commodity-key');
    var groupCount = $('.coa-item[data-commodity-key="' + commodityKey + '"]').length;

    $row.html(
        '<td class="commodity-seri text-center"></td>' +
        '<td>' + nl2br(escapeHtml(commodity.ur_barang || '-')) + '</td>' +
        '<td>' + nl2br(escapeHtml(commodity.spesifikasi || '-')) + '</td>' +
        '<td>' + escapeHtml(commodity.jml_barang || '-') + '</td>' +
        '<td>' + escapeHtml(commodity.satuan_label || commodity.satuan || '-') + '</td>' +
        '<td class="text-center"><span class="badge bg-light-primary text-primary">' + groupCount + '</span></td>' +
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

function addGroup(data) {
    var commodityKey = data && data.commodityKey ? data.commodityKey : getFirstCommodityKey();
    var $panel = getCommodityPanel(commodityKey);

    if (!$panel.length) {
        return;
    }

    var $group = cloneTemplate('template-group');
    var groupKey = data && data.groupKey ? data.groupKey : generateGroupKey();

    $group.attr('data-group-key', groupKey);
    $group.attr('data-commodity-key', commodityKey);
    $panel.find('.commodity-group-list').append($group);

    (data && Array.isArray(data.spec) ? data.spec : []).forEach(function (spec) {
        addSpecRow($group, spec);
    });

    (data && Array.isArray(data.param) ? data.param : []).forEach(function (param) {
        addParamRow($group, param);
    });

    resetSpecForm($group);
    resetParamForm($group);
    openCommodityPanel($panel);
    openCurrentGroup($group);
    refreshTitles();
    refreshEmptyStates();
}

function addSpecRow($group, data) {
    var spec = $.extend({
        rowKey: generateRowKey('spec'),
        ash_arb: '',
        ash_adb: '',
        tm_arb: '',
        inh_adb: '',
        tsulf_arb: '',
        tsulf_adb: '',
        vol_matter: '',
        fix_carb: '',
        size_0: '',
        size_50: '',
        hgi: ''
    }, data || {});

    var $row = $group.find('.spec-table-body tr[data-row-key="' + spec.rowKey + '"]');
    if (!$row.length) {
        $row = $('<tr class="spec-row"></tr>').attr('data-row-key', spec.rowKey);
        $group.find('.spec-table-body').append($row);
    }

    $row.data('spec', spec);
    renderSpecRow($row);
}

function addParamRow($group, data) {
    var param = $.extend({
        rowKey: generateRowKey('param'),
        gcv_arb: '',
        gcv_adb: '',
        ncv_arb: ''
    }, data || {});

    var $row = $group.find('.param-table-body tr[data-row-key="' + param.rowKey + '"]');
    if (!$row.length) {
        $row = $('<tr class="param-row"></tr>').attr('data-row-key', param.rowKey);
        $group.find('.param-table-body').append($row);
    }

    $row.data('param', param);
    renderParamRow($row);
}

function renderSpecRow($row) {
    var spec = getSpecRowData($row);

    $row.html(
        '<td class="spec-seri text-center"></td>' +
        '<td>' +
        'Ash ARB: ' + escapeHtml(spec.ash_arb || '-') + '<br>' +
        'Ash ADB: ' + escapeHtml(spec.ash_adb || '-') + '<br>' +
        'TM ARB: ' + escapeHtml(spec.tm_arb || '-') + '<br>' +
        'IM ADB: ' + escapeHtml(spec.inh_adb || '-') + '<br>' +
        'TS ARB: ' + escapeHtml(spec.tsulf_arb || '-') + '<br>' +
        'TS ADB: ' + escapeHtml(spec.tsulf_adb || '-') +
        '</td>' +
        '<td>' +
        'Volatile: ' + escapeHtml(spec.vol_matter || '-') + '<br>' +
        'Fixed Carbon: ' + escapeHtml(spec.fix_carb || '-') + '<br>' +
        'Size 0: ' + escapeHtml(spec.size_0 || '-') + '<br>' +
        'Size 50: ' + escapeHtml(spec.size_50 || '-') +
        '</td>' +
        '<td>' + escapeHtml(spec.hgi || '-') + '</td>' +
        '<td class="text-nowrap">' +
        '<button type="button" class="btn btn-sm btn-warning me-1 btn-edit-spec"><i class="fa fa-edit"></i></button>' +
        '<button type="button" class="btn btn-sm btn-danger btn-remove-spec"><i class="fa fa-trash"></i></button>' +
        '</td>'
    );
}

function renderParamRow($row) {
    var param = getParamRowData($row);

    $row.html(
        '<td class="param-seri text-center"></td>' +
        '<td>' + escapeHtml(param.gcv_arb || '-') + '</td>' +
        '<td>' + escapeHtml(param.gcv_adb || '-') + '</td>' +
        '<td>' + escapeHtml(param.ncv_arb || '-') + '</td>' +
        '<td class="text-nowrap">' +
        '<button type="button" class="btn btn-sm btn-warning me-1 btn-edit-param"><i class="fa fa-edit"></i></button>' +
        '<button type="button" class="btn btn-sm btn-danger btn-remove-param"><i class="fa fa-trash"></i></button>' +
        '</td>'
    );
}

function getSpecRowData($row) {
    return $.extend({
        rowKey: $row.data('row-key'),
        ash_arb: '',
        ash_adb: '',
        tm_arb: '',
        inh_adb: '',
        tsulf_arb: '',
        tsulf_adb: '',
        vol_matter: '',
        fix_carb: '',
        size_0: '',
        size_50: '',
        hgi: ''
    }, $row.data('spec') || {});
}

function getParamRowData($row) {
    return $.extend({
        rowKey: $row.data('row-key'),
        gcv_arb: '',
        gcv_adb: '',
        ncv_arb: ''
    }, $row.data('param') || {});
}

function collectFields($scope, selector) {
    var data = {};

    $scope.find(selector).each(function () {
        var key = $(this).data('field');
        data[key] = ($(this).val() || '').trim();
    });

    return data;
}

function setFields($scope, selector, data) {
    $scope.find(selector).each(function () {
        var key = $(this).data('field');
        $(this).val(data[key] || '');
    });
}

function setSpecForm($group, data) {
    $group.find('.spec-edit-key').val(data.rowKey || '');
    setFields($group, '.spec-input', data);
    $group.find('.btn-save-spec').html('<i class="bx bx-save me-1"></i>Update Spec');
}

function resetSpecForm($group) {
    $group.find('.spec-edit-key').val('');
    setFields($group, '.spec-input', {});
    $group.find('.btn-save-spec').html('<i class="bx bx-save me-1"></i>Simpan Spec');
}

function setParamForm($group, data) {
    $group.find('.param-edit-key').val(data.rowKey || '');
    setFields($group, '.param-input', data);
    $group.find('.btn-save-param').html('<i class="bx bx-save me-1"></i>Update Param');
}

function resetParamForm($group) {
    $group.find('.param-edit-key').val('');
    setFields($group, '.param-input', {});
    $group.find('.btn-save-param').html('<i class="bx bx-save me-1"></i>Simpan Param');
}

function collectSpecForm($group) {
    var data = collectFields($group, '.spec-input');
    data.rowKey = ($group.find('.spec-edit-key').val() || '').trim();
    return data;
}

function collectParamForm($group) {
    var data = collectFields($group, '.param-input');
    data.rowKey = ($group.find('.param-edit-key').val() || '').trim();
    return data;
}

function validateSpecForm(data) {
    var requiredFields = ['ash_arb', 'ash_adb', 'tm_arb', 'inh_adb', 'tsulf_arb', 'tsulf_adb'];

    for (var i = 0; i < requiredFields.length; i += 1) {
        if (!(data[requiredFields[i]] || '').trim()) {
            return 'Field ' + requiredFields[i] + ' pada spec wajib diisi.';
        }
    }

    return true;
}

function validateParamForm(data) {
    if (!(data.gcv_arb || '').trim()) {
        return 'Field gcv_arb pada param wajib diisi.';
    }

    return true;
}

function refreshGroupTables($group) {
    $group.find('.spec-row').each(function (index) {
        renderSpecRow($(this));
        $(this).find('.spec-seri').text(index + 1);
    });

    $group.find('.param-row').each(function (index) {
        renderParamRow($(this));
        $(this).find('.param-seri').text(index + 1);
    });

    $group.find('.spec-empty-row').toggle($group.find('.spec-row').length === 0);
    $group.find('.param-empty-row').toggle($group.find('.param-row').length === 0);
}

function refreshTitles() {
    refreshCommodityTable();

    $('#coa-commodity-list .coa-commodity-panel').each(function (commodityIndex) {
        var $panel = $(this);
        var commodityKey = $panel.data('commodity-key');
        var commodity = getCommodityByKey(commodityKey);
        var panelId = 'coa-commodity-collapse-' + commodityIndex;
        var groupCount = $panel.find('.coa-item').length;
        var title = 'Komoditas ' + (commodityIndex + 1);

        if ((commodity.ur_barang || '').trim()) {
            title += ' - ' + commodity.ur_barang.trim();
        }

        $panel.find('.commodity-panel-title').text(title);
        $panel.find('.commodity-panel-subtitle').text(groupCount + ' COA Group | Spec dan Param dikelola di dalam komoditas ini.');
        $panel.find('.commodity-panel-toggle')
            .attr('data-bs-target', '#' + panelId)
            .attr('aria-controls', panelId);
        $panel.find('.commodity-panel-collapse')
            .attr('id', panelId);
        $panel.find('.commodity-group-empty').toggleClass('d-none', groupCount > 0);
    });

    $('.coa-item').each(function (indexGroup) {
        var $group = $(this);
        var groupId = 'coa-group-collapse-' + indexGroup;
        var specTabId = 'coa-spec-tab-' + indexGroup;
        var paramTabId = 'coa-param-tab-' + indexGroup;
        var specPaneId = 'coa-spec-pane-' + indexGroup;
        var paramPaneId = 'coa-param-pane-' + indexGroup;
        var specCount = $group.find('.spec-row').length;
        var paramCount = $group.find('.param-row').length;
        var commodity = getCommodityByKey($group.data('commodity-key'));
        var commodityLabel = (commodity.ur_barang || '').trim() || 'Komoditas';

        refreshGroupTables($group);

        $group.find('.group-title').text('Data COA');
        $group.find('.group-subtitle').text(commodityLabel + ' | ' + specCount + ' Spec | ' + paramCount + ' Param');

        $group.find('.group-toggle')
            .attr('data-bs-target', '#' + groupId)
            .attr('aria-controls', groupId);
        $group.find('.group-collapse')
            .attr('id', groupId);
        $group.find('.group-spec-tab')
            .attr('id', specTabId)
            .attr('data-bs-target', '#' + specPaneId)
            .attr('aria-controls', specPaneId);
        $group.find('.group-param-tab')
            .attr('id', paramTabId)
            .attr('data-bs-target', '#' + paramPaneId)
            .attr('aria-controls', paramPaneId);
        $group.find('.group-spec-pane')
            .attr('id', specPaneId)
            .attr('aria-labelledby', specTabId);
        $group.find('.group-param-pane')
            .attr('id', paramPaneId)
            .attr('aria-labelledby', paramTabId);
    });
}

function refreshEmptyStates() {
    $('#komoditas-empty-row').toggle($('#komoditas-table-body .komoditas-row').length === 0);
    $('#coa-commodity-empty').toggleClass('d-none', $('#coa-commodity-list .coa-commodity-panel').length > 0);
    $('#coa-commodity-list .coa-commodity-panel').each(function () {
        $(this).find('.commodity-group-empty').toggleClass('d-none', $(this).find('.coa-item').length > 0);
    });
}

function getCommoditySummaries() {
    var commodities = [];

    $('#komoditas-table-body .komoditas-row').each(function () {
        var commodity = getCommodityRowData($(this));
        commodities.push({
            key: commodity.commodityKey,
            ur_barang: (commodity.ur_barang || '').trim()
        });
    });

    return commodities;
}

function refreshCommodityPanels(ensureDefaultGroup) {
    if (typeof ensureDefaultGroup === 'undefined') {
        ensureDefaultGroup = true;
    }

    var commodities = getCommoditySummaries();
    var existingKeys = {};

    commodities.forEach(function (commodity, index) {
        existingKeys[commodity.key] = true;
        var $panel = getCommodityPanel(commodity.key);

        if (!$panel.length) {
            $panel = cloneTemplate('template-commodity-coa-panel');
            $panel.attr('data-commodity-key', commodity.key);
            $('#coa-commodity-list').append($panel);
        }

        var title = 'Komoditas ' + (index + 1);
        if (commodity.ur_barang) {
            title += ' - ' + commodity.ur_barang;
        }

        $panel.find('.commodity-panel-title').text(title);
        if (ensureDefaultGroup) {
            ensureSingleGroupForCommodity(commodity.key);
        }
    });

    $('#coa-commodity-list .coa-commodity-panel').each(function () {
        var $panel = $(this);
        if (!existingKeys[$panel.data('commodity-key')]) {
            $panel.remove();
        }
    });

    expandAllCommodityPanels();
}

function getCommodityByKey(commodityKey) {
    var commodity = {
        commodityKey: commodityKey || '',
        ur_barang: '',
        spesifikasi: '',
        jml_barang: '',
        satuan: '',
        satuan_label: ''
    };
    var $row = $('#komoditas-table-body .komoditas-row[data-commodity-key="' + commodityKey + '"]');

    if ($row.length) {
        commodity = getCommodityRowData($row);
    }

    return commodity;
}

function getCommodityPanel(commodityKey) {
    return $('#coa-commodity-list .coa-commodity-panel[data-commodity-key="' + commodityKey + '"]');
}

function getFirstCommodityKey() {
    return $('#komoditas-table-body .komoditas-row').first().data('commodity-key') || '';
}

function ensureSingleGroupForCommodity(commodityKey) {
    if (!(commodityKey || '').trim()) {
        return;
    }

    var $panel = getCommodityPanel(commodityKey);
    if (!$panel.length) {
        return;
    }

    if (!$panel.find('.coa-item').length) {
        addGroup({ commodityKey: commodityKey });
    }
}

function openCommodityPanel($panel) {
    if (!$panel || !$panel.length) {
        return;
    }

    var panelCollapse = $panel.find('.commodity-panel-collapse')[0];
    if (panelCollapse) {
        bootstrap.Collapse.getOrCreateInstance(panelCollapse, { toggle: false }).show();
    }
}

function expandAllCommodityPanels() {
    $('#coa-commodity-list .coa-commodity-panel').each(function () {
        var $panel = $(this);
        var collapseEl = $panel.find('.commodity-panel-collapse')[0];
        var $toggle = $panel.find('.commodity-panel-toggle');

        if (!collapseEl) {
            return;
        }

        bootstrap.Collapse.getOrCreateInstance(collapseEl, { toggle: false }).show();
        $toggle.removeClass('collapsed').attr('aria-expanded', 'true');
    });
}

function expandAllGroupPanels() {
    $('.coa-item').each(function () {
        var $group = $(this);
        var collapseEl = $group.find('.group-collapse')[0];
        var $toggle = $group.find('.group-toggle');

        if (!collapseEl) {
            return;
        }

        bootstrap.Collapse.getOrCreateInstance(collapseEl, { toggle: false }).show();
        $toggle.removeClass('collapsed').attr('aria-expanded', 'true');
    });
}

function openCurrentGroup($group) {
    if (!$group || !$group.length) {
        return;
    }

    openCommodityPanel($group.closest('.coa-commodity-panel'));

    var groupCollapse = $group.find('.group-collapse')[0];
    if (groupCollapse) {
        bootstrap.Collapse.getOrCreateInstance(groupCollapse, { toggle: false }).show();
        $group.find('.group-toggle').removeClass('collapsed').attr('aria-expanded', 'true');
    }
}

function collectHeaderData() {
    return {
        idData: $('#idData').val(),
        jns_penerbitan: $('#i_jns_penerbitan').val(),
        nib: ($('#i_nib').val() || '').trim(),
        npwp: ($('#i_npwp').val() || '').trim(),
        nitku: ($('#i_nitku').val() || '').trim(),
        nama_perusahaan: ($('#i_nama_perusahaan').val() || '').trim(),
        no_ls: ($('#i_no_ls').val() || '').trim(),
        tgl_ls: ($('#i_tgl_ls').val() || '').trim(),
        kode_ls: ($('#i_kode_ls').val() || '').trim(),
        nomor_coa: ($('#i_nomor_coa').val() || '').trim(),
        tgl_coa: ($('#i_tgl_coa').val() || '').trim(),
        tgl_periksa: ($('#i_tgl_periksa').val() || '').trim()
    };
}

function collectCommodityData() {
    var commodities = [];

    $('#komoditas-table-body .komoditas-row').each(function () {
        commodities.push(getCommodityRowData($(this)));
    });

    return commodities;
}

function collectGroupData() {
    var groups = [];

    $('.coa-item').each(function () {
        var $group = $(this);
        var group = {
            commodityKey: $group.data('commodity-key') || '',
            spec: [],
            param: []
        };

        $group.find('.spec-row').each(function () {
            var spec = getSpecRowData($(this));
            delete spec.rowKey;
            group.spec.push(spec);
        });

        $group.find('.param-row').each(function () {
            var param = getParamRowData($(this));
            delete param.rowKey;
            group.param.push(param);
        });

        groups.push(group);
    });

    return groups;
}

function buildPayloadForSave() {
    var payload = collectHeaderData();
    var commodities = collectCommodityData();
    var groups = collectGroupData();
    var commodityMap = {};

    payload.komoditas = commodities.map(function (commodity) {
        commodityMap[commodity.commodityKey] = {
            ur_barang: commodity.ur_barang,
            spesifikasi: commodity.spesifikasi,
            jml_barang: commodity.jml_barang,
            satuan: commodity.satuan,
            coa: []
        };
        return commodityMap[commodity.commodityKey];
    });

    groups.forEach(function (group) {
        if (group.commodityKey && commodityMap[group.commodityKey]) {
            commodityMap[group.commodityKey].coa.push({
                spec: group.spec,
                param: group.param
            });
        }
    });

    return payload;
}

function escapeHtml(text) {
    return $('<div>').text(text || '').html();
}

function nl2br(text) {
    return (text || '').replace(/\n/g, '<br>');
}

function requestLsSuggestions(keyword) {
    $.ajax({
        url: baseurl + 'ekspor/coa/ls-suggestions',
        type: 'POST',
        dataType: 'json',
        data: {
            q: keyword,
            csrf_appls: typeof csrfName !== 'undefined' ? csrfName : ''
        },
        success: function (resp) {
            renderLsSuggestions(resp && Array.isArray(resp.data) ? resp.data : []);
        }
    });
}

function renderLsSuggestions(items) {
    var options = '';

    items.forEach(function (item) {
        if (!(item.value || '').trim()) {
            return;
        }

        options += '<option value="' + escapeHtml(item.value) + '">' + escapeHtml(item.label || item.value) + '</option>';
    });

    $('#coa-ls-suggestions').html(options);
}

function setLsFeedback(type, message) {
    var $feedback = $('#ls-reference-feedback');

    $feedback
        .removeClass('alert-success alert-warning alert-danger')
        .addClass('alert-' + type)
        .text(message)
        .show();
}

function clearLsFeedback() {
    $('#ls-reference-feedback')
        .removeClass('alert-success alert-warning alert-danger')
        .text('')
        .hide();
}

function lookupLsReference(noLs) {
    if (!(noLs || '').trim()) {
        clearLsFeedback();
        return;
    }

    $.ajax({
        url: baseurl + 'ekspor/coa/ls-reference',
        type: 'POST',
        dataType: 'json',
        data: {
            no_ls: noLs,
            csrf_appls: typeof csrfName !== 'undefined' ? csrfName : ''
        },
        success: function (resp) {
            if (resp && resp.code === '00' && resp.data) {
                fillLsReference(resp.data);
                setLsFeedback('success', 'Data LS ditemukan. Field terkait telah terisi otomatis dan tetap bisa Anda ubah.');
            } else if (resp && resp.msg) {
                setLsFeedback('warning', resp.msg + ' Anda tetap bisa mengisi data secara manual.');
            }
        },
        error: function () {
            setLsFeedback('danger', 'Gagal mengambil referensi LS. Anda tetap bisa mengisi data secara manual.');
        }
    });
}

function fillLsReference(data) {
    $('#i_no_ls').val(data.no_ls || $('#i_no_ls').val());
    $('#i_tgl_ls').val(data.tgl_ls || $('#i_tgl_ls').val());
    $('#i_kode_ls').val(data.kode_ls || $('#i_kode_ls').val());
    $('#i_nib').val(data.nib || $('#i_nib').val());
    $('#i_npwp').val(data.npwp || $('#i_npwp').val());
    $('#i_nitku').val(data.nitku || $('#i_nitku').val());
    $('#i_nama_perusahaan').val(data.nama_perusahaan || $('#i_nama_perusahaan').val());
}

$('#btn-save-komoditas').click(function () {
    var commodity = collectCommodityForm();
    var validation = validateCommodityForm(commodity);

    if (validation !== true) {
        showAlert({ code: '99', msg: validation });
        return;
    }

    if (!commodity.commodityKey) {
        commodity.commodityKey = generateCommodityKey();
    }

    addKomoditas(commodity);
    resetCommodityForm();
    refreshCommodityPanels();
    refreshTitles();
    refreshEmptyStates();
});

$('#btn-reset-komoditas').click(function () {
    resetCommodityForm();
});

$(document).on('click', '.btn-edit-komoditas', function () {
    var commodity = getCommodityRowData($(this).closest('.komoditas-row'));
    setCommodityForm(commodity);

    var tab = document.querySelector('a[href="#coaKomoditasTab"]');
    if (tab) {
        bootstrap.Tab.getOrCreateInstance(tab).show();
    }
});

$(document).on('click', '.btn-add-group', function () {
    var $panel = $(this).closest('.coa-commodity-panel');
    var commodityKey = $panel.data('commodity-key') || '';

    if (!commodityKey) {
        showAlert({ code: '99', msg: 'Komoditas tidak valid untuk penambahan COA Group.' });
        return;
    }

    addGroup({ commodityKey: commodityKey });

    var tab = document.querySelector('a[href="#coaGroupTab"]');
    if (tab) {
        bootstrap.Tab.getOrCreateInstance(tab).show();
    }
});

$(document).on('click', '.btn-save-spec', function () {
    var $group = $(this).closest('.coa-item');
    var spec = collectSpecForm($group);
    var validation = validateSpecForm(spec);

    if (validation !== true) {
        showAlert({ code: '99', msg: validation });
        return;
    }

    if (!spec.rowKey) {
        var existingSpecKey = $group.find('.spec-row').first().data('row-key');
        spec.rowKey = existingSpecKey || generateRowKey('spec');
    }

    addSpecRow($group, spec);
    resetSpecForm($group);
    refreshTitles();
});

$(document).on('click', '.btn-reset-spec', function () {
    resetSpecForm($(this).closest('.coa-item'));
});

$(document).on('click', '.btn-edit-spec', function () {
    var $group = $(this).closest('.coa-item');
    var spec = getSpecRowData($(this).closest('.spec-row'));
    setSpecForm($group, spec);
    $group.find('.group-spec-tab').trigger('click');
    openCurrentGroup($group);
});

$(document).on('click', '.btn-remove-spec', function () {
    var $group = $(this).closest('.coa-item');
    var rowKey = $(this).closest('.spec-row').data('row-key');

    $(this).closest('.spec-row').remove();

    if ($group.find('.spec-edit-key').val() === rowKey) {
        resetSpecForm($group);
    }

    refreshTitles();
});

$(document).on('click', '.btn-save-param', function () {
    var $group = $(this).closest('.coa-item');
    var param = collectParamForm($group);
    var validation = validateParamForm(param);

    if (validation !== true) {
        showAlert({ code: '99', msg: validation });
        return;
    }

    if (!param.rowKey) {
        var existingParamKey = $group.find('.param-row').first().data('row-key');
        param.rowKey = existingParamKey || generateRowKey('param');
    }

    addParamRow($group, param);
    resetParamForm($group);
    refreshTitles();
});

$(document).on('click', '.btn-reset-param', function () {
    resetParamForm($(this).closest('.coa-item'));
});

$(document).on('click', '.btn-edit-param', function () {
    var $group = $(this).closest('.coa-item');
    var param = getParamRowData($(this).closest('.param-row'));
    setParamForm($group, param);
    $group.find('.group-param-tab').trigger('click');
    openCurrentGroup($group);
});

$(document).on('click', '.btn-remove-param', function () {
    var $group = $(this).closest('.coa-item');
    var rowKey = $(this).closest('.param-row').data('row-key');

    $(this).closest('.param-row').remove();

    if ($group.find('.param-edit-key').val() === rowKey) {
        resetParamForm($group);
    }

    refreshTitles();
});

$(document).on('click', '.btn-remove-komoditas', function () {
    var $row = $(this).closest('.komoditas-row');
    var removedKey = $row.data('commodity-key');

    $row.remove();

    if ($('#commodity-edit-key').val() === removedKey) {
        resetCommodityForm();
    }

    $('.coa-item[data-commodity-key="' + removedKey + '"]').remove();
    refreshCommodityPanels();
    refreshTitles();
    refreshEmptyStates();
});

$(document).on('click', '.btn-remove-group', function () {
    $(this).closest('.coa-item').remove();
    refreshTitles();
    refreshEmptyStates();
    openCurrentGroup($('.coa-item').first());
});

$(document).on('click', '.btn-delete-file-coa', function () {
    if (!$('#idData').val()) {
        $('#i_file_coa').val('');
        $('#span-view-coa').addClass('d-none');
        $('#span-file-coa').removeClass('d-none');
        return;
    }

    swal_confirm('Konfirmasi Hapus', 'Hapus file COA?', function (confirm) {
        if (!confirm) {
            return;
        }

        var formData = new FormData();
        formData.append('id', $('#idData').val());

        const callback = function (resp) {
            showAlert(resp);

            if (resp.code == '00') {
                $('#i_file_coa').val('');
                $('#span-view-coa').addClass('d-none');
                $('#span-file-coa').removeClass('d-none');
            }
        };

        postAjax(baseurl + 'ekspor/coa/delete-file', formData, callback);
    });
});

$('#i_no_ls').on('input', function () {
    var keyword = ($(this).val() || '').trim();

    clearTimeout(coaLsSuggestionTimer);
    clearLsFeedback();

    if (keyword.length < 2) {
        renderLsSuggestions([]);
        return;
    }

    coaLsSuggestionTimer = setTimeout(function () {
        requestLsSuggestions(keyword);
    }, 250);
});

$('#i_no_ls').on('change blur', function () {
    var noLs = ($(this).val() || '').trim();
    lookupLsReference(noLs);
});

$('#btn-back-coa').click(function () {
    window.location.href = baseurl + 'ekspor/coa';
});

$('a[href="#coaGroupTab"]').on('shown.bs.tab', function () {
    expandAllCommodityPanels();
    expandAllGroupPanels();
});

$('#btn-save-coa').click(function () {
    if (!(($('#i_no_ls').val() || '').trim())) {
        showAlert({ code: '99', msg: 'Nomor LS wajib diisi.' });
        bootstrap.Tab.getOrCreateInstance(document.querySelector('a[href="#coaUmumTab"]')).show();
        return;
    }

    var formData = new FormData();
    formData.append('postdata', JSON.stringify(buildPayloadForSave()));

    var fileCoa = $('#i_file_coa')[0];
    if (fileCoa && fileCoa.files && fileCoa.files[0]) {
        var fileValidation = validateUploadFile(fileCoa.files[0], 'COA');
        if (fileValidation !== true) {
            showAlert({ code: '99', msg: fileValidation });
            bootstrap.Tab.getOrCreateInstance(document.querySelector('a[href="#coaUmumTab"]')).show();
            return;
        }
        formData.append('file_coa', fileCoa.files[0]);
    }

    const callback = function (resp) {
        showAlert(resp);

        if (resp.code == '00') {
            if (resp.data && resp.data.id) {
                $('#idData').val(resp.data.id);
            }

            setTimeout(function () {
                window.location.href = baseurl + 'ekspor/coa';
            }, 1200);
        }
    };

    postAjax(baseurl + 'ekspor/coa/save', formData, callback, 'json', false);
});

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
