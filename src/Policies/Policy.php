<?php

namespace GrapheneICT\NovaPermissions\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
	use HandlesAuthorization;
	
	/**
	 * Determine whether the user can create models.
	 *
	 * @param  User $user
	 * @return mixed
	 */
	public function create(User $user)
	{
		return $user->hasPermissionTo(static::$key . '.create');
	}
	
	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param  User $user
	 * @return mixed
	 */
	public function delete(User $user)
	{
		return $user->hasPermissionTo(static::$key . '.delete');
	}
	
	/**
	 * Determine whether the user can permanently delete the model.
	 *
	 * @param  User $user
	 * @return mixed
	 */
	public function forceDelete(User $user)
	{
		return $user->hasPermissionTo(static::$key . '.destroy');
	}
	
	/**
	 * Determine whether the user can restore the model.
	 *
	 * @param  User $user
	 * @return mixed
	 */
	public function restore(User $user)
	{
		return $user->hasPermissionTo(static::$key . '.restore');
	}
	
	/**
	 * Determine whether the user can update the model.
	 *
	 * @param  User $user
	 * @return mixed
	 */
	public function update(User $user)
	{
		return $user->hasPermissionTo(static::$key . '.update');
	}
	
	/**
	 * Determine whether the user can view the model.
	 *
	 * @param  User $user
	 * @return mixed
	 */
	public function view(User $user)
	{
		return $user->hasPermissionTo(static::$key . '.view');
	}
	
	/**
	 * @param User $user
	 *
	 * @return mixed
	 */
	public function viewAny(User $user)
	{
		return $user->hasPermissionTo(static::$key . '.view');
	}
}