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
          "name": "Histórico Tris",
          "item": "<?=$site_url.'/'.$categoryInfo['slug'].'/historico'?>"
        }
      ]
    },
    {
      "@type": "WebPage",
      "name": "<?=$categoryInfo['history_meta_title']?>",
      "url": "<?=$site_url.'/'.$categoryInfo['slug'].'/historico'?>",
      "description": "<?=$categoryInfo['history_meta_desc']?>",
      "publisher": {
        "@type": "Organization",
        "name": "<?=$settings['site_name']?>",
        "url": "<?=$site_url?>",
        "logo": {
          "@type": "ImageObject",
          "url": "<?=$site_url.'/images/'.$settings['site_logo']?>"
        }
      },
      "datePublished": "<?=date('Y-m-d')?>",
      "dateModified": "<?=date('Y-m-d')?>",
      "mainEntity": {
        "@type": "Event",
        "name": "Resulta do del tris de hoy",
        "description": "Aquí están todos los resultados de la lotería Tris de México en tiempo y forma. Estos resultados son 100% precisos, así que no te preocupes por su exactitud. Además, puedes consultar las bolas calientes y frías, así como las predicciones."
      }
    }
  ]
}
</script>
