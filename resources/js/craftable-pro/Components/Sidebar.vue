<template>
  <div>
    <nav class="mt-5 space-y-1">
      <SidebarGroup :title="$t('craftable-pro', 'Catalog')">
        <SidebarItem :href="route('craftable-pro.items.index')" :icon="CubeIcon" v-can="'craftable-pro.items.index'">
          {{ $t("craftable-pro", "Items") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.item-categories.index')" :icon="Squares2X2Icon" v-can="'craftable-pro.item-categories.index'">
          {{ $t("craftable-pro", "Categories") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.prices.index')" :icon="TagIcon" v-can="'craftable-pro.prices.index'">
          {{ $t("craftable-pro", "Prices") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.bar-codes.index')" :icon="QrCodeIcon" v-can="'craftable-pro.bar-codes.index'">
          {{ $t("craftable-pro", "Bar Codes") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.measure-units.index')" :icon="ScaleIcon" v-can="'craftable-pro.measure-units.index'">
          {{ $t("craftable-pro", "Measure Units") }}
        </SidebarItem>
      </SidebarGroup>

      <SidebarGroup :title="$t('craftable-pro', 'Sales')">
        <SidebarItem :href="route('craftable-pro.order-headers.index')" :icon="ShoppingCartIcon" v-can="'craftable-pro.order-headers.index'">
          {{ $t("craftable-pro", "Orders") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.invoices.index')" :icon="DocumentTextIcon" v-can="'craftable-pro.invoices.index'">
          {{ $t("craftable-pro", "Invoices") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.payments.index')" :icon="BanknotesIcon" v-can="'craftable-pro.payments.index'">
          {{ $t("craftable-pro", "Payments") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.shipments.index')" :icon="TruckIcon" v-can="'craftable-pro.shipments.index'">
          {{ $t("craftable-pro", "Shipments") }}
        </SidebarItem>
      </SidebarGroup>

      <SidebarGroup :title="$t('craftable-pro', 'Returns')">
        <SidebarItem :href="route('craftable-pro.sale-returns.index')" :icon="ArrowUturnLeftIcon" v-can="'craftable-pro.sale-returns.index'">
          {{ $t("craftable-pro", "Sale Returns") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.refunds.index')" :icon="ReceiptRefundIcon" v-can="'craftable-pro.refunds.index'">
          {{ $t("craftable-pro", "Refunds") }}
        </SidebarItem>
      </SidebarGroup>

      <SidebarGroup :title="$t('craftable-pro', 'Purchasing')">
        <SidebarItem :href="route('craftable-pro.purchases.index')" :icon="ShoppingBagIcon" v-can="'craftable-pro.purchases.index'">
          {{ $t("craftable-pro", "Purchases") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.suppliers.index')" :icon="BuildingOffice2Icon" v-can="'craftable-pro.suppliers.index'">
          {{ $t("craftable-pro", "Suppliers") }}
        </SidebarItem>
      </SidebarGroup>

      <SidebarGroup :title="$t('craftable-pro', 'Inventory')">
        <SidebarItem :href="route('craftable-pro.inventory-counts.index')" :icon="ClipboardDocumentCheckIcon" v-can="'craftable-pro.inventory-counts.index'">
          {{ $t("craftable-pro", "Inventory Counts") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.stock-returns.index')" :icon="ArrowUturnRightIcon" v-can="'craftable-pro.stock-returns.index'">
          {{ $t("craftable-pro", "Stock Returns") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.loss-and-damages.index')" :icon="ExclamationTriangleIcon" v-can="'craftable-pro.loss-and-damages.index'">
          {{ $t("craftable-pro", "Loss & Damage") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.stock-histories.index')" :icon="ArchiveBoxIcon" v-can="'craftable-pro.stock-histories.index'">
          {{ $t("craftable-pro", "Stock History") }}
        </SidebarItem>
      </SidebarGroup>

      <SidebarGroup :title="$t('craftable-pro', 'Customers')">
        <SidebarItem :href="route('craftable-pro.customers.index')" :icon="UserGroupIcon" v-can="'craftable-pro.customers.index'">
          {{ $t("craftable-pro", "Customers") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.loyalty-cards.index')" :icon="CreditCardIcon" v-can="'craftable-pro.loyalty-cards.index'">
          {{ $t("craftable-pro", "Loyalty Cards") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.discounts.index')" :icon="TicketIcon" v-can="'craftable-pro.discounts.index'">
          {{ $t("craftable-pro", "Discounts") }}
        </SidebarItem>
      </SidebarGroup>

      <SidebarGroup :title="$t('craftable-pro', 'Configuration')">
        <SidebarItem :href="route('craftable-pro.stores.index')" :icon="BuildingStorefrontIcon" v-can="'craftable-pro.stores.index'">
          {{ $t("craftable-pro", "Stores") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.tax-types.index')" :icon="ReceiptPercentIcon" v-can="'craftable-pro.tax-types.index'">
          {{ $t("craftable-pro", "Tax Types") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.payment-methods.index')" :icon="CreditCardIcon" v-can="'craftable-pro.payment-methods.index'">
          {{ $t("craftable-pro", "Payment Methods") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.sale-channels.index')" :icon="Squares2X2Icon" v-can="'craftable-pro.sale-channels.index'">
          {{ $t("craftable-pro", "Sale Channels") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.documents.index')" :icon="FolderIcon" v-can="'craftable-pro.documents.index'">
          {{ $t("craftable-pro", "Documents") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.media.index')" :icon="PhotoIcon" v-can="'craftable-pro.media.index'">
          {{ $t("craftable-pro", "Media") }}
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
        <SidebarItem :href="route('craftable-pro.craftable-pro-users.index')" :icon="UsersIcon" v-can="'craftable-pro.craftable-pro-user.index'">
          {{ $t("craftable-pro", "Access") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.roles.index')" :icon="KeyIcon" v-can="'craftable-pro.role.index'">
          {{ $t("craftable-pro", "Roles") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.translations.index')" :icon="LanguageIcon" v-can="'craftable-pro.translation.index'">
          {{ $t("craftable-pro", "Localization") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.settings.index')" :icon="Cog8ToothIcon" v-can="'craftable-pro.settings.edit'">
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
  CubeIcon,
  ShoppingCartIcon,
  ShoppingBagIcon,
  ArchiveBoxIcon,
  BanknotesIcon,
  ArrowUturnLeftIcon,
  ArrowUturnRightIcon,
  UserGroupIcon,
  TruckIcon,
  TicketIcon,
  CreditCardIcon,
  DocumentTextIcon,
  Squares2X2Icon,
  QrCodeIcon,
  ScaleIcon,
  TagIcon,
  ReceiptPercentIcon,
  ReceiptRefundIcon,
  ClipboardDocumentCheckIcon,
  ExclamationTriangleIcon,
  BuildingStorefrontIcon,
  BuildingOffice2Icon,
  FolderIcon,
} from "@heroicons/vue/24/outline";
import { SidebarItem, SidebarGroup } from "craftable-pro/Components";
</script>
