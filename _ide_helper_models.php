<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models\Auth{
/**
 * App\Models\Auth\Permission
 *
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $title
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Auth\Role[] $roles
 * @method static \Illuminate\Database\Eloquent\Builder|Permission action($action = 'read')
 * @method static \Illuminate\Database\Eloquent\Builder|Permission collect($sort = 'display_name')
 * @method static \Database\Factories\Permission factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission sortable($defaultParameters = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission usingSearchString($string)
 */
	class Permission extends \Eloquent {}
}

namespace App\Models\Auth{
/**
 * App\Models\Auth\Role
 *
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string|null $description
 * @property string|null $created_from
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Auth\Permission[] $permissions
 * @method static \Illuminate\Database\Eloquent\Builder|Role collect($sort = 'display_name')
 * @method static \Database\Factories\Role factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role sortable($defaultParameters = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Role usingSearchString($string)
 */
	class Role extends \Eloquent {}
}

namespace App\Models\Auth{
/**
 * App\Models\Auth\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $last_logged_in_at
 * @property string $locale
 * @property string|null $landing_page
 * @property bool $enabled
 * @property string|null $created_from
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Common\Company[] $companies
 * @property-read \App\Models\Common\Contact|null $contact
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Common\Dashboard[] $dashboards
 * @property-read mixed $last_logged
 * @property-read mixed $picture
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Common\Media[] $media
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Auth\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Auth\Role[] $roles
 * @method static \Plank\Mediable\MediableCollection|static[] all($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|User collect($sort = 'name')
 * @method static \Illuminate\Database\Eloquent\Builder|User enabled()
 * @method static \Database\Factories\User factory(...$parameters)
 * @method static \Plank\Mediable\MediableCollection|static[] get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|User isCustomer()
 * @method static \Illuminate\Database\Eloquent\Builder|User isNotCustomer()
 * @method static \Illuminate\Database\Eloquent\Builder|User isNotOwner()
 * @method static \Illuminate\Database\Eloquent\Builder|User isOwner()
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User orWherePermissionIs($permission = '')
 * @method static \Illuminate\Database\Eloquent\Builder|User orWhereRoleIs($role = '', $team = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User sortable($defaultParameters = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User source($source)
 * @method static \Illuminate\Database\Eloquent\Builder|User usingSearchString($string)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDoesntHavePermission()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDoesntHaveRole()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereHasMedia($tags = [], bool $matchAll = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereHasMediaMatchAll(array $tags)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePermissionIs($permission = '', $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRoleIs($role = '', $team = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|User withMedia($tags = [], bool $matchAll = false, bool $withVariants = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User withMediaAndVariants($tags = [], bool $matchAll = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User withMediaAndVariantsMatchAll($tags = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User withMediaMatchAll(bool $tags = [], bool $withVariants = false)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Translation\HasLocalePreference {}
}

namespace App\Models\Auth{
/**
 * App\Models\Auth\UserCompany
 *
 * @property int $user_id
 * @property int $company_id
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \App\Models\Auth\User|null $user
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserCompany all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserCompany avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserCompany cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserCompany cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserCompany count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserCompany disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserCompany exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserCompany flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserCompany getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserCompany inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserCompany insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserCompany isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserCompany max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserCompany min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserCompany newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserCompany newQuery()
 * @method static \Illuminate\Database\Query\Builder|UserCompany onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserCompany query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserCompany sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserCompany truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|UserCompany withTrashed()
 * @method static \Illuminate\Database\Query\Builder|UserCompany withoutTrashed()
 */
	class UserCompany extends \Eloquent {}
}

namespace App\Models\Auth{
/**
 * App\Models\Auth\UserDashboard
 *
 * @property int $user_id
 * @property int $dashboard_id
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Common\Dashboard|null $dashboard
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \App\Models\Auth\User|null $user
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserDashboard all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserDashboard avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserDashboard cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserDashboard cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserDashboard count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserDashboard disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserDashboard exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserDashboard flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserDashboard getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserDashboard inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserDashboard insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserDashboard isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserDashboard max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserDashboard min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserDashboard newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserDashboard newQuery()
 * @method static \Illuminate\Database\Query\Builder|UserDashboard onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserDashboard query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserDashboard sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|UserDashboard truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|UserDashboard withTrashed()
 * @method static \Illuminate\Database\Query\Builder|UserDashboard withoutTrashed()
 */
	class UserDashboard extends \Eloquent {}
}

namespace App\Models\Banking{
/**
 * App\Models\Banking\Account
 *
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property string $number
 * @property string $currency_code
 * @property float $opening_balance
 * @property string|null $bank_name
 * @property string|null $bank_phone
 * @property string|null $bank_address
 * @property bool $enabled
 * @property string|null $created_from
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Setting\Currency|null $currency
 * @property-read string $balance
 * @property-read string $expense_balance
 * @property-read string $income_balance
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Banking\Transaction[] $transactions
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account exists()
 * @method static \Database\Factories\Account factory(...$parameters)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account name($name)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account number($number)
 * @method static \Illuminate\Database\Query\Builder|Account onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|Account withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Account withoutTrashed()
 */
	class Account extends \Eloquent {}
}

namespace App\Models\Banking{
/**
 * App\Models\Banking\Reconciliation
 *
 * @property int $id
 * @property int $company_id
 * @property int $account_id
 * @property \Illuminate\Support\Carbon $started_at
 * @property \Illuminate\Support\Carbon $ended_at
 * @property float $closing_balance
 * @property bool $reconciled
 * @property string|null $created_from
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Banking\Account|null $account
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Reconciliation all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Reconciliation avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Reconciliation cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Reconciliation cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Reconciliation count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Reconciliation disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Reconciliation exists()
 * @method static \Database\Factories\Reconciliation factory(...$parameters)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Reconciliation flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Reconciliation getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Reconciliation inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Reconciliation insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Reconciliation isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Reconciliation max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Reconciliation min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Reconciliation newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Reconciliation newQuery()
 * @method static \Illuminate\Database\Query\Builder|Reconciliation onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Reconciliation query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Reconciliation sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Reconciliation truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|Reconciliation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Reconciliation withoutTrashed()
 */
	class Reconciliation extends \Eloquent {}
}

namespace App\Models\Banking{
/**
 * App\Models\Banking\Transaction
 *
 * @property int $id
 * @property int $company_id
 * @property string $type
 * @property \Illuminate\Support\Carbon $paid_at
 * @property float $amount
 * @property string $currency_code
 * @property float $currency_rate
 * @property int $account_id
 * @property int|null $document_id
 * @property int|null $contact_id
 * @property int $category_id
 * @property string|null $description
 * @property string $payment_method
 * @property string|null $reference
 * @property int $parent_id
 * @property string|null $created_from
 * @property int|null $created_by
 * @property int $reconciled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Banking\Account|null $account
 * @property-read \App\Models\Document\Document|null $bill
 * @property-read \App\Models\Setting\Category|null $category
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Common\Contact|null $contact
 * @property-read \App\Models\Setting\Currency|null $currency
 * @property-read \App\Models\Document\Document|null $document
 * @property-read float $amount_for_account
 * @property-read string $attachment
 * @property-read bool $has_transfer_relation
 * @property-read string $route_id
 * @property-read string $route_name
 * @property-read mixed $template_path
 * @property-read string $type_title
 * @property-read \App\Models\Document\Document|null $invoice
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Common\Media[] $media
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read Transaction|null $parent
 * @property-read \App\Models\Common\Recurring|null $recurring
 * @property-read \App\Models\Auth\User|null $user
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction accountId($account_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction categoryId($category_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction contactId($contact_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction documentId($document_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction expense()
 * @method static \Database\Factories\Transaction factory(...$parameters)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction flushCache(array $tags = [])
 * @method static \Plank\Mediable\MediableCollection|static[] get($columns = ['*'])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction income()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction isDocument()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction isNotDocument()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction isNotReconciled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction isNotTransfer()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction isReconciled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction isTransfer()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction latest()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction newQuery()
 * @method static \Illuminate\Database\Query\Builder|Transaction onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction paid()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction type($types)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction whereHasMedia($tags = [], bool $matchAll = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction whereHasMediaMatchAll(array $tags)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction withMedia($tags = [], bool $matchAll = false, bool $withVariants = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction withMediaAndVariants($tags = [], bool $matchAll = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction withMediaAndVariantsMatchAll($tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transaction withMediaMatchAll(bool $tags = [], bool $withVariants = false)
 * @method static \Illuminate\Database\Query\Builder|Transaction withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Transaction withoutTrashed()
 */
	class Transaction extends \Eloquent {}
}

