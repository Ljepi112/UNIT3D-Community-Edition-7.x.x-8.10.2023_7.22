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

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @see \Tests\Todo\Unit\Http\Requests\StorePollTest
 */
class StoreGiftRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array<\Illuminate\Contracts\Validation\Rule|string>|string>
     */
    public function rules(Request $request): array
    {
        $user = $request->user();

        return [
            'receiver_username' => [
                'required',
                Rule::exists('users', 'username')->whereNot('username', $user->username),
            ],
            'cost' => [
                'required',
                'numeric',
                'min:1',
                'max:'.$user->seedbonus,
            ],
            'comment' => [
                'required',
                'string',
                'max:255'
            ],
        ];
    }
}
