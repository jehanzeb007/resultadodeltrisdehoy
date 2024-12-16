<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Article",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "<?=$site_url . '/' . $row_page['slug']?>"
  },
  "headline": "<?=$row_page['title']?>",
  "description": "<?=$row_page['meta_description'] ?? 'Default description for the page.'?>",
  "image": {
    "@type": "ImageObject",
    "url": "<?=$site_url . '/images/' . ($row_page['image_url'] ?? 'default-image.jpg')?>",
    "width": "500",
    "height": "400"
  },
  "author": {
    "@type": "Organization",
    "name": "Don Tse",
    "url": "https://resultadodeltrisdehoy.com/author"
  },
  "publisher": {
    "@type": "Organization",
    "name": "resultadodeltrisdehoy.com",
    "logo": {
      "@type": "ImageObject",
      "url": "<?=$site_url . '/images/' . ($settings['site_logo'] ?? 'default-logo.png')?>",
      "width": "60",
      "height": "60"
    }
  },
  "datePublished": "<?=$row_page['created_at'] ?? '2024-12-02'?>",
  "dateModified": "<?=$row_page['updated_at'] ?? '2024-12-02'?>"
}
</script>
