#!/usr/bin/env bash
# QA fixtures for Audio Preview - idempotent. Personas + a product with a preview.
#   WP_PATH=~/Local\ Sites/woo/app/public bash bin/qa-fixtures.sh
set -euo pipefail
PLUGIN_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
CONFIG="${PLUGIN_DIR}/docs/qa/qa-config.json"
WP_PATH="${WP_PATH:-${PLUGIN_DIR%/wp-content/plugins/*}}"
[[ -f "${WP_PATH}/wp-load.php" ]] || { echo "no wp-load.php in ${WP_PATH} - set WP_PATH"; exit 1; }
wp() { command wp --path="${WP_PATH}" "$@" 2>/dev/null | grep -v '^Warning: PHP Startup' || true; }
python3 -c '
import json,sys
for p in json.load(open(sys.argv[1])).get("personas",[]):
    if p.get("login"): print("\t".join([p["login"],p.get("role") or "subscriber",p.get("email") or p["login"]+"@example.test"]))
' "${CONFIG}" | while IFS=$'\t' read -r login role email; do
  [[ -n "$(wp user get "${login}" --field=ID)" ]] && echo "  = ${login}" || { wp user create "${login}" "${email}" --role="${role}" --user_pass="qa-${login}-pass" >/dev/null; echo "  + ${login}"; }
done
wp eval '
$found = get_posts(array("post_type"=>"product","title"=>"QA-RFT Audio Product","post_status"=>"any","numberposts"=>1,"fields"=>"ids"));
$pid = $found ? $found[0] : (function(){ $p=new WC_Product_Simple(); $p->set_name("QA-RFT Audio Product"); $p->set_regular_price("20"); $p->set_status("publish"); return $p->save(); })();
$up = wp_upload_dir();
update_post_meta($pid, "wcap_audio", array("wcap_audio_names"=>array("QA-RFT preview"), "wcap_audio_urls"=>array(content_url("uploads/qa-rft-preview.mp3"))));
printf("  product #%d has an audio preview\n", $pid);
'
echo "fixtures ready"
