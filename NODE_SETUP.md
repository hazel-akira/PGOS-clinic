# Node.js Setup for Vite Assets

## Current Issue

Your system has Node.js v12.22.9, but Vite 7 requires Node.js 20.19.0 or higher.

## Quick Fix (Temporary)

I've added a fallback in the Blade layouts that uses CDN assets when the Vite manifest is missing. This allows the application to run, but for production you should upgrade Node.js.

## Proper Solution: Upgrade Node.js

### Option 1: Using NVM (Recommended)

```bash
# Install NVM if not already installed
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash

# Reload shell
source ~/.bashrc

# Install Node.js 20 LTS
nvm install 20
nvm use 20

# Verify version
node --version  # Should show v20.x.x

# Build assets
cd /home/engineer/Desktop/school-clinic
npm run build
```

### Option 2: Using System Package Manager

```bash
# Ubuntu/Debian
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install -y nodejs

# Verify
node --version

# Build assets
cd /home/engineer/Desktop/school-clinic
npm run build
```

## After Upgrading Node.js

1. **Build assets for production:**
   ```bash
   npm run build
   ```

2. **Or run dev server (for development):**
   ```bash
   npm run dev
   ```

3. **The fallback will automatically be disabled** once `public/build/manifest.json` exists.

## Current Workaround

The application will work with CDN fallbacks, but:
- ⚠️ Slower page loads (CDN assets)
- ⚠️ No custom Tailwind configuration
- ⚠️ Not suitable for production

For production deployment, **upgrade Node.js and build assets properly**.
