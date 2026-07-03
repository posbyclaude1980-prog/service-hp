<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CodeGeneratorService
{
    /**
     * Generate a sequential code with the given prefix (e.g. CUS-00001).
     *
     * @param  class-string<Model>  $model
     */
    public function generateCode(string $prefix, string $model, string $column = 'code'): string
    {
        if (! is_subclass_of($model, Model::class)) {
            throw new InvalidArgumentException("{$model} must extend ".Model::class);
        }

        return DB::transaction(function () use ($prefix, $model, $column): string {
            $pattern = $prefix.'-%';

            $lastCode = $model::query()
                ->where($column, 'like', $pattern)
                ->lockForUpdate()
                ->orderByDesc($column)
                ->value($column);

            $nextNumber = 1;

            if ($lastCode !== null && preg_match('/-(\d+)$/', $lastCode, $matches)) {
                $nextNumber = (int) $matches[1] + 1;
            }

            return sprintf('%s-%05d', $prefix, $nextNumber);
        });
    }
}
