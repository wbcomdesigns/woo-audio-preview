# QA Gate — Audio Preview for WooCommerce (free)

Pre-release checklist for `woo-audio-preview`. Run top to bottom on a clean WP + WooCommerce install before every release. Two layers: **[code]** items verify from the terminal (lint, contract, wp-cli, DB); **[browser]** items require you to open the page and *read* the rendered result — layout, states, friction — not just assert a DOM node exists.

**Combo install:** this free plugin and `woo-audio-preview-pro` are a pair that share the `wcap_audio` post-meta contract. When Pro is active it deactivates free and takes over the metabox + settings menu. Run this gate with **free active alone** first, then run the Pro gate for the combined behavior.

A check passes only with evidence — a command output or a screenshot you actually read. Ship only when every box is ticked or the gap is a logged, accepted exception.

---

## Phase 1 — Code flow: boot & activation

- [ ] Activates on a clean WP + WooCommerce install with no PHP notice/warning/fatal in `debug.log`.
- [ ] Bootstraps exactly once — `run_wc_audio_preview()` fires once on `plugins_loaded`; no duplicate hook registration across admin loads.
- [ ] `Requires Plugins: woocommerce` header present; the runtime `wcap_check_require_plugins()` self-deactivation is consistent with it (no double-nagging, no fatal when WC absent).
- [ ] Textdomain `woo-audio-preview` loads on `plugins_loaded`/`init`; no output before headers.
- [ ] Deactivate → reactivate is clean; `uninstall.php` removes only this plugin's own options/meta (`wcap_audio`, `wcap_admin_errors`, welcome/general options).
- [ ] With Pro active, free's `admin_menu` settings registration is correctly suppressed (no duplicate "Audio Preview" submenu).
- [ ] PHP lint (7.4–8.4) + PHPStan clean; WPCS clean.

## Phase 2 — Code flow: data, contracts & scale

- [ ] Contract audit clean vs Pro: `wcap_audio` array shape (`wcap_audio_names`/`wcap_audio_urls`/`wcap_audio_source`) matches what the frontend reads; no orphan meta keys. Confirm `wcap_preview_attachment` is either used or documented as legacy.
- [ ] The `wcap_audio` store is reachable the two ways free supports: admin metabox (write) and single-product frontend (read). No third store shipping dead weight.
- [ ] Product query on the storefront is WooCommerce-native (`wc_get_product`); no per-product custom SQL, no N+1 when many products render.
- [ ] Nonce + capability check on the delete AJAX (`wcap_delete_audio_ajax`): `ajax-nonce` verified, `edit_posts` + `edit_post($id)` enforced, guest path bails. Confirm the `nopriv` registration cannot mutate data.
- [ ] Metabox save persists: enter 3 previews → Save → reopen → all three names + URLs retained; invalid URLs are rejected (not silently stored) via `validate_audio_url`.
- [ ] Save path does not strip other plugins' hooks globally; `remove_all_actions('admin_notices')` in `wcap_hide_all_admin_notices_from_setting_page` is scoped to this plugin's own pages only (verify the page guard).
- [ ] Uploaded files land in `uploads/wcap_files/` only; delete AJAX removes the file and clears meta without touching other attachments.

## Phase 3 — Browser: admin presentation

- [ ] **Product-edit audio metabox saves and re-opens with the value:** add a name + Media Library file to row 1, a CDN URL (Drive/Dropbox) to row 2 → Save → reopen the product → both rows show the saved values and the CDN indicator renders. *Look at it* — don't trust the DOM alone.
- [ ] Media Library button opens the WP media frame and inserts the selected file's URL into the correct row; Clear button empties the correct row.
- [ ] Live CDN-detection indicator ("🔗 … link detected") appears when a recognized CDN URL is pasted, and not for a plain `.mp3`.
- [ ] Settings page (`page=woo-audio-preview-settings`) renders on the shared wbcom shell; Welcome / General (PRO) / FAQ tabs each switch and **paint** (not merely present in the DOM).
- [ ] Admin validation errors (bad URL, wrong extension) surface visibly in the metabox / admin notice — no silent drop.
- [ ] No console errors on the product editor or settings screen; admin assets enqueue once, no 404s. Assets load only on `product` add/edit and the settings page (verify the screen guard).

