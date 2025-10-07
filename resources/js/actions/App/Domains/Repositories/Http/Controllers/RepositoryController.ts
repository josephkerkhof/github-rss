import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::index
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:20
* @route '/api/repositories'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/repositories',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::index
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:20
* @route '/api/repositories'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::index
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:20
* @route '/api/repositories'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::index
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:20
* @route '/api/repositories'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::index
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:20
* @route '/api/repositories'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::index
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:20
* @route '/api/repositories'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Domains\Repositories\Http\Controllers\RepositoryController::index
* @see app/Domains/Repositories/Http/Controllers/RepositoryController.php:20
* @route '/api/repositories'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

const RepositoryController = { index }

export default RepositoryController