namespace App\Models\Banking{
/**
 * App\Models\Banking\Transfer
 *
 * @property int $id
 * @property int $company_id
 * @property int $expense_transaction_id
 * @property int $income_transaction_id
 * @property string|null $created_from
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Banking\Transaction|null $expense_transaction
 * @property-read float $amount
 * @property-read string $attachment
 * @property-read string $description
 * @property-read int $from_account_id
 * @property-read string $from_account_rate
 * @property-read string $from_currency_code
 * @property-read string $payment_method
 * @property-read string $reference
 * @property-read mixed $template_path
 * @property-read int $to_account_id
 * @property-read string $to_account_rate
 * @property-read string $to_currency_code
 * @property-read \App\Models\Banking\date $transferred_at
 * @property-read \App\Models\Banking\Transaction|null $income_transaction
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Common\Media[] $media
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer exists()
 * @method static \Database\Factories\Transfer factory(...$parameters)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer flushCache(array $tags = [])
 * @method static \Plank\Mediable\MediableCollection|static[] get($columns = ['*'])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer newQuery()
 * @method static \Illuminate\Database\Query\Builder|Transfer onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer whereHasMedia($tags = [], bool $matchAll = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer whereHasMediaMatchAll(array $tags)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer withMedia($tags = [], bool $matchAll = false, bool $withVariants = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer withMediaAndVariants($tags = [], bool $matchAll = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer withMediaAndVariantsMatchAll($tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Transfer withMediaMatchAll(bool $tags = [], bool $withVariants = false)
 * @method static \Illuminate\Database\Query\Builder|Transfer withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Transfer withoutTrashed()
 */
	class Transfer extends \Eloquent {}
}

namespace App\Models\Common{
/**
 * App\Models\Common\Company
 *
 * @property int $id
 * @property string|null $domain
 * @property bool $enabled
 * @property string|null $created_from
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Banking\Account[] $accounts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Setting\Category[] $categories
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Common\Contact[] $contacts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Setting\Currency[] $currencies
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Common\Dashboard[] $dashboards
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentHistory[] $document_histories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentItemTax[] $document_item_taxes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentItem[] $document_items
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentTotal[] $document_totals
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Document\Document[] $documents
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Common\EmailTemplate[] $email_templates
 * @property-read string $company_logo
 * @property-read mixed $location
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Common\Item[] $items
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Common\Media[] $media
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Module\ModuleHistory[] $module_histories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Module\Module[] $modules
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Banking\Reconciliation[] $reconciliations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Common\Recurring[] $recurring
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Common\Report[] $reports
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Setting\Setting[] $settings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Setting\Tax[] $taxes
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Banking\Transaction[] $transactions
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Banking\Transfer[] $transfers
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Auth\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Common\Widget[] $widgets
 * @method static \Plank\Mediable\MediableCollection|static[] all($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|Company autocomplete($filter)
 * @method static \Illuminate\Database\Eloquent\Builder|Company collect($sort = 'name')
 * @method static \Illuminate\Database\Eloquent\Builder|Company enabled($value = 1)
 * @method static \Plank\Mediable\MediableCollection|static[] get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|Company isNotOwner()
 * @method static \Illuminate\Database\Eloquent\Builder|Company isOwner()
 * @method static \Illuminate\Database\Eloquent\Builder|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company newQuery()
 * @method static \Illuminate\Database\Query\Builder|Company onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder|Company sortable($defaultParameters = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Company source($source)
 * @method static \Illuminate\Database\Eloquent\Builder|Company userId($user_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Company usingSearchString($string)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereHasMedia($tags = [], bool $matchAll = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereHasMediaMatchAll(array $tags)
 * @method static \Illuminate\Database\Eloquent\Builder|Company withMedia($tags = [], bool $matchAll = false, bool $withVariants = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Company withMediaAndVariants($tags = [], bool $matchAll = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Company withMediaAndVariantsMatchAll($tags = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Company withMediaMatchAll(bool $tags = [], bool $withVariants = false)
 * @method static \Illuminate\Database\Query\Builder|Company withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Company withoutTrashed()
 */
	class Company extends \Eloquent implements \Laratrust\Contracts\Ownable {}
}

namespace App\Models\Common{
/**
 * App\Models\Common\Contact
 *
 * @property int $id
 * @property int $company_id
 * @property string $type
 * @property string $name
 * @property string|null $email
 * @property int|null $user_id
 * @property string|null $tax_number
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $city
 * @property string|null $zip_code
 * @property string|null $state
 * @property string|null $country
 * @property string|null $website
 * @property string $currency_code
 * @property bool $enabled
 * @property string|null $reference
 * @property string|null $created_from
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Setting\Currency|null $currency
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Document\Document[] $documents
 * @property-read mixed $location
 * @property-read string $logo
 * @property-read mixed $unpaid
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Common\Media[] $media
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Banking\Transaction[] $transactions
 * @property-read \App\Models\Auth\User|null $user
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact customer()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact email($email)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact exists()
 * @method static \Database\Factories\Contact factory(...$parameters)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact flushCache(array $tags = [])
 * @method static \Plank\Mediable\MediableCollection|static[] get($columns = ['*'])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact newQuery()
 * @method static \Illuminate\Database\Query\Builder|Contact onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact type($types)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact vendor()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact whereHasMedia($tags = [], bool $matchAll = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact whereHasMediaMatchAll(array $tags)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact withMedia($tags = [], bool $matchAll = false, bool $withVariants = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact withMediaAndVariants($tags = [], bool $matchAll = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact withMediaAndVariantsMatchAll($tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Contact withMediaMatchAll(bool $tags = [], bool $withVariants = false)
 * @method static \Illuminate\Database\Query\Builder|Contact withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Contact withoutTrashed()
 */
	class Contact extends \Eloquent {}
}

namespace App\Models\Common{
/**
 * App\Models\Common\Dashboard
 *
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property bool $enabled
 * @property string|null $created_from
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read string $alias
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Auth\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Common\Widget[] $widgets
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard alias($alias)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard exists()
 * @method static \Database\Factories\Dashboard factory(...$parameters)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard newQuery()
 * @method static \Illuminate\Database\Query\Builder|Dashboard onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Dashboard userId($user_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|Dashboard withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Dashboard withoutTrashed()
 */
	class Dashboard extends \Eloquent {}
}

namespace App\Models\Common{
/**
 * App\Models\Common\EmailTemplate
 *
 * @property int $id
 * @property int $company_id
 * @property string $alias
 * @property string $class
 * @property string $name
 * @property string $subject
 * @property string $body
 * @property string|null $params
 * @property string|null $created_from
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate alias($alias)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate moduleAlias($alias)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate newQuery()
 * @method static \Illuminate\Database\Query\Builder|EmailTemplate onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|EmailTemplate truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|EmailTemplate withTrashed()
 * @method static \Illuminate\Database\Query\Builder|EmailTemplate withoutTrashed()
 */
	class EmailTemplate extends \Eloquent {}
}

namespace App\Models\Common{
/**
 * App\Models\Common\Item
 *
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property string|null $sku
 * @property string|null $description
 * @property float $sale_price
 * @property float $purchase_price
 * @property int $quantity
 * @property int|null $category_id
 * @property int|null $tax_id
 * @property bool $enabled
 * @property string|null $created_from
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Setting\Category|null $category
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentItem[] $document_items
 * @property-read string $item_id
 * @property-read string $picture
 * @property-read string $tax_ids
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Common\Media[] $media
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Common\ItemTax[] $taxes
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item autocomplete($filter)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item exists()
 * @method static \Database\Factories\Item factory(...$parameters)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item flushCache(array $tags = [])
 * @method static \Plank\Mediable\MediableCollection|static[] get($columns = ['*'])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item name($name)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item newQuery()
 * @method static \Illuminate\Database\Query\Builder|Item onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item whereHasMedia($tags = [], bool $matchAll = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item whereHasMediaMatchAll(array $tags)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item withMedia($tags = [], bool $matchAll = false, bool $withVariants = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item withMediaAndVariants($tags = [], bool $matchAll = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item withMediaAndVariantsMatchAll($tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Item withMediaMatchAll(bool $tags = [], bool $withVariants = false)
 * @method static \Illuminate\Database\Query\Builder|Item withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Item withoutTrashed()
 */
	class Item extends \Eloquent {}
}

namespace App\Models\Common{
/**
 * App\Models\Common\ItemTax
 *
 * @property int $id
 * @property int $company_id
 * @property int $item_id
 * @property int|null $tax_id
 * @property string|null $created_from
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Common\Item|null $item
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \App\Models\Setting\Tax|null $tax
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ItemTax all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ItemTax avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ItemTax cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ItemTax cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ItemTax count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ItemTax disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ItemTax exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ItemTax flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ItemTax getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ItemTax inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ItemTax insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ItemTax isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ItemTax max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ItemTax min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ItemTax newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ItemTax newQuery()
 * @method static \Illuminate\Database\Query\Builder|ItemTax onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ItemTax query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ItemTax sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ItemTax truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|ItemTax withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ItemTax withoutTrashed()
 */
	class ItemTax extends \Eloquent {}
}

namespace App\Models\Common{
/**
 * App\Models\Common\Media
 *
 * @property int $id
 * @property int $company_id
 * @property string $disk
 * @property string $directory
 * @property string $filename
 * @property string $extension
 * @property string $mime_type
 * @property string $aggregate_type
 * @property int $size
 * @property string|null $variant_name
 * @property int|null $original_media_id
 * @property string|null $created_from
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string $basename
 * @property-read Media|null $originalMedia
 * @property-read \Illuminate\Database\Eloquent\Collection|Media[] $variants
 * @method static \Illuminate\Database\Eloquent\Builder|Media forPathOnDisk(string $disk, string $path)
 * @method static \Illuminate\Database\Eloquent\Builder|Media inDirectory(string $disk, string $directory, bool $recursive = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Media inOrUnderDirectory(string $disk, string $directory)
 * @method static \Illuminate\Database\Eloquent\Builder|Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media newQuery()
 * @method static \Illuminate\Database\Query\Builder|Media onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Media query()
 * @method static \Illuminate\Database\Eloquent\Builder|Media unordered()
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereBasename(string $basename)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereIsOriginal()
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereIsVariant(?string $variant_name = null)
 * @method static \Illuminate\Database\Query\Builder|Media withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Media withoutTrashed()
 */
	class Media extends \Eloquent {}
}

