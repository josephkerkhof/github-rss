<script setup lang="ts">
import TokenController from '@/actions/App/Http/Controllers/Settings/TokenController';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { edit } from '@/routes/tokens';
import { Form, Head, router } from '@inertiajs/vue3';
import { computed } from 'vue';

import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { type BreadcrumbItem } from '@/types';

interface Token {
    id: number;
    name: string;
    last_used_at: string | null;
    created_at: string;
}

interface Props {
    tokens: Token[];
    token?: string;
}

const props = defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Token settings',
        href: edit().url,
    },
];

const plainTextToken = computed(() => props.token);

const deleteToken = (tokenId: number) => {
    router.delete(TokenController.destroy(tokenId).url, {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Token settings" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall
                    title="API tokens"
                    description="Manage your personal access tokens for API authentication"
                />

                <!-- Create Token Form -->
                <Card>
                    <CardHeader>
                        <CardTitle>Create new token</CardTitle>
                        <CardDescription>
                            Create a new personal access token
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Form
                            v-bind="TokenController.store.form()"
                            :options="{
                                preserveScroll: true,
                            }"
                            reset-on-success
                            class="space-y-4"
                            v-slot="{ errors, processing }"
                        >
                            <div class="grid gap-2">
                                <Label for="name">Token name</Label>
                                <Input
                                    id="name"
                                    name="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    placeholder="My API Token"
                                />
                                <InputError :message="errors.name" />
                            </div>

                            <Button :disabled="processing">
                                Create token
                            </Button>
                        </Form>

                        <!-- Show plain text token after creation -->
                        <div
                            v-if="plainTextToken"
                            class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md"
                        >
                            <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200 mb-2">
                                Token created successfully! Make sure to copy it now, you won't be able to see it again.
                            </p>
                            <code class="block p-2 bg-white dark:bg-gray-950 rounded text-sm break-all">
                                {{ plainTextToken }}
                            </code>
                        </div>
                    </CardContent>
                </Card>

                <!-- Existing Tokens -->
                <Card>
                    <CardHeader>
                        <CardTitle>Existing tokens</CardTitle>
                        <CardDescription>
                            Manage your existing personal access tokens
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div v-if="tokens.length === 0" class="text-sm text-muted-foreground">
                            No tokens created yet.
                        </div>
                        <div v-else class="space-y-4">
                            <div
                                v-for="token in tokens"
                                :key="token.id"
                                class="flex items-center justify-between p-4 border rounded-md"
                            >
                                <div class="flex-1">
                                    <h4 class="font-medium">{{ token.name }}</h4>
                                    <p class="text-sm text-muted-foreground">
                                        Created {{ token.created_at }}
                                        <span v-if="token.last_used_at">
                                            • Last used {{ token.last_used_at }}
                                        </span>
                                        <span v-else>
                                            • Never used
                                        </span>
                                    </p>
                                </div>
                                <Dialog>
                                    <DialogTrigger as-child>
                                        <Button variant="destructive" size="sm">
                                            Delete
                                        </Button>
                                    </DialogTrigger>
                                    <DialogContent>
                                        <DialogHeader>
                                            <DialogTitle>
                                                Delete token?
                                            </DialogTitle>
                                            <DialogDescription>
                                                This action cannot be undone. Any applications using this token will no longer be able to access the API.
                                            </DialogDescription>
                                        </DialogHeader>
                                        <DialogFooter>
                                            <DialogClose as-child>
                                                <Button variant="outline">Cancel</Button>
                                            </DialogClose>
                                            <Button
                                                variant="destructive"
                                                @click="deleteToken(token.id)"
                                            >
                                                Delete
                                            </Button>
                                        </DialogFooter>
                                    </DialogContent>
                                </Dialog>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
