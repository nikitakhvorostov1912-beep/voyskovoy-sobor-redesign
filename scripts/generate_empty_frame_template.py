"""
Генерирует пустую рамку-шаблон для фото священнослужителей в стиле site-photo-frame.

Пропорции 4:5 (книжная) под фото батюшек.
Дизайн повторяет .site-photo-frame:
- Тёмно-синий фон #1a1f2e
- Внутренняя золотая тонкая обводка rgba(201, 169, 97, 0.42)
- Внутри — светлая область для вставки фото

Выход:
    docs/assets/images/clergy/_template-empty-frame.png       — для использования в графических редакторах
    docs/assets/images/clergy/_template-empty-frame@2x.png    — Retina
    docs/clergy-template-preview.html                          — простая страница для Кристины:
        как выглядит рамка + куда вставлять фото + примеры пропорций
"""
from __future__ import annotations

from pathlib import Path

from PIL import Image, ImageDraw, ImageFont

OUT_DIR = Path(__file__).resolve().parent.parent / "docs" / "assets" / "images" / "clergy"
OUT_DIR.mkdir(parents=True, exist_ok=True)


def make_frame(width: int, height: int, output: Path) -> None:
    """
    Воспроизводит .site-photo-frame:
      - внешний тёмно-синий фон (~14px по периметру, как padding в CSS)
      - тонкая золотая обводка внутри (inset:6px → между внешним краем и фотозоной)
      - белая фотозона в центре (куда Кристина вставит фото)
    """
    bg_navy = (26, 31, 46)           # #1a1f2e
    gold = (201, 169, 97)            # #c9a961
    photo_zone = (250, 247, 240)     # тёплая бумага — фото-плейсхолдер
    hint_color = (170, 162, 148)     # светло-серый текст подсказки

    # RGB без alpha — простой и предсказуемый rendering
    img = Image.new("RGB", (width, height), bg_navy)
    draw = ImageDraw.Draw(img)

    # Размер «padding» по периметру (тёмный кант)
    pad = round(width * 14 / 600)            # 14px при ширине 600

    # Фотозона
    photo_box = (pad, pad, width - pad, height - pad)
    draw.rectangle(photo_box, fill=photo_zone)

    # Золотая обводка inset:6px из CSS — между внешним краем и фотозоной
    inset = round(width * 6 / 600)
    gold_box = (inset, inset, width - inset, height - inset)
    line_w = max(2, round(width / 300))   # видимая на любом масштабе
    draw.rectangle(gold_box, outline=gold, width=line_w)

    # Подсказка по центру фотозоны
    try:
        font_size = round(width / 22)
        font = ImageFont.truetype("arial.ttf", font_size)
    except OSError:
        font = ImageFont.load_default()

    hint = "Сюда фото"
    tw = draw.textlength(hint, font=font)
    draw.text(
        ((width - tw) / 2, height / 2 - font_size / 2),
        hint,
        font=font,
        fill=hint_color,
    )

    img.save(output, "PNG", optimize=True)
    print(f"✓ {output.name} ({width}×{height})")


def main() -> int:
    # Книжная пропорция 4:5, подходит для портрета батюшки
    make_frame(600, 750, OUT_DIR / "_template-empty-frame.png")
    make_frame(1200, 1500, OUT_DIR / "_template-empty-frame@2x.png")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
