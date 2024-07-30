<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cat;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\TEXT;
use MoonShine\Components\MoonShineComponent;

/**
 * @extends ModelResource<Cats>
 */
class CatsResource extends ModelResource
{
    protected string $model = Cat::class;

    protected string $title = 'Cat';

    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('title')
            ]),
        ];
    }

    /**
     * @param Cats $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
