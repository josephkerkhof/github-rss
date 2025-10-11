import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::store
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:38
* @route '/repositories'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/repositories',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::store
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:38
* @route '/repositories'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::store
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:38
* @route '/repositories'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::store
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:38
* @route '/repositories'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::store
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:38
* @route '/repositories'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::update
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:55
* @route '/repositories/{repository}'
*/
export const update = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/repositories/{repository}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::update
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:55
* @route '/repositories/{repository}'
*/
update.url = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { repository: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
        args = { repository: args.uuid }
    }

    if (Array.isArray(args)) {
        args = {
            repository: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        repository: typeof args.repository === 'object'
        ? args.repository.uuid
        : args.repository,
    }

    return update.definition.url
            .replace('{repository}', parsedArgs.repository.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::update
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:55
* @route '/repositories/{repository}'
*/
update.put = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::update
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:55
* @route '/repositories/{repository}'
*/
const updateForm = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::update
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:55
* @route '/repositories/{repository}'
*/
updateForm.put = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::destroy
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:70
* @route '/repositories/{repository}'
*/
export const destroy = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/repositories/{repository}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::destroy
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:70
* @route '/repositories/{repository}'
*/
destroy.url = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { repository: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
        args = { repository: args.uuid }
    }

    if (Array.isArray(args)) {
        args = {
            repository: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        repository: typeof args.repository === 'object'
        ? args.repository.uuid
        : args.repository,
    }

    return destroy.definition.url
            .replace('{repository}', parsedArgs.repository.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::destroy
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:70
* @route '/repositories/{repository}'
*/
destroy.delete = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::destroy
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:70
* @route '/repositories/{repository}'
*/
const destroyForm = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::destroy
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:70
* @route '/repositories/{repository}'
*/
destroyForm.delete = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const repositories = {
    store: Object.assign(store, store),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default repositories