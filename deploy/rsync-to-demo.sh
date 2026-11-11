#!/usr/bin/env bash
# Sync code to demo host. NEVER transfers .env or secrets.
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
REMOTE="${DEMO_REMOTE:-root@13.140.134.114}"
DEST="${DEMO_DEST:-/opt/dc-church}"

EXCLUDES=(
  --exclude '.git/'
  --exclude '.env'
  --exclude '.env.*'
  --exclude '.env2'
  --exclude '.DS_Store'
  --exclude 'node_modules/'
  --exclude 'storage/logs/'
  --exclude 'storage/framework/cache/'
  --exclude 'storage/framework/sessions/'
  --exclude 'storage/framework/views/'
  --exclude 'deploy/compose.env'
  --exclude 'deploy/.env.production'
  --exclude 'deploy/church_dc.sql'
  --exclude 'deploy/rsync-to-demo.sh'
)

# Safety: refuse if .env would be included
if rsync -an --out-format='%n' "${EXCLUDES[@]}" "$ROOT/" "$REMOTE:$DEST/" 2>/dev/null | grep -E '(^|/)\.env($|\.)' >/dev/null; then
  echo "REFUSING: dry-run listed a .env file. Aborting." >&2
  exit 1
fi

echo "Deploying $ROOT -> $REMOTE:$DEST (excluding .env)"
rsync -az --delete "${EXCLUDES[@]}" \
  -e "${RSYNC_RSH:-ssh}" \
  "$ROOT/" "$REMOTE:$DEST/"

# Never overwrite .env contents. Ensure php-fpm (www-data) can read it,
# and APP_INSTALLED stays true (false triggers the install wizard).
ssh "$REMOTE" "bash -s" <<EOF
set -e
ENV='$DEST/.env'
if [[ ! -f "\$ENV" ]]; then
  echo "WARN: \$ENV missing on server" >&2
  exit 0
fi
chgrp www-data "\$ENV"
chmod 640 "\$ENV"
if grep -q '^APP_INSTALLED=' "\$ENV"; then
  sed -i 's/^APP_INSTALLED=.*/APP_INSTALLED=true/' "\$ENV"
else
  echo 'APP_INSTALLED=true' >> "\$ENV"
fi
grep '^APP_INSTALLED=' "\$ENV"
EOF

echo "Done. Server .env was not transferred."
