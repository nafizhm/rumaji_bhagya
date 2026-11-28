(function($){
 'use strict';
 var modal=$('#pencairan-modal'),form=$('#pencairan-form'),busy=false,changed=false,loading=false;
 var pengajuan=Number(modal.attr('data-pengajuan-id')||0);
 var base=modal.attr('data-url'),project,spr,stage=0,available=0;
 function rupiah(value){return 'Rp '+Number(value||0).toLocaleString('id-ID',{maximumFractionDigits:0});}
 function digits(value){return String(value==null?'':value).replace(/[^0-9]/g,'').slice(0,13);}
 function amount(value){return Number(digits(value))||0;}
 function formatInput(input){
  var cursor=input.selectionStart||0,before=input.value.slice(0,cursor).replace(/[^0-9]/g,'').length;
  var raw=digits(input.value);input.value=raw?Number(raw).toLocaleString('id-ID'):'';
  var position=0,count=0;while(position<input.value.length&&count<before){if(/[0-9]/.test(input.value[position]))count++;position++;}
  if(document.activeElement===input)input.setSelectionRange(position,position);
 }
 function error(xhr){return xhr.responseJSON&&xhr.responseJSON.message?xhr.responseJSON.message:'Data gagal diproses. Periksa koneksi lalu coba kembali.';}
 function calculate(){var entered=0;$('#pencairan-categories input').each(function(){var value=amount(this.value);if(this.dataset.sbum!=='1')entered+=value;this.setCustomValidity(stage&&value>Number(this.dataset.balance)?'Pencairan tidak boleh melebihi sisa retensi kategori ini.':'');});var retensi=stage?available-entered:entered;var net=stage?entered:amount($('#pencairan-plafon').val())-amount($('#pencairan-kpp').val())-retensi;$('#pencairan-retensi-total').text(rupiah(retensi));$('#pencairan-net').text(rupiah(net)).toggleClass('text-danger',net<0||retensi<0);return net;}
 function load(){
  loading=true;$('#pencairan-loading').show();form.prop('hidden',true);$('#pencairan-error').prop('hidden',true);
  return $.getJSON(base+'/data/'+project+'/'+spr,pengajuan?{pengajuan_id:pengajuan}:{}).done(function(data){
   form[0].reset();Object.keys({projid:1,sprno:1,token:1,request_key:1,base_id:1}).forEach(function(key){form.find('[name="'+key+'"]').val(key==='projid'?project:key==='sprno'?spr:data[key]);});
   $('#pencairan-customer').text('SPR '+spr+' · '+data.spr.blok+'/'+data.spr.no+' · '+data.spr.nama);
   stage=Number(data.base_id);available=Number(data.sisa);$('#pencairan-plafon').val(Math.round(Number(data.plafon)||0)).prop('readonly',!!stage);$('#pencairan-kpp').val(Number(data.kkp)||0).prop('readonly',true);
   $('#pencairan-stage-note').text(stage?'Pencairan lanjutan. Saldo tersedia: '+rupiah(available)+'. Isi nominal yang ingin dicairkan di sebelah kanan, maksimal sesuai saldo di kiri. Plafon dan KMK awal tidak dipotong ulang.':'Pencairan awal: plafon dikurangi KMK dan retensi.');
   var container=$('#pencairan-categories').empty();
   $('#pencairan-retensi-title').text(stage?'Pencairan Retensi':'Retensi yang Ditahan');
   $('#pencairan-plafon,#pencairan-kpp').closest('.form-group').prop('hidden',!!stage);
   data.kategori.forEach(function(category){
    var id='retensi-value-'+category.id,group=$('<div class="col-xs-12 form-group pencairan-category-row">');
    var input=$('<input type="text" inputmode="numeric" autocomplete="off" maxlength="17" class="form-control pencairan-amount" required>').attr({id:id,name:'retensi['+category.id+']'}).val(0);
    var isSbum=String(category.nama_retensi).trim().toUpperCase()==='SBUM';
    input.attr('data-sbum',isSbum?'1':'0');
    if(stage){
     input.attr('data-balance',category.sisa);
     var balance=$('<div class="retensi-balance">');$('<label>').attr('for',id).text(category.nama_retensi+' (Rp)').appendTo(balance);$('<span class="text-muted">').text('Sisa retensi: '+rupiah(category.sisa)).appendTo(balance);
     var entry=$('<div class="retensi-entry">');entry.append(input);group.append(balance,entry);
    }else{$('<label>').attr('for',id).text(category.nama_retensi+' (Rp)').appendTo(group);group.append(input);}
    container.append(group);
    if(isSbum)group.append($('<small class="text-muted">').text('SBUM dicatat tanpa memotong jumlah pencairan.'));
   });
   if(!data.kategori.length)container.append($('<p class="col-xs-12 text-muted">').text('Belum ada kategori retensi. Tambahkan kategori di Master Data bila diperlukan.'));
   var history=$('#pencairan-history').empty();
   data.histori.forEach(function(item){var row=$('<tr>');row.append($('<td>').text(item.tanggal_pembayaran||item.created_at),$('<td>').text(rupiah(item.total_retensi)));var details=$('<td>');var remaining=item.retensi.filter(function(line){return Number(line.nilai)>0;});remaining.forEach(function(line){var detail=$('<div class="retensi-history-line">');detail.append($('<span>').text(line.nama_retensi),$('<span class="retensi-history-amount">').text(rupiah(line.nilai)));details.append(detail);});if(!remaining.length)details.text('Tidak ada retensi tersisa');row.append(details,$('<td>').text(rupiah(item.jumlah)));history.append(row);});
   if(!data.histori.length)history.append('<tr><td colspan="4" class="text-center">Belum ada histori pencairan.</td></tr>');
   var complete=!!stage&&available<=0;$('#pencairan-fields').prop('disabled',complete);$('#pencairan-save').prop('hidden',complete);if(complete)$('#pencairan-stage-note').text('Pencairan sudah lengkap. Sisa pencairan Rp 0.');calculate();
   form.find('.pencairan-amount').each(function(){formatInput(this);});
   form.prop('hidden',false);
  }).fail(function(xhr){$('#pencairan-error').text(error(xhr)).prop('hidden',false);$('#pencairan-history').empty();}).always(function(){loading=false;$('#pencairan-loading').hide();});
 }
 $(document).on('click','.open-pencairan',function(){if(busy||loading)return;project=$(this).attr('data-projid');spr=$(this).attr('data-sprno');$('#pencairan-success').prop('hidden',true);modal.modal('show');load();});
 modal.on('hide.bs.modal',function(e){if(busy||loading)e.preventDefault();}).on('hidden.bs.modal',function(){if(changed)window.location.reload();});
 form.on('beforeinput','.pencairan-amount',function(e){var original=e.originalEvent;if(original&&original.inputType==='insertText'&&original.data&&/[^0-9]/.test(original.data))e.preventDefault();});
 form.on('input','.pencairan-amount',function(){formatInput(this);calculate();});
 form.on('submit',function(e){e.preventDefault();calculate();if(busy||!this.reportValidity())return;if(calculate()<=0){$('#pencairan-error').text('Jumlah pencairan harus lebih dari nol dan tidak boleh melebihi saldo tersedia.').prop('hidden',false);return;}
  var data=form.serializeArray();if(pengajuan)data.push({name:'pengajuan_id',value:String(pengajuan)});data.forEach(function(field){if(field.name==='plafon'||field.name==='kpp'||field.name.indexOf('retensi[')===0)field.value=digits(field.value);if(stage&&field.name.indexOf('retensi[')===0){var input=form.find('[name="'+field.name+'"]')[0];field.value=String(Number(input.dataset.balance)-amount(field.value));}});busy=true;$('#pencairan-fields').prop('disabled',true);$('#pencairan-close,.modal-header .close',modal).prop('disabled',true);$('#pencairan-save').html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Menyimpan…');$('#pencairan-error').prop('hidden',true);
  $.ajax({url:base+'/save',method:'POST',data:data,dataType:'json'}).done(function(result){changed=true;$('#pencairan-success').text(result.message).prop('hidden',false);load();}).fail(function(xhr){$('#pencairan-fields').prop('disabled',false);$('#pencairan-error').text(error(xhr)).prop('hidden',false);}).always(function(){busy=false;$('#pencairan-close,.modal-header .close',modal).prop('disabled',false);$('#pencairan-save').html('<i class="fa fa-save" aria-hidden="true"></i> Simpan Pencairan');});
 });
}(jQuery));
