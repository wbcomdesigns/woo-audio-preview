# Adding audio previews

Previews are configured per product, in the product editor.

## Steps

1. Go to **Products** and edit (or add) a product.
2. Scroll to the **Audio Preview Items** meta box (below the product data box).
3. The box shows three rows in the free version. For each row:
   - Enter an **Audio Name** (shown to customers, for example "Chorus Preview").
   - Set the **Audio URL** by clicking **Media Library** to upload/select a
     file, or by pasting a URL.
4. Leave any row empty to skip it.
5. Click **Update** (or Publish) to save.

The player appears on the product page right away, by default just before the
Add to Cart form.

## What gets saved

- A row is saved only when it has **both** a name and a URL.
- A row with a URL but no name is dropped, and the editor shows a notice telling
  you to add a name.
- A URL that fails validation (unsupported type, or an unrecognised link) is
  dropped, and the editor shows a notice naming the row and the reason.
- The **Clear** button next to a filled URL empties that row.

## Editing and removing

- To change a preview, edit its name or URL and save again.
- To remove a preview, clear both fields (or use Clear) and save. When no valid
  rows remain, the stored data for the product is removed.

## Multiple previews

Add up to three. When a product has more than one valid preview, the product
page shows them as a list under an "Audio Previews" heading, each with its own
player. For more than three previews per product, see the Pro add-on.
