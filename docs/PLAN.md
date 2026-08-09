# Audio Preview for WooCommerce - plan

**Role: CORE.** Pro adds premium features on top of this plugin through the hooks below. This
plugin never stands down when Pro is active, and Pro never deactivates it.

Branch `1.5.2`. Surface of record: `audit/manifest.json` (regenerate when a hook, option or meta
key changes). Status re-evaluated 2026-08-09.

---

## The seams this plugin owns

Pro extends through these. Adding a premium feature means answering one of them, never copying
code out of here.

| Seam | Type | What it lets Pro do |
|---|---|---|
| `wcap_public_instance` | filter | Return a subclass of `Wc_Audio_Preview_Public` so ONE object renders previews |
| `wcap_preview_hook` | filter | Choose which WooCommerce action the preview renders on |
| `wcap_settings_nav_groups` | filter | Add tabs to the settings page this plugin owns |
| `wcap_settings_tab_content` | action | Render those tabs inside this plugin's page shell |

Helpers on the public class are `protected`, not `private`, so a subclass can use them instead of
growing its own copy. `get_asset_filename()` takes the directory to search - a subclass must pass
its own, because `__FILE__` is lexical.

---

## Done

- [x] **Shape-tolerant audio reader** (`includes/class-wcap-audio.php`). Five historical storage
      shapes are read without a migration, so an upgrade cannot lose a store's audio. 12 tests,
      one per shape.
- [x] **Settings page rebuilt on the house Pattern A shell** - sidebar, hash-routable `?tab=`,
      Lucide icons, cards. Free's Welcome and FAQ tabs plus the three Pro tabs shown in place,
      badged and locked, each listing what it does.
- [x] **The shell is shared with Pro byte for byte** and carries no translatable string; each
      plugin passes its own labels in its own text domain.
- [x] **Legacy admin removed** - 115 lines of hand-rolled tab strip and settings-sections, three
      partials of `wbcom-` markup and emoji headings, two option rows that never held a setting.
- [x] **Stopped standing down when Pro is active.** That was the core inversion: it left Pro with
      nothing to extend, which is why both plugins grew a copy of the same settings screen.
- [x] **One renderer** - `wcap_public_instance` plus `wcap_preview_hook`. Both plugins used to hook
      the product page and draw a player each; a store running Pro showed the preview twice.
- [x] **Asset handles are fixed strings**, not `$this->plugin_name`. With one shared object both
      plugins registered under the same handle and WordPress silently ignored the second.
- [x] **`phpcs.xml` pinned to the standard CI runs.** Without it a bare `composer lint` fell back
      to PEAR and reported hundreds of failures CI does not check.
- [x] **`audit/manifest.json`** - measured surface: 11 classes, 11 hooks fired, 4 options, 2 meta
      keys, 1 shortcode. Zero dead files, zero unreferenced classes.

## Pending

- [ ] **REST API.** The portfolio rule is three entry points per data store - frontend, admin, API
      - and this plugin has two. Audio previews are readable and writable only through the product
      editor. Sizing: one namespaced controller, capability-gated, mirroring what Document Preview
      already ships.
- [ ] **Block + shortcode parity.** One shortcode exists; there is no block, so a block-theme store
      cannot place a preview without shortcode syntax.
- [ ] **`readme.txt` changelog for 1.5.3** in the house action-prefix format. Nothing is written
      yet for the settings-page rebuild or the seam work.
- [ ] **Docs** under `docs/website/` - none exist. At minimum: getting started, the four seams
      above for developers, and what upgrading to Pro changes.
- [ ] **Big-site readiness.** No admin list view ships today, so the checklist is not yet engaged.
      It becomes due the moment a per-product listing is added - do not add one without pagination,
      an indexed `COUNT(*)`, and a filter.

---

## Architecture decision: the settings screen is a bundled library

`lib/wbcom-settings/` is **not this plugin's code**. It is a shared library, byte-identical in
every Wbcom plugin that ships it, and it is versioned:

- Each plugin registers its copy at include time via `wbcom_settings_register( version, file )`.
- On `plugins_loaded:-999` the **highest version wins** and is the only copy loaded.
- Every other copy stands down.

This is the pattern Action Scheduler and the EDD licensing SDK use, and it was chosen over the two
alternatives on purpose:

- **A shared toolkit plugin** would have to be installed, updated and version-matched by the site
  owner, and a product that breaks because a dependency was deactivated is worse than duplication
  on disk. The house rule is explicit that each plugin keeps its own files.
- **Copy-paste per plugin** is what this replaces. There was exactly one implementation and the
  next step was to copy it into a second product; at 100+ plugins that is 100 screens drifting
  apart, which is the failure this codebase has already had with detectors and renderers.

The practical consequence: a fix shipped in ANY plugin's release becomes the screen every other
Wbcom plugin on that site uses. Do not fork it. If a product needs something the screen cannot do,
add it to the library and bump `Wbcom_Settings_Page::VERSION`.

## Known gaps carried deliberately

- The Welcome and FAQ tabs are static content. They are the only surfaces here that could drift
  from what the product does; check them when a feature lands.
- `woo-product-audio-preview.php:154` uses a non-Yoda comparison. Pre-existing, unrelated to any
  current work, left alone rather than swept into an unrelated commit.
