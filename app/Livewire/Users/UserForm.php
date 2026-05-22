<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Locked;
use Livewire\Component;

class UserForm extends Component
{
    #[Locked]
    public ?int $userId = null;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public array $selectedRoles = ['user'];
    public bool $isActive = true;
    public string $roleSearch = '';

    public function mount(?int $userId = null): void
    {
        if ($userId) {
            $user = User::findOrFail($userId);
            $this->userId        = $user->id;
            $this->name          = $user->name;
            $this->email         = $user->email;
            $this->selectedRoles = $user->getRoleNames()->toArray();
            $this->isActive      = $user->is_active;
        }
    }

    protected function rules(): array
    {
        $passwordRules = $this->userId
            ? ['nullable', Password::defaults(), 'confirmed']
            : ['required', Password::defaults(), 'confirmed'];

        return [
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->userId)],
            'password'              => $passwordRules,
            'password_confirmation' => ['required_with:password'],
            'selectedRoles'         => ['required', 'array', 'min:1'],
            'selectedRoles.*'       => ['string', 'exists:roles,name'],
            'isActive'              => ['boolean'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'password_confirmation' => 'password confirmation',
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        $data = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'is_active' => $validated['isActive'],
        ];

        if ($validated['password']) {
            $data['password'] = Hash::make($validated['password']);
        }

        if ($this->userId) {
            $savedUser = User::findOrFail($this->userId);
            $savedUser->update($data);
            $savedUser->syncRoles($this->selectedRoles);
            activity()->causedBy(Auth::user())->performedOn($savedUser)->log('updated');
            $message = 'User updated successfully.';
        } else {
            $data['email_verified_at'] = now();
            $savedUser = User::create($data);
            $savedUser->syncRoles($this->selectedRoles);
            activity()->causedBy(Auth::user())->performedOn($savedUser)->log('created');
            $message = 'User created successfully.';
        }

        $this->dispatch('toast', message: $message);
        $this->redirect(route('users.index'), navigate: true);
    }

    public function clearRoles(): void
    {
        $this->selectedRoles = [];
    }

    public function render()
    {
        $title = $this->userId ? 'Edit User' : 'New User';
        $roles = \Spatie\Permission\Models\Role::orderBy('name')->pluck('name');

        if (trim($this->roleSearch) !== '') {
            $query = mb_strtolower(trim($this->roleSearch));
            $roles = $roles->filter(fn (string $role) => str_contains(mb_strtolower($role), $query))->values();
        }

        return view('livewire.users.user-form', compact('roles'))
            ->layout('layouts.app', ['title' => $title]);
    }
}
