(function () {
    'use strict';
    var form=document.getElementById('retensi-request-form');if(!form)return;
    var project=document.getElementById('request-project'),category=document.getElementById('request-category');
    var result=document.getElementById('request-results'),notice=document.getElementById('request-reprocess');
    var initialProject=project.value,initialCategory=category.value;
    var accounts=document.getElementById('request-accounts'),options=Array.prototype.slice.call(accounts.children);
    function filterAccounts(){accounts.innerHTML='';options.forEach(function(option){if(option.dataset.projid===project.value)accounts.appendChild(option);});}
    function invalidate(){var changed=project.value!==initialProject || category.value!==initialCategory;if(result){result.hidden=changed;result.querySelectorAll('input,button').forEach(function(input){input.disabled=changed;});}notice.hidden=!changed;}
    project.addEventListener('change',function(){document.getElementById('request-account').value='';filterAccounts();invalidate();});
    category.addEventListener('change',invalidate);filterAccounts();
    var checks=Array.prototype.slice.call(form.querySelectorAll('.request-participant')),all=document.getElementById('request-select-all');
    function update(){var chosen=checks.filter(function(input){return input.checked;}),total=0;chosen.forEach(function(input){total+=Number(input.dataset.amount)||0;});
        if(all){all.checked=checks.length>0 && chosen.length===checks.length;all.indeterminate=chosen.length>0 && chosen.length<checks.length;all.disabled=checks.length===0;}
        var count=document.getElementById('request-count'),amount=document.getElementById('request-total');if(count)count.textContent=chosen.length;if(amount)amount.textContent='Rp '+total.toLocaleString('id-ID');
    }
    checks.forEach(function(input){input.addEventListener('change',update);});
    if(all)all.addEventListener('change',function(){checks.forEach(function(input){input.checked=all.checked;});update();});
    form.addEventListener('submit',function(event){if(event.submitter && event.submitter.value==='save' && !checks.some(function(input){return input.checked && !input.disabled;})){event.preventDefault();window.alert('Centang minimal satu konsumen.');}});
    update();
}());
