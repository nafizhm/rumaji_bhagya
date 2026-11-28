(function ($) {
    'use strict';
    var modal=$('#lot-release-modal'), form=$('#lot-release-form');
    if (!form.length) return;
    var busy=false;
    var money=form.find('.release-money');
    function rawMoney(value) {
        return String(value).replace(/\./g,'').replace(',','.');
    }
    function formatMoney(value) {
        var parts=String(value).replace(/[^\d,]/g,'').split(',');
        var integer=parts[0].replace(/^0+(?=\d)/,'');
        return integer.replace(/\B(?=(\d{3})+(?!\d))/g,'.')+(parts.length>1 ? ','+parts.slice(1).join('') : '');
    }
    function validateMoney(input) {
        input.setCustomValidity(/^\d{1,12}(?:\.\d{1,2})?$/.test(rawMoney(input.value)) ? '' : 'Isi nominal maksimal 12 digit dan 2 angka desimal (contoh: 1.500.000,50).');
    }
    function calculate() {
        var down=form.find('[name="uang_muka"]')[0], discount=form.find('[name="diskon_uang_muka"]')[0];
        money.each(function () { validateMoney(this); });
        var downCents=Math.round(Number(rawMoney(down.value) || 0)*100), discountCents=Math.round(Number(rawMoney(discount.value) || 0)*100);
        if (discountCents>downCents) discount.setCustomValidity('Diskon tidak boleh melebihi uang muka.');
        var total=Number.isFinite(downCents-discountCents) ? Math.max(0,downCents-discountCents) : 0;
        form.find('[name="uang_muka_setelah_diskon"]').val(formatMoney((total/100).toFixed(2).replace('.',',')));
        validateMoney(form.find('[name="uang_muka_setelah_diskon"]')[0]);
        var utj=form.find('[name="utj"]')[0], utjCents=Math.round(Number(rawMoney(utj.value) || 0)*100);
        var utjTimes=Number(form.find('[name="utj_cicilan"]').val()) || 1, umTimes=Number(form.find('[name="um_cicilan"]').val()) || 1;
        function rupiah(cents) { return 'Rp '+formatMoney((cents/100).toFixed(2).replace('.',',')); }
        $('#release-cicilan-preview').text('UTJ: '+rupiah(utjCents/utjTimes)+' × '+utjTimes+' | UM: '+rupiah(Math.max(0,total-utjCents)/umTimes)+' × '+umTimes+' | BLL: '+rupiah(Number(rawMoney(form.find('[name="biaya_lain"]').val()) || 0)*100));
    }
    modal.on('show.bs.modal',function (event) {
        var lot=JSON.parse($(event.relatedTarget).attr('data-release'));
        form[0].reset();
        $('#lot-release-error').hide().text('');
        $('#lot-release-title').text('Release Lot '+lot.kode_lot);
        $('#release-lot-id').val(lot.id);
        form.find('input[name]').each(function () {
            if (this.name==='id' || this.name==='lot_token') return;
            if (this.type==='checkbox') { this.checked=Number(lot[this.name])===1; return; }
            this.value=lot[this.name] == null ? '' : lot[this.name];
        });
        money.each(function () { this.value=formatMoney(this.value.replace('.',',')); });
        calculate();
    });
    modal.on('hide.bs.modal',function (event) { if (busy) event.preventDefault(); });
    form.find('[name="utj_cicilan"], [name="um_cicilan"]').on('input',calculate);
    money.on('input',function () {
        var cursor=this.selectionStart;
        var before=this.value.slice(0,cursor).replace(/[^\d,]/g,'').length;
        this.value=formatMoney(this.value);
        var position=0, count=0;
        while (position<this.value.length && count<before) {
            if (/[\d,]/.test(this.value.charAt(position))) count++;
            position++;
        }
        this.setSelectionRange(position,position);
        calculate();
    });
    form.on('submit',function (event) {
        event.preventDefault();
        calculate();
        if (busy || !form[0].reportValidity()) return;
        busy=true;
        $('#lot-release-submit').prop('disabled',true).text('Menyimpan...');
        $('#lot-release-error').hide();
        var data=form.serializeArray();
        data.forEach(function (field) {
            if (money.filter('[name="'+field.name+'"]').length) field.value=rawMoney(field.value);
        });
        $.ajax({url:form.attr('action'),method:'POST',data:$.param(data),dataType:'json'})
            .done(function () { window.location.reload(); })
            .fail(function (xhr) {
                $('#lot-release-error').text(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Data release gagal disimpan. Muat ulang halaman atau coba kembali.').show();
            })
            .always(function () { busy=false; $('#lot-release-submit').prop('disabled',false).text('Simpan Release'); });
    });
}(jQuery));