## Phase 4 — Browser: frontend presentation (shopper side)

- [ ] **Player renders and plays on a generic theme single product:** on Storefront (and a default block theme — *not* only BuddyX / Reign), the player appears before Add to Cart and a local `.mp3` plays with working play/pause + progress bar.
- [ ] **A CDN / remote audio URL previews correctly:** a Google Drive share link renders the Drive iframe and plays; a SoundCloud link renders the widget and plays; a Dropbox share link plays in the HTML5 element.
- [ ] Variable / grouped / external product types: the player renders for a product that has previews and is absent (cleanly) for one that has none — no empty container, no PHP notice.
- [ ] Button/link hover, focus, and visited states are correct — themes override `<a>`/`<button>`; check all three, and confirm the play button is a real `<button>` (it is) with a visible focus ring.
- [ ] No JS errors in the storefront console; no handler bound to a selector the markup never emits. `preload=none` respected — audio bytes only fetch after the first play click.
- [ ] Does not leak store internals to shoppers (file paths, admin notices, `WP_DEBUG` warnings on the storefront).

## Phase 5 — Cross-cutting: responsive · RTL · dark · a11y

- [ ] 390 / 768 / 1024 / 1280: no horizontal body scroll on the single product; the player and its progress bar stack/fit; metabox rows stack on narrow admin.
- [ ] **Player controls reachable one-thumb at 390px:** play/pause tappable, tap target ≥ 44px, progress bar usable without a mouse.
- [ ] RTL: player and metabox mirror correctly; the RTL CSS variant is the one that loads (`get_asset_filename` picks css-rtl).
- [ ] Player uses neutral/theme-inherited colors, not hard-coded values that clash on a dark theme (free has no dark-mode tokens — confirm it inherits rather than fights the theme).
- [ ] Keyboard: play button tabbable with visible focus, `aria-label` "Play {name}" present; audio element has a text fallback for unsupported browsers.

## Phase 6 — Packaging & release artifact

- [ ] Version agrees across main-file header, `WCAP_TEXT_VERSION`, `readme.txt` stable tag, and `package.json` (currently 1.5.1).
- [ ] Built zip excludes `node_modules/`, tests, `.github/`, `*.md` audit docs, dev-only `vendor/` deps; includes `public/js/soundcloud.min.js` and the RTL/min CSS+JS.
- [ ] No licensing/update SDK in the zip — `.org` forbids it: `vendor/edd-sl-sdk/` is absent and no `edd_sl_sdk`/`EDD_` reference remains in any bundled PHP.
- [ ] Pristine install from the built zip (fresh Docker WP + WC): activates, single product returns 200, player renders.
- [ ] Changelog in WooCommerce action-prefix style (New/Improve/Fix/Security/Dev/Compat), no em-dashes, no emoji; free ↔ Pro lockstep link.

## Phase 7 — Friction hunt

- [ ] **First run:** fresh activation redirects to the settings page; is it obvious to the owner that the next step is "edit a product and add previews", or does the settings page look like the feature lives there?
- [ ] **Owner setup:** can a non-developer add a preview without docs? The metabox labels ("Audio Name", "Audio URL", Media Library) read by function; the 3-field limit and the Pro upsell are clear, not confusing.
- [ ] **Shopper path:** on a real product, count clicks from landing to hearing audio (should be one — click play). Any dead end, silent no-op, or a play button that spins forever on a broken CDN URL?
- [ ] **Error honesty:** paste a non-audio URL, a private Google Drive file, a 404 link → does the admin reject it on save, and does the frontend fail gracefully (no infinite spinner, no console flood)?
- [ ] **Same-class sweep:** every friction found on one preview row — is it the same on rows 2 and 3, on a CDN row vs an upload row, and on both a simple and a variable product? Fix the class, prove the sweep.

## Phase 8 — Release sign-off

- [ ] Phases 1–7 complete, or each unchecked item logged as an accepted exception with a reason.
- [ ] Functionality catalog current: `audit/manifest.json` + `CAPABILITIES.md` regenerated no earlier than the newest `includes/` / `admin/` / `public/` change.
- [ ] Combo run done: free-alone gate green, then Pro gate green with the pair active.
