<?php

namespace App\Providers;

use App\Models\Admin;
use Filament\Forms\Components\Field;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\Filter;
use Gate;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        FilamentColor::register([
            'teal' => Color::Teal,
        ]);
        $this->app->bind(Authenticatable::class, Admin::class);
        Field::configureUsing(function (Field $field): void {
            $field->translateLabel();
        });
        Column::configureUsing(function (Column $column): void {
            $column->translateLabel();
        });
        Filter::configureUsing(function (Filter $filter): void {
            $filter->translateLabel();
        });
        // Hack: until issue #12742 is fixed
        app(Router::class)->middlewareGroup('filament.actions', ['web', 'auth:admin']);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        Gate::before(function (Admin $user, string $ability) {
            return $user->isSuperAdmin() ? true : null;
        });
    }
}
