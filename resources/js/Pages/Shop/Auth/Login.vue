<template>
    <Head title="Sign in" />

    <div class="mx-auto max-w-md px-4 py-16">
        <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">
            <div class="text-center">
                <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-orange-600 text-white shadow-lg shadow-brand-500/25">
                    <UserIcon class="h-6 w-6" />
                </span>
                <h1 class="mt-4 text-2xl font-bold tracking-tight text-gray-900">Welcome back</h1>
                <p class="mt-1 text-sm text-gray-500">Sign in to your account.</p>
            </div>

            <form @submit.prevent="submit" class="mt-8 space-y-5">
                <Field label="Email" :error="form.errors.email"><input v-model="form.email" type="email" autocomplete="email" :class="input" /></Field>
                <Field label="Password" :error="form.errors.password"><input v-model="form.password" type="password" autocomplete="current-password" :class="input" /></Field>
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input v-model="form.remember" type="checkbox" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500" /> Remember me
                </label>
                <button type="submit" :disabled="form.processing" class="w-full rounded-full bg-brand-500 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-500/25 transition hover:bg-brand-600 disabled:opacity-60">
                    {{ form.processing ? 'Signing in…' : 'Sign in' }}
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-500">
                New here?
                <Link href="/account/register" class="font-semibold text-brand-600 hover:text-brand-700">Create an account</Link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { UserIcon } from '@heroicons/vue/24/outline';
import Field from '@/Components/Field.vue';

const input = 'w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20';

const form = useForm({ email: '', password: '', remember: false });
function submit() {
    form.post('/account/login', { onFinish: () => form.reset('password') });
}
</script>
