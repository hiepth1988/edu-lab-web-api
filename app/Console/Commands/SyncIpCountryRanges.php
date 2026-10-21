<?php

namespace App\Console\Commands;

use App\Support\Tracking\GeoIp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

// Rebuilds ip_country_ranges from the five RIR delegation files (config tracking.rir_sources),
// which is what the tracking module uses to tell Vietnamese visitors from foreign ones.
// Free and key-less; country accuracy is that of the registrant, which is very good for VN.
// Scheduled monthly; safe to re-run — the table is replaced only after every source downloaded.
class SyncIpCountryRanges extends Command
{
    protected $signature = 'tracking:sync-ip-ranges';

    protected $description = 'Download RIR delegation files and rebuild the IP → country table used by tracking';

    public function handle(): int
    {
        $files = [];

        try {
            foreach (config('tracking.rir_sources') as $url) {
                $this->info("Downloading {$url}");
                $files[] = $tmp = tempnam(sys_get_temp_dir(), 'rir');

                $response = Http::timeout(300)->retry(2, 2000, throw: false)->sink($tmp)->get($url);

                if (! $response->successful()) {
                    $this->error("Failed ({$response->status()}) — table left unchanged.");

                    return self::FAILURE;
                }
            }

            // Parsed and inserted as a stream so memory stays flat (~400k ranges in total).
            $total = DB::transaction(function () use ($files) {
                DB::table('ip_country_ranges')->delete();

                $total = 0;
                foreach ($files as $file) {
                    $count = 0;
                    foreach ($this->chunks($file) as $chunk) {
                        DB::table('ip_country_ranges')->insert($chunk);
                        $count += count($chunk);
                    }
                    $this->line("  {$count} ranges");
                    $total += $count;
                }

                // Rolls the delete back if a registry served a truncated/empty file.
                if ($total < 100000) {
                    throw new \RuntimeException("Suspiciously few ranges ({$total}) — table left unchanged.");
                }

                return $total;
            });
        } catch (\RuntimeException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        } finally {
            array_map(fn ($file) => @unlink($file), $files);
        }

        $this->info("Imported {$total} ranges.");

        return self::SUCCESS;
    }

    // Line format: registry|cc|type|start|value|date|status[|extensions]
    // ipv4 value = number of addresses, ipv6 value = prefix length.
    private function chunks(string $file): \Generator
    {
        $handle = fopen($file, 'r');
        $rows = [];

        while (($line = fgets($handle)) !== false) {
            $parts = explode('|', trim($line));

            if (count($parts) < 7 || ! in_array($parts[6], ['allocated', 'assigned'], true) || ! preg_match('/^[A-Z]{2}$/', $parts[1])) {
                continue;
            }

            [, $country, $type, $start, $value] = $parts;

            if ($type === 'ipv4' && ($first = ip2long($start)) !== false) {
                $rows[] = [
                    'ip_version' => 4,
                    'range_start' => sprintf('%08x', $first),
                    'range_end' => sprintf('%08x', $first + (int) $value - 1),
                    'country' => $country,
                ];
            } elseif ($type === 'ipv6' && $hex = GeoIp::toHex($start)) {
                $rows[] = [
                    'ip_version' => 6,
                    'range_start' => $hex[1],
                    'range_end' => GeoIp::ipv6RangeEnd($hex[1], (int) $value),
                    'country' => $country,
                ];
            }

            if (count($rows) === 1000) {
                yield $rows;
                $rows = [];
            }
        }

        fclose($handle);

        if ($rows) {
            yield $rows;
        }
    }
}
