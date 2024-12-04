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
          "name": "<?=str_replace('Tris', '', $category_info['name'])?>",
          "item": "<?=$site_url.'/'.$category_info['slug'].'/predicciones'?>"
        }
      ]
    },
    {
      "@type": "WebPage",
      "name": "<?=$category_info['prediction_post_meta_title']?>",
      "url": "<?=$site_url.'/'.$category_info['slug'].'/predicciones'?>",
      "description": "<?=$category_info['prediction_post_meta_desc']?>",
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
        "description": "Aquí están todos los resultados de la lotería Tris de México en tiempo y forma. Estos resultados son 100% precisos, así que no te preocupes por su exactitud. Además, puedes consultar las bolas calientes y frías, así como las predicciones."
      }
    }
  ]
}
</script>
