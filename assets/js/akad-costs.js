(function () {
    'use strict';
    var form=document.getElementById('akad-cost-form');if (!form) return;
    var editable=form.dataset.editable==='1',busy=false,rows=Array.prototype.slice.call(form.querySelectorAll('tbody tr'));
    var money=Array.prototype.slice.call(form.querySelectorAll('.cost-money')),error=document.getElementById('akad-cost-error'),button=document.getElementById('akad-cost-save'),finishButton=document.getElementById('akad-cost-finish');
    var fillButtons=[];
    function updateFillButtons() {
        fillButtons.forEach(function (item) {
            item.button.disabled=busy || item.input.disabled || !item.input.value.trim() || (item.input.classList.contains('cost-money') && !valid(raw(item.input.value)));
        });
    }
    function raw(value) { return value.replace(/\./g,'').replace(',','.'); }
    function format(value) {
        var parts=value.replace(/[^\d,]/g,'').split(',');
        return parts[0].replace(/^0+(?=\d)/,'').replace(/\B(?=(\d{3})+(?!\d))/g,'.')+(parts.length>1 ? ','+parts.slice(1).join('') : '');
    }
    function valid(value) { return value==='' || /^\d{1,12}(?:\.\d{1,2})?$/.test(value); }
    function cents(value) {
        if (!value || !valid(value)) return 0n;
        var parts=value.split('.');return BigInt(parts[0])*100n+BigInt((parts[1]||'').padEnd(2,'0'));
    }
    function formatTotal(value) {
        var whole=(value/100n).toString().replace(/\B(?=(\d{3})+(?!\d))/g,'.'),fraction=(value%100n).toString().padStart(2,'0');
        return whole+(fraction==='00' ? '' : ','+fraction);
    }
    function totals() {
        rows.forEach(function (row) {
            if (form.dataset.closed==='1') return;
            var output=row.querySelector('[data-field="pph"]');if (!output) return;
            var rate=BigInt(row.dataset.pphRate||0),price=row.querySelector('[data-field="harga_jual_rumah"]');
            var base=price ? cents(raw(price.value)) : 0n;
            output.value=rate ? formatTotal((base*rate+500n)/1000n) : '';
            var total=row.querySelector('[data-field="biaya_akad"]'),sum=0n;
            ['bn_roya_validasi','ppjb','ajb','bphtb','pph','ppn','biaya_akad_btn'].forEach(function (field) {
                var input=row.querySelector('[data-field="'+field+'"]');if(input)sum+=cents(raw(input.value));
            });
            if (total) total.value=formatTotal(sum);
            var totalTax=row.querySelector('[data-field="total_pph"]'),notary=0n;
            ['bn_roya_validasi','ppjb','ajb'].forEach(function (field) {
                var input=row.querySelector('[data-field="'+field+'"]');if(input)notary+=cents(raw(input.value));
            });
            if (totalTax) totalTax.value=formatTotal(((notary+1950n)/3900n)*100n);
        });
        var sums={};
        money.forEach(function (input) {
            var value=raw(input.value);input.setCustomValidity(valid(value) ? '' : 'Nominal maksimal 12 digit dan 2 angka desimal, contoh 2.800.000,50.');
            sums[input.dataset.field]=(sums[input.dataset.field]||0n)+cents(value);
        });
        form.querySelectorAll('[data-total-field]').forEach(function (cell) { cell.textContent=formatTotal(sums[cell.dataset.totalField]||0n); });
        updateFillButtons();
    }
    money.forEach(function (input) {
        input.addEventListener('input',function () {
            var before=this.value.slice(0,this.selectionStart).replace(/[^\d,]/g,'').length;
            this.value=format(this.value);var position=0,count=0;
            while (position<this.value.length && count<before) { if (/[\d,]/.test(this.value.charAt(position))) count++;position++; }
            this.setSelectionRange(position,position);totals();
        });
    });
    if (editable && rows.length>1) form.querySelectorAll('thead [data-fill-field]').forEach(function (header) {
        var field=header.dataset.fillField,input=rows[0].querySelector('[data-field="'+field+'"]');
        if (!input || input.readOnly) return;
        var fill=document.createElement('button'),label=header.dataset.fillLabel;
        fill.type='button';fill.className='cost-fill-down';
        fill.innerHTML='<svg width="14" height="14" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="7" y="7" width="10" height="10" rx="1.5"/><path d="M12 7V3H3v9h4"/></svg>';
        fill.title='Salin '+label+' dari baris pertama ke semua baris di bawah';
        fill.setAttribute('aria-label',fill.title);
        fill.addEventListener('click',function () {
            if (busy || fill.disabled || !input.reportValidity()) return;
            var count=0;
            rows.slice(1).forEach(function (next) {
                var target=next.querySelector('[data-field="'+field+'"]');
                if (target && !target.disabled) { target.value=input.value;count++; }
            });
            totals();
            document.getElementById('cost-copy-status').textContent=label+' disalin ke '+count+' baris di bawah. Klik Simpan Biaya Akad untuk menyimpan.';
        });
        header.appendChild(fill);fillButtons.push({input:input,button:fill});
        if (!input.classList.contains('cost-money')) input.addEventListener('input',updateFillButtons);
    });
    form.addEventListener('submit',function (event) {
        event.preventDefault();totals();if (busy || !editable || !form.reportValidity()) return;
        var data=rows.map(function (row) {
            var item={projid:Number(row.dataset.projid),sprno:Number(row.dataset.sprno)};
            row.querySelectorAll('[data-field]').forEach(function (input) { item[input.dataset.field]=input.classList.contains('cost-money') ? raw(input.value) : input.value; });return item;
        });
        var finishing=event.submitter===finishButton;
        var payload=new FormData(form);payload.set('costs_json',JSON.stringify(data));payload.set('finish',finishing ? '1' : '0');
        busy=true;error.hidden=true;if(button){button.disabled=true;button.textContent='Menyimpan...';}if(finishButton){finishButton.disabled=true;}
        updateFillButtons();
        fetch(form.action,{method:'POST',body:payload}).then(function (response) {
            return response.json().then(function (result) { if(!response.ok)throw new Error(result.message||'Biaya akad gagal disimpan.');return result; });
        }).then(function () { if(finishing) window.location.reload();else window.location.href=form.dataset.returnUrl; }).catch(function (failure) {
            error.textContent=failure.message||'Biaya akad gagal disimpan. Silakan coba kembali.';error.hidden=false;error.scrollIntoView({behavior:'smooth',block:'center'});
        }).finally(function () { busy=false;if(button){button.disabled=false;button.textContent='Simpan Biaya Akad';}if(finishButton){finishButton.disabled=false;}updateFillButtons(); });
    });
    totals();
}());