namespace App\Models\Common{
/**
 * App\Models\Common\Recurring
 *
 * @property int $id
 * @property int $company_id
 * @property string $recurable_type
 * @property int $recurable_id
 * @property string $frequency
 * @property int $interval
 * @property string $started_at
 * @property int $count
 * @property string|null $created_from
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $recurable
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Recurring all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Recurring avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Recurring cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Recurring cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Recurring count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Recurring disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Recurring exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Recurring flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Recurring getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Recurring inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Recurring insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Recurring isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Recurring max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Recurring min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Recurring newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Recurring newQuery()
 * @method static \Illuminate\Database\Query\Builder|Recurring onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Recurring query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Recurring sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Recurring truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|Recurring withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Recurring withoutTrashed()
 */
	class Recurring extends \Eloquent {}
}

namespace App\Models\Common{
/**
 * App\Models\Common\Report
 *
 * @property int $id
 * @property int $company_id
 * @property string $class
 * @property string $name
 * @property string $description
 * @property object|null $settings
 * @property string|null $created_from
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read string $alias
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Report alias($alias)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Report all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Report avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Report cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Report cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Report count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Report disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Report exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Report flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Report getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Report inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Report insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Report isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Report max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Report min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Report newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Report newQuery()
 * @method static \Illuminate\Database\Query\Builder|Report onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Report query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Report sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Report truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|Report withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Report withoutTrashed()
 */
	class Report extends \Eloquent {}
}

namespace App\Models\Common{
/**
 * App\Models\Common\Widget
 *
 * @property int $id
 * @property int $company_id
 * @property int $dashboard_id
 * @property string $class
 * @property string $name
 * @property int $sort
 * @property object|null $settings
 * @property string|null $created_from
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Common\Dashboard|null $dashboard
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Auth\User[] $users
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Widget all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Widget avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Widget cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Widget cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Widget count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Widget disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Widget exists()
 * @method static \Database\Factories\Widget factory(...$parameters)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Widget flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Widget getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Widget inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Widget insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Widget isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Widget max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Widget min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Widget newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Widget newQuery()
 * @method static \Illuminate\Database\Query\Builder|Widget onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Widget query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Widget sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Widget truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|Widget withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Widget withoutTrashed()
 */
	class Widget extends \Eloquent {}
}

namespace App\Models\Document{
/**
 * App\Models\Document\Document
 *
 * @property int $id
 * @property int $company_id
 * @property string $type
 * @property string $document_number
 * @property string|null $order_number
 * @property string $status
 * @property \Illuminate\Support\Carbon $issued_at
 * @property \Illuminate\Support\Carbon $due_at
 * @property float $amount
 * @property string $currency_code
 * @property float $currency_rate
 * @property int $category_id
 * @property int $contact_id
 * @property string $contact_name
 * @property string|null $contact_email
 * @property string|null $contact_tax_number
 * @property string|null $contact_phone
 * @property string|null $contact_address
 * @property string|null $contact_city
 * @property string|null $contact_zip_code
 * @property string|null $contact_state
 * @property string|null $contact_country
 * @property string|null $notes
 * @property string|null $footer
 * @property int $parent_id
 * @property string|null $created_from
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Setting\Category|null $category
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Common\Contact|null $contact
 * @property-read \App\Models\Setting\Currency|null $currency
 * @property-read string $amount_due
 * @property-read string $amount_without_tax
 * @property-read string $attachment
 * @property-read mixed $contact_location
 * @property-read string $discount
 * @property-read string $paid
 * @property-read mixed $received_at
 * @property-read int $reconciled
 * @property-read mixed $sent_at
 * @property-read string $status_label
 * @property-read mixed $template_path
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentHistory[] $histories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentItemTax[] $item_taxes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentItem[] $items
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Common\Media[] $media
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read Document|null $parent
 * @property-read \App\Models\Common\Recurring|null $recurring
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentTotal[] $totals
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Banking\Transaction[] $transactions
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document accrued()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document bill()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document due($date)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document exists()
 * @method static \Database\Factories\Document factory(...$parameters)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document flushCache(array $tags = [])
 * @method static \Plank\Mediable\MediableCollection|static[] get($columns = ['*'])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document invoice()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document latest()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document notPaid()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document number(string $number)
 * @method static \Illuminate\Database\Query\Builder|Document onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document paid()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document type(string $type)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document whereHasMedia($tags = [], bool $matchAll = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document whereHasMediaMatchAll(array $tags)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document withMedia($tags = [], bool $matchAll = false, bool $withVariants = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document withMediaAndVariants($tags = [], bool $matchAll = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document withMediaAndVariantsMatchAll($tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document withMediaMatchAll(bool $tags = [], bool $withVariants = false)
 * @method static \Illuminate\Database\Query\Builder|Document withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Document withoutTrashed()
 */
	class Document extends \Eloquent {}
}

namespace App\Models\Document{
/**
 * App\Models\Document\DocumentHistory
 *
 * @property int $id
 * @property int $company_id
 * @property string $type
 * @property int $document_id
 * @property string $status
 * @property int $notify
 * @property string|null $description
 * @property string|null $created_from
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Document\Document|null $document
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory bill()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory invoice()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory newQuery()
 * @method static \Illuminate\Database\Query\Builder|DocumentHistory onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory type(string $type)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|DocumentHistory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|DocumentHistory withoutTrashed()
 */
	class DocumentHistory extends \Eloquent {}
}

namespace App\Models\Document{
/**
 * App\Models\Document\DocumentItem
 *
 * @property int $id
 * @property int $company_id
 * @property string $type
 * @property int $document_id
 * @property int|null $item_id
 * @property string $name
 * @property string|null $description
 * @property string|null $sku
 * @property float $quantity
 * @property float $price
 * @property float $tax
 * @property string $discount_type
 * @property float $discount_rate
 * @property float $total
 * @property string|null $created_from
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Document\Document|null $document
 * @property-read string $discount
 * @property-read \App\Models\Common\Item|null $item
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentItemTax[] $taxes
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem bill()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem invoice()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem newQuery()
 * @method static \Illuminate\Database\Query\Builder|DocumentItem onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem type(string $type)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|DocumentItem withTrashed()
 * @method static \Illuminate\Database\Query\Builder|DocumentItem withoutTrashed()
 */
	class DocumentItem extends \Eloquent {}
}

namespace App\Models\Document{
/**
 * App\Models\Document\DocumentItemTax
 *
 * @property int $id
 * @property int $company_id
 * @property string $type
 * @property int $document_id
 * @property int $document_item_id
 * @property int $tax_id
 * @property string $name
 * @property float $amount
 * @property string|null $created_from
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Document\Document|null $document
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \App\Models\Setting\Tax|null $tax
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax bill()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax invoice()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax newQuery()
 * @method static \Illuminate\Database\Query\Builder|DocumentItemTax onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax type(string $type)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|DocumentItemTax withTrashed()
 * @method static \Illuminate\Database\Query\Builder|DocumentItemTax withoutTrashed()
 */
	class DocumentItemTax extends \Eloquent {}
}

namespace App\Models\Document{
/**
 * App\Models\Document\DocumentTotal
 *
 * @property int $id
 * @property int $company_id
 * @property string $type
 * @property int $document_id
 * @property string|null $code
 * @property string $name
 * @property float $amount
 * @property int $sort_order
 * @property string|null $created_from
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Document\Document|null $document
 * @property-read mixed $title
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal bill()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal code($code)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal invoice()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal newQuery()
 * @method static \Illuminate\Database\Query\Builder|DocumentTotal onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal type(string $type)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|DocumentTotal withTrashed()
 * @method static \Illuminate\Database\Query\Builder|DocumentTotal withoutTrashed()
 */
	class DocumentTotal extends \Eloquent {}
}

namespace App\Models\Module{
/**
 * App\Models\Module\Module
 *
 * @property int $id
 * @property int $company_id
 * @property string $alias
 * @property bool $enabled
 * @property string|null $created_from
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Module alias($alias)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Module all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Module avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Module cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Module cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Module count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Module disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Module exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Module flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Module getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Module inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Module insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Module isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Module max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Module min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Module newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Module newQuery()
 * @method static \Illuminate\Database\Query\Builder|Module onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Module query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Module sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Module truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|Module withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Module withoutTrashed()
 */
	class Module extends \Eloquent {}
}

