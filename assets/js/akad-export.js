(function($){
 'use strict';
 var element=$('#akad-table');
 if(!element.length)return;
 var title=element.attr('data-title'),project=element.attr('data-projid');
 function escape(value){return $('<div>').text(value==null?'':String(value)).html();}
 function money(data,type){if(type==='export')return data==null?'':Number(data);return data==null?'Belum tersedia':'Rp '+Number(data).toLocaleString('id-ID');}
 var options={columns:[0,1,2,3,4,5,6,7,8],orthogonal:'export',modifier:{page:'current'}};
 function caption(){var info=table.page.info();return 'Halaman '+(info.page+1)+' dari '+Math.max(1,info.pages)+' | Filter: '+$('#akad-status option:selected').text();}
 var table=element.DataTable({serverSide:true,processing:true,pageLength:10,lengthMenu:[10,25,50,100],searchDelay:400,order:[[2,'asc']],dom:'lfrtip',
  ajax:{url:element.attr('data-url'),data:function(data){data.status_pencairan=$('#akad-status').val();}},
  columns:[
   {data:'sprno',render:function(data,type){if(type==='export')return data||'';return data==null?'Belum tersedia':String(data).padStart(4,'0');}},
   {data:'lot',render:function(data,type){return type==='display'?escape(data):data;}},
   {data:null,render:function(data,type,row){var value=row.blok+'/'+row.no;return type==='display'?escape(value):value;}},
   {data:'nama',defaultContent:'',render:function(data,type){return type==='display'?escape(data||'Belum tersedia'):(data||'');}},
   {data:'plafon',render:money,className:'text-right',orderable:false},
   {data:'potongan_kpp',render:money,className:'text-right',orderable:false},
   {data:'jumlah_pencairan',render:money,className:'text-right',orderable:false},
   {data:'total_retensi',render:money,className:'text-right',orderable:false},
   {data:'sisa',orderable:false,className:'text-right',render:function(data,type){return type==='display'?'<strong class="'+(Number(data)>0?'text-danger':'')+'">'+money(data,type)+'</strong>':Number(data);}},
   {data:null,orderable:false,searchable:false,render:function(data,type,row){if(type!=='display')return '';var html='<a class="btn btn-primary btn-sm" href="'+escape(row.detail_url)+'">Detail</a>';if(row.sprno!=null)html+=' <button type="button" class="btn btn-success btn-sm open-pencairan" data-projid="'+escape(project)+'" data-sprno="'+escape(row.sprno)+'">Pencairan</button>';return html;}}
  ],
  language:{processing:'Memuat data?',search:'Cari:',lengthMenu:'Tampilkan _MENU_ data',info:'Menampilkan _START_?_END_ dari _TOTAL_ data',infoEmpty:'Belum ada data',infoFiltered:'(dari _MAX_ unit)',emptyTable:'Tidak ada data akad sesuai filter.',zeroRecords:'Tidak ada data sesuai pencarian.',paginate:{next:'Berikutnya',previous:'Sebelumnya'}},
  drawCallback:function(settings){$('#akad-total').text(Number(settings.json?settings.json.recordsFiltered:0).toLocaleString('id-ID'));},
  buttons:[
   {extend:'pdfHtml5',text:'<i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF (halaman ini)',title:title,messageTop:caption,filename:'Akad-'+new Date().toISOString().slice(0,10),orientation:'landscape',pageSize:'A4',exportOptions:options,
    customize:function(doc){doc.defaultStyle.fontSize=8;doc.styles.tableHeader.fontSize=8;doc.content.forEach(function(item){if(item.table)item.table.widths=['8%','6%','8%','18%','12%','12%','12%','12%','12%'];});}},
   {extend:'excelHtml5',text:'<i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel (halaman ini)',title:title,messageTop:caption,filename:'Akad-'+new Date().toISOString().slice(0,10),exportOptions:options,
    customize:function(xlsx){var sheet=xlsx.xl.worksheets['sheet1.xml'];$('row c',sheet).each(function(){var value=$('is t',this).text();if(/^[=+@-]/.test(value))$('is t',this).text("'"+value);});}}
  ]});
 table.buttons().container().appendTo('#akad-export-buttons');
 $('#akad-filter').on('submit',function(event){event.preventDefault();table.ajax.reload();});
 $('#akad-status').on('change',function(){table.ajax.reload();});
}(jQuery));
