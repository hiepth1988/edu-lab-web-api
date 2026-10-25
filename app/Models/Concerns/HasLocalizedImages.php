<?php

namespace App\Models\Concerns;

// Shared image columns can have an optional per-locale override, e.g. featured_image_en.
// The default locale (vi) always uses the base column.
trait HasLocalizedImages
{
    public function imageFor(string $column, string $locale): ?string
    {
        $override = $locale !== 'vi' ? $this->getAttribute("{$column}_{$locale}") : null;

        return $override ?: $this->getAttribute($column);
    }
}