namespace App\Models\Module{
/**
 * App\Models\Module\ModuleHistory
 *
 * @property int $id
 * @property int $company_id
 * @property int $module_id
 * @property string $version
 * @property string|null $description
 * @property string|null $created_from
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModuleHistory all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModuleHistory avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModuleHistory cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModuleHistory cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModuleHistory count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModuleHistory disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModuleHistory exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModuleHistory flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModuleHistory getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModuleHistory inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModuleHistory insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModuleHistory isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModuleHistory max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModuleHistory min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModuleHistory newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModuleHistory newQuery()
 * @method static \Illuminate\Database\Query\Builder|ModuleHistory onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModuleHistory query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModuleHistory sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ModuleHistory truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|ModuleHistory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ModuleHistory withoutTrashed()
 */
	class ModuleHistory extends \Eloquent {}
}

namespace App\Models\Setting{
/**
 * App\Models\Setting\Category
 *
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property string $type
 * @property string $color
 * @property bool $enabled
 * @property string|null $created_from
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Document\Document[] $documents
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Common\Item[] $items
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Banking\Transaction[] $transactions
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category expense()
 * @method static \Database\Factories\Category factory(...$parameters)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category income()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category item()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category name($name)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category newQuery()
 * @method static \Illuminate\Database\Query\Builder|Category onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category other()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category transfer()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Category type($types)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|Category withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Category withoutTrashed()
 */
	class Category extends \Eloquent {}
}

namespace App\Models\Setting{
/**
 * App\Models\Setting\Currency
 *
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property string $code
 * @property float $rate
 * @property string $precision
 * @property string $symbol
 * @property string $symbol_first
 * @property string $decimal_mark
 * @property string $thousands_separator
 * @property bool $enabled
 * @property string|null $created_from
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Banking\Account[] $accounts
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Common\Contact[] $contacts
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Document\Document[] $documents
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Banking\Transaction[] $transactions
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Currency all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Currency avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Currency cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Currency cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Currency code($code)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Currency count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Currency disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Currency exists()
 * @method static \Database\Factories\Currency factory(...$parameters)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Currency flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Currency getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Currency inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Currency insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Currency isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Currency max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Currency min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Currency newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Currency newQuery()
 * @method static \Illuminate\Database\Query\Builder|Currency onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Currency query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Currency sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Currency truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|Currency withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Currency withoutTrashed()
 */
	class Currency extends \Eloquent {}
}

namespace App\Models\Setting{
/**
 * App\Models\Setting\Setting
 *
 * @property int $id
 * @property int $company_id
 * @property string $key
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Setting all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Setting avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Setting cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Setting cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Setting count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Setting disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Setting exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Setting flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Setting getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Setting inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Setting insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Setting isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Setting max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Setting min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Setting newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Setting newQuery()
 * @method static \Illuminate\Database\Query\Builder|Setting onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Setting prefix($prefix = 'company')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Setting query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Setting sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Setting truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|Setting withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Setting withoutTrashed()
 */
	class Setting extends \Eloquent {}
}

namespace App\Models\Setting{
/**
 * App\Models\Setting\Tax
 *
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property float $rate
 * @property string $type
 * @property bool $enabled
 * @property string|null $created_from
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentItemTax[] $document_items
 * @property-read string $title
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Common\Item[] $items
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax compound()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax exists()
 * @method static \Database\Factories\Tax factory(...$parameters)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax fixed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax inclusive()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax name($name)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax normal()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax notRate($rate)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax notWithholding()
 * @method static \Illuminate\Database\Query\Builder|Tax onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax rate($rate)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax type($types)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|Tax withTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Tax withholding()
 * @method static \Illuminate\Database\Query\Builder|Tax withoutTrashed()
 */
	class Tax extends \Eloquent {}
}

namespace App\Models\Purchase{
/**
 * App\Models\Purchase\Bill
 *
 * @deprecated 
 * @see Document
 * @property int $id
 * @property int $company_id
 * @property string $type
 * @property string $document_number
 * @property string|null $order_number
 * @property string $status
 * @property \Illuminate\Support\Carbon $issued_at
 * @property \Illuminate\Support\Carbon $due_at
 * @property float $amount
 * @property string $currency_code
 * @property float $currency_rate
 * @property int $category_id
 * @property int $contact_id
 * @property string $contact_name
 * @property string|null $contact_email
 * @property string|null $contact_tax_number
 * @property string|null $contact_phone
 * @property string|null $contact_address
 * @property string|null $contact_city
 * @property string|null $contact_zip_code
 * @property string|null $contact_state
 * @property string|null $contact_country
 * @property string|null $notes
 * @property string|null $footer
 * @property int $parent_id
 * @property string|null $created_from
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Setting\Category|null $category
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Common\Contact|null $contact
 * @property-read \App\Models\Setting\Currency|null $currency
 * @property-read string $amount_due
 * @property-read string $amount_without_tax
 * @property-read string $attachment
 * @property mixed $bill_number
 * @property mixed $billed_at
 * @property-read mixed $contact_location
 * @property-read string $discount
 * @property-read string $paid
 * @property-read mixed $received_at
 * @property-read int $reconciled
 * @property-read mixed $sent_at
 * @property-read string $status_label
 * @property-read mixed $template_path
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentHistory[] $histories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentItemTax[] $item_taxes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentItem[] $items
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Common\Media[] $media
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \App\Models\Document\Document|null $parent
 * @property-read \App\Models\Common\Recurring|null $recurring
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentTotal[] $totals
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Banking\Transaction[] $transactions
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document accrued()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Bill all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Bill avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document bill()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Bill cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Bill cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Bill count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Bill disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document due($date)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Bill exists()
 * @method static \Database\Factories\Document factory(...$parameters)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Bill flushCache(array $tags = [])
 * @method static \Plank\Mediable\MediableCollection|static[] get($columns = ['*'])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Bill getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Bill inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Bill insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document invoice()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Bill isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document latest()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Bill max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Bill min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Bill newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Bill newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document notPaid()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document number(string $number)
 * @method static \Illuminate\Database\Query\Builder|Bill onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document paid()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Bill query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Bill sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Bill truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document type(string $type)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document whereHasMedia($tags = [], bool $matchAll = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document whereHasMediaMatchAll(array $tags)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document withMedia($tags = [], bool $matchAll = false, bool $withVariants = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document withMediaAndVariants($tags = [], bool $matchAll = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document withMediaAndVariantsMatchAll($tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document withMediaMatchAll(bool $tags = [], bool $withVariants = false)
 * @method static \Illuminate\Database\Query\Builder|Bill withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Bill withoutTrashed()
 */
	class Bill extends \Eloquent {}
}

namespace App\Models\Purchase{
/**
 * App\Models\Purchase\BillHistory
 *
 * @deprecated 
 * @see DocumentHistory
 * @property int $id
 * @property int $company_id
 * @property string $type
 * @property int $document_id
 * @property string $status
 * @property int $notify
 * @property string|null $description
 * @property string|null $created_from
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Document\Document|null $document
 * @property mixed $bill_id
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillHistory all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillHistory avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory bill()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillHistory cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillHistory cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillHistory count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillHistory disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillHistory exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillHistory flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillHistory getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillHistory inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillHistory insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory invoice()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillHistory isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillHistory max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillHistory min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillHistory newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillHistory newQuery()
 * @method static \Illuminate\Database\Query\Builder|BillHistory onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillHistory query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillHistory sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillHistory truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory type(string $type)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|BillHistory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|BillHistory withoutTrashed()
 */
	class BillHistory extends \Eloquent {}
}

namespace App\Models\Purchase{
/**
 * App\Models\Purchase\BillItem
 *
 * @deprecated 
 * @see DocumentItem
 * @property int $id
 * @property int $company_id
 * @property string $type
 * @property int $document_id
 * @property int|null $item_id
 * @property string $name
 * @property string|null $description
 * @property string|null $sku
 * @property float $quantity
 * @property float $price
 * @property float $tax
 * @property string $discount_type
 * @property float $discount_rate
 * @property float $total
 * @property string|null $created_from
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Document\Document|null $document
 * @property mixed $bill_id
 * @property-read string $discount
 * @property-read \App\Models\Common\Item|null $item
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentItemTax[] $taxes
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItem all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItem avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem bill()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItem cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItem cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItem count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItem disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItem exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItem flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItem getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItem inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItem insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem invoice()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItem isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItem max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItem min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItem newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItem newQuery()
 * @method static \Illuminate\Database\Query\Builder|BillItem onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItem query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItem sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItem truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem type(string $type)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|BillItem withTrashed()
 * @method static \Illuminate\Database\Query\Builder|BillItem withoutTrashed()
 */
	class BillItem extends \Eloquent {}
}

namespace App\Models\Purchase{
/**
 * App\Models\Purchase\BillItemTax
 *
 * @deprecated 
 * @see DocumentItemTax
 * @property int $id
 * @property int $company_id
 * @property string $type
 * @property int $document_id
 * @property int $document_item_id
 * @property int $tax_id
 * @property string $name
 * @property float $amount
 * @property string|null $created_from
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Document\Document|null $document
 * @property mixed $bill_id
 * @property mixed $bill_item_id
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \App\Models\Setting\Tax|null $tax
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItemTax all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItemTax avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax bill()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItemTax cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItemTax cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItemTax count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItemTax disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItemTax exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItemTax flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItemTax getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItemTax inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItemTax insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax invoice()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItemTax isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItemTax max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItemTax min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItemTax newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItemTax newQuery()
 * @method static \Illuminate\Database\Query\Builder|BillItemTax onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItemTax query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItemTax sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillItemTax truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax type(string $type)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|BillItemTax withTrashed()
 * @method static \Illuminate\Database\Query\Builder|BillItemTax withoutTrashed()
 */
	class BillItemTax extends \Eloquent {}
}

