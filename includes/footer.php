<style>.footer footer > div {margin-bottom: 1px;}@media (max-width: 600px) {.footer footer ul {display: flex;flex-wrap: wrap;justify-content: space-between;padding: 0;list-style: none;}.footer footer ul li {width: 48%;}.footer footer ul li a {display: flex;align-items: center;text-decoration: none;}.footer footer ul li i {margin-right: 8px;}}</style><div class="footer"><div class="container"><footer><div class="info"><span class="logo"><img alt="el tris" src="<?=getUrl()?>/images/<?=isset($settings['site_logo'])?$settings['site_logo']:''?>" width="136" height="40" loading="lazy"/></span><?= _translate('footer-text') ?></div><div><p><span style="border: 1px solid white; padding: 4px;border-radius: 10px;">Enlaces Sociales</span></p><ul><li><a href="https://www.facebook.com/trisdehoy.resultadodeltris.trispronosticos"><i class="fab fa-facebook"></i> Facebook</a></li><li><a href="https://www.instagram.com/resultadodeltrisdehoy/"><i class="fab fa-instagram"></i> Instagram</a></li><li><a href="https://www.pinterest.com.mx/resultadodeltrisdehoy/"><i class="fab fa-pinterest"> Pinterest</i></a></li><li><a href="https://www.youtube.com/@resultadodeltrisdehoy"><i class="fab fa-youtube"></i> Youtube</a></li></ul></div><div><p><span style="border: 1px solid white; padding: 4px;border-radius: 10px;">Páginas de la empresa</span></p><ul><li><a href="<?=setUrl('privacidad-y-pol-tica')?>">Privacidad y Política</a></li><li><a href="<?=setUrl('sobre-nosotros')?>">Sobre Nosotros</a></li><li><a href="<?=setUrl('contact-us')?>">Contacto</a></li><li><a href="<?=setUrl('blog')?>">Blog</a></li></ul></div><div><p><span style="border: 1px solid white; padding: 4px;border-radius: 10px;">JURÍDICO</span></p><ul><li><a href="<?=setUrl('terminos-de-servicio-para-los-usuarios')?>">Términos de servicio</a></li><li><a href="https://resultadodeltrisdehoy.com/sitemap.xml" target="_blank">Sitemap</a></li><li><a href="https://resultadodeltrisdehoy.com/sitemap_news.xml" target="_blank">News Sitemap</a></li><li style="display: flex; gap: 5px;"><a href="https://chromewebstore.google.com/detail/resultadodel-tris-de-hoy/iemglakinfblilfddbaplhmdakeldide" target="_blank"><i class="fab fa-chrome"></i></a><br><a href="https://microsoftedge.microsoft.com/addons/detail/resultadodel-tris-de-hoy/kbgliaajcddldijjkkniefjlpkkniegb" target="_blank"><i class="fab fa-edge"></i></a><br><a href="https://maps.app.goo.gl/8uQYjCRjEjfJwSHF7" target="_blank"><i class="fas fa-map-marker-alt"></i></a><br><a href="https://addons.mozilla.org/en-US/firefox/addon/resultadodel-tris-de-hoy/" target="_blank"><i class="fab fa-firefox"></i></a></li></ul><img class="svg" alt="" width="112" height="208" src="https://stc.utdstc.com/img/svgs/postdownload-element.svg" /></div></footer><div style="text-align: center; padding-top: 5px; border-top: 1px solid white; margin-top: 20px;"><p style="color: white;">© 2024 Resultadodeltrisdehoy.com. Reservados todos los derechos. El contenido de este sitio se proporciona únicamente con fines informativos y es propiedad de la empresa. Está prohibida la duplicación sin permiso.</p></div></div></div><script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.5/datepicker.min.js" defer></script><script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.2/moment-with-locales.min.js" defer></script><script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script><script>$(function($){$('.slide-menu-div i').on("click",function(){$(this).parent().next('.submenu').toggle('slow');$(this).toggleClass('fa-plus fa-minus');});$('#datepicker').datepicker({format:'dd-mm-yyyy',language:'es-ES',autoHide:true,endDate:'<?=date('d-m-Y')?>'});var menu=$('.slide-menu');$('#menu-toggle').click(function(){$(this).toggleClass('open');menu.toggleClass('visible');});});function scrollToComment(){$('html, body').animate({scrollTop:($("#subscribe_wrapper").offset().top-100)},1000);}<?php if(isset($_SESSION['comment-post-check'])&&$_SESSION['comment-post-check']=='true'){unset($_SESSION["comment-post-check"]);?>scrollToComment();<?php } ?>var comment_page=1;function loadComments(element){var btnObj=$(element);var action='<?=$site_url?>/includes/scripts.php';var method='POST';var data={page:comment_page,type:'load-comments',page_name:'<?=cleanSlash($_SERVER['REQUEST_URI '])?>'};if(btnObj.is('[disabled=disabled]')){return false;}else{btnObj.attr('disabled','disabled');$.ajax({type:method,url:action,data:data,success:function(result){btnObj.removeAttr('disabled');try{result=JSON.parse(result);}catch(e){}if($.trim(result.success)=='true'){$('#loaded_comments').html(result.html);$('#loaded_comments').show();}},error:function(request,status,error){btnObj.removeAttr('disabled');}});}}$(function(){$('#lucky-numbers-form').on('submit',function(event){event.preventDefault();event.stopPropagation();$('#lucky-numbers-list').html('');var number_count=$('#num-of-lucky-numbers').val();for(let i=0;i<number_count;i++){let rand_number=Math.floor(Math.random()*10);$('#lucky-numbers-list').append('<span class="score">'+rand_number+'</span>');}});});const items=document.querySelectorAll(".accordion button");function toggleAccordion(){const itemToggle=this.getAttribute('aria-expanded');for(i=0;i<items.length;i++){items[i].setAttribute('aria-expanded','false');}if(itemToggle=='false'){this.setAttribute('aria-expanded','true');}}items.forEach(item=>item.addEventListener('click',toggleAccordion));</script>


