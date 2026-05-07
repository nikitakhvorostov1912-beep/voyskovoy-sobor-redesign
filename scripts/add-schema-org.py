"""Add Schema.org Church JSON-LD to all HTML files in docs/.

Idempotent: checks for existing 'application/ld+json' before inserting.
"""
from pathlib import Path

DOCS = Path(r"C:\CLOUDE_PR\Церковь\github-staging\docs")

JSONLD = '''<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Church",
  "name": "Войсковой собор святого благоверного князя Александра Невского",
  "alternateName": "Войсковой Собор Александра Невского",
  "url": "https://alexander-nevskiysobor.ru",
  "telephone": "+7 (861) 262-00-20",
  "email": "nevskiy-sobor@mail.ru",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "ул. Постовая, 26",
    "addressLocality": "Краснодар",
    "postalCode": "350063",
    "addressRegion": "Краснодарский край",
    "addressCountry": "RU"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": 45.014800,
    "longitude": 38.971200
  },
  "foundingDate": "1853",
  "openingHours": "Mo-Su 07:00-19:00",
  "sameAs": [
    "https://vk.com/voyskovoysoborkrasnodar",
    "https://t.me/alexnewsobor"
  ],
  "parentOrganization": {
    "@type": "ReligiousOrganization",
    "name": "Екатеринодарская и Кубанская епархия Русской Православной Церкви"
  }
}
</script>
'''

def transform(html: str) -> str:
    if "application/ld+json" in html:
        return html
    return html.replace("</head>", JSONLD + "</head>", 1)

def main() -> None:
    files = sorted(DOCS.glob("*.html"))
    for fp in files:
        original = fp.read_text(encoding="utf-8")
        updated = transform(original)
        if updated != original:
            fp.write_text(updated, encoding="utf-8")
            print(f"OK   {fp.name}")
        else:
            print(f"--   {fp.name} (already has JSON-LD)")

if __name__ == "__main__":
    main()
