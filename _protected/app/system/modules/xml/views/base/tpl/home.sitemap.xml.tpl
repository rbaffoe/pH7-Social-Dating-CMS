{{ $design->xmlHeader() }}
{{ XmlDesign::xslHeader() }}


{* General Link *}

<url>
  <loc>{{ $design->url('xml','sitemap','xmlrouter','main') }}</loc>
  <lastmod>{current_date}</lastmod>
  <changefreq>daily</changefreq>
  <priority>1.0</priority>
</url>


{* Mod *}

<url>
  <loc>{{ $design->url('xml','sitemap','xmlrouter','user') }}</loc>
  <lastmod>{current_date}</lastmod>
  <changefreq>daily</changefreq>
  <priority>0.7</priority>
</url>

<url>
  <loc>{{ $design->url('xml','sitemap','xmlrouter','blog') }}</loc>
  <lastmod>{current_date}</lastmod>
  <changefreq>weekly</changefreq>
  <priority>0.9</priority>
</url>

<url>
  <loc>{{ $design->url('xml','sitemap','xmlrouter','note') }}</loc>
  <lastmod>{current_date}</lastmod>
  <changefreq>weekly</changefreq>
  <priority>0.8</priority>
</url>

<url>
  <loc>{{ $design->url('xml','sitemap','xmlrouter','forums') }}</loc>
  <lastmod>{current_date}</lastmod>
  <changefreq>weekly</changefreq>
  <priority>0.8</priority>
</url>

<url>
  <loc>{{ $design->url('xml','sitemap','xmlrouter','comment') }}</loc>
  <lastmod>{current_date}</lastmod>
  <changefreq>weekly</changefreq>
  <priority>0.6</priority>
</url>

<url>
  <loc>{{ $design->url('xml','sitemap','xmlrouter','picture') }}</loc>
  <lastmod>{current_date}</lastmod>
  <changefreq>weekly</changefreq>
  <priority>0.6</priority>
</url>

<url>
  <loc>{{ $design->url('xml','sitemap','xmlrouter','video') }}</loc>
  <lastmod>{current_date}</lastmod>
  <changefreq>weekly</changefreq>
  <priority>0.8</priority>
</url>

<url>
  <loc>{{ $design->url('xml','sitemap','xmlrouter','game') }}</loc>
  <lastmod>{current_date}</lastmod>
  <changefreq>monthly</changefreq>
  <priority>0.8</priority>
</url>

{{ XmlDesign::xslFooter() }}