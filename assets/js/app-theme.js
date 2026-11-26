(function(){
'use strict';
var toggle=document.getElementById('theme-toggle');
function applyTheme(theme){document.documentElement.dataset.theme=theme;var dark=theme==='dark';toggle.setAttribute('aria-pressed',String(dark));toggle.setAttribute('aria-label',dark?'Aktifkan mode terang':'Aktifkan mode gelap');toggle.querySelector('i').className=dark?'fa fa-sun-o':'fa fa-moon-o';toggle.querySelector('span').textContent=dark?'Terang':'Gelap';}
if(toggle){applyTheme(document.documentElement.dataset.theme);toggle.addEventListener('click',function(){var theme=document.documentElement.dataset.theme==='dark'?'light':'dark';applyTheme(theme);try{localStorage.setItem('rumaji-theme',theme);}catch(e){}});}
document.querySelectorAll('.user-avatar').forEach(function(image){image.addEventListener('error',function(){if(image.dataset.fallback){var fallback=image.dataset.fallback;delete image.dataset.fallback;image.src=fallback;}});if(image.complete&&image.naturalWidth===0){image.dispatchEvent(new Event('error'));}});
document.addEventListener('click',function(event){var link=event.target.closest('[data-confirm-logout]');if(link&&!window.confirm('Anda yakin ingin keluar dari aplikasi?')){event.preventDefault();}});
}());
