<script setup lang="ts">
import { ref } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
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

const page = usePage();

interface Repository {
    uuid: string;
    name: string;
    slug: string;
    owner: string;
    repo: string;
}

interface Props {
    repositories: Repository[];
}

interface FormData {
    name: string;
    owner: string;
    repo: string;
}

interface ValidationErrors {
    name?: string;
    owner?: string;
    repo?: string;
}

defineProps<Props>();

const isDialogOpen = ref(false);
const editingRepository = ref<Repository | null>(null);

const formData = ref<FormData>({
    name: '',
    owner: '',
    repo: '',
});

const errors = ref<ValidationErrors>({});
const processing = ref(false);

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Repositories',
        href: '/repositories',
    },
];

const resetForm = () => {
    formData.value = {
        name: '',
        owner: '',
        repo: '',
    };
    errors.value = {};
    editingRepository.value = null;
};

const openCreateDialog = () => {
    resetForm();
    isDialogOpen.value = true;
};

const openEditDialog = (repository: Repository) => {
    editingRepository.value = repository;
    formData.value = {
        name: repository.name,
        owner: repository.owner,
        repo: repository.repo,
    };
    errors.value = {};
    isDialogOpen.value = true;
};

const closeDialog = () => {
    isDialogOpen.value = false;
    resetForm();
};

const saveRepository = async () => {
    processing.value = true;
    errors.value = {};

    try {
        const url = editingRepository.value
            ? `/repositories/${editingRepository.value.uuid}`
            : '/repositories';

        const method = editingRepository.value ? 'PUT' : 'POST';

        // Get CSRF token from cookie
        const token = document.cookie
            .split('; ')
            .find(row => row.startsWith('XSRF-TOKEN='))
            ?.split('=')[1];

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-XSRF-TOKEN': token ? decodeURIComponent(token) : '',
            },
            body: JSON.stringify(formData.value),
            credentials: 'same-origin',
        });

        const data = await response.json();

        if (response.ok) {
            closeDialog();
            router.reload({ preserveScroll: true });
        } else if (response.status === 422) {
            errors.value = data.errors || {};
        } else {
            console.error('Failed to save repository:', response.status, data);
            alert('Failed to save repository. Check console for details.');
        }
    } catch (error) {
        console.error('Failed to save repository:', error);
        alert('An error occurred while saving. Check console for details.');
    } finally {
        processing.value = false;
    }
};

const deleteRepository = async (uuid: string) => {
    try {
        // Get CSRF token from cookie
        const token = document.cookie
            .split('; ')
            .find(row => row.startsWith('XSRF-TOKEN='))
            ?.split('=')[1];

        const response = await fetch(`/repositories/${uuid}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-XSRF-TOKEN': token ? decodeURIComponent(token) : '',
            },
            credentials: 'same-origin',
        });

        if (response.ok) {
            router.reload({ preserveScroll: true });
        } else {
            const data = await response.json();
            console.error('Failed to delete repository:', response.status, data);
            alert('Failed to delete repository. Check console for details.');
        }
    } catch (error) {
        console.error('Failed to delete repository:', error);
        alert('An error occurred while deleting. Check console for details.');
    }
};
</script>

<template>
    <Head title="Repositories" />

    <AppLayout :breadcrumbs="breadcrumbItems">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <HeadingSmall
                        title="Repositories"
                        description="Manage your GitHub repositories"
                    />
                    <Button @click="openCreateDialog">
                        Add Repository
                    </Button>
                </div>

                <!-- Repositories List -->
                <Card>
                    <CardHeader>
                        <CardTitle>Your Repositories</CardTitle>
                        <CardDescription>
                            View and manage all your repositories
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div
                            v-if="repositories.length === 0"
                            class="text-sm text-muted-foreground"
                        >
                            No repositories yet. Add one to get started.
                        </div>
                        <div v-else class="space-y-4">
                            <div
                                v-for="repository in repositories"
                                :key="repository.uuid"
                                class="flex items-center justify-between p-4 border rounded-md"
                            >
                                <div class="flex-1">
                                    <h4 class="font-medium">
                                        {{ repository.name }}
                                    </h4>
                                    <p class="text-sm text-muted-foreground">
                                        {{ repository.slug }}
                                    </p>
                                </div>
                                <div class="flex gap-2">
                                    <Button
                                        variant="outline"
                                        size="sm"
                                        @click="openEditDialog(repository)"
                                    >
                                        Edit
                                    </Button>
                                    <Dialog>
                                        <DialogTrigger as-child>
                                            <Button variant="destructive" size="sm">
                                                Delete
                                            </Button>
                                        </DialogTrigger>
                                        <DialogContent>
                                            <DialogHeader>
                                                <DialogTitle>
                                                    Delete repository?
                                                </DialogTitle>
                                                <DialogDescription>
                                                    This action cannot be undone. This will permanently delete the repository "{{ repository.name }}".
                                                </DialogDescription>
                                            </DialogHeader>
                                            <DialogFooter>
                                                <DialogClose as-child>
                                                    <Button variant="outline">Cancel</Button>
                                                </DialogClose>
                                                <Button
                                                    variant="destructive"
                                                    @click="deleteRepository(repository.uuid)"
                                                >
                                                    Delete
                                                </Button>
                                            </DialogFooter>
                                        </DialogContent>
                                    </Dialog>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>

        <!-- Create/Edit Repository Dialog -->
        <Dialog v-model:open="isDialogOpen">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>
                        {{ editingRepository ? 'Edit Repository' : 'Add Repository' }}
                    </DialogTitle>
                    <DialogDescription>
                        {{ editingRepository ? 'Update the repository details.' : 'Add a new repository to track.' }}
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="saveRepository" class="space-y-4">
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input
                            id="name"
                            v-model="formData.name"
                            type="text"
                            placeholder="My Repository"
                        />
                        <InputError :message="errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="owner">Owner</Label>
                        <Input
                            id="owner"
                            v-model="formData.owner"
                            type="text"
                            placeholder="octocat"
                        />
                        <InputError :message="errors.owner" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="repo">Repository</Label>
                        <Input
                            id="repo"
                            v-model="formData.repo"
                            type="text"
                            placeholder="hello-world"
                        />
                        <InputError :message="errors.repo" />
                    </div>

                    <DialogFooter>
                        <DialogClose as-child>
                            <Button type="button" variant="outline">
                                Cancel
                            </Button>
                        </DialogClose>
                        <Button type="submit" :disabled="processing">
                            {{ processing ? 'Saving...' : (editingRepository ? 'Update' : 'Create') }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
