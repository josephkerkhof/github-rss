import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Domains\Repositories\Http\Controllers\SearchPullRequestController::__invoke
* @see app/Domains/Repositories/Http/Controllers/SearchPullRequestController.php:23
* @route '/api/{repository}/list'
*/
const SearchPullRequestController = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: SearchPullRequestController.url(args, options),
    method: 'post',
})

SearchPullRequestController.definition = {
    methods: ["post"],
    url: '/api/{repository}/list',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Domains\Repositories\Http\Controllers\SearchPullRequestController::__invoke
* @see app/Domains/Repositories/Http/Controllers/SearchPullRequestController.php:23
* @route '/api/{repository}/list'
*/
SearchPullRequestController.url = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions) => {
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

    return SearchPullRequestController.definition.url
            .replace('{repository}', parsedArgs.repository.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Domains\Repositories\Http\Controllers\SearchPullRequestController::__invoke
* @see app/Domains/Repositories/Http/Controllers/SearchPullRequestController.php:23
* @route '/api/{repository}/list'
*/
SearchPullRequestController.post = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: SearchPullRequestController.url(args, options),
    method: 'post',
})

/**
* @see \App\Domains\Repositories\Http\Controllers\SearchPullRequestController::__invoke
* @see app/Domains/Repositories/Http/Controllers/SearchPullRequestController.php:23
* @route '/api/{repository}/list'
*/
const SearchPullRequestControllerForm = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: SearchPullRequestController.url(args, options),
    method: 'post',
})

/**
* @see \App\Domains\Repositories\Http\Controllers\SearchPullRequestController::__invoke
* @see app/Domains/Repositories/Http/Controllers/SearchPullRequestController.php:23
* @route '/api/{repository}/list'
*/
SearchPullRequestControllerForm.post = (args: { repository: string | number | { uuid: string | number } } | [repository: string | number | { uuid: string | number } ] | string | number | { uuid: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: SearchPullRequestController.url(args, options),
    method: 'post',
})

SearchPullRequestController.form = SearchPullRequestControllerForm

export default SearchPullRequestController