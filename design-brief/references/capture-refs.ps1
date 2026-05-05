$ErrorActionPreference = 'Continue'
$chrome = "C:\Program Files\Google\Chrome\Application\chrome.exe"
$out = $PSScriptRoot
$desktopUA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36'

$refs = @(
  # Orthodox Russian media — современная типографика, разные подходы
  @{ slug='orthodox-media/foma-ru';        url='https://foma.ru/'            },
  @{ slug='orthodox-media/predanie-ru';    url='https://predanie.ru/'        },
  @{ slug='orthodox-media/pravoslavie-ru'; url='https://pravoslavie.ru/'     },
  @{ slug='orthodox-media/patriarchia-ru'; url='https://patriarchia.ru/'     },
  @{ slug='orthodox-media/pravmir-ru';     url='https://pravmir.ru/'         },
  @{ slug='orthodox-media/blagovest-info'; url='https://blagovest-info.ru/'  },

  # English orthodox / catholic — для UX-патернов
  @{ slug='church-sites/oca';              url='https://www.oca.org/'                       },
  @{ slug='church-sites/svots';            url='https://svots.edu/'                          },
  @{ slug='church-sites/holy-trinity-jordanville'; url='https://hts.edu/'                    },
  @{ slug='church-sites/saintsabbas';      url='https://saintsabbas.org/'                   },

  # Современные приходские сайты в России
  @{ slug='church-sites/sretensky';        url='https://www.sretenskiy-monastyr.ru/'        },
  @{ slug='church-sites/optina';           url='https://www.optina.ru/'                      }
)

function Capture {
  param([string]$url, [string]$file, [int]$w = 1440, [int]$h = 900)
  Get-CimInstance Win32_Process -Filter "Name='chrome.exe'" |
    Where-Object { $_.CommandLine -like '*--headless*' } |
    ForEach-Object { try { Stop-Process -Id $_.ProcessId -Force -ErrorAction Stop } catch {} }
  Start-Sleep -Milliseconds 1500

  $dir = Split-Path $file -Parent
  if (-not (Test-Path $dir)) { New-Item -ItemType Directory -Path $dir -Force | Out-Null }
  if (Test-Path $file) { Remove-Item $file -Force }

  $args = @(
    '--headless','--disable-gpu','--no-sandbox','--no-first-run',
    '--no-default-browser-check','--hide-scrollbars',
    '--virtual-time-budget=12000',
    "--window-size=$w,$h",
    ('--user-agent="' + $desktopUA + '"'),
    "--screenshot=$file",
    $url
  )
  $proc = Start-Process -FilePath $chrome -ArgumentList $args -PassThru -Wait -WindowStyle Hidden
  $size = if (Test-Path $file) { (Get-Item $file).Length } else { 0 }
  $marker = if ($size -gt 50000) { '[OK]' } elseif ($size -gt 0) { '[?] ' } else { '[FAIL]' }
  Write-Host ("  {0} {1,-50} {2,10:N0} b" -f $marker, (Split-Path $file -Leaf), $size)
}

Write-Host "=== Capturing references ===" -ForegroundColor Cyan
foreach ($r in $refs) {
  $file = Join-Path $out "$($r.slug).png"
  Capture -url $r.url -file $file
}

Write-Host "`n=== Tree ===" -ForegroundColor Green
Get-ChildItem $out -Recurse -Filter "*.png" | Sort-Object FullName | ForEach-Object {
  $rel = $_.FullName.Substring($out.Length + 1)
  Write-Host ("  {0,-50} {1,10:N0} b" -f $rel, $_.Length)
}
