(function () {
    'use strict';
    var form=document.getElementById('pengajuan-peserta-form');if (!form) return;
    var editable=form.dataset.editable==='1',rows=Array.prototype.slice.call(form.querySelectorAll('tbody tr'));
    var canAttend=form.dataset.attendanceEditable==='1';
    var all=document.getElementById('pengajuan-select-all');
    function update() {
        var count=0,present=0;
        rows.forEach(function (row) {
            var selected=row.querySelector('.peserta-select').checked,hadir=row.querySelector('.peserta-hadir');
            if (hadir) hadir.disabled=!canAttend || !selected || hadir.dataset.saving==='1';
            if (selected) { count++;if (hadir && hadir.checked) present++; }
        });
        document.getElementById('pengajuan-jumlah').textContent=count;var presentTotal=document.getElementById('pengajuan-hadir');if(presentTotal)presentTotal.textContent=present;
        if (all) {
            var visible=rows.filter(function (row) { return !row.hidden; }),checked=visible.filter(function (row) { return row.querySelector('.peserta-select').checked; }).length;
            all.checked=visible.length>0 && checked===visible.length;all.indeterminate=checked>0 && checked<visible.length;
        }
    }
    rows.forEach(function (row) {
        row.querySelector('.peserta-select').addEventListener('change',update);
        var attendance=row.querySelector('.peserta-hadir');
        if(attendance) attendance.addEventListener('change',function () {
            if(!canAttend || attendance.dataset.saving==='1')return;
            var checked=attendance.checked,status=document.getElementById('pengajuan-attendance-status');
            attendance.dataset.saving='1';update();status.textContent='Menyimpan kehadiran...';status.className='text-muted';
            var payload=new FormData();
            ['id','token'].forEach(function(name){payload.set(name,form.elements[name].value);});
            payload.set('projid',row.dataset.projid);payload.set('sprno',row.dataset.sprno);payload.set('hadir',checked ? '1' : '0');
            fetch(form.dataset.attendanceUrl,{method:'POST',body:payload}).then(function(response){
                return response.json().then(function(result){if(!response.ok)throw new Error(result.message||'Kehadiran gagal disimpan.');return result;});
            }).then(function(result){status.textContent=result.message;status.className='text-success';}).catch(function(error){
                attendance.checked=!checked;status.textContent=error.message||'Kehadiran gagal disimpan. Silakan coba lagi.';status.className='text-danger';
            }).finally(function(){attendance.dataset.saving='0';update();});
        });
    });
    if (all) all.addEventListener('change',function () { rows.forEach(function (row) { if (!row.hidden) row.querySelector('.peserta-select').checked=all.checked; });update(); });
    function filter() {
        var search=document.getElementById('pengajuan-search').value.toLowerCase().trim(),project=document.getElementById('pengajuan-project').value;
        rows.forEach(function (row) { row.hidden=(project && row.dataset.projid!==project) || row.textContent.toLowerCase().indexOf(search)===-1; });update();
    }
    document.getElementById('pengajuan-search').addEventListener('input',filter);document.getElementById('pengajuan-project').addEventListener('change',filter);
    form.addEventListener('submit',function (event) {
        update();if (!editable || !form.reportValidity()) { event.preventDefault();return; }
        var participants=rows.filter(function (row) { return row.querySelector('.peserta-select').checked; }).map(function (row) {
            return {projid:Number(row.dataset.projid),sprno:Number(row.dataset.sprno),hadir:false};
        });
        document.getElementById('pengajuan-peserta-json').value=JSON.stringify(participants);
    });
    update();
}());
