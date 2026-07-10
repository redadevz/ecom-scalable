<template>
    <Head title="Create account" />

    <div class="mx-auto max-w-md px-4 py-16">
        <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">
            <div class="text-center">
                <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-orange-600 text-white shadow-lg shadow-brand-500/25">
                    <UserPlusIcon class="h-6 w-6" />
                </span>
                <h1 class="mt-4 text-2xl font-bold tracking-tight text-gray-900">Create your account</h1>
                <p class="mt-1 text-sm text-gray-500">Track orders and check out faster.</p>
            </div>

            <form @submit.prevent="submit" class="mt-8 space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <Field label="First name" :error="form.errors.first_name"><input v-model="form.first_name" type="text" :class="input" /></Field>
                    <Field label="Last name" :error="form.errors.last_name"><input v-model="form.last_name" type="text" :class="input" /></Field>
                </div>
                <Field label="Email" :error="form.errors.email"><input v-model="form.email" type="email" autocomplete="email" :class="input" /></Field>
                <Field label="Phone" :error="form.errors.phone"><input v-model="form.phone" type="tel" :class="input" /></Field>
                <Field label="Password" :error="form.errors.password"><input v-model="form.password" type="password" autocomplete="new-password" :class="input" /></Field>
                <Field label="Confirm password" :error="form.errors.password_confirmation"><input v-model="form.password_confirmation" type="password" autocomplete="new-password" :class="input" /></Field>
                <button type="submit" :disabled="form.processing" class="w-full rounded-full bg-brand-500 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-500/25 transition hover:bg-brand-600 disabled:opacity-60">
                    {{ form.processing ? 'Creating…' : 'Create account' }}
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-500">
                Already have an account?
                <Link href="/account/login" class="font-semibold text-brand-600 hover:text-brand-700">Sign in</Link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { UserPlusIcon } from '@heroicons/vue/24/outline';
import Field from '@/Components/Field.vue';

const input = 'w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20';

const form = useForm({ first_name: '', last_name: '', email: '', phone: '', password: '', password_confirmation: '' });
function submit() {
    form.post('/account/register', { onFinish: () => form.reset('password', 'password_confirmation') });
}
</script>
