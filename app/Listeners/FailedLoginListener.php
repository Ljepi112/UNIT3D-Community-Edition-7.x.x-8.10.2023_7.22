<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Listeners;

use App\Models\FailedLoginAttempt;
use App\Models\Group;
use App\Notifications\FailedLogin;
use Exception;
use Illuminate\Auth\Events\Failed;

class FailedLoginListener
{
    /**
     * Handle the event.
     *
     * @throws Exception
     */
    public function handle(Failed $event): void
    {
        $bannedGroup = cache()->rememberForever('banned_group', fn () => Group::where('slug', '=', 'banned')->pluck('id'));

        if ($event->user instanceof \App\Models\User
            && $event->user->group_id !== $bannedGroup[0]) {
            FailedLoginAttempt::record(
                $event->user,
                request()->input('username'),
                request()->ip()
            );

            $event->user->notify(new FailedLogin(
                request()->ip()
            ));
        }
    }
}
