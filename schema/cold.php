<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Inicio",
          "item": "<?=$site_url?>"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "<?=str_replace('Tris', '', $category_info['name'])?>",
          "item": "<?=$site_url.'/'.$category_info['slug']?>"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": "N�meros Fr�os",
          "item": "<?=$site_url.'/'.$category_info['slug'].'/numeros-frios'?>"
        }
      ]
    },
    {
      "@type": "WebPage",
      "name": "<?=$category_info['cold_meta_title']?>",
      "url": "<?=$site_url.'/'.$category_info['slug'].'/numeros-frios'?>",
      "description": "<?=$category_info['cold_meta_desc']?>",
      "publisher": {
        "@type": "Organization",
        "name": "<?=$settings['site_name']?>",
        "url": "<?=$site_url?>",
        "logo": {
          "@type": "ImageObject",
          "url": "<?=$site_url.'/images/'.$settings['site_logo']?>"
        }
      },
      "datePublished": "2024-12-02",
      "dateModified": "2024-12-02",
      "mainEntity": {
        "@type": "Event",
        "name": "Resulta do del tris de hoy",
        "description": "Aqu� est�n todos los resultados de la loter�a Tris de M�xico en tiempo y forma. Estos resultados son 100% precisos, as� que no te preocupes por su exactitud. Adem�s, puedes consultar las bolas calientes y fr�as, as� como las predicciones."
      }
    }
  ]
}
</script>
