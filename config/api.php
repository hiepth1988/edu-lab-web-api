<?php

return [

    // IPs that skip the public API rate limit. The Nuxt front end renders pages on the
    // server and calls this API from the server's own IP, so every visitor (and Googlebot)
    // shares that IP. Throttling it turns busy moments into 404 pages.
    // Comma-separated, e.g. "127.0.0.1,::1,103.216.119.46".
    'unthrottled_ips' => array_values(array_filter(array_map('trim', explode(',', (string) env('API_UNTHROTTLED_IPS', '127.0.0.1,::1'))))),

];
