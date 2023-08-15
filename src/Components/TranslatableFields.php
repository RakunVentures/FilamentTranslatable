<?php

namespace Getabed\FilamentTranslatable\Components;

use Closure;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Tabs;

class TranslatableFields extends Tabs
{
    private array | Closure $fields;
    private array | Closure | null $locales = null;

    public function fields(array | Closure $fields): static
    {
        $this->fields = $fields;

        return $this;
    }

    public function getChildComponents(): array
    {
        $tabs = $this->childComponents ?: $this->makeTabs();

        return $tabs;
    }

    public function locales(array | Closure $locales): static
    {
        $this->locales = $locales;

        return $this;

    }

    private function makeTabs(): array
    {
        $tabs = [];

        foreach($this->getLocales() as $locale) {
            $tabs[] = $this->makeTab($locale)
                ->schema($this->makeFields($locale));
        }

        return $tabs;
    }

    private function getLocales(): array
    {
        return $this->locales ? $this->evaluate($this->locales) : app('translatable.locales')->all();
    }

    private function makeTab(string $locale): Tab
    {
        $label = ucfirst($locale);
        return Tab::make($label);
    }

    private function makeFields(string $locale): array
    {
        $result = [];
        $fields = $this->evaluate($this->fields);

        foreach($fields as $field) {
            $name = $locale.'.'.$field->getName();
            $newField = clone $field;
            $result[] = $newField->name($name)->statePath($name);
        }

        return $result;
    }
}
