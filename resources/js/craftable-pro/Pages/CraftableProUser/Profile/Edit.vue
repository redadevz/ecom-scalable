<template>
  <PageHeader sticky :title="$t('craftable-pro', 'My Account')" />

  <PageContent>
    <template #tabs>
      <TabGroup variant="underline">
        <Tab>{{ $t("craftable-pro", "Profile") }}</Tab>
        <Tab>{{ $t("craftable-pro", "Security") }}</Tab>
        <Tab>{{ $t("craftable-pro", "Appearance") }}</Tab>
      </TabGroup>
    </template>

    <!-- ===== Profile ===== -->
    <TabPanel>
      <!-- Hero -->
      <section class="relative mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]">
        <div class="relative h-28 bg-gradient-to-br from-[#7c2d12] via-primary-600 to-amber-500">
          <UserCircleIcon class="pointer-events-none absolute -right-6 -top-6 h-40 w-40 text-white/10" />
        </div>
        <div class="flex flex-col gap-4 px-6 pb-6 sm:flex-row sm:items-end">
          <div class="-mt-12 flex h-24 w-24 flex-shrink-0 items-center justify-center overflow-hidden rounded-2xl border-4 border-white bg-gradient-to-br from-primary-500 to-orange-600 text-3xl font-bold uppercase text-white shadow-lg ring-1 ring-black/5 dark:border-[#1e2029]">
            <img v-if="avatarUrl" :src="avatarUrl" :alt="fullName" class="h-full w-full object-cover" />
            <template v-else>{{ initials }}</template>
          </div>
          <div class="min-w-0 flex-1 sm:pb-1">
            <div class="flex flex-wrap items-center gap-2">
              <h2 class="truncate text-xl font-semibold text-gray-900 dark:text-white">{{ fullName }}</h2>
              <span v-if="roleName" class="inline-flex items-center rounded-full bg-primary-500/10 px-2.5 py-0.5 text-xs font-semibold text-primary-600 dark:text-primary-400">{{ roleName }}</span>
              <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium"
                :class="craftableProUser.active !== false ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-gray-100 text-gray-500 dark:bg-white/5 dark:text-gray-400'">
                <span class="h-1.5 w-1.5 rounded-full" :class="craftableProUser.active !== false ? 'bg-green-500' : 'bg-gray-400'"></span>
                {{ craftableProUser.active !== false ? 'Active' : 'Inactive' }}
              </span>
            </div>
            <p class="mt-0.5 truncate text-sm text-gray-500 dark:text-gray-400">{{ craftableProUser.email }}</p>
          </div>
        </div>
      </section>

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Personal details -->
        <section :class="cardCls" class="lg:col-span-2">
          <header :class="headerCls">
            <span :class="iconBadge"><IdentificationIcon class="h-5 w-5" /></span>
            <div><h3 :class="titleCls">Personal details</h3><p :class="subCls">Your name, email and language.</p></div>
          </header>
          <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-6 sm:grid-cols-2">
            <TextInput v-model="form.first_name" name="first_name" :label="$t('craftable-pro', 'First name')" />
            <TextInput v-model="form.last_name" name="last_name" :label="$t('craftable-pro', 'Last name')" />
            <div class="sm:col-span-2">
              <TextInput v-model="form.email" name="email" :label="$t('craftable-pro', 'E-mail')" type="email" disabled />
              <p class="mt-1 text-xs text-gray-400">Contact an administrator to change your email.</p>
            </div>
            <div class="sm:col-span-2">
              <Multiselect v-model="form.locale" name="locale" :label="$t('craftable-pro', 'Locale')" mode="single"
                :options="availableLocales" :canDeselect="false">
                <template #singlelabel="{ value }"><LocaleFlag :locale="value.value" /></template>
                <template #option="{ option }"><LocaleFlag :locale="option.value" /></template>
              </Multiselect>
            </div>
          </div>
          <div class="flex justify-end border-t border-gray-100 px-6 py-4 dark:border-[#2a2d38]">
            <Button :leftIcon="ArrowDownTrayIcon" @click="submit" :loading="form.processing">
              {{ $t("craftable-pro", "Save changes") }}
            </Button>
          </div>
        </section>

        <!-- Photo + Account -->
        <div class="space-y-6">
          <section :class="cardCls">
            <header :class="headerCls"><span :class="iconBadge"><PhotoIcon class="h-5 w-5" /></span><h3 :class="titleCls">Photo</h3></header>
            <div class="p-6">
              <ImageUpload v-model="form.avatar" name="avatar" :label="$t('craftable-pro', 'User photo')" />
            </div>
          </section>

          <section :class="cardCls">
            <header :class="headerCls"><span :class="iconBadge"><ShieldCheckIcon class="h-5 w-5" /></span><h3 :class="titleCls">Account</h3></header>
            <dl class="space-y-3 p-6 text-sm">
              <div class="flex justify-between"><dt class="text-gray-400">Role</dt><dd class="font-medium text-gray-900 dark:text-white">{{ roleName ?? '—' }}</dd></div>
              <div class="flex justify-between"><dt class="text-gray-400">Two-factor</dt>
                <dd class="font-medium" :class="twoFactorOn ? 'text-green-600 dark:text-green-400' : 'text-gray-500'">{{ twoFactorOn ? 'Enabled' : 'Off' }}</dd>
              </div>
              <div v-if="craftableProUser.created_at" class="flex justify-between"><dt class="text-gray-400">Member since</dt><dd class="text-gray-700 dark:text-gray-300">{{ fmt(craftableProUser.created_at) }}</dd></div>
            </dl>
          </section>
        </div>
      </div>
    </TabPanel>

    <!-- ===== Security ===== -->
    <TabPanel>
      <div class="mx-auto flex max-w-3xl flex-col gap-6 2xl:max-w-4xl">
        <ChangePasswordCard />
        <TwoFactorAuthenticationCard
          :two-factor-authentication-enabled-at="craftableProUser.two_factor_confirmed_at ? dayjs(craftableProUser.twoFactorAuthenticationEnabledAt).format('YYYY-MM-DD HH:mm:ss') : null" />
      </div>
    </TabPanel>

    <!-- ===== Appearance ===== -->
    <TabPanel>
      <ThemeCardLayout />
    </TabPanel>
  </PageContent>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { PageContent, PageHeader, Tab, TabGroup, Button, TextInput, Multiselect, ImageUpload, LocaleFlag } from "craftable-pro/Components";