namespace App\Models\Purchase{
/**
 * App\Models\Purchase\BillTotal
 *
 * @deprecated 
 * @see DocumentTotal
 * @property int $id
 * @property int $company_id
 * @property string $type
 * @property int $document_id
 * @property string|null $code
 * @property string $name
 * @property float $amount
 * @property int $sort_order
 * @property string|null $created_from
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Document\Document|null $document
 * @property mixed $bill_id
 * @property-read mixed $title
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillTotal all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillTotal avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal bill()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillTotal cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillTotal cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal code($code)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillTotal count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillTotal disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillTotal exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillTotal flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillTotal getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillTotal inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillTotal insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal invoice()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillTotal isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillTotal max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillTotal min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillTotal newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillTotal newQuery()
 * @method static \Illuminate\Database\Query\Builder|BillTotal onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillTotal query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillTotal sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|BillTotal truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal type(string $type)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|BillTotal withTrashed()
 * @method static \Illuminate\Database\Query\Builder|BillTotal withoutTrashed()
 */
	class BillTotal extends \Eloquent {}
}

namespace App\Models\Sale{
/**
 * App\Models\Sale\Invoice
 *
 * @deprecated 
 * @see Document
 * @property int $id
 * @property int $company_id
 * @property string $type
 * @property string $document_number
 * @property string|null $order_number
 * @property string $status
 * @property \Illuminate\Support\Carbon $issued_at
 * @property \Illuminate\Support\Carbon $due_at
 * @property float $amount
 * @property string $currency_code
 * @property float $currency_rate
 * @property int $category_id
 * @property int $contact_id
 * @property string $contact_name
 * @property string|null $contact_email
 * @property string|null $contact_tax_number
 * @property string|null $contact_phone
 * @property string|null $contact_address
 * @property string|null $contact_city
 * @property string|null $contact_zip_code
 * @property string|null $contact_state
 * @property string|null $contact_country
 * @property string|null $notes
 * @property string|null $footer
 * @property int $parent_id
 * @property string|null $created_from
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Setting\Category|null $category
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Common\Contact|null $contact
 * @property-read \App\Models\Setting\Currency|null $currency
 * @property-read string $amount_due
 * @property-read string $amount_without_tax
 * @property-read string $attachment
 * @property-read mixed $contact_location
 * @property-read string $discount
 * @property mixed $invoice_number
 * @property mixed $invoiced_at
 * @property-read string $paid
 * @property-read mixed $received_at
 * @property-read int $reconciled
 * @property-read mixed $sent_at
 * @property-read string $status_label
 * @property-read mixed $template_path
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentHistory[] $histories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentItemTax[] $item_taxes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentItem[] $items
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Common\Media[] $media
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \App\Models\Document\Document|null $parent
 * @property-read \App\Models\Common\Recurring|null $recurring
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentTotal[] $totals
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Banking\Transaction[] $transactions
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document accrued()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Invoice all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Invoice avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document bill()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Invoice cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Invoice cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Invoice count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Invoice disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document due($date)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Invoice exists()
 * @method static \Database\Factories\Document factory(...$parameters)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Invoice flushCache(array $tags = [])
 * @method static \Plank\Mediable\MediableCollection|static[] get($columns = ['*'])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Invoice getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Invoice inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Invoice insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document invoice()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Invoice isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document latest()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Invoice max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Invoice min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Invoice newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Invoice newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document notPaid()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document number(string $number)
 * @method static \Illuminate\Database\Query\Builder|Invoice onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document paid()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Invoice query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Invoice sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Invoice truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document type(string $type)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document whereHasMedia($tags = [], bool $matchAll = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document whereHasMediaMatchAll(array $tags)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document withMedia($tags = [], bool $matchAll = false, bool $withVariants = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document withMediaAndVariants($tags = [], bool $matchAll = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document withMediaAndVariantsMatchAll($tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Document withMediaMatchAll(bool $tags = [], bool $withVariants = false)
 * @method static \Illuminate\Database\Query\Builder|Invoice withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Invoice withoutTrashed()
 */
	class Invoice extends \Eloquent {}
}

namespace App\Models\Sale{
/**
 * App\Models\Sale\InvoiceHistory
 *
 * @deprecated 
 * @see DocumentHistory
 * @property int $id
 * @property int $company_id
 * @property string $type
 * @property int $document_id
 * @property string $status
 * @property int $notify
 * @property string|null $description
 * @property string|null $created_from
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Document\Document|null $document
 * @property mixed $invoice_id
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceHistory all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceHistory avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory bill()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceHistory cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceHistory cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceHistory count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceHistory disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceHistory exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceHistory flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceHistory getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceHistory inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceHistory insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory invoice()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceHistory isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceHistory max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceHistory min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceHistory newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceHistory newQuery()
 * @method static \Illuminate\Database\Query\Builder|InvoiceHistory onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceHistory query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceHistory sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceHistory truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentHistory type(string $type)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|InvoiceHistory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|InvoiceHistory withoutTrashed()
 */
	class InvoiceHistory extends \Eloquent {}
}

namespace App\Models\Sale{
/**
 * App\Models\Sale\InvoiceItem
 *
 * @deprecated 
 * @see DocumentItem
 * @property int $id
 * @property int $company_id
 * @property string $type
 * @property int $document_id
 * @property int|null $item_id
 * @property string $name
 * @property string|null $description
 * @property string|null $sku
 * @property float $quantity
 * @property float $price
 * @property float $tax
 * @property string $discount_type
 * @property float $discount_rate
 * @property float $total
 * @property string|null $created_from
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Document\Document|null $document
 * @property-read string $discount
 * @property mixed $invoice_id
 * @property-read \App\Models\Common\Item|null $item
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document\DocumentItemTax[] $taxes
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItem all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItem avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem bill()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItem cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItem cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItem count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItem disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItem exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItem flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItem getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItem inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItem insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem invoice()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItem isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItem max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItem min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItem newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItem newQuery()
 * @method static \Illuminate\Database\Query\Builder|InvoiceItem onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItem query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItem sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItem truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItem type(string $type)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|InvoiceItem withTrashed()
 * @method static \Illuminate\Database\Query\Builder|InvoiceItem withoutTrashed()
 */
	class InvoiceItem extends \Eloquent {}
}

namespace App\Models\Sale{
/**
 * App\Models\Sale\InvoiceItemTax
 *
 * @deprecated 
 * @see DocumentItemTax
 * @property int $id
 * @property int $company_id
 * @property string $type
 * @property int $document_id
 * @property int $document_item_id
 * @property int $tax_id
 * @property string $name
 * @property float $amount
 * @property string|null $created_from
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Document\Document|null $document
 * @property mixed $invoice_id
 * @property mixed $invoice_item_id
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \App\Models\Setting\Tax|null $tax
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItemTax all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItemTax avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax bill()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItemTax cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItemTax cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItemTax count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItemTax disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItemTax exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItemTax flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItemTax getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItemTax inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItemTax insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax invoice()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItemTax isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItemTax max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItemTax min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItemTax newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItemTax newQuery()
 * @method static \Illuminate\Database\Query\Builder|InvoiceItemTax onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItemTax query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItemTax sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceItemTax truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentItemTax type(string $type)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|InvoiceItemTax withTrashed()
 * @method static \Illuminate\Database\Query\Builder|InvoiceItemTax withoutTrashed()
 */
	class InvoiceItemTax extends \Eloquent {}
}

namespace App\Models\Sale{
/**
 * App\Models\Sale\InvoiceTotal
 *
 * @deprecated 
 * @see DocumentTotal
 * @property int $id
 * @property int $company_id
 * @property string $type
 * @property int $document_id
 * @property string|null $code
 * @property string $name
 * @property float $amount
 * @property int $sort_order
 * @property string|null $created_from
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Document\Document|null $document
 * @property mixed $invoice_id
 * @property-read mixed $title
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceTotal all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceTotal avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal bill()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceTotal cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceTotal cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal code($code)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceTotal count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceTotal disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceTotal exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceTotal flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceTotal getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceTotal inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceTotal insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal invoice()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceTotal isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceTotal max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceTotal min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceTotal newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceTotal newQuery()
 * @method static \Illuminate\Database\Query\Builder|InvoiceTotal onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceTotal query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceTotal sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|InvoiceTotal truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DocumentTotal type(string $type)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|InvoiceTotal withTrashed()
 * @method static \Illuminate\Database\Query\Builder|InvoiceTotal withoutTrashed()
 */
	class InvoiceTotal extends \Eloquent {}
}

