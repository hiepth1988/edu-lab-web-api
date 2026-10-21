<?php

namespace App\Support\Tracking;

use Illuminate\Http\Request;

// Decides whether a visitor is Vietnamese ("vn") or foreign, strongest signal first:
//   1. CF-IPCountry header (only when config tracking.trust_cloudflare is on)
//   2. IP lookup in ip_country_ranges
//   3. Browser timezone (Asia/Ho_Chi_Minh → vn, any other zone → foreign)
//   4. Browser language (vi* → vn)
// geo_source records which one won, so the admin can see how reliable the split is.
class VisitorClassifier
{
    public function __construct(private GeoIp $geoIp) {}

    /**
     * @return array{visitor_type: string, country: ?string, geo_source: ?string}
     */
    public function classify(Request $request, ?string $timezone, ?string $language): array
    {
        $home = config('tracking.home_country');

        $country = null;
        $source = null;

        if (config('tracking.trust_cloudflare')) {
            $header = strtoupper((string) $request->header('CF-IPCountry'));
            // XX = unknown, T1 = Tor exit node.
            if (preg_match('/^[A-Z]{2}$/', $header) && ! in_array($header, ['XX', 'T1'], true)) {
                [$country, $source] = [$header, 'cloudflare'];
            }
        }

        if (! $country && $country = $this->geoIp->country($request->ip())) {
            $source = 'ip';
        }

        if ($country) {
            return ['visitor_type' => $country === $home ? 'vn' : 'foreign', 'country' => $country, 'geo_source' => $source];
        }

        if ($timezone) {
            $isHome = in_array($timezone, config('tracking.home_timezones'), true);

            return ['visitor_type' => $isHome ? 'vn' : 'foreign', 'country' => null, 'geo_source' => 'timezone'];
        }

        if ($language && str_starts_with(strtolower($language), 'vi')) {
            return ['visitor_type' => 'vn', 'country' => null, 'geo_source' => 'language'];
        }

        return ['visitor_type' => 'unknown', 'country' => null, 'geo_source' => null];
    }
}