import { useForm } from "craftable-pro/hooks/useForm";
import { usePage } from "@inertiajs/vue3";
import { TabPanel } from "@headlessui/vue";
import ChangePasswordCard from "craftable-pro/Pages/CraftableProUser/Components/ChangePasswordCard.vue";
import TwoFactorAuthenticationCard from "craftable-pro/Pages/CraftableProUser/Components/TwoFactorAuthenticationCard.vue";
import ThemeCardLayout from "craftable-pro/Components/Appearance/Theme/ThemeCardLayout.vue";
import type { CraftableProUser } from "craftable-pro/types/models";
import { ArrowDownTrayIcon, IdentificationIcon, PhotoIcon, ShieldCheckIcon, UserCircleIcon } from "@heroicons/vue/24/outline";
import dayjs from "dayjs";

interface Props { craftableProUser: CraftableProUser & Record<string, any>; }
const props = defineProps<Props>();

const cardCls = "rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-[#2c2f3d] dark:bg-[#1e2029]";
const headerCls = "flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-[#2a2d38]";
const iconBadge = "flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary-500/10 text-primary-500";
const titleCls = "text-[15px] font-semibold text-gray-900 dark:text-white";
const subCls = "text-xs text-gray-400";

const availableLocales = computed(() => usePage().props.settings.available_locales);

const fullName = computed(() => [props.craftableProUser.first_name, props.craftableProUser.last_name].filter(Boolean).join(" ").trim() || props.craftableProUser.email || "User");
const initials = computed(() => {
  const parts = fullName.value.split(/\s+/).filter(Boolean);
  return ((parts[0]?.[0] ?? "") + (parts[1]?.[0] ?? "")).toUpperCase() || "U";
});
const avatarUrl = computed(() => props.craftableProUser.avatar_url || null);
const roleName = computed(() => props.craftableProUser.roles?.[0]?.name ?? null);
const twoFactorOn = computed(() => !!props.craftableProUser.two_factor_confirmed_at);
const fmt = (d: any) => (d ? dayjs(d).format("DD MMM YYYY") : "—");

const { form, submit } = useForm(
  {
    first_name: props.craftableProUser.first_name ?? "",
    last_name: props.craftableProUser.last_name ?? "",
    email: props.craftableProUser.email ?? "",
    locale: props.craftableProUser.locale ?? "",
    avatar: props.craftableProUser.avatar ?? [],
  },
  route("craftable-pro.craftable-pro-users.profile.update")
);
</script>
