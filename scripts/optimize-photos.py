# -*- coding: utf-8 -*-
"""Оптимизация фото для веба:
- Max width: 1920px (sharpening если нужно)
- JPEG quality 82, optimize=True, progressive
- Сохраняем оригиналы в backups/photos-original/
"""
import sys, shutil, time
from pathlib import Path
from PIL import Image, ImageOps

sys.stdout.reconfigure(encoding="utf-8")

PHOTOS = Path(r"C:\CLOUDE_PR\Церковь\github-staging\docs\assets\images\photos")
BACKUP = Path(r"C:\CLOUDE_PR\Церковь\github-staging\backups") / f"photos-original-{int(time.time())}"
MAX_W = 1920
QUALITY = 82


def main() -> None:
    BACKUP.mkdir(parents=True, exist_ok=True)
    files = sorted(PHOTOS.glob("*.jpg"))
    total_before = 0
    total_after = 0
    for fp in files:
        before = fp.stat().st_size
        total_before += before
        # Backup original
        shutil.copy2(fp, BACKUP / fp.name)
        # Open + EXIF transpose + resize
        img = Image.open(fp)
        img = ImageOps.exif_transpose(img)
        if img.mode in ("RGBA", "LA", "P"):
            img = img.convert("RGB")
        if img.width > MAX_W:
            ratio = MAX_W / img.width
            new_h = int(img.height * ratio)
            img = img.resize((MAX_W, new_h), Image.LANCZOS)
        img.save(fp, "JPEG", quality=QUALITY, optimize=True, progressive=True)
        after = fp.stat().st_size
        total_after += after
        print(f"  {fp.name:34}  {before:>9} → {after:>9}  ({(1-after/before)*100:.1f}% saved)  size={img.size}")
    print(f"\nTotal: {total_before:,} → {total_after:,} ({(1-total_after/total_before)*100:.1f}% saved)")
    print(f"Originals in: {BACKUP}")


if __name__ == "__main__":
    main()
