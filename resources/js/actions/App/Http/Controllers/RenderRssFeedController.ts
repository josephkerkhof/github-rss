import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\RenderRssFeedController::__invoke
* @see app/Http/Controllers/RenderRssFeedController.php:11
* @route '/repos/{owner}/{repo}/pulls/merged.rss'
*/
const RenderRssFeedController = (args: { owner: string | number, repo: string | number } | [owner: string | number, repo: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RenderRssFeedController.url(args, options),
    method: 'get',
})

RenderRssFeedController.definition = {
    methods: ["get","head"],
    url: '/repos/{owner}/{repo}/pulls/merged.rss',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\RenderRssFeedController::__invoke
* @see app/Http/Controllers/RenderRssFeedController.php:11
* @route '/repos/{owner}/{repo}/pulls/merged.rss'
*/
RenderRssFeedController.url = (args: { owner: string | number, repo: string | number } | [owner: string | number, repo: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            owner: args[0],
            repo: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        owner: args.owner,
        repo: args.repo,
    }

    return RenderRssFeedController.definition.url
            .replace('{owner}', parsedArgs.owner.toString())
            .replace('{repo}', parsedArgs.repo.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RenderRssFeedController::__invoke
* @see app/Http/Controllers/RenderRssFeedController.php:11
* @route '/repos/{owner}/{repo}/pulls/merged.rss'
*/
RenderRssFeedController.get = (args: { owner: string | number, repo: string | number } | [owner: string | number, repo: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RenderRssFeedController.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\RenderRssFeedController::__invoke
* @see app/Http/Controllers/RenderRssFeedController.php:11
* @route '/repos/{owner}/{repo}/pulls/merged.rss'
*/
RenderRssFeedController.head = (args: { owner: string | number, repo: string | number } | [owner: string | number, repo: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RenderRssFeedController.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\RenderRssFeedController::__invoke
* @see app/Http/Controllers/RenderRssFeedController.php:11
* @route '/repos/{owner}/{repo}/pulls/merged.rss'
*/
const RenderRssFeedControllerForm = (args: { owner: string | number, repo: string | number } | [owner: string | number, repo: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RenderRssFeedController.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\RenderRssFeedController::__invoke
* @see app/Http/Controllers/RenderRssFeedController.php:11
* @route '/repos/{owner}/{repo}/pulls/merged.rss'
*/
RenderRssFeedControllerForm.get = (args: { owner: string | number, repo: string | number } | [owner: string | number, repo: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RenderRssFeedController.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\RenderRssFeedController::__invoke
* @see app/Http/Controllers/RenderRssFeedController.php:11
* @route '/repos/{owner}/{repo}/pulls/merged.rss'
*/
RenderRssFeedControllerForm.head = (args: { owner: string | number, repo: string | number } | [owner: string | number, repo: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: RenderRssFeedController.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

RenderRssFeedController.form = RenderRssFeedControllerForm

export default RenderRssFeedController