<?php

namespace App\Support;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryIcon
{
    public static function keyFor(Category $category): string
    {
        $icons = config('category-icons.icons', []);
        $selected = trim((string) $category->icon);

        if ($selected !== '' && $selected !== 'auto' && isset($icons[$selected])) {
            return $selected;
        }

        $legacyAlias = config("category-icons.legacy_aliases.{$selected}");

        if (isset($icons[$legacyAlias])) {
            return $legacyAlias;
        }

        $slug = Str::slug((string) ($category->slug ?: $category->name));
        $automaticKey = config("category-icons.automatic.{$slug}");

        return isset($icons[$automaticKey])
            ? $automaticKey
            : config('category-icons.default', 'package');
    }

    public static function classFor(Category $category): string
    {
        $key = self::keyFor($category);
        $icon = config("category-icons.icons.{$key}", []);

        return self::cssClass($icon);
    }

    public static function selectOptions(): array
    {
        $options = [
            'Pengaturan' => [
                'auto' => self::optionLabel('auto'),
            ],
        ];

        foreach (config('category-icons.icons', []) as $key => $icon) {
            $options[$icon['group']][$key] = self::optionLabel($key);
        }

        return $options;
    }

    public static function optionLabel(?string $key): string
    {
        $key = config("category-icons.legacy_aliases.{$key}", $key);

        if ($key === 'auto' || ! isset(config('category-icons.icons', [])[$key])) {
            return '<span class="d-inline-flex align-items-center gap-2"><i class="bi bi-star-fill" aria-hidden="true"></i><span>Otomatis (sesuai nama kategori)</span></span>';
        }

        $icon = config("category-icons.icons.{$key}");
        $class = e(self::cssClass($icon));
        $label = e($icon['label']);

        return '<span class="d-inline-flex align-items-center gap-2"><i class="'.$class.'" aria-hidden="true"></i><span>'.$label.'</span></span>';
    }

    public static function tableLabel(Category $category): string
    {
        $selected = trim((string) $category->icon);
        $icons = config('category-icons.icons', []);
        $legacyAlias = config("category-icons.legacy_aliases.{$selected}");
        $label = isset($icons[$selected])
            ? $icons[$selected]['label']
            : (isset($icons[$legacyAlias])
                ? $icons[$legacyAlias]['label']
                : 'Otomatis: '.($icons[self::keyFor($category)]['label'] ?? 'Default'));

        return '<span class="d-inline-flex align-items-center gap-2"><i class="'.self::classFor($category).'" aria-hidden="true"></i><span>'.e($label).'</span></span>';
    }

    private static function cssClass(array $icon): string
    {
        $class = $icon['class'] ?? 'box-seam';

        return ($icon['library'] ?? 'bootstrap') === 'fontawesome'
            ? 'fa-solid '.$class
            : 'bi bi-'.$class;
    }
}
