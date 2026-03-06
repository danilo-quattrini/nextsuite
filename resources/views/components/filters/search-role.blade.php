<?php

use App\Domain\Role\Services\RoleService;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Role;

new #[Lazy]
class extends Component {

    public array $roleList = [];
    public ?string $roleToSearch = '';
    public int $visibleCount = 3;
    public int $step = 3;

    public function mount(): void
    {
        $this->roleList = RoleService::getAllRoleNames();
        $this->resetVisibleCount();
    }

    public function showMore(): void
    {
        $this->visibleCount += $this->step;
    }

    public function showLess(): void
    {
        $this->visibleCount = 3;
    }

    public function resetVisibleCount(): void
    {
        $this->visibleCount = 3;
    }

    public function updatedRoleToSearch(): void
    {
        $this->visibleCount = 3;
        $this->dispatch('role-updated', role: $this->roleToSearch);
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
        $this->dispatch('role-updated', role: $this->roleToSearch);
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
        $this->dispatch('role-updated', role: $this->roleToSearch);
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
                content-classes="flex flex-col p-4 gap-2 bg-white font-medium text-sm"
        >
            <x-slot:trigger>
                <x-input
                        wire:model.live="roleToSearch"
                        wire:keydown.enter="setRoleToSearch"
                        @keydown.enter="open = false"
                        right-icon="magnifying-glass"
                        size="xl"
                        placeholder="Search the role you want"
                />
            </x-slot:trigger>

            <x-slot:content>
                @php $filtered = $this->filteredRoleList  @endphp
                @forelse(array_slice($filtered, 0, $visibleCount) as $role)
                    <span
                            id="{{ strtolower($role) }}"
                            class="btn-white rounded-md p-3 cursor-pointer"
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
                            wire:transition
                            @click.stop
                            class="text-xs text-primary-grey hover:text-primary cursor-pointer transition-colors mt-1 px-2"
                    >
                        Show more ({{ count($filtered) - $visibleCount }} remaining)
                    </button>
                @elseif(count($filtered)  !== 1)
                    <button
                            wire:click.preserve-scroll="showLess"
                            wire:transition
                            @click.stop
                            class="text-xs text-primary-grey hover:text-primary cursor-pointer transition-colors mt-1 px-2"
                    >
                        Show less
                    </button>
                @endif
            </x-slot:content>
        </x-dropdown>
    </x-form.input-container>
</x-filters.filter-subsection>