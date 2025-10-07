import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Settings\TokenController::edit
* @see app/Http/Controllers/Settings/TokenController.php:14
* @route '/settings/tokens'
*/
export const edit = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/settings/tokens',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Settings\TokenController::edit
* @see app/Http/Controllers/Settings/TokenController.php:14
* @route '/settings/tokens'
*/
edit.url = (options?: RouteQueryOptions) => {
    return edit.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\TokenController::edit
* @see app/Http/Controllers/Settings/TokenController.php:14
* @route '/settings/tokens'
*/
edit.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\TokenController::edit
* @see app/Http/Controllers/Settings/TokenController.php:14
* @route '/settings/tokens'
*/
edit.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Settings\TokenController::edit
* @see app/Http/Controllers/Settings/TokenController.php:14
* @route '/settings/tokens'
*/
const editForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\TokenController::edit
* @see app/Http/Controllers/Settings/TokenController.php:14
* @route '/settings/tokens'
*/
editForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\TokenController::edit
* @see app/Http/Controllers/Settings/TokenController.php:14
* @route '/settings/tokens'
*/
editForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

/**
* @see \App\Http\Controllers\Settings\TokenController::store
* @see app/Http/Controllers/Settings/TokenController.php:27
* @route '/settings/tokens'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/settings/tokens',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Settings\TokenController::store
* @see app/Http/Controllers/Settings/TokenController.php:27
* @route '/settings/tokens'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\TokenController::store
* @see app/Http/Controllers/Settings/TokenController.php:27
* @route '/settings/tokens'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Settings\TokenController::store
* @see app/Http/Controllers/Settings/TokenController.php:27
* @route '/settings/tokens'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Settings\TokenController::store
* @see app/Http/Controllers/Settings/TokenController.php:27
* @route '/settings/tokens'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Settings\TokenController::destroy
* @see app/Http/Controllers/Settings/TokenController.php:38
* @route '/settings/tokens/{tokenId}'
*/
export const destroy = (args: { tokenId: string | number } | [tokenId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/settings/tokens/{tokenId}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Settings\TokenController::destroy
* @see app/Http/Controllers/Settings/TokenController.php:38
* @route '/settings/tokens/{tokenId}'
*/
destroy.url = (args: { tokenId: string | number } | [tokenId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tokenId: args }
    }

    if (Array.isArray(args)) {
        args = {
            tokenId: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        tokenId: args.tokenId,
    }

    return destroy.definition.url
            .replace('{tokenId}', parsedArgs.tokenId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\TokenController::destroy
* @see app/Http/Controllers/Settings/TokenController.php:38
* @route '/settings/tokens/{tokenId}'
*/
destroy.delete = (args: { tokenId: string | number } | [tokenId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Settings\TokenController::destroy
* @see app/Http/Controllers/Settings/TokenController.php:38
* @route '/settings/tokens/{tokenId}'
*/
const destroyForm = (args: { tokenId: string | number } | [tokenId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Settings\TokenController::destroy
* @see app/Http/Controllers/Settings/TokenController.php:38
* @route '/settings/tokens/{tokenId}'
*/
destroyForm.delete = (args: { tokenId: string | number } | [tokenId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const TokenController = { edit, store, destroy }

export default TokenController