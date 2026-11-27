(function($){
  'use strict';
  var root=$('#notaris-page'),base=root.attr('data-url'),token=root.attr('data-token');
  var editor=$('#notaris-editor'),feedback=$('#notaris-feedback'),busy=false,deleteId=null;
  var canUpdate=root.attr('data-update')==='1',canDelete=root.attr('data-delete')==='1';
  function message(xhr){return xhr.responseJSON&&xhr.responseJSON.message?xhr.responseJSON.message:'Permintaan gagal. Periksa koneksi lalu coba kembali.';}
  function popup(text,kind){
    deleteId=null;feedback.removeClass('is-delete is-error');
    if(kind==='error')feedback.addClass('is-error');
    feedback.find('.feedback-icon i').attr('class',kind==='error'?'fa fa-exclamation':'fa fa-check');
    $('#notaris-feedback-title').text(kind==='error'?'Belum berhasil':'Berhasil!');
    $('#notaris-feedback-message').text(text);$('#notaris-cancel-delete').prop('hidden',true);
    $('#notaris-feedback-action').prop('disabled',false).text('Oke, mengerti');feedback.modal('show');
  }
  function editorBusy(value){
    busy=value;editor.find('button,input,textarea').prop('disabled',value);
    $('#notaris-save').html(value?'<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Menyimpan…':'<i class="fa fa-save" aria-hidden="true"></i> Simpan');
    $('#notaris-form').attr('aria-busy',String(value));
  }
  $.fn.dataTable.ext.errMode='none';
  var table=$('#notaris-table').DataTable({
    processing:true,serverSide:true,pageLength:25,lengthMenu:[10,25,50,100],searchDelay:350,order:[[1,'asc']],
    ajax:{url:base+'/data',error:function(xhr){$('#notaris-load-error').text(message(xhr)).prop('hidden',false);}},
    columns:[
      {data:null,orderable:false,searchable:false,render:function(data,type,row,meta){return meta.settings._iDisplayStart+meta.row+1;}},
      {data:'nama_notaris',render:$.fn.dataTable.render.text()},
      {data:'keterangan',render:$.fn.dataTable.render.text()},
      {data:null,orderable:false,searchable:false,render:function(){return (canUpdate?'<button type="button" class="btn btn-primary btn-sm edit-notaris"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button> ':'')+(canDelete?'<button type="button" class="btn btn-danger btn-sm delete-notaris"><i class="fa fa-trash" aria-hidden="true"></i> Hapus</button>':'');}}
    ],
    language:{processing:'Memuat data…',search:'Cari:',lengthMenu:'Tampilkan _MENU_ data',info:'Menampilkan _START_–_END_ dari _TOTAL_ data',infoEmpty:'Belum ada data',infoFiltered:'(dari _MAX_ data)',zeroRecords:'Data tidak ditemukan',emptyTable:'Belum ada notaris. Tambahkan notaris pertama Anda.',paginate:{first:'Pertama',last:'Terakhir',next:'Berikutnya',previous:'Sebelumnya'}}
  });
  $('#notaris-table').on('xhr.dt',function(event,settings,json){if(json)$('#notaris-load-error').prop('hidden',true);});
  function openEditor(row){
    if(busy)return;$('#notaris-form')[0].reset();$('#notaris-form-error').prop('hidden',true);
    $('#notaris-id').val(row?row.id:'');$('#notaris-name').val(row?row.nama_notaris:'');$('#notaris-description').val(row?row.keterangan:'');
    $('#notaris-editor-title').text(row?'Edit Notaris':'Tambah Notaris');editor.modal('show');
  }
  editor.on('shown.bs.modal',function(){$('#notaris-name').focus();}).on('hide.bs.modal',function(e){if(busy)e.preventDefault();});
  $('#add-notaris').on('click',function(){openEditor(null);});
  $('#notaris-table').on('click','.edit-notaris',function(){openEditor(table.row($(this).closest('tr')).data());});
  $('#notaris-form').on('submit',function(e){
    e.preventDefault();if(busy||!this.checkValidity())return;
    var data=$(this).serializeArray();data.push({name:'token',value:token});editorBusy(true);$('#notaris-form-error').prop('hidden',true);
    $.ajax({url:base+'/save',method:'POST',data:data,dataType:'json'}).done(function(result){
      editorBusy(false);editor.one('hidden.bs.modal',function(){popup(result.message);});editor.modal('hide');table.ajax.reload(null,!$('#notaris-id').val());
    }).fail(function(xhr){editorBusy(false);$('#notaris-form-error').text(message(xhr)).prop('hidden',false);});
  });
  $('#notaris-table').on('click','.delete-notaris',function(){
    if(busy)return;var row=table.row($(this).closest('tr')).data();deleteId=row.id;
    feedback.removeClass('is-error').addClass('is-delete');feedback.find('.feedback-icon i').attr('class','fa fa-trash');
    $('#notaris-feedback-title').text('Hapus notaris?');$('#notaris-feedback-message').text('Notaris “'+row.nama_notaris+'” akan dihapus.');
    $('#notaris-cancel-delete').prop('hidden',false);$('#notaris-feedback-action').prop('disabled',false).text('Ya, hapus');feedback.modal('show');
  });
  feedback.on('hide.bs.modal',function(e){if(busy)e.preventDefault();});
  $('#notaris-feedback-action').on('click',function(){
    if(busy)return;if(!deleteId){feedback.modal('hide');return;}
    busy=true;$('#notaris-feedback-action,#notaris-cancel-delete').prop('disabled',true);$(this).html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Menghapus…');
    $.ajax({url:base+'/delete',method:'POST',data:{id:deleteId,token:token},dataType:'json'}).done(function(result){
      var info=table.page.info();if(info.end-info.start===1&&info.page>0)table.page(info.page-1);table.ajax.reload(null,false);popup(result.message);
    }).fail(function(xhr){popup(message(xhr),'error');}).always(function(){busy=false;$('#notaris-feedback-action,#notaris-cancel-delete').prop('disabled',false);});
  });
}(jQuery));
