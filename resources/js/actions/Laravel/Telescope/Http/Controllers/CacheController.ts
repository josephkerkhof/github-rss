import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \Laravel\Telescope\Http\Controllers\CacheController::index
* @see vendor/laravel/telescope/src/Http/Controllers/CacheController.php:33
* @route '/telescope/telescope-api/cache'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: index.url(options),
    method: 'post',
})

index.definition = {
    methods: ["post"],
    url: '/telescope/telescope-api/cache',
} satisfies RouteDefinition<["post"]>

/**
* @see \Laravel\Telescope\Http\Controllers\CacheController::index
* @see vendor/laravel/telescope/src/Http/Controllers/CacheController.php:33
* @route '/telescope/telescope-api/cache'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \Laravel\Telescope\Http\Controllers\CacheController::index
* @see vendor/laravel/telescope/src/Http/Controllers/CacheController.php:33
* @route '/telescope/telescope-api/cache'
*/
index.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: index.url(options),
    method: 'post',
})

/**
* @see \Laravel\Telescope\Http\Controllers\CacheController::index
* @see vendor/laravel/telescope/src/Http/Controllers/CacheController.php:33
* @route '/telescope/telescope-api/cache'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: index.url(options),
    method: 'post',
})

/**
* @see \Laravel\Telescope\Http\Controllers\CacheController::index
* @see vendor/laravel/telescope/src/Http/Controllers/CacheController.php:33
* @route '/telescope/telescope-api/cache'
*/
indexForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: index.url(options),
    method: 'post',
})

index.form = indexForm

/**
* @see \Laravel\Telescope\Http\Controllers\CacheController::show
* @see vendor/laravel/telescope/src/Http/Controllers/CacheController.php:51
* @route '/telescope/telescope-api/cache/{telescopeEntryId}'
*/
export const show = (args: { telescopeEntryId: string | number } | [telescopeEntryId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/telescope/telescope-api/cache/{telescopeEntryId}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Laravel\Telescope\Http\Controllers\CacheController::show
* @see vendor/laravel/telescope/src/Http/Controllers/CacheController.php:51
* @route '/telescope/telescope-api/cache/{telescopeEntryId}'
*/
show.url = (args: { telescopeEntryId: string | number } | [telescopeEntryId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { telescopeEntryId: args }
    }

    if (Array.isArray(args)) {
        args = {
            telescopeEntryId: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        telescopeEntryId: args.telescopeEntryId,
    }

    return show.definition.url
            .replace('{telescopeEntryId}', parsedArgs.telescopeEntryId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \Laravel\Telescope\Http\Controllers\CacheController::show
* @see vendor/laravel/telescope/src/Http/Controllers/CacheController.php:51
* @route '/telescope/telescope-api/cache/{telescopeEntryId}'
*/
show.get = (args: { telescopeEntryId: string | number } | [telescopeEntryId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \Laravel\Telescope\Http\Controllers\CacheController::show
* @see vendor/laravel/telescope/src/Http/Controllers/CacheController.php:51
* @route '/telescope/telescope-api/cache/{telescopeEntryId}'
*/
show.head = (args: { telescopeEntryId: string | number } | [telescopeEntryId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \Laravel\Telescope\Http\Controllers\CacheController::show
* @see vendor/laravel/telescope/src/Http/Controllers/CacheController.php:51
* @route '/telescope/telescope-api/cache/{telescopeEntryId}'
*/
const showForm = (args: { telescopeEntryId: string | number } | [telescopeEntryId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \Laravel\Telescope\Http\Controllers\CacheController::show
* @see vendor/laravel/telescope/src/Http/Controllers/CacheController.php:51
* @route '/telescope/telescope-api/cache/{telescopeEntryId}'
*/
showForm.get = (args: { telescopeEntryId: string | number } | [telescopeEntryId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \Laravel\Telescope\Http\Controllers\CacheController::show
* @see vendor/laravel/telescope/src/Http/Controllers/CacheController.php:51
* @route '/telescope/telescope-api/cache/{telescopeEntryId}'
*/
showForm.head = (args: { telescopeEntryId: string | number } | [telescopeEntryId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

const CacheController = { index, show }

export default CacheController