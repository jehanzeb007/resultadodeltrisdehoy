<?php
$page_title = 'Autor- Don Tse | Resultadodeltrisdehoy.com';
$page_meta  = '<meta name="description" content="Don Tse nació en 1981 en Ciudad Madera, México. Estudió Comportamiento Humano y terminó sus estudios en esta área en 2011.">';
?>
<!DOCTYPE html>
<html lang="es-MX">
<head>
<?php include 'includes/head.php';?> 
<style>.wrap{display:flex;justify-content:center;align-items:center;}.author-card{background-color:#fff;box-shadow:0 4px 8px rgba(0,0,0,0.1);padding:20px;border-radius:10px;text-align:center;max-width:400px;width:100%;display:flex;flex-direction:column;align-items:center;}.author-card img{width:120px;height:120px;border-radius:50%;border:4px solid #ddd;margin-bottom:15px;}.author-card h1{font-size:24px;margin:10px 0;color:#333;border:1px solid #02acff;padding:10px;border-radius:10px;display:inline-block;}.author-card p{font-size:16px;color:#666;line-height:1.5;text-align:justify;}.social-links{margin-top:10px;}.social-links a{display:inline-block;margin:0 10px;font-size:20px;color:#555;text-decoration:none;transition:color 0.3s ease;}.social-links a:hover{color:#007bff;} .follow-button {margin-left: 10px; padding: 5px 15px; background-color: #02acff; color: #fff; border: none; border-radius: 5px; font-size: 14px; cursor: pointer; transition: background-color 0.3s ease;} .follow-button:hover {background-color: #e00707;}.clear.mb20{margin-bottom:10px;} .text-heading {display: flex; justify-content: center; align-items: center; flex-wrap: wrap; gap: 10px;}</style>
</head>
<body>
<div class="wrap">
    <?php include 'includes/nav.php';?>
    <div class="container">
    <div class="row content-block">
        <section class="col-12">
            <div class="clear mb20"></div>
            <div class="text-heading">
                <h1 style="border: 1px solid #02acff; padding: 10px; border-radius: 10px; display: inline-block;">Autor - Don Tse</h1>
                <button class="follow-button">Seguir</button>
            </div>
              <div class="text-heading">  <p style="margin: 5px 0; font-size: 16px; color: #666;">Bastón Tris | <a href="https://www.mexamcef.org/gary-segura">Experto en lotería</a> | <a href="https://en.wikipedia.org/wiki/Mexico">México</a></p></div>  
                         <div class="wrap">
        <div class="author-card">
            <img src="images/author.jpeg" alt="Imagen del Autor">
            <p><?=_translate('author')?></p>
            <div class="social-links">
                <a href="https://twitter.com" target="_blank" aria-label="Twitter">&#x1F426;</a>
                <a href="https://linkedin.com" target="_blank" aria-label="LinkedIn">&#x1F465;</a>
                <a href="https://instagram.com" target="_blank" aria-label="Instagram">&#x1F4F8;</a>
                <a href="https://facebook.com" target="_blank" aria-label="Facebook">&#x1F466;</a>
            </div>
        </div>
            </section>
        </div>
    </div>
</div>

<?php include 'includes/footer.php';?>
</body>

</html>