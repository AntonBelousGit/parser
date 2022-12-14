<?php

declare(strict_types=1);

namespace App\Traits\Historical;

use App\Traits\Historical\DTOs\ColumnChange;
use App\Models\History;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

trait Historical
{
    public static function bootHistorical()
    {
        static::updated(function (Model $model) {
            $model->getChangedColumns($model)->each(function ($change) use ($model) {
                $model->saveChange($change);
            });
        });
    }

    /**
     * Save history
     * @param ColumnChange $change
     */

    protected function saveChange(ColumnChange $change): void
    {
        $this->history()->create([
            'changed_column' => $change->column,
            'changed_value_from' => $change->from,
            'changed_value_to' => $change->to,
        ]);
    }

    /**
     * Get changed data
     * @param Model $model
     * @return Collection
     */

    protected function getChangedColumns(Model $model): Collection
    {
        $original = $model->getOriginal();
        return collect(
            Arr::except($model->getChanges(), $this->ignoreHistoryColumns()),
        )
            ->map(function ($change, $column) use ($original) {
                return new ColumnChange($column, Arr::get($original, $column), $change);
            });
    }

    /**
     * @return MorphMany
     */

    public function history(): MorphMany
    {
        return $this->morphMany(History::class, 'historical')
            ->latest();
    }

    /**
     * Default ignore field
     * @return string[]
     */
    public function ignoreHistoryColumns(): array
    {
        return [
            'updated_at',
        ];
    }
}
