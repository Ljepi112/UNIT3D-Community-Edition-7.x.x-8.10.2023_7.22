<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PrivateMessage;
use App\Models\User;
use App\Services\Unit3dAnnounce;
use Illuminate\Http\Request;

class PasskeyController extends Controller
{
    /**
     * Update user passkey.
     */
    protected function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        abort_unless($request->user()->is($user) || $request->user()->group->is_modo, 403);

        $changedByStaff = $request->user()->isNot($user);

        abort_if($changedByStaff && ! $request->user()->group->is_owner && $request->user()->group->level <= $user->group->level, 403);

        $user->update([
            'passkey' => md5(random_bytes(60).$user->password)
        ]);

        if ($changedByStaff) {
            PrivateMessage::create([
                'sender_id'   => 1,
                'receiver_id' => $user->id,
                'subject'     => 'ATTENTION - Your passkey has been reset',
                'message'     => "Your passkey has been reset by staff. You will need to update your passkey in all your torrent clients to continue seeding.\n\nFor more information, please create a helpdesk ticket.\n\n[color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]",
            ]);
        }

        cache()->forget('user:'.$user->passkey);

        Unit3dAnnounce::removeUser($user);
        Unit3dAnnounce::addUser($user);

        return to_route('users.passkey.edit', ['user' => $user])
            ->withSuccess('Your passkey was changed successfully.');
    }

    /**
     * Edit user passkey.
     */
    public function edit(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->is($user) || $request->user()->group->is_modo, 403);

        return view('user.passkey.edit', ['user' => $user]);
    }
}
