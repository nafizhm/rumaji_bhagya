<div class="modal fade" id="spr-booking-document-modal" tabindex="-1" role="dialog" aria-labelledby="spr-booking-document-title"><div class="modal-dialog modal-lg" role="document"><div class="modal-content">
 <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Tutup">&times;</button><h4 class="modal-title" id="spr-booking-document-title">Dokumen Booking</h4></div>
 <div class="modal-body" id="spr-booking-document-content"></div>
 <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button></div>
</div></div></div>
<script>
jQuery(function($){
 $('.spr-booking-view').on('click',function(){
  var button=$(this),body=$('#spr-booking-document-content').empty(),url=button.attr('data-url'),mime=button.attr('data-mime');
  $('#spr-booking-document-title').text(button.attr('data-title'));
  if(['image/jpeg','image/png','image/webp'].indexOf(mime)!==-1){$('<img>').attr({src:url,alt:button.attr('data-title')}).css({display:'block',maxWidth:'100%',height:'auto',margin:'auto'}).appendTo(body);}
  else if(mime==='application/pdf'){$('<iframe>').attr({src:url,title:button.attr('data-title')}).css({width:'100%',height:'70vh',border:0}).appendTo(body);}
  else{body.text('Pratinjau tidak tersedia. Gunakan tombol Download untuk membuka dokumen.');}
  $('#spr-booking-document-modal').modal('show');
 });
 $('#spr-booking-document-modal').on('hidden.bs.modal',function(){$('#spr-booking-document-content').empty();});
});
</script>
