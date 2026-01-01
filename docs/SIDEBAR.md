# Documentation - Sidebar CEEAC

## Vue d'ensemble

Le sidebar (menu latéral) est un composant moderne et responsive qui remplace le menu de navigation horizontal. Il s'affiche à gauche sur desktop et en overlay sur mobile.

## Structure

### Fichiers principaux

1. **`config/navigation.php`** - Configuration des items du menu
2. **`resources/views/components/app/sidebar.blade.php`** - Composant sidebar
3. **`resources/views/components/app/topbar.blade.php`** - Barre supérieure
4. **`resources/views/layouts/auth.blade.php`** - Layout avec sidebar
5. **`app/Helpers/NavigationHelper.php`** - Helper pour RBAC et vérifications
6. **`public/css/sidebar.css`** - Styles du sidebar

## Configuration

### Ajouter un item au menu

Éditez `config/navigation.php` :

```php
[
    'label' => 'Mon Module',
    'icon' => 'bi-icon-name', // Bootstrap Icons
    'route' => 'mon-module.index',
    'permission' => 'mon-module.view', // Optionnel
    'role' => ['admin'], // Optionnel (alternative à permission)
    'active' => ['mon-module.*'], // Routes qui activent cet item
],
```

### Item avec sous-menu

```php
[
    'label' => 'Mon Module',
    'icon' => 'bi-folder',
    'permission' => null,
    'children' => [
        [
            'label' => 'Sous-item 1',
            'icon' => 'bi-file',
            'route' => 'sous-item.index',
            'permission' => 'sous-item.view',
        ],
        // ...
    ],
],
```

### Badge (compteur)

```php
[
    'label' => 'Alertes',
    'icon' => 'bi-bell',
    'route' => 'alertes.index',
    'badge' => 'alertes.count', // À implémenter dans NavigationHelper
],
```

## RBAC (Spatie Permission)

### Vérification par permission

```php
'permission' => 'gantt.view',
```

L'item ne s'affiche que si `auth()->user()->can('gantt.view')` retourne `true`.

### Vérification par rôle

```php
'role' => ['admin', 'admin_dsi'],
```

L'item ne s'affiche que si l'utilisateur a au moins un des rôles spécifiés.

### Combinaison permission + rôle

Si les deux sont spécifiés, l'utilisateur doit avoir **soit** la permission **soit** le rôle.

## Utilisation dans les vues

### Layout avec sidebar

```blade
<x-auth-layout>
    <x-slot name="header">
        <h2>Mon titre</h2>
    </x-slot>

    <div>
        Contenu de la page
    </div>
</x-auth-layout>
```

### Layout classique (sans sidebar)

Les pages publiques continuent d'utiliser `<x-guest-layout>` ou le layout par défaut.

## Responsive

### Desktop (≥ 992px)
- Sidebar fixe à gauche (256px)
- Toujours visible
- Contenu décalé de 256px

### Mobile (< 992px)
- Sidebar cachée par défaut
- Bouton hamburger dans la topbar
- Overlay sombre au clic
- Fermeture avec ESC ou clic sur overlay

## Accessibilité

- ✅ Attributs ARIA (`aria-label`, `aria-expanded`, `aria-current`)
- ✅ Navigation clavier (Tab, Enter, ESC)
- ✅ Focus visible
- ✅ Contraste suffisant (WCAG AA)

## Personnalisation

### Couleurs

Modifiez les variables CSS dans `public/css/sidebar.css` :

```css
:root {
    --sidebar-width: 256px;
    --sidebar-bg: #1e40af;
    --sidebar-bg-dark: #1e3a8a;
    --sidebar-text: rgba(255, 255, 255, 0.9);
    --sidebar-active-bg: rgba(255, 255, 255, 0.1);
}
```

### Largeur du sidebar

```css
--sidebar-width: 280px; /* Par défaut: 256px */
```

## Tests

### Test manuel

1. ✅ Se connecter en tant qu'admin
2. ✅ Vérifier que le sidebar s'affiche
3. ✅ Vérifier que tous les items sont visibles
4. ✅ Tester le responsive (réduire la fenêtre)
5. ✅ Tester le toggle mobile
6. ✅ Vérifier les sous-menus (expand/collapse)
7. ✅ Vérifier que l'item actif est mis en évidence

### Test avec utilisateur standard

1. ✅ Se connecter avec un utilisateur sans rôle admin
2. ✅ Vérifier que "Administration" n'est pas visible
3. ✅ Vérifier que les autres items sont visibles

## Dépannage

### Le sidebar ne s'affiche pas

1. Vérifier que `auth()->check()` retourne `true`
2. Vérifier que le CSS est chargé : `public/css/sidebar.css`
3. Vérifier la console du navigateur pour les erreurs JS

### Les items ne s'affichent pas

1. Vérifier les permissions dans `config/navigation.php`
2. Exécuter `php artisan menu:diagnose` pour vérifier les permissions
3. Vérifier que les routes existent : `php artisan route:list`

### Le sidebar ne se ferme pas sur mobile

1. Vérifier que le JavaScript est chargé
2. Vérifier la console pour les erreurs
3. Vérifier que Bootstrap JS est chargé

## Migration depuis l'ancien menu

L'ancien menu horizontal (`<x-app.navigation />`) reste disponible pour compatibilité. Pour migrer une page :

**Avant :**
```blade
<x-app-layout>
    <x-slot name="header">...</x-slot>
    Contenu
</x-app-layout>
```

**Après :**
```blade
<x-auth-layout>
    <x-slot name="header">...</x-slot>
    Contenu
</x-auth-layout>
```

Ou simplement utiliser le layout `app.blade.php` qui détecte automatiquement l'authentification.


