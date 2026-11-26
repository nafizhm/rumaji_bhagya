(function($){
 'use strict';
 var svg=document.getElementById('siteplan-svg'),dataElement=document.getElementById('siteplan-unit-data');if(!svg||!dataElement)return;
 var data=JSON.parse(dataElement.textContent),initial=svg.getAttribute('viewBox').trim().split(/[ ,]+/).map(Number),box=initial.slice(),drag=null,moved=false;
 function render(){svg.setAttribute('viewBox',box.join(' '));}
 function point(event){var p=svg.createSVGPoint();p.x=event.clientX;p.y=event.clientY;return p.matrixTransform(svg.getScreenCTM().inverse());}
 function zoom(factor,center){var width=box[2]*factor;if(width<initial[2]/12||width>initial[2]*2)return;center=center||{x:box[0]+box[2]/2,y:box[1]+box[3]/2};box=[center.x-(center.x-box[0])*factor,center.y-(center.y-box[1])*factor,width,box[3]*factor];render();}
 $('#siteplan-plus').on('click',function(){zoom(.8);});$('#siteplan-minus').on('click',function(){zoom(1.25);});$('#siteplan-reset').on('click',function(){box=initial.slice();render();});
 svg.addEventListener('wheel',function(event){event.preventDefault();zoom(event.deltaY<0?.9:1.1,point(event));},{passive:false});
 svg.addEventListener('pointerdown',function(event){if(event.button!==0)return;moved=false;drag={id:event.pointerId,x:event.clientX,y:event.clientY,point:point(event),box:box.slice()};});
 svg.addEventListener('pointermove',function(event){if(!drag||event.pointerId!==drag.id)return;if(!moved&&Math.hypot(event.clientX-drag.x,event.clientY-drag.y)<5)return;moved=true;svg.classList.add('is-dragging');if(!svg.hasPointerCapture(event.pointerId))svg.setPointerCapture(event.pointerId);var p=point(event);box[0]+=drag.point.x-p.x;box[1]+=drag.point.y-p.y;render();});
 function end(event){if(!drag||event.pointerId!==drag.id)return;drag=null;svg.classList.remove('is-dragging');if(svg.hasPointerCapture(event.pointerId))svg.releasePointerCapture(event.pointerId);}
 svg.addEventListener('pointerup',end);svg.addEventListener('pointercancel',end);
 function detail(code){var unit=data[code];if(!unit)return;$('#siteplan-detail-title').text('Unit '+unit.kode);var body=$('#siteplan-detail-body').empty();[['Proyek',$('#siteplan-project option:selected').text()],['Blok / No',unit.blok+'/'+unit.no],['Lot',unit.lot],['Luas Tanah',unit.luas+' m²'],['Tipe',unit.tipe],['Jenis Perumahan',unit.jenis],['Status',unit.status],['Status Stok',unit.status_stok]].forEach(function(pair){$('<tr>').append($('<th>').text(pair[0]),$('<td>').text(pair[1]||'-')).appendTo(body);});$('#siteplan-stock-link').attr('href',unit.stock_url);$('#siteplan-spr-link').prop('hidden',!unit.spr_url).attr('href',unit.spr_url||'#');$('#siteplan-detail').modal('show');}
 $(svg).on('click','.siteplan-unit',function(){if(!moved&&!this.classList.contains('is-dimmed'))detail(this.dataset.code);}).on('keydown','.siteplan-unit',function(event){if((event.key==='Enter'||event.key===' ')&&!this.classList.contains('is-dimmed')){event.preventDefault();detail(this.dataset.code);}});
 function filter(){var query=$('#siteplan-search').val().toUpperCase().replace(/[\s/\-]/g,''),state=$('#siteplan-status').val(),count=0;$('.siteplan-unit',svg).each(function(){var match=(!query||this.dataset.code.indexOf(query)!==-1)&&(!state||this.dataset.state===state);$(this).toggleClass('is-dimmed',!match).attr('tabindex',match?'0':'-1');if(match)count++;});$('#siteplan-result').text(count+' unit sesuai filter. Klik unit untuk melihat detail.');}
 $('#siteplan-search').on('input',filter);$('#siteplan-status').on('change',filter);
}(jQuery));
