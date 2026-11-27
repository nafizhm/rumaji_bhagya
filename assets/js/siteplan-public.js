(function () {
    'use strict';
    var svg=document.getElementById('siteplan-svg'), dataNode=document.getElementById('sp-unit-data');
    if (!svg || !dataNode) return;
    var data=JSON.parse(dataNode.textContent), initial=svg.getAttribute('viewBox').trim().split(/[ ,]+/).map(Number);
    var box=initial.slice(), pointers=new Map(), pinch=null, suppressClickUntil=0, selected=null;
    var popup=document.getElementById('sp-popover'), closeButton=document.getElementById('sp-close');
    var nodes=Array.prototype.slice.call(svg.querySelectorAll('.sp-unit'));
    function text(id,value) { document.getElementById(id).textContent=value == null || value==='' ? '-' : value; }
    function positionPopup() {
        if (!selected || popup.hidden || window.innerWidth<=640) return;
        var rect=selected.getBoundingClientRect(), width=popup.offsetWidth, height=popup.offsetHeight;
        var left=Math.max(12,Math.min(rect.left+rect.width/2-width/2,window.innerWidth-width-12));
        var top=rect.top-height-18;
        if (top<12) top=rect.bottom+18;
        top=Math.max(12,Math.min(top,window.innerHeight-height-12));
        popup.style.left=left+'px';popup.style.top=top+'px';
    }
    function closePopup(restoreFocus) {
        popup.hidden=true;
        if (selected) {
            selected.classList.remove('is-selected');
            selected.setAttribute('aria-expanded','false');
            if (restoreFocus) selected.focus({preventScroll:true});
        }
        selected=null;
    }
    function detail(node) {
        var unit=data[node.dataset.code];
        if (!unit || unit.state!=='tersedia' || node.classList.contains('is-disabled') || node.classList.contains('is-filtered')) return;
        closePopup(false);selected=node;node.classList.add('is-selected');node.setAttribute('aria-expanded','true');
        text('sp-detail-status',unit.status);document.getElementById('sp-detail-status').dataset.state=unit.state;
        text('sp-detail-blok',unit.blok);text('sp-detail-code',unit.no);
        text('sp-detail-type',unit.tipe);text('sp-detail-area',unit.luas ? Number(unit.luas).toLocaleString('id-ID')+' m\u00b2' : '-');
        var ready=unit.state==='tersedia';
        document.querySelector('.sp-price').classList.toggle('is-unavailable',!ready);
        text('sp-price-label',ready ? 'Total Harga' : 'Ketersediaan');
        text('sp-detail-price',ready ? (unit.harga>0 ? 'Rp '+Number(unit.harga).toLocaleString('id-ID') : 'Harga belum tersedia') : unit.status);
        popup.hidden=false;positionPopup();closeButton.focus({preventScroll:true});
    }
    function render() { svg.setAttribute('viewBox',box.join(' '));positionPopup(); }
    function point(x,y) { var p=svg.createSVGPoint();p.x=x;p.y=y;return p.matrixTransform(svg.getScreenCTM().inverse()); }
    function zoom(factor,center) {
        var width=Math.max(initial[2]/15,Math.min(initial[2]*2,box[2]*factor));
        factor=width/box[2];center=center || {x:box[0]+box[2]/2,y:box[1]+box[3]/2};
        box=[center.x-(center.x-box[0])*factor,center.y-(center.y-box[1])*factor,width,box[3]*factor];render();
    }
    document.getElementById('sp-plus').addEventListener('click',function () { closePopup(false);zoom(.8); });
    document.getElementById('sp-minus').addEventListener('click',function () { closePopup(false);zoom(1.25); });
    document.getElementById('sp-reset').addEventListener('click',function () { closePopup(false);box=initial.slice();render(); });
    svg.addEventListener('wheel',function (event) { event.preventDefault();closePopup(false);zoom(event.deltaY<0 ? .9 : 1.1,point(event.clientX,event.clientY)); },{passive:false});
    svg.addEventListener('pointerdown',function (event) {
        if (event.button!==0) return;
        pointers.set(event.pointerId,{x:event.clientX,y:event.clientY,startX:event.clientX,startY:event.clientY});
        if (pointers.size===2) {
            var pair=Array.from(pointers.values());
            pinch={distance:Math.max(1,Math.hypot(pair[1].x-pair[0].x,pair[1].y-pair[0].y)),width:box[2],anchor:point((pair[0].x+pair[1].x)/2,(pair[0].y+pair[1].y)/2)};
            closePopup(false);suppressClickUntil=performance.now()+400;
        }
    });
    svg.addEventListener('pointermove',function (event) {
        var previous=pointers.get(event.pointerId);if (!previous) return;
        var current={x:event.clientX,y:event.clientY,startX:previous.startX,startY:previous.startY};
        pointers.set(event.pointerId,current);
        if (pointers.size===2 && pinch) {
            var pair=Array.from(pointers.values()),midX=(pair[0].x+pair[1].x)/2,midY=(pair[0].y+pair[1].y)/2;
            var distance=Math.max(1,Math.hypot(pair[1].x-pair[0].x,pair[1].y-pair[0].y));
            zoom((pinch.width*pinch.distance/distance)/box[2]);
            var mid=point(midX,midY);box[0]+=pinch.anchor.x-mid.x;box[1]+=pinch.anchor.y-mid.y;render();
        } else {
            if (Math.hypot(current.x-current.startX,current.y-current.startY)<6 && !svg.classList.contains('is-dragging')) return;
            closePopup(false);
            var from=point(previous.x,previous.y),to=point(current.x,current.y);
            box[0]+=from.x-to.x;box[1]+=from.y-to.y;render();
        }
        svg.classList.add('is-dragging');suppressClickUntil=performance.now()+300;
        if (!svg.hasPointerCapture(event.pointerId)) svg.setPointerCapture(event.pointerId);
    });
    function endPointer(event) {
        pointers.delete(event.pointerId);pinch=null;
        pointers.forEach(function (p) { p.startX=p.x;p.startY=p.y; });
        if (svg.hasPointerCapture(event.pointerId)) svg.releasePointerCapture(event.pointerId);
        if (!pointers.size) svg.classList.remove('is-dragging');
    }
    svg.addEventListener('pointerup',endPointer);svg.addEventListener('pointercancel',endPointer);
    svg.addEventListener('click',function (event) {
        if (performance.now()<suppressClickUntil) return;
        var node=event.target.closest('.sp-unit');if (node && svg.contains(node)) detail(node);
    });
    svg.addEventListener('keydown',function (event) {
        var node=event.target.closest('.sp-unit');
        if (node && (event.key==='Enter'||event.key===' ')) { event.preventDefault();detail(node); }
    });
    closeButton.addEventListener('click',function () { closePopup(true); });
    document.addEventListener('keydown',function (event) { if (event.key==='Escape' && !popup.hidden) closePopup(true); });
    document.addEventListener('click',function (event) { if (!popup.hidden && !event.target.closest('.sp-unit,.sp-popover')) closePopup(false); });
    function revealActiveProject() {
        var active=document.querySelector('.sp-project.is-active'),nav=document.querySelector('.sp-projects');
        if (!active || !nav) return;
        var rect=active.getBoundingClientRect(),frame=nav.getBoundingClientRect();
        if (rect.left<frame.left+8 || rect.right>frame.right-8) nav.scrollLeft+=rect.left-frame.left-12;
    }
    window.addEventListener('resize',function () { positionPopup();requestAnimationFrame(revealActiveProject); });
    window.addEventListener('scroll',positionPopup,{passive:true});
    document.getElementById('sp-search').addEventListener('input',function () {
        var query=this.value.toUpperCase().replace(/[\s/\-]/g,''),count=0;closePopup(false);
        nodes.forEach(function (node) {
            var unit=data[node.dataset.code],code=(node.dataset.code+unit.blok+unit.no).toUpperCase().replace(/[\s/\-]/g,'');
            var match=!query || code.indexOf(query)!==-1;
            node.classList.toggle('is-filtered',!match);node.setAttribute('tabindex',match && unit.state==='tersedia' ? '0' : '-1');
            if (match) count++;
        });
        document.getElementById('sp-result').textContent=query ? count+' kavling ditemukan' : 'Ketuk kavling untuk melihat detail';
    });
    requestAnimationFrame(revealActiveProject);
}());
