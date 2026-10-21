<?php

namespace App\Support\Tracking;

use App\Models\IpCountryRange;
use Illuminate\Support\Facades\Cache;

// IP → ISO country lookup against the ip_country_ranges table (built from the public RIR
// delegation files by `tracking:sync-ip-ranges`). Country-level only, no external service.
class GeoIp
{
    public function country(?string $ip): ?string
    {
        $address = $ip ? self::toHex($ip) : null;

        if (! $address || ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return null;
        }

        [$version, $hex] = $address;

        // Cached as '' for "not found" because Cache::remember never stores null.
        $country = Cache::remember('geoip:'.sha1($hex), 86400, function () use ($version, $hex) {
            $range = IpCountryRange::where('ip_version', $version)
                ->where('range_start', '<=', $hex)
                ->orderByDesc('range_start')
                ->first();

            return $range && strcmp($range->range_end, $hex) >= 0 ? $range->country : '';
        });

        return $country ?: null;
    }

    /**
     * @return array{0: int, 1: string}|null  [ip version, fixed-width lowercase hex]
     */
    public static function toHex(string $ip): ?array
    {
        $packed = @inet_pton($ip);

        if ($packed === false) {
            return null;
        }

        // IPv4-mapped IPv6 (::ffff:a.b.c.d) is looked up as plain IPv4.
        if (strlen($packed) === 16 && str_starts_with($packed, str_repeat("\0", 10)."\xff\xff")) {
            $packed = substr($packed, 12);
        }

        return [strlen($packed) === 4 ? 4 : 6, bin2hex($packed)];
    }

    /**
     * Last address of an IPv6 block given its first address (32 hex chars) and prefix length.
     */
    public static function ipv6RangeEnd(string $startHex, int $prefix): string
    {
        $bytes = hex2bin($startHex);

        for ($i = 0; $i < 16; $i++) {
            $hostBits = max(0, min(8, ($i + 1) * 8 - $prefix));
            $bytes[$i] = chr(ord($bytes[$i]) | ((1 << $hostBits) - 1));
        }

        return bin2hex($bytes);
    }
}
