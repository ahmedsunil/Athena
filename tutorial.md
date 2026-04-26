# Adding a New Module — Step-by-Step Tutorial

This guide walks through adding a **Post** module (create, read, update, delete) to this Laravel 13 + Livewire 4 application. Follow these steps in order and you'll have a fully working module that matches the existing architecture.

---

## 1. Create the Migration

```bash
php artisan make:migration create_posts_table
```

Edit `database/migrations/xxxx_create_posts_table.php`:

```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('body');
    $table->enum('status', ['draft', 'published'])->default('draft');
    $table->timestamp('published_at')->nullable();
    $table->timestamps();
});
```

```bash
php artisan migrate
```

---

## 2. Create the Model

`app/Models/Post.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Post extends Model
{
    use LogsActivity;

    protected $fillable = ['user_id', 'title', 'slug', 'body', 'status', 'published_at'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'status'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->setDescriptionForEvent(fn (string $eventName) => "Post {$eventName}");
    }

    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }
}
```

---

## 3. Add Permissions (optional but recommended)

In `database/seeders/RolesAndPermissionsSeeder.php`, add to the permissions array:

```php
// Posts
'posts.view',
'posts.create',
'posts.edit',
'posts.delete',
```

Re-run the seeder:

```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

---

## 4. Create the Livewire List Component

`app/Livewire/Posts/PostsList.php`:

```php
<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PostsList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public bool $confirmingDelete = false;
    public ?int $deletingId = null;

    protected $queryString = [
        'search'       => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sortField'    => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch(): void   { $this->resetPage(); }
    public function updatingStatusFilter(): void { $this->resetPage(); }

    public function sortBy(string $field): void
    {
        $this->sortField     = $this->sortField === $field ? $this->sortField : $field;
        $this->sortDirection = $this->sortField === $field && $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId       = $id;
        $this->confirmingDelete = true;
    }

    public function deletePost(): void
    {
        $post = Post::findOrFail($this->deletingId);
        activity()->causedBy(Auth::user())->performedOn($post)->log('deleted');
        $post->delete();
        $this->confirmingDelete = false;
        $this->deletingId       = null;
        $this->dispatch('toast', message: 'Post deleted.');
    }

    public function render()
    {
        $posts = Post::with('author')
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.posts.posts-list', compact('posts'))
            ->layout('layouts.app', ['title' => 'Posts']);
    }
}
```

---

## 5. Create the Livewire Form Component

`app/Livewire/Posts/PostForm.php`:

```php
<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class PostForm extends Component
{
    #[Locked]
    public ?int $postId = null;

    public string $title = '';
    public string $slug = '';
    public string $body = '';
    public string $status = 'draft';

    public function mount(?int $postId = null): void
    {
        if ($postId) {
            $post          = Post::findOrFail($postId);
            $this->postId  = $post->id;
            $this->title   = $post->title;
            $this->slug    = $post->slug;
            $this->body    = $post->body;
            $this->status  = $post->status;
        }
    }

    public function updatedTitle(string $value): void
    {
        if (! $this->postId) {
            $this->slug = Str::slug($value);
        }
    }

    public function save(): void
    {
        $this->validate([
            'title'  => ['required', 'string', 'max:255'],
            'slug'   => ['required', 'string', 'max:255', Rule::unique('posts', 'slug')->ignore($this->postId)],
            'body'   => ['required', 'string'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);

        $data = [
            'title'        => $this->title,
            'slug'         => $this->slug,
            'body'         => $this->body,
            'status'       => $this->status,
            'published_at' => $this->status === 'published' ? now() : null,
        ];

        if ($this->postId) {
            $post = Post::findOrFail($this->postId);
            $post->update($data);
            activity()->causedBy(Auth::user())->performedOn($post)->log('updated');
            $message = 'Post updated.';
        } else {
            $data['user_id'] = Auth::id();
            $post = Post::create($data);
            activity()->causedBy(Auth::user())->performedOn($post)->log('created');
            $message = 'Post created.';
        }

        $this->dispatch('toast', message: $message);
        $this->redirect(route('posts.index'), navigate: true);
    }

    public function render()
    {
        $title = $this->postId ? 'Edit Post' : 'New Post';
        return view('livewire.posts.post-form')
            ->layout('layouts.app', ['title' => $title]);
    }
}
```

---

## 6. Create the Views

### List view — `resources/views/livewire/posts/posts-list.blade.php`

```blade
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-base font-semibold text-zinc-950">Posts</h1>
            <p class="text-xs text-zinc-400">Manage your blog posts.</p>
        </div>
        <a href="{{ route('posts.create') }}" wire:navigate
           class="rounded-lg bg-zinc-950 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-zinc-800">
            + New post
        </a>
    </div>

    <div class="flex flex-wrap gap-2">
        <input type="text" wire:model.live.debounce.300ms="search"
               placeholder="Search posts…"
               class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950 sm:w-64">

        <select wire:model.live="statusFilter"
                class="rounded-lg border border-zinc-300 px-3 py-2 text-sm text-zinc-700 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            <option value="">All statuses</option>
            <option value="draft">Draft</option>
            <option value="published">Published</option>
        </select>
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-100 text-left">
                    <th class="px-4 py-3">
                        <button wire:click="sortBy('title')" class="flex items-center gap-1 text-xs font-medium text-zinc-500 hover:text-zinc-700">
                            Title
                            @if($sortField === 'title') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                        </button>
                    </th>
                    <th class="px-4 py-3 text-xs font-medium text-zinc-500">Author</th>
                    <th class="px-4 py-3 text-xs font-medium text-zinc-500">Status</th>
                    <th class="px-4 py-3">
                        <button wire:click="sortBy('created_at')" class="flex items-center gap-1 text-xs font-medium text-zinc-500 hover:text-zinc-700">
                            Created
                            @if($sortField === 'created_at') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                        </button>
                    </th>
                    <th class="px-4 py-3 text-xs font-medium text-zinc-500">Actions</th>
                </tr>
            </thead>
            <tbody class="admin-table-body">
                @forelse($posts as $post)
                    <tr>
                        <td class="px-4 py-3 font-medium text-zinc-950">{{ $post->title }}</td>
                        <td class="px-4 py-3 text-zinc-500">{{ $post->author->name }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $post->status === 'published' ? 'bg-zinc-950 text-white' : 'bg-zinc-100 text-zinc-600' }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-zinc-400">{{ $post->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('posts.edit', $post) }}" wire:navigate
                                   class="text-xs font-medium text-zinc-950 hover:text-zinc-800">Edit</a>
                                <button wire:click="confirmDelete({{ $post->id }})"
                                        class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-sm text-zinc-400">No posts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($posts->hasPages())
            <div class="border-t border-zinc-100 px-4 py-3">{{ $posts->links() }}</div>
        @endif
    </div>

    @if($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
            <div class="w-full max-w-sm rounded-xl bg-white p-6 shadow-xl">
                <h3 class="text-sm font-semibold text-zinc-950">Delete post?</h3>
                <p class="mt-1 text-xs text-zinc-500">This action cannot be undone.</p>
                <div class="mt-4 flex gap-2">
                    <button wire:click="deletePost"
                            class="flex-1 rounded-lg bg-red-600 py-2 text-xs font-semibold text-white hover:bg-red-700">Delete</button>
                    <button wire:click="$set('confirmingDelete', false)"
                            class="flex-1 rounded-lg border border-zinc-300 py-2 text-xs font-medium text-zinc-700 hover:bg-zinc-50">Cancel</button>
                </div>
            </div>
        </div>
    @endif
</div>
```

### Form view — `resources/views/livewire/posts/post-form.blade.php`

```blade
<div class="mx-auto max-w-3xl space-y-4">
    <div class="flex items-center gap-3">
        <a href="{{ route('posts.index') }}" wire:navigate
           class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-500 shadow-sm hover:bg-zinc-50 hover:text-zinc-950">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
        </a>
        <div>
            <h1 class="text-base font-semibold text-zinc-950">{{ $postId ? 'Edit post' : 'New post' }}</h1>
            <p class="text-xs text-zinc-500">Fill in the details below.</p>
        </div>
    </div>

    <form wire:submit="save" class="space-y-4">
        <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm space-y-4">
            <div>
                <label class="mb-1.5 block text-xs font-medium text-zinc-700">Title</label>
                <input type="text" wire:model.live="title"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 placeholder-zinc-400 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-medium text-zinc-700">Slug</label>
                <input type="text" wire:model="slug"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 font-mono text-sm text-zinc-950 placeholder-zinc-400 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('slug') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-medium text-zinc-700">Body</label>
                <textarea wire:model="body" rows="8"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 placeholder-zinc-400 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('body') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-medium text-zinc-700">Status</label>
                <div class="grid h-9 w-48 grid-cols-2 rounded-md border border-zinc-200 bg-zinc-100 p-0.5 shadow-sm">
                    <button type="button" wire:click="$set('status', 'draft')"
                            class="rounded-[5px] text-xs font-medium transition-colors {{ $status === 'draft' ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">
                        Draft
                    </button>
                    <button type="button" wire:click="$set('status', 'published')"
                            class="rounded-[5px] text-xs font-medium transition-colors {{ $status === 'published' ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">
                        Published
                    </button>
                </div>
                @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('posts.index') }}" wire:navigate
               class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 text-xs font-medium text-zinc-700 shadow-sm transition-colors hover:bg-zinc-50 hover:text-zinc-950">
                Cancel
            </a>
            <button type="submit"
                    class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-950 focus:ring-offset-2">
                {{ $postId ? 'Update post' : 'Create post' }}
            </button>
        </div>
    </form>
</div>
```

---

## 7. Register Routes

In `routes/web.php`, inside the `auth + verified` middleware group, add:

```php
use App\Livewire\Posts\PostForm;
use App\Livewire\Posts\PostsList;

// Posts
Route::get('/posts', PostsList::class)->name('posts.index');
Route::get('/posts/create', PostForm::class)->name('posts.create');
Route::get('/posts/{postId}/edit', PostForm::class)->name('posts.edit');
```

---

## 8. Add to the Sidebar

In `resources/views/layouts/partials/sidebar.blade.php`, add a new group (or item to an existing group):

```php
[
    'label' => 'Content',
    'items' => [
        [
            'label' => 'Posts',
            'route' => 'posts',
            'href'  => route('posts.index'),
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z"/>',
        ],
    ],
],
```

Also add `'posts' => 'Posts'` to the `$routeLabels` array in `resources/views/layouts/app.blade.php`.

---

## 9. (Optional) Factory & Seeder

```bash
php artisan make:factory PostFactory --model=Post
```

`database/factories/PostFactory.php`:

```php
public function definition(): array
{
    return [
        'user_id'      => \App\Models\User::factory(),
        'title'        => fake()->sentence(),
        'slug'         => fake()->unique()->slug(),
        'body'         => fake()->paragraphs(3, true),
        'status'       => fake()->randomElement(['draft', 'published']),
        'published_at' => fake()->optional()->dateTimeBetween('-1 year'),
    ];
}
```

In `DatabaseSeeder.php`:

```php
\App\Models\Post::factory(20)->create();
```

---

## Checklist

- [ ] Migration created and run
- [ ] Model created with fillable, casts, relationship, and activity log
- [ ] `PostsList` component — search, filter, sort, paginate, delete confirm
- [ ] `PostForm` component — create and edit, `#[Locked]` ID, validation, activity log
- [ ] List view and form view created
- [ ] Routes registered inside `auth + verified` middleware group
- [ ] Sidebar item + breadcrumb label added
- [ ] (Optional) Permissions seeded and `->role()` / `->hasPermissionTo()` gates applied
- [ ] (Optional) Factory and seeder for test data
