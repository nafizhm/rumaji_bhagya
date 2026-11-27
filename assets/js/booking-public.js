(function(){
 'use strict';var root=document.querySelector('.booking-shell'),base=root.dataset.url,form=document.getElementById('booking-form'),project=document.getElementById('booking-project'),unit=document.getElementById('booking-unit'),busy=false,version=0,priceVersion=0;
 function money(value){return 'Rp '+Number(value||0).toLocaleString('id-ID');}
 function showError(message){var box=document.getElementById('booking-error');box.textContent=message;box.hidden=false;box.scrollIntoView({behavior:'smooth',block:'center'});}
 function resetPrice(){document.getElementById('booking-price').textContent=money(0);document.getElementById('booking-cost').textContent=money(0);document.getElementById('booking-total').textContent=money(0);document.getElementById('booking-blok').value='';document.getElementById('booking-no').value='';}
 function json(url,options){return fetch(url,options).then(function(response){return response.json().then(function(data){if(!response.ok)throw new Error(data.message||'Permintaan gagal diproses.');return data;});});}
 var lot=document.getElementById('booking-lot'),lotVersion=0;
 function resetUnits(message){++priceVersion;resetPrice();unit.replaceChildren(new Option(message,''));unit.disabled=true;}
 project.addEventListener('change',function(){
  var current=++version;++lotVersion;resetUnits('Pilih Lot Release terlebih dahulu');
  lot.replaceChildren(new Option(project.value?'Memuat Lot Release...':'Pilih perumahan terlebih dahulu',''));lot.disabled=true;
  if(!project.value)return;
  json(base+'/lots/'+encodeURIComponent(project.value)).then(function(result){
   if(current!==version)return;
   lot.replaceChildren(new Option(result.data.length?'Pilih Lot Release':'Belum ada Lot Release dengan unit ready',''));
   result.data.forEach(function(item){lot.add(new Option(item.kode_lot+' ('+item.jumlah_unit+' unit ready)',item.id));});
   lot.disabled=!result.data.length;
   if(!result.data.length)resetUnits('Belum ada unit yang bisa dijual');
  }).catch(function(error){if(current===version)showError(error.message);});
 });
 lot.addEventListener('change',function(){
  var current=++lotVersion,projectVersion=version;++priceVersion;resetUnits('Pilih Lot Release terlebih dahulu');
  if(!lot.value||!project.value)return;
  unit.replaceChildren(new Option('Memuat kavling...',''));
  json(base+'/units/'+encodeURIComponent(project.value)+'?'+new URLSearchParams({lot_release_id:lot.value})).then(function(result){
   if(current!==lotVersion||projectVersion!==version)return;
   unit.replaceChildren(new Option(result.data.length?'Pilih blok / kavling':'Belum ada unit ready pada Lot Release ini',''));
   result.data.forEach(function(item){var option=new Option(item.blok+'/'+item.no+' - Lot '+item.lot,JSON.stringify([item.blok,item.no]));option.dataset.jenis=item.jenis||'Subsidi';unit.add(option);});
   unit.disabled=!result.data.length;
  }).catch(function(error){if(current===lotVersion&&projectVersion===version)showError(error.message);});
 });
 unit.addEventListener('change',function(){var current=++priceVersion;resetPrice();if(!unit.value)return;var choice=JSON.parse(unit.value);document.getElementById('booking-blok').value=choice[0];document.getElementById('booking-no').value=choice[1];document.getElementById('jenis_perumahan').value=unit.selectedOptions[0].dataset.jenis==='Komersial'?'Komersil':'Subsidi';json(base+'/price?'+new URLSearchParams({projid:project.value,lot_release_id:lot.value,blok:choice[0],no:choice[1]})).then(function(price){if(current!==priceVersion)return;document.getElementById('booking-price').textContent=money(price.harga);document.getElementById('booking-cost').textContent=money(price.biaya_titipan);document.getElementById('booking-total').textContent=money(price.total);if(!price.total)showError('Harga unit belum ditentukan. Hubungi marketing.');}).catch(function(error){if(current===priceVersion)showError(error.message);});});
 document.getElementById('booking_fee').addEventListener('input',function(){var value=this.value.replace(/[^0-9]/g,'').slice(0,13);this.value=value?Number(value).toLocaleString('id-ID'):'';});
 ['nik','nik_p'].forEach(function(id){document.getElementById(id).addEventListener('input',function(){this.value=this.value.replace(/[^0-9]/g,'').slice(0,16);});});
 form.addEventListener('submit',function(event){event.preventDefault();if(busy||!form.reportValidity())return;if(!document.getElementById('booking-blok').value){showError('Pilih unit yang tersedia terlebih dahulu.');return;}var invalid=false;form.querySelectorAll('input[type=file]').forEach(function(input){if(input.files[0]&&input.files[0].size>10*1024*1024)invalid=true;});if(invalid){showError('Ukuran setiap berkas maksimal 10 MB.');return;}var data=new FormData(form);busy=true;var button=document.getElementById('booking-submit');button.disabled=true;button.querySelector('.booking-spinner').hidden=false;document.getElementById('booking-submit-label').textContent='Mengirim…';document.getElementById('booking-error').hidden=true;
  json(form.action,{method:'POST',body:data}).then(function(result){document.getElementById('booking-success-message').textContent=result.message;document.getElementById('booking-registration').textContent=result.no_registrasi;document.getElementById('booking-success').showModal();form.querySelectorAll('input,select,textarea,button').forEach(function(input){input.disabled=true;});}).catch(function(error){showError(error.message||'Koneksi terputus. Silakan coba kembali.');button.disabled=false;}).finally(function(){busy=false;button.querySelector('.booking-spinner').hidden=true;document.getElementById('booking-submit-label').textContent='Kirim Booking';});
 });
}());
