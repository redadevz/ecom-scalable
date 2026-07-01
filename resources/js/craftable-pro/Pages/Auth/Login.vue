<script lang="ts">
// Render full-screen — skip the centered GuestLayout so we can do a split panel.
export default { layout: null };
</script>

<template>
  <div class="flex min-h-screen bg-white dark:bg-gray-950">
    <Head :title="$t('craftable-pro', 'Login')" />

    <!-- Left: branded gradient panel -->
    <div
      class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-gradient-to-br from-primary-600 via-primary-600 to-teal-500 p-12 text-white lg:flex"
    >
      <div class="relative z-10 flex items-center gap-2 text-lg font-semibold tracking-tight">
        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/15 backdrop-blur">
          <ShoppingBagIcon class="h-5 w-5" />
        </span>
        Craftable <span class="rounded-md bg-white/20 px-1.5 py-0.5 text-xs font-bold">PRO</span>
      </div>

      <div class="relative z-10 max-w-md">
        <h1 class="text-4xl font-semibold leading-tight tracking-tight">
          Run your store<br />with confidence.
        </h1>
        <p class="mt-4 text-base text-white/80">
          Inventory, sales, purchasing and billing — one clean, fast back office.
        </p>
        <div class="mt-8 flex items-center gap-6 text-sm text-white/70">
          <span class="flex items-center gap-2"><CheckCircleIcon class="h-5 w-5" /> Real-time stock</span>
          <span class="flex items-center gap-2"><CheckCircleIcon class="h-5 w-5" /> Instant invoicing</span>
        </div>
      </div>

      <div class="relative z-10 text-sm text-white/60">© {{ new Date().getFullYear() }} Craftable PRO</div>

      <!-- decorative glows -->
      <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-white/10 blur-3xl" />
      <div class="pointer-events-none absolute -bottom-32 right-16 h-80 w-80 rounded-full bg-white/10 blur-3xl" />
    </div>

    <!-- Right: sign-in form -->
    <div class="flex w-full items-center justify-center px-6 py-12 lg:w-1/2">
      <div class="w-full max-w-sm" v-auto-animate>
        <div class="mb-8">
          <h2 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
            {{ $t("craftable-pro", "Welcome back") }}
          </h2>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ $t("craftable-pro", "Sign in to your account to continue") }}
          </p>
        </div>

        <Alert v-if="status" type="info" class="mb-6">{{ status }}</Alert>

        <form class="space-y-5" @submit.prevent="submit">
          <TextInput v-model="form.email" :label="$t('craftable-pro', 'E-mail address')" name="email" />
          <TextInput
            v-model="form.password"
            :label="$t('craftable-pro', 'Password')"
            name="password"
            type="password"
            autocomplete="current-password"
          />

          <div class="flex items-center justify-between">
            <Checkbox v-model="form.remember" :label="$t('craftable-pro', 'Remember me')" name="remember-me" />
            <div v-if="canResetPassword" class="text-sm">
              <Link :href="route('craftable-pro.password.request')" class="font-medium text-primary-600 hover:text-primary-500">
                {{ $t("craftable-pro", "Forgot your password?") }}
              </Link>
            </div>
          </div>

          <Button class="w-full" type="submit" :disabled="form.processing">
            {{ $t("craftable-pro", "Sign in") }}
          </Button>

          <hr
            v-if="Object.values($page.props.config?.socialite).filter((value) => value === true).length > 0"
          />

          <Button
            as="a"
            class="w-full justify-center rounded-lg px-5 py-2.5 text-sm font-medium"
            :href="route('craftable-pro.social-login.login', 'microsoft')"
            v-if="$page.props.config?.socialite?.microsoft"
          >
            <MicrosoftLoginLogo class="mr-2" />
            {{ $t("craftable-pro", "Microsoft login") }}
          </Button>
          <Button
            as="a"
            class="mr-2 !mt-2 inline-flex w-full items-center justify-center rounded-lg bg-[#24292F] px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-[#24292F]/90 focus:ring-4 focus:ring-[#24292F]/50 dark:focus:ring-gray-500 dark:hover:bg-[#050708]/30"
            :href="route('craftable-pro.social-login.login', 'github')"
            v-if="$page.props.config?.socialite?.github"
          >
            <GithubLoginLogo class="mr-2" />
            {{ $t("craftable-pro", "Sign in with Github") }}
          </Button>
          <Button
            as="a"
            class="mr-2 !mt-2 inline-flex w-full items-center justify-center rounded-lg bg-[#4285F4] px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-[#4285F4]/90 focus:ring-4 focus:ring-[#4285F4]/50 dark:focus:ring-[#4285F4]/55"
            :href="route('craftable-pro.social-login.login', 'google')"
            v-if="$page.props.config?.socialite?.google"
          >
            <GoogleLoginLogo class="mr-2" />
            {{ $t("craftable-pro", "Sign in with Google") }}
          </Button>
          <Button
            as="a"
            class="mr-2 !mt-2 inline-flex w-full items-center justify-center rounded-lg bg-[#3b5998] px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-[#3b5998]/90 focus:ring-4 focus:ring-[#3b5998]/50 dark:focus:ring-[#3b5998]/55"
            :href="route('craftable-pro.social-login.login', 'facebook')"
            v-if="$page.props.config?.socialite?.facebook"
          >
            <FacebookLoginLogo class="mr-2" />
            {{ $t("craftable-pro", "Sign in with Facebook") }}
          </Button>

          <div v-if="canRegister" class="text-center text-sm text-gray-500 dark:text-gray-400">
            {{ $t("craftable-pro", "Not registered?") }}
            <Link :href="route('craftable-pro.register')" class="font-medium text-primary-600 hover:text-primary-500">
              {{ $t("craftable-pro", "Create an account") }}
            </Link>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useForm, Head, Link } from "@inertiajs/vue3";
import {
  ShoppingBagIcon,
  CheckCircleIcon,
} from "@heroicons/vue/24/outline";
import {
  Button,
  TextInput,
  Checkbox,
  Alert,
  MicrosoftLoginLogo,
  FacebookLoginLogo,
  GithubLoginLogo,
  GoogleLoginLogo,
} from "craftable-pro/Components";
import { useToast } from "@brackets/vue-toastification";

interface Props {
  canResetPassword: boolean;
  canRegister: boolean;
  status: string;
}

defineProps<Props>();
const toast = useToast();

const form = useForm({
  email: "",
  password: "",
  remember: false,
});

const submit = () => {
  form.post(route("craftable-pro.login"), {
    onFinish: () => form.reset("password"),
    onError: (errors: Error) => {
      if (errors && Object.values(errors)) {
        toast.error(Object.values(errors)[0]);
      }
    },
  });
};
</script>
