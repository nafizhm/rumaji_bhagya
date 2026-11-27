(function(){
    'use strict';
    var form=document.getElementById('retensi-payment-form');if(!form)return;
    var checks=Array.prototype.slice.call(form.querySelectorAll('.retensi-payment-check:not(:disabled)'));
    var all=document.getElementById('retensi-payment-all'),save=document.getElementById('retensi-payment-save');
    function update(){var chosen=checks.filter(function(input){return input.checked;}),sum=0;
        chosen.forEach(function(input){sum+=Number(input.dataset.amount)||0;});
        document.getElementById('retensi-payment-count').textContent=chosen.length;
        document.getElementById('retensi-payment-total').textContent='Rp '+sum.toLocaleString('id-ID');
        if(all){all.checked=checks.length>0&&chosen.length===checks.length;all.indeterminate=chosen.length>0&&chosen.length<checks.length;all.disabled=checks.length===0;}
    }
    checks.forEach(function(input){input.addEventListener('change',update);});
    if(all)all.addEventListener('change',function(){checks.forEach(function(input){input.checked=all.checked;});update();});
    form.addEventListener('submit',function(event){if(!checks.some(function(input){return input.checked;})){event.preventDefault();window.alert('Centang minimal satu konsumen yang sudah dibayar.');return;}if(save)save.disabled=true;});
    update();
}());
