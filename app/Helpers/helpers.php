<?php

if (!function_exists('azure_url')) {
  
    function azure_url(?string $path, ?string $fallback = null): string
    {
        if (empty($path)) {
            return $fallback ?? asset('assets/images/placeholder_image.svg');
        }
        $baseUrl = rtrim(config('filesystems.disks.azure.base_url'), '/');
        return $baseUrl . '/' . ltrim($path, '/');
    }
}
