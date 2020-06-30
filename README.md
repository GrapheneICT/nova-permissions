# Laravel Nova Permissions

Nova Permissions tool based on spatie permissions

We have a Migration, Seed, Policy and Resource ready for a good Authorization Experience.

![image](https://user-images.githubusercontent.com/22980168/86119518-ec628700-bad2-11ea-9724-03f25eaba41e.PNG)

1. [Installation](#Installation)
    * [Database Migration](#database-migration)
    * [Configuration](#configuration)
    * [Database Seeding](#database-seeding)
2. [Permissions](#permissions)
    * [Detail View](#detail-view)
    * [Edit View](#edit-view)
    * [Create a Model Policy](#create-a-model-policy)
    * [Scope Resource for User](#scope-resource-for-user)
3. [Customization](#customization)
    * [Use your own Resources](#use-your-own-resources)
4. [Credits](#credits)

## Installation

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:
```bash
composer require graphene-ict/nova-permissions
```

### Database Migration

Publish the Migration with the following command:

```bash
php artisan vendor:publish --provider="GrapheneICT\NovaPermissions\ToolServiceProvider" --tag="migrations"
```

Migrate the Database:

```bash
php artisan migrate
```

### Configuration

You must register the tool with Nova. This is typically done in the `tools` method of the `NovaServiceProvider`.

```php
// in app/Providers/NovaServiceProvider.php

// ...

public function tools()
{
    return [
        // ...
        new \GrapheneICT\NovaPermissions\NovaPermissions(),
    ];
}
```

If you want to hide the tool from certain users, you can write your custom logic for the ability to see the tool:

```php
// in app/Providers/NovaServiceProvider.php

// ...

public function tools()
{
    return [
        // ...
        (new \GrapheneICT\NovaPermissions\NovaPermissions())->canSee(function ($request) {
            return $request->user()->isSuperAdmin();
        }),
    ];
}
```

Finally, add `MorphToMany` fields to you `app/Nova/User` resource:

```php
// ...
use Laravel\Nova\Fields\MorphToMany;

public function fields(Request $request)
{
    return [
        // ...
        MorphToMany::make('Roles', 'roles', \GrapheneICT\NovaPermissions\Nova\Role::class),
        MorphToMany::make('Permissions', 'permissions', \GrapheneICT\NovaPermissions\Nova\Permission::class),
    ];
}
```

Add  the Spatie\Permission\Traits\HasRoles trait to your User model(s):

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    // ...
}
```

A Super Admin can do everything. If you extend our Policy, make sure to add a isSuperAdmin() Function to your App\User Model:

> You can modify this function as you please.

```php
class User {
    /**
     * Determines if the User is a Super admin
     * @return null
    */
    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }
}
```


### Database Seeding

Publish our Seeder with the following command:

```
php artisan vendor:publish --provider="GrapheneICT\NovaPermissions\ToolServiceProvider" --tag="seeds"
```

This is just an example on how you could seed your Database with Roles and Permissions. Modify `RolesAndPermissionsSeeder.php` in `database/seeds`. List all your Models you want to have Permissions for in the `$collection` Array and change the email for the Super-Admin:

```php
class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $collection = collect([
            User::class,
            Role::class,
            Permission::class,
            // ... // List all your Models you want to have Permissions for.
        ]);
   
         $user = App\Models\User::whereEmail('your@email.com')->first(); // Change this to your email.
         $user->assignRole('super-admin');

    }
}
```

Now you can seed the Database. Add `$this->call(RolesAndPermissionsSeeder::class);` to the `DatabaseSeeder`.

> **Note**: If this doesn't work, run `composer dumpautoload` to autoload the Seeder.

## Permissions

### Detail View
![image](https://user-images.githubusercontent.com/22980168/86119952-bd004a00-bad3-11ea-97a0-9745e3e89997.PNG)

### Edit View
![image](https://user-images.githubusercontent.com/22980168/86120039-e325ea00-bad3-11ea-87c0-ac7fc2f99c10.PNG)

### Create a Model Policy

You can extend `GrapheneICT\NovaPermissions\Policies\Policy` and have a very clean Model Policy that works with Nova.

For Example: Create a new User Policy with `php artisan make:policy UserPolicy` with the following code:

```php
class UserPolicy extends Policy
{
    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'users';
}
```

It should now work as exptected. Just create a Role, modify its Permissions and the Policy should take care of the rest.

> **Note**: Don't forget to add your Policy to your `$policies` in `App\Providers\AuthServiceProvider`.

> **Note**: Only extend the Policy if you have created your Permissions according to our Seeding Example. Otherwise, make sure to have `view users, view own users, manage users, manage own users, restore users,  forceDelete users` as Permissions in your Table in order to extend our Policy.

> `view own users` is superior to `view users` and allows the User to only view his own Users.

> `manage own users` is superior to `manage users` and allows the User to only manage his own Users.

### Scope Resource for User

If you use our Policy and Seeder, the user will still be able to see other Entries. In order to only **allow a User to view his own Entries** and no others, you can extens our `GrapheneICT\NovaPermissions\Nova\ResourceForUser` Class like this:

```php
class User extends ResourceForUser 
{
    //...
}
```

> **Note**: ResourceForUser assumes the Resource has a `user_id` column in the Database. If you are using another column, feel free to copy the contents of the Resource and modify it.

## Customization

### Use your own Resources

If you want to use your own resource classes, you can define them when you register the tool:

```php
// in app/Providers/NovaServiceProvider.php

// ...

public function tools()
{
    return [
        // ...
        \GrapheneICT\NovaPermissions\NovaPermissionTool::make()
            ->roleResource(Role::class)
            ->permissionResource(Permission::class),
    ];
}
```

## Credits

This Package is inspired by [eminiarts/nova-permission](https://novapackages.com/packages/eminiarts/nova-permissions)