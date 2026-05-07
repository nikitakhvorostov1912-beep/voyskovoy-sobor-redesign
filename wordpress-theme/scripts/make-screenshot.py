# -*- coding: utf-8 -*-
"""Создаёт screenshot.png — превью темы для WP-админки.

WP требует:
- 1200×900 рекомендуемый размер
- PNG или JPG
- В корне темы

Стратегия: берём cathedral-2021-facade.jpg + накладываем тёмный overlay + название темы.
"""
import sys
from pathlib import Path
from PIL import Image, ImageDraw, ImageFont

sys.stdout.reconfigure(encoding="utf-8")

THEME = Path(r"C:\CLOUDE_PR\Церковь\wordpress-theme\voiskovoy-sobor")
SRC = THEME / "assets" / "images" / "photos" / "cathedral-2021-facade.jpg"
OUT = THEME / "screenshot.png"

W, H = 1200, 900


def main() -> None:
    if not SRC.exists():
        print(f"! {SRC} не найден")
        return

    # Открываем фото и кропим под 1200×900 (cover)
    img = Image.open(SRC).convert("RGB")
    src_w, src_h = img.size
    target_ratio = W / H
    src_ratio = src_w / src_h

    if src_ratio > target_ratio:
        # source шире — резать по бокам
        new_w = int(src_h * target_ratio)
        x = (src_w - new_w) // 2
        img = img.crop((x, 0, x + new_w, src_h))
    else:
        # source выше — резать сверху/снизу
        new_h = int(src_w / target_ratio)
        y = (src_h - new_h) // 2
        img = img.crop((0, y, src_w, y + new_h))

    img = img.resize((W, H), Image.LANCZOS)

    # Тёмный градиент-overlay
    overlay = Image.new("RGBA", (W, H), (0, 0, 0, 0))
    draw = ImageDraw.Draw(overlay)
    for y in range(H):
        # Top немного светлый, bottom — тёмный
        alpha = int(110 + 110 * (y / H))
        if alpha > 220:
            alpha = 220
        draw.line([(0, y), (W, y)], fill=(15, 18, 28, alpha))

    composed = Image.alpha_composite(img.convert("RGBA"), overlay).convert("RGB")

    # Текст
    draw = ImageDraw.Draw(composed)
    # Найти системные шрифты Cormorant / Times / Arial
    font_big = None
    font_small = None
    for fontname in ["arial.ttf", "calibri.ttf", "georgia.ttf", "times.ttf"]:
        try:
            font_big = ImageFont.truetype(fontname, 64)
            font_small = ImageFont.truetype(fontname, 22)
            break
        except OSError:
            continue
    if font_big is None:
        font_big = ImageFont.load_default()
        font_small = ImageFont.load_default()

    # Название темы
    title_lines = ["Войсковой Собор", "Александра Невского"]
    sub = "WORDPRESS · ТЕМА · КРАСНОДАР · 1872"
    color_paper = (245, 233, 200)
    color_gold = (201, 169, 97)

    # Расчёт высоты блока
    line_h = 76
    total_h = len(title_lines) * line_h + 50
    y0 = (H - total_h) // 2 - 30
    for i, line in enumerate(title_lines):
        bbox = draw.textbbox((0, 0), line, font=font_big)
        tw = bbox[2] - bbox[0]
        x = (W - tw) // 2
        draw.text((x, y0 + i * line_h), line, font=font_big, fill=color_paper)

    # Подзаголовок
    bbox = draw.textbbox((0, 0), sub, font=font_small)
    tw = bbox[2] - bbox[0]
    x = (W - tw) // 2
    draw.text((x, y0 + len(title_lines) * line_h + 22), sub, font=font_small, fill=color_gold)

    # Декоративная золотая линия
    draw.line([(W // 2 - 60, y0 + len(title_lines) * line_h + 16),
               (W // 2 + 60, y0 + len(title_lines) * line_h + 16)],
              fill=color_gold, width=1)

    composed.save(OUT, "PNG", optimize=True)
    size = OUT.stat().st_size
    print(f"  ✓ {OUT.name} {composed.size}, {size:,} bytes")


if __name__ == "__main__":
    main()
