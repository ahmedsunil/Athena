<?php

namespace App\Livewire\Auditing;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class ActivityLogShow extends Component
{
    public Activity $activity;

    public function mount(int $activityId): void
    {
        $this->activity = Activity::with(['causer', 'subject'])->findOrFail($activityId);
    }

    public function changeRows(): array
    {
        $old = $this->oldAttributes();
        $new = $this->newAttributes();
        $fields = array_values(array_unique(array_merge(array_keys($old), array_keys($new))));

        return array_map(function (string $field) use ($old, $new): array {
            $hasOld = array_key_exists($field, $old);
            $hasNew = array_key_exists($field, $new);
            $oldValue = $hasOld ? $old[$field] : null;
            $newValue = $hasNew ? $new[$field] : null;

            return [
                'field' => $field,
                'old' => $hasOld ? $this->formatValue($field, $oldValue) : 'Not set',
                'new' => $hasNew ? $this->formatValue($field, $newValue) : 'Removed',
                'old_type' => $hasOld ? $this->valueType($oldValue) : 'missing',
                'new_type' => $hasNew ? $this->valueType($newValue) : 'missing',
                'status' => $this->changeStatus($hasOld, $hasNew, $oldValue, $newValue),
            ];
        }, $fields);
    }

    public function oldAttributes(): array
    {
        return $this->arrayValue(Arr::get($this->properties(), 'old', []));
    }

    public function newAttributes(): array
    {
        return $this->arrayValue(Arr::get($this->properties(), 'attributes', []));
    }

    public function extraProperties(): array
    {
        return Arr::except($this->properties(), ['old', 'attributes']);
    }

    public function rawAttributeChanges(): array
    {
        return $this->arrayValue($this->activity->getAttribute('attribute_changes'));
    }

    public function properties(): array
    {
        return $this->arrayValue($this->activity->properties);
    }

    public function formatValue(string $field, mixed $value): string
    {
        if ($this->isSensitiveField($field)) {
            return 'Hidden';
        }

        if ($value === null) {
            return 'null';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_array($value)) {
            return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?: '[]';
        }

        if ($value instanceof Collection) {
            return json_encode($value->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?: '[]';
        }

        if (is_object($value)) {
            if (method_exists($value, '__toString')) {
                return (string) $value;
            }

            return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?: class_basename($value);
        }

        return (string) $value;
    }

    public function valueType(mixed $value): string
    {
        if ($value === null) {
            return 'null';
        }

        if (is_object($value)) {
            return class_basename($value);
        }

        return gettype($value);
    }

    public function eventColor(): string
    {
        return match ($this->activity->event) {
            'created' => 'bg-emerald-100 text-emerald-700',
            'updated' => 'bg-blue-100 text-blue-700',
            'deleted' => 'bg-red-100 text-red-700',
            default => 'bg-zinc-100 text-zinc-600',
        };
    }

    public function statusColor(string $status): string
    {
        return match ($status) {
            'Added' => 'bg-emerald-100 text-emerald-700',
            'Changed' => 'bg-blue-100 text-blue-700',
            'Removed' => 'bg-red-100 text-red-700',
            default => 'bg-zinc-100 text-zinc-600',
        };
    }

    public function render()
    {
        return view('livewire.auditing.activity-log-show')
            ->layout('layouts.app', ['title' => 'Activity Details']);
    }

    private function changeStatus(bool $hasOld, bool $hasNew, mixed $oldValue, mixed $newValue): string
    {
        if (! $hasOld && $hasNew) {
            return 'Added';
        }

        if ($hasOld && ! $hasNew) {
            return 'Removed';
        }

        return $oldValue === $newValue ? 'Unchanged' : 'Changed';
    }

    private function isSensitiveField(string $field): bool
    {
        $field = strtolower($field);

        foreach (['password', 'secret', 'token', 'recovery_code', 'remember'] as $needle) {
            if (str_contains($field, $needle)) {
                return true;
            }
        }

        return false;
    }

    private function arrayValue(mixed $value): array
    {
        if ($value instanceof Collection) {
            return $value->toArray();
        }

        if (is_array($value)) {
            return $value;
        }

        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);

            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }
}