namespace Modules\Church\Models{
/**
 * Modules\Church\Models\Church
 *
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property string|null $email
 * @property string $code
 * @property string|null $founded_on
 * @property string|null $photo
 * @property int|null $latitude
 * @property int|null $longitude
 * @property bool $enabled
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Church\Models\ChurchHistory[] $history
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Church\Models\PastorHistory[] $pastor_history
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Church\Models\PastorHistory[] $pastors
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Church\Models\PaymentDivision[] $payment_division
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Church\Models\PastorHistory[] $senior_pastor
 * @property-read \Modules\Church\Models\ChurchHistory|null $subregion
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Church all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Church avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Church cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Church cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Church count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Church disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Church exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Church flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Church getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Church inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Church insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Church isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Church max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Church min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Church newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Church newQuery()
 * @method static \Illuminate\Database\Query\Builder|Church onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Church query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Church sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Church truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|Church withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Church withoutTrashed()
 */
	class Church extends \Eloquent {}
}

namespace Modules\Church\Models{
/**
 * Modules\Church\Models\ChurchHistory
 *
 * @property int $id
 * @property int $church_id
 * @property int $demarcation_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Church\Models\Church|null $church
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \Modules\Demarcations\Models\Demarcation|null $demarcation
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchHistory all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchHistory avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchHistory cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchHistory cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchHistory count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchHistory disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchHistory exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchHistory flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchHistory getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchHistory inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchHistory insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchHistory isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchHistory max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchHistory min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchHistory newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchHistory newQuery()
 * @method static \Illuminate\Database\Query\Builder|ChurchHistory onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchHistory query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchHistory sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchHistory truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|ChurchHistory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ChurchHistory withoutTrashed()
 */
	class ChurchHistory extends \Eloquent {}
}

namespace Modules\Church\Models{
/**
 * Modules\Church\Models\ChurchTransaction
 *
 * @property int $id
 * @property int $church_id
 * @property int $transaction_id
 * @property string $captured_from
 * @property int $captured_by
 * @property int $week
 * @property string $month
 * @property int $year
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Church\Models\Church|null $church
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Plank\Mediable\MediableCollection|\App\Models\Banking\Transaction[] $transactions
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchTransaction all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchTransaction avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchTransaction cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchTransaction cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchTransaction count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchTransaction disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchTransaction exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchTransaction flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchTransaction getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchTransaction inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchTransaction insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchTransaction isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchTransaction max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchTransaction min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchTransaction newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchTransaction newQuery()
 * @method static \Illuminate\Database\Query\Builder|ChurchTransaction onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchTransaction query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchTransaction sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ChurchTransaction truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|ChurchTransaction withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ChurchTransaction withoutTrashed()
 */
	class ChurchTransaction extends \Eloquent {}
}

namespace Modules\Church\Models{
/**
 * Modules\Church\Models\Pastor
 *
 * @property int $id
 * @property int $company_id
 * @property int $contact_id
 * @property string|null $picture
 * @property string|null $date_of_birth
 * @property string $gender
 * @property string|null $id_or_passport
 * @property string|null $phone
 * @property string|null $phone2
 * @property string|null $ukumbozi_number
 * @property bool $enabled
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Church\Models\PastorHistory|null $church
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Common\Contact|null $contact
 * @property-read \Modules\Church\Models\PastorHistory|null $history
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Modules\Church\Models\PastorHistory|null $rank
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Church\Models\PastorHistory[] $rank_history
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Pastor all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Pastor avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Pastor cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Pastor cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Pastor count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Pastor disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Pastor exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Pastor flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Pastor getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Pastor inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Pastor insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Pastor isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Pastor max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Pastor min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Pastor newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Pastor newQuery()
 * @method static \Illuminate\Database\Query\Builder|Pastor onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Pastor query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Pastor sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Pastor truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|Pastor withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Pastor withoutTrashed()
 */
	class Pastor extends \Eloquent {}
}

namespace Modules\Church\Models{
/**
 * Modules\Church\Models\PastorHistory
 *
 * @property int $id
 * @property int $pastor_id
 * @property int $church_id
 * @property string|null $position
 * @property string|null $rank
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Church\Models\Church|null $church
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Modules\Church\Models\Pastor|null $pastor
 * @property-read \Modules\Church\Models\Church|null $ranked
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PastorHistory all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PastorHistory avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PastorHistory cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PastorHistory cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PastorHistory count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PastorHistory disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PastorHistory exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PastorHistory flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PastorHistory getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PastorHistory inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PastorHistory insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PastorHistory isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PastorHistory max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PastorHistory min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PastorHistory newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PastorHistory newQuery()
 * @method static \Illuminate\Database\Query\Builder|PastorHistory onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PastorHistory query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PastorHistory sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PastorHistory truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|PastorHistory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PastorHistory withoutTrashed()
 */
	class PastorHistory extends \Eloquent {}
}

namespace Modules\Church\Models{
/**
 * Modules\Church\Models\PaymentDivision
 *
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivision all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivision avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivision cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivision cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivision count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivision disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivision exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivision flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivision getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivision inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivision insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivision isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivision max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivision min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivision newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivision newQuery()
 * @method static \Illuminate\Database\Query\Builder|PaymentDivision onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivision query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivision sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivision truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|PaymentDivision withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PaymentDivision withoutTrashed()
 */
	class PaymentDivision extends \Eloquent {}
}

namespace Modules\Church\Models{
/**
 * Modules\Church\Models\PaymentDivisionView
 *
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivisionView all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivisionView avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivisionView cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivisionView cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivisionView count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivisionView disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivisionView exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivisionView flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivisionView getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivisionView inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivisionView insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivisionView isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivisionView max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivisionView min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivisionView newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivisionView newQuery()
 * @method static \Illuminate\Database\Query\Builder|PaymentDivisionView onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivisionView query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivisionView sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDivisionView truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|PaymentDivisionView withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PaymentDivisionView withoutTrashed()
 */
	class PaymentDivisionView extends \Eloquent {}
}

namespace Modules\Demarcations\Models{
/**
 * Modules\Demarcations\Models\Demarcation
 *
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property string $code
 * @property string $type
 * @property bool $enabled
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Church\Models\ChurchHistory[] $children
 * @property-read \App\Models\Common\Company|null $company
 * @property-read mixed $churches_count
 * @property-read mixed $demarcation_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Demarcations\Models\DemarcationHistory[] $history
 * @property-read \Modules\Demarcations\Models\DemarcationOverseer|null $overseer
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Modules\Demarcations\Models\DemarcationHistory|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Demarcations\Models\DemarcationOverseer[] $pastors
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Demarcations\Models\DemarcationHistory[] $subregions
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation newQuery()
 * @method static \Illuminate\Database\Query\Builder|Demarcation onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation region()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation subregion()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Demarcation truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|Demarcation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Demarcation withoutTrashed()
 */
	class Demarcation extends \Eloquent {}
}

namespace Modules\Demarcations\Models{
/**
 * Modules\Demarcations\Models\DemarcationHistory
 *
 * @property int $id
 * @property int $demarcation_id
 * @property int $parent_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \Modules\Demarcations\Models\Demarcation|null $demarcation
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Modules\Demarcations\Models\Demarcation|null $parent
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationHistory all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationHistory avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationHistory cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationHistory cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationHistory count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationHistory disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationHistory exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationHistory flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationHistory getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationHistory inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationHistory insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationHistory isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationHistory max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationHistory min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationHistory newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationHistory newQuery()
 * @method static \Illuminate\Database\Query\Builder|DemarcationHistory onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationHistory query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationHistory sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationHistory truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|DemarcationHistory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|DemarcationHistory withoutTrashed()
 */
	class DemarcationHistory extends \Eloquent {}
}

namespace Modules\Demarcations\Models{
/**
 * Modules\Demarcations\Models\DemarcationOverseer
 *
 * @property int $id
 * @property int $pastor_id
 * @property int $demarcation_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Modules\Church\Models\Pastor|null $pastor
 * @property-read \Modules\Church\Models\PastorHistory|null $rank
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationOverseer all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationOverseer avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationOverseer cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationOverseer cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationOverseer count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationOverseer disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationOverseer exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationOverseer flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationOverseer getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationOverseer inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationOverseer insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationOverseer isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationOverseer max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationOverseer min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationOverseer newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationOverseer newQuery()
 * @method static \Illuminate\Database\Query\Builder|DemarcationOverseer onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationOverseer query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationOverseer sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DemarcationOverseer truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|DemarcationOverseer withTrashed()
 * @method static \Illuminate\Database\Query\Builder|DemarcationOverseer withoutTrashed()
 */
	class DemarcationOverseer extends \Eloquent {}
}

