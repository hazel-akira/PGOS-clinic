# Fix for "Secure Connection Failed" Error

## Problem
You're getting `PR_END_OF_FILE_ERROR` when trying to access `127.0.0.1:8000:8000` (note the double port).

## Solution

### 1. Use HTTP (not HTTPS) for Local Development

The Laravel development server (`php artisan serve`) only serves HTTP, not HTTPS.

**Correct URL:**
```
http://127.0.0.1:8000
```
or
```
http://localhost:8000
```

**NOT:**
- ❌ `https://127.0.0.1:8000` (HTTPS won't work)
- ❌ `127.0.0.1:8000:8000` (double port)

### 2. Clear Browser HSTS Cache

If your browser has cached HTTPS redirects, you need to clear HSTS (HTTP Strict Transport Security):

**For Firefox:**
1. Go to `about:config`
2. Search for `security.tls.insecure_fallback_hosts`
3. Add `127.0.0.1` and `localhost` to the list
4. Or search for `domains` and clear HSTS data for `127.0.0.1`

**For Chrome/Edge:**
1. Go to `chrome://net-internals/#hsts`
2. Under "Delete domain security policies", enter `127.0.0.1`
3. Click "Delete"
4. Also delete `localhost` if needed

**For Safari:**
1. Go to Safari → Preferences → Privacy
2. Click "Manage Website Data"
3. Search for `127.0.0.1` and remove it

### 3. Verify Server is Running

Check if the server is running:
```bash
php artisan serve
```

You should see:
```
INFO  Server running on [http://127.0.0.1:8000]
```

### 4. Test the Connection

Open a new incognito/private window and try:
```
http://127.0.0.1:8000
```

### 5. Alternative: Use a Different Port

If port 8000 has issues, use a different port:
```bash
php artisan serve --port=8080
```

Then access:
```
http://127.0.0.1:8080
```

## For Production (HTTPS)

When deploying to production, you'll need:
1. A proper SSL certificate
2. HTTPS configured on your web server (Nginx/Apache)
3. Update `APP_URL` in `.env` to use `https://`

## Quick Fix Commands

```bash
# Stop any running server
pkill -f "php artisan serve"

# Start fresh
cd /home/eng-susan/PGOS-clinic
php artisan serve

# Then access: http://127.0.0.1:8000
```
