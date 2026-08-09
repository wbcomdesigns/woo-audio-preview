# Audio Preview (free + Pro) — plan and handoff

**Written 2026-08-09.** Covers both `woo-audio-preview` (branch 1.5.2) and
`woo-audio-preview-pro` (branch 2.2.1). Boards: Audio Preview for WooCommerce `42374833`,
Woo Audio Preview Pro `37557769`.

Agreed order: **settings contract → admin panel → remove legacy admin code.**

---

## Done already

**Shape-tolerant audio reader** — `includes/class-wcap-audio.php`, committed `83c29d0`, with 12
tests in `tests/HistoricalShapesTest.php`.

This was not preventative. Measured against seeded products, **four of six historical shapes
returned zero files** with the old reader and their audio never appeared on the storefront while
the data sat in the database:

| Shape | Before | After |
|---|---|---|
| pre-1.2.0 singular `wcap_audio_url` | 0 | 1 |
| loose keys containing `url` | 0 | 1 |
| bare URL string | 0 | 1 |
| URL saved with a blank name | 0 | 1 |
| 1.2.0+ plural | 1 | 1 |
| 1.3.0 plural + display toggle | 1 | 1 |

The shape list came from the **git history of both plugins**, not the current code — the current
code only knows the newest shape, which is exactly how the older ones came to be dropped. At one
point the plugin wrote `$_POST['wcap_audio']` straight into the meta, so the stored shape was
literally whatever the form fields were named that release.

Two sub-keys a cleanup would delete as dead, both proven live first:
- **`wcap_display_audio_players`** — nothing has read it since 1.4.0, but Pro's Dokan dashboard
  still renders the checkbox (`dokan/dokan-woo-audio-preview-pro.php:176`), so vendors set it
  today and it does nothing. That is a bug to fix, not code to delete.
- **`wcap_preview_attachment`** — the old upload mechanism. Current code only writes `''`, but
  older installs hold real values. `WCAP_Audio::save()` preserves sub-keys it does not own.

**Still open on the data-safety card:** point Pro's reader at `WCAP_Audio`; make the v2 migration
batch, resume, and only set its version flag when every row converted; surface an owner-visible
count of unreadable products.

---

## 1. Settings contract (do first)

**Six settings are saved and read by nothing.** The admin writes `autoplay`, `default_volume`,
`loop_playback`, `player_width`, `preload_strategy`, `show_time_display`; grepping `public/` for
those keys returns nothing. The only settings the player reads are the watermark ones.

That single defect sits under **four existing Pro bug cards** — Multiple Player Settings, Advanced
Player Settings, Player Theme, Preview Duration. They are not four bugs. Fixing them one screen at
a time leaves the next setting anyone adds broken the same way.

**Build:** one settings reader with documented defaults; the player consumes that object; every
control on the screen maps to something the player reads, or the control comes off the screen.
**A test per setting** — set it, render, assert the output changed.

**Also found by `wp-contract-audit`** (run it, it is fast):
```bash
php ~/.claude/skills/wp-contract-audit/scripts/contract-audit.php \
    "$HOME/dev/repos/woo-audio-preview" --pair="$HOME/dev/repos/woo-audio-preview-pro"
```
- **ERROR** `wcap_pro_v2_notice_dismissed` is read but never written, and its dismiss AJAX handler
  has no caller — the V2 upgrade notice can never be dismissed.
- **WARNING** dead CSS hiding `.wcap-pro-player-cl`, a class no markup emits (WCFM + WC Vendors).

The audit works at option level, so the six **sub-key** settings are invisible to it. Confirm those
with the `wppqa_audit` MCP tool per the skill, then promote each to a cert oracle.

---

## 2. Admin panel — build to the rulebook, not to Document Preview

**Standard:** `~/.claude/skills/wp-plugin-development/references/admin-ux-rulebook.md`.
**Reference implementation:** WB Listora (`~/dev/repos/wb-listora` + `-pro`), which uses
`wb_listora_settings_nav_groups` / `wb_listora_settings_tab_content`.

Do **not** copy Document Preview's panel as-is — it invented its own conventions and is itself
being retrofitted (see that plugin's `docs/ADMIN-UX-RETROFIT-PLAN.md`).

Today: Pro registers 4 menu pages, free registers 0. So this is **build** on the free side and
**collapse** on Pro's.

What the live Pro screen (`page=wcap-pro-settings`) gets wrong:
- opens on a settings form, never answering "is this working"
- horizontal pill tabs instead of a sidebar rendered from a registry
- arbitrary nesting — Product Page Position / Player Theme / Preview Duration / Player Title each
  in their own sub-card, while Enable Audio Preview and Show on Shop Pages are not
- different idiom: blue accent bars, all-caps SAVE SETTINGS
- **it shows Player Theme and Preview Duration, both of which do nothing** — which is why the
  settings contract comes first

Target: free owns the panel; Overview first (products with audio, files attached, files the player
cannot read — `WCAP_Audio` already computes the last one); Lucide icons; hash routing; Pro adds
screens through the free plugin's filters and registers **no menu page**; `page=wcap-pro-settings`
redirects; admin colours from the WordPress admin palette.

---

## 3. Remove legacy admin code

The old tabbed form, its partials, and its registrations come out **in the same change** — not
left alongside the new panel. Two code paths for one screen is how the next person edits the wrong
one. Document Preview made that mistake; do not repeat it here.

---

## Other open cards worth knowing before you start

- **Installing Pro silently switches free off.** Reproduced: both active → one `admin_init` → free
  inactive. `wcap_pro_check_free_plugin_is_active()` does it, and Pro does not require free, so
  they are a fork. **One commit from a fatal**: the moment Pro requires free, the two guards switch
  each other off and the store ends up with neither active. Fix order: render seam first, then
  remove the deactivation, then add the dependency.
- **Frontend usability** — the `<audio>` element ships with **no `controls`** and 13 pieces of
  custom markup around it, so if the player JS fails the shopper gets nothing. **Zero keyboard
  handlers.** No `role=`, no screen-reader text, no reduced-motion, no transcript affordance.
- **Promoting Pro** — free already has a good Free vs Pro table, but it advertises Watermark,
  Player Themes and Preview Duration, all of which are on Pro's bug board as not working. Fix Pro
  before driving more traffic at that table.

## Verify by looking

Screenshots, not DOM assertions, at 1440 / 782 / 390. In Document Preview a black-on-black panel
and a clipped badge both passed every measurement and were only visible in a screenshot.