namespace Modules\DoubleEntry\Models{
/**
 * Modules\DoubleEntry\Models\Account
 *
 * @property int $id
 * @property int $company_id
 * @property int $type_id
 * @property int|null $account_id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property bool $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Account[] $accounts
 * @property-read \Modules\DoubleEntry\Models\AccountBank|null $bank
 * @property-read \App\Models\Common\Company|null $company
 * @property-read double $balance
 * @property-read string $balance_colorized
 * @property-read string $balance_linked_general_ledger
 * @property-read double $balance_without_subaccounts
 * @property-read string $balance_without_subaccounts_colorized
 * @property-read double $credit_total
 * @property-read double $debit_total
 * @property-read string $name_linked_general_ledger
 * @property-read double $opening_balance
 * @property-read string $trans_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\DoubleEntry\Models\Ledger[] $ledgers
 * @property-read \App\Models\Auth\User|null $owner
 * @property-write mixed $end_date
 * @property-write mixed $start_date
 * @property-read \Illuminate\Database\Eloquent\Collection|Account[] $sub_accounts
 * @property-read \Modules\DoubleEntry\Models\AccountTax|null $tax
 * @property-read \Modules\DoubleEntry\Models\Type|null $type
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account code($code)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account exists()
 * @method static \Modules\DoubleEntry\Database\Factories\Account factory(...$parameters)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account inType($types)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account isNotSubAccount()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account newQuery()
 * @method static \Illuminate\Database\Query\Builder|Account onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Account truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|Account withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Account withoutTrashed()
 */
	class Account extends \Eloquent {}
}

namespace Modules\DoubleEntry\Models{
/**
 * Modules\DoubleEntry\Models\AccountBank
 *
 * @property int $id
 * @property int $company_id
 * @property int $account_id
 * @property int $bank_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Modules\DoubleEntry\Models\Account $account
 * @property-read \App\Models\Banking\Account|null $bank
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountBank all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountBank avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountBank cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountBank cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountBank count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountBank disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountBank exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountBank flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountBank getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountBank inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountBank insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountBank isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountBank max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountBank min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountBank newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountBank newQuery()
 * @method static \Illuminate\Database\Query\Builder|AccountBank onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountBank query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountBank sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountBank truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|AccountBank withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AccountBank withoutTrashed()
 */
	class AccountBank extends \Eloquent {}
}

namespace Modules\DoubleEntry\Models{
/**
 * Modules\DoubleEntry\Models\AccountItem
 *
 * @property int $id
 * @property int $company_id
 * @property int $account_id
 * @property int $item_id
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Modules\DoubleEntry\Models\Account $account
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Common\Item|null $item
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountItem all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountItem avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountItem cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountItem cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountItem count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountItem disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountItem exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountItem flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountItem getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountItem inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountItem insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountItem isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountItem max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountItem min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountItem newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountItem newQuery()
 * @method static \Illuminate\Database\Query\Builder|AccountItem onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountItem query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountItem sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountItem truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|AccountItem withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AccountItem withoutTrashed()
 */
	class AccountItem extends \Eloquent {}
}

namespace Modules\DoubleEntry\Models{
/**
 * Modules\DoubleEntry\Models\AccountTax
 *
 * @property int $id
 * @property int $company_id
 * @property int $account_id
 * @property int $tax_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Modules\DoubleEntry\Models\Account $account
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \App\Models\Setting\Tax|null $tax
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountTax all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountTax avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountTax cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountTax cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountTax count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountTax disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountTax exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountTax flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountTax getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountTax inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountTax insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountTax isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountTax max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountTax min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountTax newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountTax newQuery()
 * @method static \Illuminate\Database\Query\Builder|AccountTax onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountTax query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountTax sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|AccountTax truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|AccountTax withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AccountTax withoutTrashed()
 */
	class AccountTax extends \Eloquent {}
}

namespace Modules\DoubleEntry\Models{
/**
 * Modules\DoubleEntry\Models\DEClass
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\DoubleEntry\Models\Account[] $accounts
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\DoubleEntry\Models\Type[] $types
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DEClass all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DEClass avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DEClass cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DEClass cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DEClass count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DEClass disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DEClass exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DEClass flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DEClass getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DEClass inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DEClass insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DEClass isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DEClass max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DEClass min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DEClass newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DEClass newQuery()
 * @method static \Illuminate\Database\Query\Builder|DEClass onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DEClass query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DEClass sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|DEClass truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|DEClass withTrashed()
 * @method static \Illuminate\Database\Query\Builder|DEClass withoutTrashed()
 */
	class DEClass extends \Eloquent {}
}

namespace Modules\DoubleEntry\Models{
/**
 * Modules\DoubleEntry\Models\Journal
 *
 * @property int $id
 * @property int $company_id
 * @property string $paid_at
 * @property float $amount
 * @property string $description
 * @property string|null $reference
 * @property string|null $journal_number
 * @property string|null $basis
 * @property string|null $currency_code
 * @property float|null $currency_rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Common\Contact|null $contact
 * @property-read string $attachment
 * @property-read \Modules\DoubleEntry\Models\Ledger|null $ledger
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\DoubleEntry\Models\Ledger[] $ledgers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Common\Media[] $media
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal exists()
 * @method static \Modules\DoubleEntry\Database\Factories\Journal factory(...$parameters)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal flushCache(array $tags = [])
 * @method static \Plank\Mediable\MediableCollection|static[] get($columns = ['*'])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal newQuery()
 * @method static \Illuminate\Database\Query\Builder|Journal onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal whereHasMedia($tags = [], bool $matchAll = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal whereHasMediaMatchAll(array $tags)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal withMedia($tags = [], bool $matchAll = false, bool $withVariants = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal withMediaAndVariants($tags = [], bool $matchAll = false)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal withMediaAndVariantsMatchAll($tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Journal withMediaMatchAll(bool $tags = [], bool $withVariants = false)
 * @method static \Illuminate\Database\Query\Builder|Journal withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Journal withoutTrashed()
 */
	class Journal extends \Eloquent {}
}

namespace Modules\DoubleEntry\Models{
/**
 * Modules\DoubleEntry\Models\Ledger
 *
 * @property int $id
 * @property int $company_id
 * @property int $account_id
 * @property string $ledgerable_type
 * @property int $ledgerable_id
 * @property string $issued_at
 * @property string $entry_type
 * @property float|null $debit
 * @property float|null $credit
 * @property string|null $reference
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Modules\DoubleEntry\Models\Account $account
 * @property-read \App\Models\Common\Company|null $company
 * @property-read mixed $description
 * @property-read mixed $ledgerable_link
 * @property-read mixed $transaction
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ledgerable
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger accrual()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger cash()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger contact($contact)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger newQuery()
 * @method static \Illuminate\Database\Query\Builder|Ledger onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger record($id, $type)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Ledger truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|Ledger withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Ledger withoutTrashed()
 */
	class Ledger extends \Eloquent {}
}

namespace Modules\DoubleEntry\Models{
/**
 * Modules\DoubleEntry\Models\Type
 *
 * @property int $id
 * @property int $class_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\DoubleEntry\Models\Account[] $accounts
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \Modules\DoubleEntry\Models\DEClass|null $declass
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Type all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Type avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Type cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Type cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Type count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Type disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Type exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Type flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Type getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Type inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Type insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Type isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Type max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Type min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Type newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Type newQuery()
 * @method static \Illuminate\Database\Query\Builder|Type onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Type query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Type sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Type truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|Type withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Type withoutTrashed()
 */
	class Type extends \Eloquent {}
}

namespace Modules\GlPostingSettings\Models{
/**
 * Modules\GlPostingSettings\Models\GlPostingReceipt
 *
 * @property int $id
 * @property string $number
 * @property int $company_id
 * @property int $bank_integration_id
 * @property string $amount
 * @property int $ammendment
 * @property string|null $download_link
 * @property int $created_by
 * @property int $contact_id
 * @property string|null $class
 * @property string|null $mode_of_payment
 * @property string|null $currency
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $creator
 * @property-read \App\Models\Common\Contact|null $customer
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Modules\GlPostingSettings\Models\OnlineReconsiliation|null $recon
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingReceipt all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingReceipt avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingReceipt cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingReceipt cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingReceipt count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingReceipt disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingReceipt exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingReceipt flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingReceipt getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingReceipt inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingReceipt insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingReceipt isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingReceipt max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingReceipt min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingReceipt newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingReceipt newQuery()
 * @method static \Illuminate\Database\Query\Builder|GlPostingReceipt onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingReceipt query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingReceipt sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingReceipt truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|GlPostingReceipt withTrashed()
 * @method static \Illuminate\Database\Query\Builder|GlPostingReceipt withoutTrashed()
 */
	class GlPostingReceipt extends \Eloquent {}
}

namespace Modules\GlPostingSettings\Models{
/**
 * Modules\GlPostingSettings\Models\GlPostingSettingsSetting
 *
 * @property int $id
 * @property string $payment_type
 * @property string $account_type
 * @property string $income_ac_to_credit
 * @property string $cash_book_ac_to_dedit
 * @property string $debtors_acc
 * @property int $status
 * @property int $company_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingSettingsSetting all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingSettingsSetting avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingSettingsSetting cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingSettingsSetting cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingSettingsSetting count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingSettingsSetting disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingSettingsSetting exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingSettingsSetting flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingSettingsSetting getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingSettingsSetting inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingSettingsSetting insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingSettingsSetting isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingSettingsSetting max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingSettingsSetting min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingSettingsSetting newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingSettingsSetting newQuery()
 * @method static \Illuminate\Database\Query\Builder|GlPostingSettingsSetting onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingSettingsSetting query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingSettingsSetting sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|GlPostingSettingsSetting truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|GlPostingSettingsSetting withTrashed()
 * @method static \Illuminate\Database\Query\Builder|GlPostingSettingsSetting withoutTrashed()
 */
	class GlPostingSettingsSetting extends \Eloquent {}
}

