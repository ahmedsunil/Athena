#!/usr/bin/env bash
set -euo pipefail

# ─── colours ───────────────────────────────────────────────────────────────────
GREEN='\033[0;32m'; YELLOW='\033[1;33m'; RED='\033[0;31m'; RESET='\033[0m'
info()    { echo -e "${GREEN}▶${RESET}  $*"; }
warn()    { echo -e "${YELLOW}⚠${RESET}  $*"; }
success() { echo -e "${GREEN}✔${RESET}  $*"; }
die()     { echo -e "${RED}✖${RESET}  $*" >&2; exit 1; }

SKELETON_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# ─── project name ──────────────────────────────────────────────────────────────
if [[ -n "${1:-}" ]]; then
    PROJECT_NAME="$1"
else
    read -rp "Project name: " PROJECT_NAME
fi

[[ -z "$PROJECT_NAME" ]] && die "Project name cannot be empty."
[[ -d "$PROJECT_NAME" ]] && die "Directory '$PROJECT_NAME' already exists."

# ─── check tools ───────────────────────────────────────────────────────────────
command -v composer &>/dev/null || die "composer not found."
command -v php      &>/dev/null || die "php not found."
command -v npm      &>/dev/null || die "npm not found."

# ─── 1. fresh laravel install ──────────────────────────────────────────────────
info "Creating Laravel project: $PROJECT_NAME"
composer create-project laravel/laravel "$PROJECT_NAME" --no-interaction --quiet
success "Laravel installed."

cd "$PROJECT_NAME"
APP_DIR="$(pwd)"

# ─── 2. require packages ───────────────────────────────────────────────────────
info "Installing packages: fortify, jenssegers/agent, livewire"
composer require laravel/fortify jenssegers/agent livewire/livewire --no-interaction --quiet
success "Composer packages installed."

# ─── 3. copy skeleton files ────────────────────────────────────────────────────
info "Copying starter kit files..."

# app — overwrite entirely (Livewire components + Models + Providers)
cp -r "$SKELETON_DIR/app/Livewire"   "$APP_DIR/app/"
cp -r "$SKELETON_DIR/app/Models/."   "$APP_DIR/app/Models/"
cp -r "$SKELETON_DIR/app/Providers/." "$APP_DIR/app/Providers/"

# config — add fortify.php alongside existing configs
cp "$SKELETON_DIR/config/fortify.php" "$APP_DIR/config/fortify.php"

# database — overwrite the default users table migration with our extended one
cp "$SKELETON_DIR/database/migrations/"* "$APP_DIR/database/migrations/"

# routes — replace the default web.php
cp "$SKELETON_DIR/routes/web.php" "$APP_DIR/routes/web.php"

# bootstrap — register providers (replaces the default providers.php)
cp "$SKELETON_DIR/bootstrap/providers.php" "$APP_DIR/bootstrap/providers.php"

# views — auth wrappers + livewire views + layouts
mkdir -p "$APP_DIR/resources/views/auth"
mkdir -p "$APP_DIR/resources/views/livewire/auth"
mkdir -p "$APP_DIR/resources/views/livewire/profile"
mkdir -p "$APP_DIR/resources/views/livewire/users"
mkdir -p "$APP_DIR/resources/views/layouts/partials"

cp "$SKELETON_DIR/resources/views/auth/."*                  "$APP_DIR/resources/views/auth/"     2>/dev/null || true
cp "$SKELETON_DIR/resources/views/auth/"*.php               "$APP_DIR/resources/views/auth/"     2>/dev/null || true
cp "$SKELETON_DIR/resources/views/auth/"*.blade.php         "$APP_DIR/resources/views/auth/"
cp "$SKELETON_DIR/resources/views/livewire/auth/"*          "$APP_DIR/resources/views/livewire/auth/"
cp "$SKELETON_DIR/resources/views/livewire/profile/"*       "$APP_DIR/resources/views/livewire/profile/"
cp "$SKELETON_DIR/resources/views/livewire/users/"*         "$APP_DIR/resources/views/livewire/users/"
cp "$SKELETON_DIR/resources/views/livewire/dashboard.blade.php" "$APP_DIR/resources/views/livewire/"
cp "$SKELETON_DIR/resources/views/layouts/app.blade.php"    "$APP_DIR/resources/views/layouts/"
cp "$SKELETON_DIR/resources/views/layouts/guest.blade.php"  "$APP_DIR/resources/views/layouts/"
cp "$SKELETON_DIR/resources/views/layouts/partials/sidebar.blade.php" "$APP_DIR/resources/views/layouts/partials/"

# design system
cp -r "$SKELETON_DIR/resources/admin-system" "$APP_DIR/resources/"

success "Files copied."

# ─── 4. alpine.js ──────────────────────────────────────────────────────────────
info "Installing Alpine.js..."
npm install alpinejs --save --silent

# Prepend Alpine bootstrap to resources/js/app.js
APP_JS="$APP_DIR/resources/js/app.js"
ALPINE_BOOT="import Alpine from 'alpinejs';\nwindow.Alpine = Alpine;\nAlpine.start();\n"
echo -e "${ALPINE_BOOT}$(cat "$APP_JS")" > "$APP_JS"
success "Alpine.js installed and wired."

# ─── 5. .env tweaks ────────────────────────────────────────────────────────────
info "Configuring .env..."

# SESSION_DRIVER=database (required for browser sessions)
sed -i.bak 's/^SESSION_DRIVER=.*/SESSION_DRIVER=database/' .env && rm -f .env.bak

# Also patch .env.example so future copies are correct
sed -i.bak 's/^SESSION_DRIVER=.*/SESSION_DRIVER=database/' .env.example && rm -f .env.example.bak

success ".env updated (SESSION_DRIVER=database)."

# ─── 6. generate app key ───────────────────────────────────────────────────────
info "Generating application key..."
php artisan key:generate --quiet
success "App key set."

# ─── 7. migrate? ───────────────────────────────────────────────────────────────
echo ""
read -rp "Run migrations now? Requires a configured DB in .env [y/N]: " RUN_MIGRATE
if [[ "${RUN_MIGRATE,,}" == "y" ]]; then
    php artisan migrate --force && success "Migrations complete."
else
    warn "Skipped. Run 'php artisan migrate' after configuring your DB in .env."
fi

# ─── 8. npm build? ─────────────────────────────────────────────────────────────
echo ""
read -rp "Build frontend assets now? [y/N]: " RUN_BUILD
if [[ "${RUN_BUILD,,}" == "y" ]]; then
    info "Building assets..."
    npm run build --silent && success "Assets built."
else
    warn "Skipped. Run 'npm run build' (or 'npm run dev') when ready."
fi

# ─── done ──────────────────────────────────────────────────────────────────────
echo ""
echo -e "${GREEN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${RESET}"
success "Project ready at: $APP_DIR"
echo ""
echo "  Next steps:"
echo "    1. Edit .env  — set DB_DATABASE, DB_USERNAME, DB_PASSWORD"
echo "    2. php artisan migrate  (if you skipped it)"
echo "    3. php artisan serve   (or use Herd / Valet)"
echo ""
echo "  Create the first admin:"
echo "    php artisan tinker"
echo "    App\\Models\\User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>bcrypt('password'),'role'=>'admin']);"
echo -e "${GREEN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${RESET}"
