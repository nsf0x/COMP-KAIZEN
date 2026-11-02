#!/usr/bin/env sh
set -eu

if command -v railway >/dev/null 2>&1; then
    railway_cmd() { railway "$@"; }
elif command -v npx >/dev/null 2>&1; then
    railway_cmd() { npx --yes @railway/cli "$@"; }
else
    echo "Install Node.js or the Railway CLI before deploying." >&2
    exit 1
fi

if ! railway_cmd whoami >/dev/null 2>&1; then
    railway_cmd login
fi

railway_cmd redeploy \
    --project 439d7a23-1439-4037-a94b-7c13758ce9f4 \
    --environment production \
    --service kaizen-web \
    --from-source \
    --yes \
    --json