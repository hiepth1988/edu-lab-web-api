<?php

namespace App\Http\Controllers\Api\Admin\Concerns;

use Illuminate\Auth\Access\AuthorizationException;

trait EnforcesPublishPermission
{
    /**
     * Editors can save drafts but only users with `content.publish` may set status to published.
     */
    protected function ensureCanSetStatus(string $status): void
    {
        if ($status === 'published' && ! auth()->user()?->can('content.publish')) {
            throw new AuthorizationException('You do not have permission to publish content.');
        }
    }
}
