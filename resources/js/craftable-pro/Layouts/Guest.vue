<template>
  <div class="flex min-h-screen bg-gray-50 dark:bg-gray-950">
    <!-- Left: branded gradient panel (Larkon orange) — matches Login -->
    <div class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-gradient-to-br from-[#7c2d12] via-primary-600 to-amber-500 p-12 text-white lg:flex">
      <ShoppingBagIcon class="pointer-events-none absolute -right-20 top-1/2 h-[28rem] w-[28rem] -translate-y-1/2 text-white/10" />

      <div class="relative z-10 flex items-center gap-2 text-lg font-semibold tracking-tight">
        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/15 ring-1 ring-white/20 backdrop-blur">
          <ShoppingBagIcon class="h-5 w-5" />
        </span>
        Craftable <span class="rounded-md bg-white/20 px-1.5 py-0.5 text-xs font-bold">PRO</span>
      </div>

      <div class="relative z-10 max-w-md">
        <h1 class="text-4xl font-semibold leading-tight tracking-tight">
          Run your store<br />with confidence.
        </h1>
        <p class="mt-4 text-base text-white/85">
          Inventory, sales, purchasing and billing — one clean, fast back office.
        </p>
        <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-white/80">
          <span class="flex items-center gap-2"><CheckCircleIcon class="h-5 w-5" /> Real-time stock</span>
          <span class="flex items-center gap-2"><CheckCircleIcon class="h-5 w-5" /> Instant invoicing</span>
          <span class="flex items-center gap-2"><CheckCircleIcon class="h-5 w-5" /> Reports &amp; KPIs</span>
        </div>
      </div>

      <div class="relative z-10 text-sm text-white/70">© {{ new Date().getFullYear() }} Craftable PRO</div>

      <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-amber-300/20 blur-3xl" />
      <div class="pointer-events-none absolute -bottom-32 -left-10 h-80 w-80 rounded-full bg-white/10 blur-3xl" />
    </div>

    <!-- Right: form column -->
    <div class="flex w-full items-center justify-center px-6 py-12 lg:w-1/2">
      <div class="w-full max-w-sm">
        <!-- brand mark (mobile + as a header cue) -->
        <div class="mb-8 flex items-center gap-2 text-base font-semibold tracking-tight text-gray-900 dark:text-white lg:hidden">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-500 text-white">
            <ShoppingBagIcon class="h-4 w-4" />
          </span>
          Craftable <span class="rounded bg-primary-500/10 px-1.5 py-0.5 text-xs font-bold text-primary-600 dark:text-primary-400">PRO</span>
        </div>

        <slot />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ShoppingBagIcon, CheckCircleIcon } from "@heroicons/vue/24/outline";
</script>
