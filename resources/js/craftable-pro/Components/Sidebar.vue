<template>
  <div>
    <nav class="mt-5 space-y-1">
      <SidebarGroup :title="$t('craftable-pro', 'Content')">
        <SidebarItem
          :href="route('craftable-pro.media.index')"
          :icon="PhotoIcon"
          v-can="'craftable-pro.media.index'"
        >
          {{ $t("craftable-pro", "Media") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.item-categories.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.item-categories.index'"
        >
            {{ $t("craftable-pro", "Item Categories") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.countries.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.countries.index'"
        >
            {{ $t("craftable-pro", "Countries") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.time-zones.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.time-zones.index'"
        >
            {{ $t("craftable-pro", "Time Zones") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.currencies.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.currencies.index'"
        >
            {{ $t("craftable-pro", "Currencies") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.languages.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.languages.index'"
        >
            {{ $t("craftable-pro", "Languages") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.measure-units.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.measure-units.index'"
        >
            {{ $t("craftable-pro", "Measure Units") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.sale-channels.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.sale-channels.index'"
        >
            {{ $t("craftable-pro", "Sale Channels") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.delivery-types.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.delivery-types.index'"
        >
            {{ $t("craftable-pro", "Delivery Types") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.payment-methods.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.payment-methods.index'"
        >
            {{ $t("craftable-pro", "Payment Methods") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.payment-times.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.payment-times.index'"
        >
            {{ $t("craftable-pro", "Payment Times") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.order-statuses.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.order-statuses.index'"
        >
            {{ $t("craftable-pro", "Order Statuses") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.loyalty-card-types.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.loyalty-card-types.index'"
        >
            {{ $t("craftable-pro", "Loyalty Card Types") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.regions.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.regions.index'"
        >
            {{ $t("craftable-pro", "Regions") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.cities.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.cities.index'"
        >
            {{ $t("craftable-pro", "Cities") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.stores.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.stores.index'"
        >
            {{ $t("craftable-pro", "Stores") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.tax-types.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.tax-types.index'"
        >
            {{ $t("craftable-pro", "Tax Types") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.suppliers.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.suppliers.index'"
        >
            {{ $t("craftable-pro", "Suppliers") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.customers.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.customers.index'"
        >
            {{ $t("craftable-pro", "Customers") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.loyalty-cards.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.loyalty-cards.index'"
        >
            {{ $t("craftable-pro", "Loyalty Cards") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.items.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.items.index'"
        >
            {{ $t("craftable-pro", "Items") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.bar-codes.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.bar-codes.index'"
        >
            {{ $t("craftable-pro", "Bar Codes") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.prices.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.prices.index'"
        >
            {{ $t("craftable-pro", "Prices") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.supplier-tax-types.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.supplier-tax-types.index'"
        >
            {{ $t("craftable-pro", "Supplier Tax Types") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.supplier-item-tax-types.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.supplier-item-tax-types.index'"
        >
            {{ $t("craftable-pro", "Supplier Item Tax Types") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.discount-types.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.discount-types.index'"
        >
            {{ $t("craftable-pro", "Discount Types") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.discounts.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.discounts.index'"
        >
            {{ $t("craftable-pro", "Discounts") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.payment-terms.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.payment-terms.index'"
        >
            {{ $t("craftable-pro", "Payment Terms") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.order-headers.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.order-headers.index'"
        >
            {{ $t("craftable-pro", "Order Headers") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.order-lines.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.order-lines.index'"
        >
            {{ $t("craftable-pro", "Order Lines") }}
        </SidebarItem>
        <SidebarItem
            :href="route('craftable-pro.order-status-histories.index')"
            :icon="CubeTransparentIcon"
            v-can="'craftable-pro.order-status-histories.index'"
        >
            {{ $t("craftable-pro", "Order Status Histories") }}
        </SidebarItem>
        <!--AppendGeneratorLink-->
      </SidebarGroup>

      <SidebarGroup
        :title="$t('craftable-pro', 'System')"
        v-can:any="[
          'craftable-pro.craftable-pro-user.index',
          'craftable-pro.role.index',
          'craftable-pro.translation.index',
          'craftable-pro.settings.edit',
        ]"
      >
        <SidebarItem
          :href="route('craftable-pro.craftable-pro-users.index')"
          :icon="UsersIcon"
          v-can="'craftable-pro.craftable-pro-user.index'"
        >
          {{ $t("craftable-pro", "Access") }}
        </SidebarItem>
        <SidebarItem
          :href="route('craftable-pro.roles.index')"
          :icon="KeyIcon"
          v-can="'craftable-pro.role.index'"
        >
          {{ $t("craftable-pro", "Roles") }}
        </SidebarItem>
        <SidebarItem
          :href="route('craftable-pro.translations.index')"
          :icon="LanguageIcon"
          v-can="'craftable-pro.translation.index'"
        >
          {{ $t("craftable-pro", "Localization") }}
        </SidebarItem>
        <SidebarItem
          :href="route('craftable-pro.settings.index')"
          :icon="Cog8ToothIcon"
          v-can="'craftable-pro.settings.edit'"
        >
          {{ $t("craftable-pro", "Settings") }}
        </SidebarItem>
      </SidebarGroup>
    </nav>
  </div>
</template>

<script setup lang="ts">
import {
  KeyIcon,
  LanguageIcon,
  PhotoIcon,
  UsersIcon,
  Cog8ToothIcon,
  CubeTransparentIcon,
} from "@heroicons/vue/24/outline";
import { SidebarItem, SidebarGroup } from "craftable-pro/Components";
</script>
