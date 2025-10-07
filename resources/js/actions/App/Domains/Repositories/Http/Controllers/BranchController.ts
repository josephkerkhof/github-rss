import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Domains\Repositories\Http\Controllers\BranchController::index
* @see app/Domains/Repositories/Http/Controllers/BranchController.php:20
* @route '/api/{repository}/branches'
*/
export const index = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/{repository}/branches',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Domains\Repositories\Http\Controllers\BranchController::index
* @see app/Domains/Repositories/Http/Controllers/BranchController.php:20
* @route '/api/{repository}/branches'
*/
index.url = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions) => {
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

    return index.definition.url
            .replace('{repository}', parsedArgs.repository.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Domains\Repositories\Http\Controllers\BranchController::index
* @see app/Domains/Repositories/Http/Controllers/BranchController.php:20
* @route '/api/{repository}/branches'
*/
index.get = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Domains\Repositories\Http\Controllers\BranchController::index
* @see app/Domains/Repositories/Http/Controllers/BranchController.php:20
* @route '/api/{repository}/branches'
*/
index.head = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

/**
* @see \App\Domains\Repositories\Http\Controllers\BranchController::index
* @see app/Domains/Repositories/Http/Controllers/BranchController.php:20
* @route '/api/{repository}/branches'
*/
const indexForm = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Domains\Repositories\Http\Controllers\BranchController::index
* @see app/Domains/Repositories/Http/Controllers/BranchController.php:20
* @route '/api/{repository}/branches'
*/
indexForm.get = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Domains\Repositories\Http\Controllers\BranchController::index
* @see app/Domains/Repositories/Http/Controllers/BranchController.php:20
* @route '/api/{repository}/branches'
*/
indexForm.head = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

const BranchController = { index }

export default BranchController