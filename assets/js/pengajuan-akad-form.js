(function ($) {
    'use strict';
    var modal=$('#pengajuan-akad-modal'),form=$('#pengajuan-akad-form'),busy=false;
    if (!form.length) return;
    modal.on('show.bs.modal',function (event) {
        var value=$(event.relatedTarget).attr('data-akad'),row=value ? JSON.parse(value) : null;
        form[0].reset();$('#pengajuan-akad-error').hide().text('');
        $('#pengajuan-akad-modal-title').text(row ? 'Edit Pengajuan Akad' : 'Tambah Pengajuan Akad');
        $('#pengajuan-akad-id').val(row ? row.id : 0);
        $('#akad-projid').val(row && row.projid ? row.projid : '');
        $('#akad-kode').val(row ? row.kode_akad : '');$('#akad-tanggal').val(row ? row.tanggal_akad : '');$('#akad-keterangan').val(row ? row.keterangan : '');
    });
    modal.on('shown.bs.modal',function () { $('#akad-projid').trigger('focus'); });
    modal.on('hide.bs.modal',function (event) { if (busy) event.preventDefault(); });
    form.on('submit',function (event) {
        event.preventDefault();if (busy || !form[0].reportValidity()) return;
        busy=true;$('#pengajuan-akad-save').prop('disabled',true).text('Menyimpan...');$('#pengajuan-akad-error').hide();
        $.ajax({url:form.attr('action'),method:'POST',data:form.serialize(),dataType:'json'})
            .done(function () {
                var project=$('#pengajuan-filter-project').val();
                window.location.href=window.location.pathname+(project && project!=='0' ? '?projid='+encodeURIComponent(project) : '');
            })
            .fail(function (xhr) { $('#pengajuan-akad-error').text(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Pengajuan gagal disimpan. Muat ulang halaman atau coba kembali.').show(); })
            .always(function () { busy=false;$('#pengajuan-akad-save').prop('disabled',false).text('Simpan'); });
    });
    var params=new URLSearchParams(window.location.search),edit=params.get('edit');
    if (params.get('form')==='add') $('#pengajuan-akad-add').trigger('click');
    else if (edit && /^\d+$/.test(edit)) $('[data-akad-id="'+edit+'"]').first().trigger('click');
}(jQuery));
