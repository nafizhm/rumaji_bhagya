(function($){
  'use strict';
  var root=$('#retensi-page'),base=root.attr('data-url'),token=root.attr('data-token');
  var editor=$('#retensi-editor'),feedback=$('#retensi-feedback'),busy=false,deleteId=null;
  var canUpdate=root.attr('data-update')==='1',canDelete=root.attr('data-delete')==='1';
  function message(xhr){return xhr.responseJSON&&xhr.responseJSON.message?xhr.responseJSON.message:'Permintaan gagal. Periksa koneksi lalu coba kembali.';}
  function popup(text,kind){
    deleteId=null;feedback.removeClass('is-delete is-error');
    if(kind==='error')feedback.addClass('is-error');
    feedback.find('.feedback-icon i').attr('class',kind==='error'?'fa fa-exclamation':'fa fa-check');
    $('#retensi-feedback-title').text(kind==='error'?'Belum berhasil':'Berhasil!');
    $('#retensi-feedback-message').text(text);$('#retensi-cancel-delete').prop('hidden',true);
    $('#retensi-feedback-action').prop('disabled',false).text('Oke, mengerti');feedback.modal('show');
  }
  function editorBusy(value){
    busy=value;editor.find('button,input,textarea').prop('disabled',value);
    $('#retensi-save').html(value?'<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Menyimpan…':'<i class="fa fa-save" aria-hidden="true"></i> Simpan');
    $('#retensi-form').attr('aria-busy',String(value));
  }
  $.fn.dataTable.ext.errMode='none';
  var table=$('#retensi-table').DataTable({
    processing:true,serverSide:true,pageLength:25,lengthMenu:[10,25,50,100],searchDelay:350,order:[[1,'asc']],
    ajax:{url:base+'/data',error:function(xhr){$('#retensi-load-error').text(message(xhr)).prop('hidden',false);}},
    columns:[
      {data:null,orderable:false,searchable:false,render:function(data,type,row,meta){return meta.settings._iDisplayStart+meta.row+1;}},
      {data:'nama_retensi',render:$.fn.dataTable.render.text()},
      {data:'keterangan',render:$.fn.dataTable.render.text()},
      {data:null,orderable:false,searchable:false,render:function(){return (canUpdate?'<button type="button" class="btn btn-primary btn-sm edit-retensi"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button> ':'')+(canDelete?'<button type="button" class="btn btn-danger btn-sm delete-retensi"><i class="fa fa-trash" aria-hidden="true"></i> Hapus</button>':'');}}
    ],
    language:{processing:'Memuat data…',search:'Cari:',lengthMenu:'Tampilkan _MENU_ data',info:'Menampilkan _START_–_END_ dari _TOTAL_ data',infoEmpty:'Belum ada data',infoFiltered:'(dari _MAX_ data)',zeroRecords:'Data tidak ditemukan',emptyTable:'Belum ada kategori retensi. Tambahkan kategori pertama Anda.',paginate:{first:'Pertama',last:'Terakhir',next:'Berikutnya',previous:'Sebelumnya'}}
  });
  $('#retensi-table').on('xhr.dt',function(event,settings,json){if(json)$('#retensi-load-error').prop('hidden',true);});
  function openEditor(row){
    if(busy)return;$('#retensi-form')[0].reset();$('#retensi-form-error').prop('hidden',true);
    $('#retensi-id').val(row?row.id:'');$('#retensi-name').val(row?row.nama_retensi:'');$('#retensi-description').val(row?row.keterangan:'');
    $('#retensi-editor-title').text(row?'Edit Kategori Retensi':'Tambah Kategori Retensi');editor.modal('show');
  }
  editor.on('shown.bs.modal',function(){$('#retensi-name').focus();}).on('hide.bs.modal',function(e){if(busy)e.preventDefault();});
  $('#add-retensi').on('click',function(){openEditor(null);});
  $('#retensi-table').on('click','.edit-retensi',function(){openEditor(table.row($(this).closest('tr')).data());});
  $('#retensi-form').on('submit',function(e){
    e.preventDefault();if(busy||!this.checkValidity())return;
    var data=$(this).serializeArray();data.push({name:'token',value:token});editorBusy(true);$('#retensi-form-error').prop('hidden',true);
    $.ajax({url:base+'/save',method:'POST',data:data,dataType:'json'}).done(function(result){
      editorBusy(false);editor.one('hidden.bs.modal',function(){popup(result.message);});editor.modal('hide');table.ajax.reload(null,!$('#retensi-id').val());
    }).fail(function(xhr){editorBusy(false);$('#retensi-form-error').text(message(xhr)).prop('hidden',false);});
  });
  $('#retensi-table').on('click','.delete-retensi',function(){
    if(busy)return;var row=table.row($(this).closest('tr')).data();deleteId=row.id;
    feedback.removeClass('is-error').addClass('is-delete');feedback.find('.feedback-icon i').attr('class','fa fa-trash');
    $('#retensi-feedback-title').text('Hapus kategori?');$('#retensi-feedback-message').text('Kategori “'+row.nama_retensi+'” akan dihapus.');
    $('#retensi-cancel-delete').prop('hidden',false);$('#retensi-feedback-action').prop('disabled',false).text('Ya, hapus');feedback.modal('show');
  });
  feedback.on('hide.bs.modal',function(e){if(busy)e.preventDefault();});
  $('#retensi-feedback-action').on('click',function(){
    if(busy)return;if(!deleteId){feedback.modal('hide');return;}
    busy=true;$('#retensi-feedback-action,#retensi-cancel-delete').prop('disabled',true);$(this).html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Menghapus…');
    $.ajax({url:base+'/delete',method:'POST',data:{id:deleteId,token:token},dataType:'json'}).done(function(result){
      var info=table.page.info();if(info.end-info.start===1&&info.page>0)table.page(info.page-1);table.ajax.reload(null,false);popup(result.message);
    }).fail(function(xhr){popup(message(xhr),'error');}).always(function(){busy=false;$('#retensi-feedback-action,#retensi-cancel-delete').prop('disabled',false);});
  });
}(jQuery));