namespace Modules\GlPostingSettings\Models{
/**
 * Modules\GlPostingSettings\Models\OnlineReconsiliation
 *
 * @property int $id
 * @property string $payload
 * @property string $bank
 * @property int $contact_id
 * @property int|null $account_id
 * @property int|null $de_account_id
 * @property string|null $reference
 * @property string|null $mode_of_payment
 * @property string $status
 * @property int $job_done
 * @property string|null $ip_address
 * @property string $payload_type
 * @property string|null $transaction_time
 * @property string|null $receipt_date
 * @property string|null $pay_load_hash
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Banking\Account|null $account
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\GlPostingSettings\Models\PaymentIntegrationConsensus[] $consensus_items
 * @property-read \App\Models\Common\Contact|null $customer
 * @property-read \App\Models\Auth\User|null $owner
 * @property-read \Modules\GlPostingSettings\Models\GlPostingReceipt|null $receipt
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OnlineReconsiliation all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OnlineReconsiliation avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OnlineReconsiliation cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OnlineReconsiliation cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OnlineReconsiliation count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OnlineReconsiliation disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OnlineReconsiliation exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OnlineReconsiliation flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OnlineReconsiliation getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OnlineReconsiliation inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OnlineReconsiliation insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OnlineReconsiliation isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OnlineReconsiliation max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OnlineReconsiliation min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OnlineReconsiliation newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OnlineReconsiliation newQuery()
 * @method static \Illuminate\Database\Query\Builder|OnlineReconsiliation onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OnlineReconsiliation query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OnlineReconsiliation sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OnlineReconsiliation truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|OnlineReconsiliation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|OnlineReconsiliation withoutTrashed()
 */
	class OnlineReconsiliation extends \Eloquent {}
}

namespace Modules\GlPostingSettings\Models{
/**
 * Modules\GlPostingSettings\Models\PaymentDistributionItem
 *
 * @property int $id
 * @property int $company_id
 * @property int $bank_integration_payload_id
 * @property int $document_id
 * @property int $item_id
 * @property string $amount
 * @property string|null $set_account
 * @property int|null $set_account_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDistributionItem all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDistributionItem avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDistributionItem cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDistributionItem cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDistributionItem count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDistributionItem disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDistributionItem exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDistributionItem flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDistributionItem getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDistributionItem inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDistributionItem insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDistributionItem isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDistributionItem max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDistributionItem min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDistributionItem newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDistributionItem newQuery()
 * @method static \Illuminate\Database\Query\Builder|PaymentDistributionItem onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDistributionItem query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDistributionItem sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentDistributionItem truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|PaymentDistributionItem withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PaymentDistributionItem withoutTrashed()
 */
	class PaymentDistributionItem extends \Eloquent {}
}

namespace Modules\GlPostingSettings\Models{
/**
 * Modules\GlPostingSettings\Models\PaymentIntegrationConsensus
 *
 * @property int $id
 * @property int $bank_integration_payload_id
 * @property string $document_type
 * @property string $document_number
 * @property int|null $document_id
 * @property int|null $document_item_id
 * @property int $item_id
 * @property string|null $item_name
 * @property int $contact_id
 * @property string $invoice_amount
 * @property string $invoice_amount_tax
 * @property string $paid_amount
 * @property string $payable_amount
 * @property int $fulfilment_status
 * @property int|null $payment_id
 * @property int|null $payable_account
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\DoubleEntry\Models\Account|null $account
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Document\DocumentItem|null $document_item
 * @property-read \App\Models\Document\Document|null $invoice
 * @property-read \App\Models\Common\Item|null $item
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentIntegrationConsensus all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentIntegrationConsensus avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentIntegrationConsensus cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentIntegrationConsensus cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentIntegrationConsensus count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentIntegrationConsensus disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentIntegrationConsensus exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentIntegrationConsensus flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentIntegrationConsensus getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentIntegrationConsensus inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentIntegrationConsensus insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentIntegrationConsensus isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentIntegrationConsensus max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentIntegrationConsensus min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentIntegrationConsensus newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentIntegrationConsensus newQuery()
 * @method static \Illuminate\Database\Query\Builder|PaymentIntegrationConsensus onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentIntegrationConsensus query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentIntegrationConsensus sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentIntegrationConsensus truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|PaymentIntegrationConsensus withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PaymentIntegrationConsensus withoutTrashed()
 */
	class PaymentIntegrationConsensus extends \Eloquent {}
}

namespace Modules\GlPostingSettings\Models{
/**
 * Modules\GlPostingSettings\Models\ShearTransaction
 *
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ShearTransaction all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ShearTransaction avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ShearTransaction cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ShearTransaction cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ShearTransaction count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ShearTransaction disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ShearTransaction exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ShearTransaction flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ShearTransaction getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ShearTransaction inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ShearTransaction insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ShearTransaction isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ShearTransaction max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ShearTransaction min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ShearTransaction newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ShearTransaction newQuery()
 * @method static \Illuminate\Database\Query\Builder|ShearTransaction onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ShearTransaction query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ShearTransaction sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ShearTransaction truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|ShearTransaction withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ShearTransaction withoutTrashed()
 */
	class ShearTransaction extends \Eloquent {}
}

namespace Modules\GlPostingSettings\Models{
/**
 * Modules\GlPostingSettings\Models\ViewDeAccountsWithClas
 *
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ViewDeAccountsWithClas all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ViewDeAccountsWithClas avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ViewDeAccountsWithClas cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ViewDeAccountsWithClas cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ViewDeAccountsWithClas count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ViewDeAccountsWithClas disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ViewDeAccountsWithClas exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ViewDeAccountsWithClas flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ViewDeAccountsWithClas getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ViewDeAccountsWithClas inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ViewDeAccountsWithClas insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ViewDeAccountsWithClas isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ViewDeAccountsWithClas max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ViewDeAccountsWithClas min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ViewDeAccountsWithClas newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ViewDeAccountsWithClas newQuery()
 * @method static \Illuminate\Database\Query\Builder|ViewDeAccountsWithClas onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ViewDeAccountsWithClas query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ViewDeAccountsWithClas sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|ViewDeAccountsWithClas truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|ViewDeAccountsWithClas withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ViewDeAccountsWithClas withoutTrashed()
 */
	class ViewDeAccountsWithClas extends \Eloquent {}
}

namespace Modules\PaymentReception\Models{
/**
 * Modules\PaymentReception\Models\PaymentReception
 *
 * @property int $id
 * @property int $church_id
 * @property string $payment_mode
 * @property string|null $reference
 * @property string|null $phone_number
 * @property string|null $payer_name
 * @property string|null $payer_phone
 * @property string|null $payer_email
 * @property int|null $created_by
 * @property string|null $status
 * @property int|null $posted_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Church\Models\Church|null $church
 * @property-read \App\Models\Common\Company|null $company
 * @property-read mixed $total
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\PaymentReception\Models\PaymentReceptionItems[] $items
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReception all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReception avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReception cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReception cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReception count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReception disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReception exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReception flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReception getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReception inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReception insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReception isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReception max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReception min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReception newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReception newQuery()
 * @method static \Illuminate\Database\Query\Builder|PaymentReception onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReception query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReception sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReception truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|PaymentReception withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PaymentReception withoutTrashed()
 */
	class PaymentReception extends \Eloquent {}
}

namespace Modules\PaymentReception\Models{
/**
 * Modules\PaymentReception\Models\PaymentReceptionItems
 *
 * @property int $id
 * @property int $payment_reception_id
 * @property int $year
 * @property string $month
 * @property int $week
 * @property float $amount
 * @property float $topup
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Common\Company|null $company
 * @property-read \App\Models\Auth\User|null $owner
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model account($accounts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReceptionItems all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model allCompanies()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReceptionItems avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReceptionItems cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReceptionItems cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collect($sort = 'name')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model collectForExport($ids = [], $sort = 'name', $id_field = 'id')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model companyId($company_id)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model contact($contacts)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReceptionItems count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReceptionItems disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model disabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model enabled()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReceptionItems exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReceptionItems flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReceptionItems getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReceptionItems inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReceptionItems insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReceptionItems isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isNotOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model isOwner()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReceptionItems max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReceptionItems min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model monthsOfYear($field)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReceptionItems newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReceptionItems newQuery()
 * @method static \Illuminate\Database\Query\Builder|PaymentReceptionItems onlyTrashed()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReceptionItems query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model reconciled($value = 1)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model sortable($defaultParameters = null)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model source($source)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReceptionItems sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|PaymentReceptionItems truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model usingSearchString()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|Model withCacheCooldownSeconds(?int $seconds = null)
 * @method static \Illuminate\Database\Query\Builder|PaymentReceptionItems withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PaymentReceptionItems withoutTrashed()
 */
	class PaymentReceptionItems extends \Eloquent {}
}

