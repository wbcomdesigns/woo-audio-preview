# Role Matrix - Audio Preview for WooCommerce (v1.5.4)

No custom capabilities. The player is public (anyone viewing a product can play
the preview). Configuration is the store owner's.

| Surface | Anon shopper | Customer | Administrator |
|---|---|---|---|
| Audio player on the product page | R (play) | R (play) | R (play) |
| Audio player on the shop/archive (Pro badge) | R | R | R |
| Product Audio Preview metabox (add/edit URLs) | - | - | R/W (`manage_options`, product edit) |
| `wcap_delete_audio_ajax` | - | - | R/W (`manage_options`, nonce) |
| Settings (WB Plugins > Audio Preview) | - | - | R/W (`manage_options`) |
