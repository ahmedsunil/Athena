<?php

namespace App\Livewire\Users;

use App\Models\User;
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
    public string $passwordConfirmation = '';
    public string $role = 'user';
    public bool $isActive = true;

    public function mount(?int $userId = null): void
    {
        if ($userId) {
            $user = User::findOrFail($userId);
            $this->userId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role;
            $this->isActive = $user->is_active;
        }
    }

    protected function rules(): array
    {
        $passwordRules = $this->userId
            ? ['nullable', Password::defaults(), 'confirmed']
            : ['required', Password::defaults(), 'confirmed'];

        return [
            'name'                 => ['required', 'string', 'max:255'],
            'email'                => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->userId)],
            'password'             => $passwordRules,
            'passwordConfirmation' => ['required_with:password'],
            'role'                 => ['required', 'in:user,admin'],
            'isActive'             => ['boolean'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        $data = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'role'      => $validated['role'],
            'is_active' => $validated['isActive'],
        ];

        if ($validated['password']) {
            $data['password'] = Hash::make($validated['password']);
        }

        if ($this->userId) {
            User::findOrFail($this->userId)->update($data);
            $message = 'User updated successfully.';
        } else {
            User::create($data);
            $message = 'User created successfully.';
        }

        $this->dispatch('notify', message: $message);
        $this->redirect(route('users.index'), navigate: true);
    }

    public function render()
    {
        $title = $this->userId ? 'Edit User' : 'New User';

        return view('livewire.users.user-form')
            ->layout('layouts.app', ['title' => $title]);
    }
}
