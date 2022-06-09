<?php


namespace App\History\Traits;


use App\History\ColumnChange;
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
            collect($model->getChangedColumns($model))->each(function ($change) use ($model) {
                $model->saveChange($change);
            });
        });
    }


    protected function saveChange(ColumnChange $change)
    {
        $this->history()->create([
            'changed_column' => $change->column,
            'changed_value_from' => $change->from,
            'changed_value_to' => $change->to,
        ]);
    }

    /**
     * @param Model $model
     * @return Collection
     */

    protected function getChangedColumns(Model $model): Collection
    {
        return collect(
            array_diff(
                Arr::except($model->getChanges(), $this->ignoreHistoryColumns()),
                $original = $model->getOriginal()
            )
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


    public function ignoreHistoryColumns()
    {
        return [
            'updated_at',
        ];
    }
}
