$ErrorActionPreference = 'Stop'
$chrome = "C:\Program Files\Google\Chrome\Application\chrome.exe"
$out = $PSScriptRoot
$base = "https://alexander-nevskiysobor.ru"

$pages = @(
  @{ slug = 'home';        url = "$base/" },
  @{ slug = 'schedule';    url = "$base/$([uri]::EscapeDataString('расписание-богослужений'))/" },
  @{ slug = 'contacts';    url = "$base/$([uri]::EscapeDataString('контакты'))/" },
  @{ slug = 'about';       url = "$base/$([uri]::EscapeDataString('о-соборе'))/" },
  @{ slug = 'donate';      url = "$base/campaign/$([uri]::EscapeDataString('помощь-храму'))/" },
  @{ slug = 'news';        url = "$base/category/$([uri]::EscapeDataString('новости'))/" },
  @{ slug = 'history';     url = "$base/category/$([uri]::EscapeDataString('летопись-собора'))/" },
  @{ slug = 'clergy';      url = "$base/category/$([uri]::EscapeDataString('персоналии-собора'))/" },
  @{ slug = 'choir';       url = "$base/$([uri]::EscapeDataString('хор-войскового-собора'))/" }
)

$mobileUA = "Mozilla/5.0 (iPhone; CPU iPhone OS 16_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Mobile/15E148 Safari/604.1"

function Capture {
  param([string]$url, [string]$file, [int]$w, [int]$h, [string]$ua)
  $args = @(
    '--headless'
    '--disable-gpu'
    '--no-sandbox'
    '--no-first-run'
    '--no-default-browser-check'
    '--hide-scrollbars'
    "--window-size=$w,$h"
    "--screenshot=$file"
  )
  if ($ua) { $args += "--user-agent=$ua" }
  $args += $url
  $proc = Start-Process -FilePath $chrome -ArgumentList $args -PassThru -Wait -WindowStyle Hidden
  if (Test-Path $file) {
    $size = (Get-Item $file).Length
    Write-Host ("  [OK] {0,-32} {1,10:N0} b" -f (Split-Path $file -Leaf), $size) -ForegroundColor Green
  } else {
    Write-Host ("  [FAIL] {0} (exit {1})" -f (Split-Path $file -Leaf), $proc.ExitCode) -ForegroundColor Red
  }
}

Write-Host "=== DESKTOP 1440x900 ===" -ForegroundColor Cyan
foreach ($p in $pages) {
  $file = Join-Path $out "$($p.slug)-desktop.png"
  Capture -url $p.url -file $file -w 1440 -h 900
}

Write-Host "`n=== MOBILE 390x844 (iPhone UA) ===" -ForegroundColor Cyan
foreach ($p in $pages) {
  $file = Join-Path $out "$($p.slug)-mobile.png"
  Capture -url $p.url -file $file -w 390 -h 844 -ua $mobileUA
}

Write-Host "`n=== TOTAL ===" -ForegroundColor Green
$shots = Get-ChildItem $out -Filter "*.png" | Sort-Object Name
Write-Host "$($shots.Count) screenshots, $('{0:N0}' -f (($shots | Measure-Object Length -Sum).Sum)) bytes total"
