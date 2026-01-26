<?php

if (! function_exists('vite_assets')) {
    /**
     * Conditionally load Vite assets or fallback to basic assets
     *
     * This is a temporary workaround for systems without Node.js 20+
     * For production, ensure Node.js 20+ is installed and run: npm run build
     */
    function vite_assets(array $assets = ['resources/css/app.css', 'resources/js/app.js']): string
    {
        $manifestPath = public_path('build/manifest.json');

        if (file_exists($manifestPath)) {
            // Vite manifest exists, use @vite directive
            return '@vite('.json_encode($assets).')';
        }

        // Fallback: return basic script/style tags
        // Note: This is a minimal fallback. For full functionality, build assets with Node.js 20+
        $html = '';

        // Add basic Tailwind CSS from CDN as fallback
        $html .= '<script src="https://cdn.tailwindcss.com"></script>'."\n";

        // Add Alpine.js from CDN
        $html .= '<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>'."\n";

        return $html;
    }
}
