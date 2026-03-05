<?php

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Role;

new #[Lazy]
class extends Component {

    public array $roleList = [];
    #[Modelable]
    public ?string $roleToSearch = '';
    public int $visibleCount = 4;
    public int $step = 4;

    public function mount(): void
    {
        if (empty($this->roleList)) {
            $this->roleList = Role::all()
                ->pluck('name')
                ->map(fn($role) => ucfirst($role))
                ->toArray();
        }
        $this->resetVisibleCount();
    }

    public function showMore(): void
    {
        $this->visibleCount += $this->step;
    }

    public function resetVisibleCount(): void
    {
        $this->visibleCount = 4;
    }

    public function updatedRoleToSearch(): void
    {
        $this->visibleCount = 4;
    }

    public function setRoleToSearch()
    {
        if (empty($this->roleToSearch)) {
            return;
        }

        $filtered = $this->getFilteredRoleListProperty();

        if (empty($filtered)) {
            return;
        }

        $this->roleToSearch = $filtered[0];
    }

    public function getFilteredRoleListProperty(): array
    {
        if (empty($this->roleToSearch)) {
            return $this->roleList;
        }

        return array_values(array_filter(
            $this->roleList,
            fn($role) => str_contains(strtolower($role), strtolower($this->roleToSearch))
        ));
    }

    #[On('set-role')]
    public function setRole(string $role): void
    {
        $this->roleToSearch = $role;
    }
};
?>

<x-filters.filter-subsection
        title="Role"
>
    <x-form.input-container>
        <x-dropdown
                align="left"
                width="80"
                content-classes="flex flex-col p-4 gap-2 bg-white font-medium"
        >
            <x-slot:trigger>
                <x-input
                        wire:model.live="roleToSearch"
                        wire:keydown.enter="setRoleToSearch"
                        @keydown.enter="open = false"
                        right-icon="user"
                        placeholder="Search the role you want"
                />
            </x-slot:trigger>

            <x-slot:content>
                @php $filtered = $this->filteredRoleList  @endphp
                @forelse(array_slice($filtered, 0, $visibleCount) as $role)
                    <span
                            id="{{ strtolower($role) }}"
                            class="btn-white rounded-md py-4 px-2 cursor-pointer"
                            wire:click="$dispatch('set-role', { role: '{{ $role }}' })"
                            @click.stop
                            @click="open = false"
                    >
                                    {{ $role }}
                    </span>
                @empty
                    <p class="text-sm text-primary-grey px-2">No roles found</p>
                @endforelse

                @if(count($filtered) > $visibleCount)
                    <button
                            wire:click="showMore"
                            @click.stop
                            class="text-xs text-primary-grey hover:text-primary cursor-pointer transition-colors mt-1 px-2"
                    >
                        Show more ({{ count($filtered) - $visibleCount }} remaining)
                    </button>
                @endif
            </x-slot:content>
        </x-dropdown>
    </x-form.input-container>
</x-filters.filter-subsection>