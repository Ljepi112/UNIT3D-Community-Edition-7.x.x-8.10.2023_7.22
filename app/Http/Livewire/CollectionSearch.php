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

namespace App\Http\Livewire;

use App\Models\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class CollectionSearch extends Component
{
    use WithPagination;

    public $search = '';

    final public function updatedPage(): void
    {
        $this->emit('paginationChanged');
    }

    final public function updatingSearch(): void
    {
        $this->resetPage();
    }

    final public function getCollectionsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Collection::withCount('movie')
            ->with('movie')
            ->when($this->search !== '', fn ($query) => $query->where('name', 'LIKE', '%'.$this->search.'%'))
            ->oldest('name')
            ->paginate(25);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.collection-search', [
            'collections' => $this->collections,
        ]);
    }
}
