# WordPress.org listing assets

These images are **not** part of the plugin ZIP (they're excluded via
`.distignore`). After the plugin is approved they go into the SVN `assets/`
directory — or are auto-deployed from this `.wordpress-org/` folder if you use
the 10up `action-wordpress-plugin-deploy` GitHub Action. They control how the
listing looks at `https://wordpress.org/plugins/ai-provider-for-mistral/`.

The final PNG/JPG files live in this folder with the exact names below.

## Concept

A **provider module** that plugs Mistral into WordPress: a hexagonal **chip**
with connector pins/leads holding the **Mistral** mark as its core, and a glowing
green **live-connection sparkle** on the top lead. It tells the plugin's whole
story in one mark — a pluggable module that wires Mistral into the WordPress AI
Client.

Palette: a deep **near-black** badge (`#15171C → #06070A`) for the module
chassis, a dark **chip panel** (`#23262F → #14161C`) with subtle leads, the
warm **Mistral amber→orange-red** mark (`#FFAF00 → #FF4D00`, echoing Mistral's
banded logo) for brand identity, and an **electric green** accent (`#3DF5B6 →
#00B488`) reserved exclusively for the live signal sparkle, so the "active
connection" reads instantly while the amber keeps the Mistral identity. Verified
legible at 128px.

## Icon

| File | Size | Notes |
|---|---|---|
| `icon.svg` | vector | Master. WP.org prefers SVG if present. |
| `icon-256x256.png` | 256 × 256 | Retina raster (rendered from `icon.svg`). |
| `icon-128x128.png` | 128 × 128 | Search-results / card raster. |
| `icon-mono.svg` | vector | Single-color variant (favicons, docs, dark UI). |

Re-rasterize after editing `icon.svg` (macOS, no extra tools):

```bash
cd .wordpress-org
for s in 256 128; do qlmanage -t -s $s -o . icon.svg && mv icon.svg.png icon-${s}x${s}.png; done
```

## Banner

| File | Size | Notes |
|---|---|---|
| `banner.svg` | vector | Master (viewBox 1544×500). |
| `banner-1544x500.png` | 1544 × 500 | Retina banner. |
| `banner-772x250.png` | 772 × 250 | Standard banner. |

Provider-module mark (chip + Mistral mark + live sparkle, sans badge) + "AI
Provider for Mistral" wordmark with an amber accent underline + tagline + a
feature subline, plus a faint dashed connection trace, on the same near-black
gradient as the icon. Verified legible at 772px.

Re-rasterize after editing `banner.svg` (macOS; qlmanage pads to a square, so
crop back to the exact size):

```bash
cd .wordpress-org
qlmanage -t -s 1544 -o . banner.svg && mv banner.svg.png banner-1544x500.png && sips -c 500 1544 banner-1544x500.png
qlmanage -t -s 772  -o . banner.svg && mv banner.svg.png banner-772x250.png  && sips -c 250 772  banner-772x250.png
```

## Screenshots (optional)

Named `screenshot-1.png`, `screenshot-2.png`, … and described, in order, by a
`== Screenshots ==` section in `readme.txt`. This plugin is a headless provider
(no UI of its own), so screenshots are optional — capture the AI Client /
Connectors interface where the Mistral provider appears, if you add any.

> Reminder: if you add screenshots, keep matching `== Screenshots ==` lines in
> `readme.txt` in sync with the files you drop here — one numbered line per
> image, in order.
