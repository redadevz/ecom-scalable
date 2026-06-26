<template>
  <div>
    <nav class="mt-5 space-y-1">
      <SidebarGroup :title="$t('craftable-pro', 'Content')">
        <SidebarItem :href="route('craftable-pro.media.index')" :icon="PhotoIcon" v-can="'craftable-pro.media.index'">
          {{ $t("craftable-pro", "Media") }}
        </SidebarItem>
      </SidebarGroup>

      <SidebarGroup :title="$t('craftable-pro', 'Catalog')">
        <SidebarItem :href="route('craftable-pro.items.index')" :icon="CubeIcon" v-can="'craftable-pro.items.index'">
          {{ $t("craftable-pro", "Items") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.item-categories.index')" :icon="CubeIcon" v-can="'craftable-pro.item-categories.index'">
          {{ $t("craftable-pro", "Item Categories") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.bar-codes.index')" :icon="CubeIcon" v-can="'craftable-pro.bar-codes.index'">
          {{ $t("craftable-pro", "Bar Codes") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.measure-units.index')" :icon="CubeIcon" v-can="'craftable-pro.measure-units.index'">
          {{ $t("craftable-pro", "Measure Units") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.prices.index')" :icon="CubeIcon" v-can="'craftable-pro.prices.index'">
          {{ $t("craftable-pro", "Prices") }}
        </SidebarItem>
      </SidebarGroup>

      <SidebarGroup :title="$t('craftable-pro', 'Orders')">
        <SidebarItem :href="route('craftable-pro.order-headers.index')" :icon="ShoppingCartIcon" v-can="'craftable-pro.order-headers.index'">
          {{ $t("craftable-pro", "Order Headers") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.order-lines.index')" :icon="ShoppingCartIcon" v-can="'craftable-pro.order-lines.index'">
          {{ $t("craftable-pro", "Order Lines") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.order-statuses.index')" :icon="ShoppingCartIcon" v-can="'craftable-pro.order-statuses.index'">
          {{ $t("craftable-pro", "Order Statuses") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.order-status-histories.index')" :icon="ShoppingCartIcon" v-can="'craftable-pro.order-status-histories.index'">
          {{ $t("craftable-pro", "Order Status Histories") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.shipments.index')" :icon="ShoppingCartIcon" v-can="'craftable-pro.shipments.index'">
          {{ $t("craftable-pro", "Shipments") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.order-reviews.index')" :icon="ShoppingCartIcon" v-can="'craftable-pro.order-reviews.index'">
          {{ $t("craftable-pro", "Order Reviews") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.order-discounts.index')" :icon="ShoppingCartIcon" v-can="'craftable-pro.order-discounts.index'">
          {{ $t("craftable-pro", "Order Discounts") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.order-line-discounts.index')" :icon="ShoppingCartIcon" v-can="'craftable-pro.order-line-discounts.index'">
          {{ $t("craftable-pro", "Order Line Discounts") }}
        </SidebarItem>
      </SidebarGroup>

      <SidebarGroup :title="$t('craftable-pro', 'Billing')">
        <SidebarItem :href="route('craftable-pro.invoices.index')" :icon="BanknotesIcon" v-can="'craftable-pro.invoices.index'">
          {{ $t("craftable-pro", "Invoices") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.invoice-lines.index')" :icon="BanknotesIcon" v-can="'craftable-pro.invoice-lines.index'">
          {{ $t("craftable-pro", "Invoice Lines") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.payments.index')" :icon="BanknotesIcon" v-can="'craftable-pro.payments.index'">
          {{ $t("craftable-pro", "Payments") }}
        </SidebarItem>
      </SidebarGroup>

      <SidebarGroup :title="$t('craftable-pro', 'Returns & Refunds')">
        <SidebarItem :href="route('craftable-pro.sale-returns.index')" :icon="ArrowUturnLeftIcon" v-can="'craftable-pro.sale-returns.index'">
          {{ $t("craftable-pro", "Sale Returns") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.sale-return-items.index')" :icon="ArrowUturnLeftIcon" v-can="'craftable-pro.sale-return-items.index'">
          {{ $t("craftable-pro", "Sale Return Items") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.refunds.index')" :icon="ArrowUturnLeftIcon" v-can="'craftable-pro.refunds.index'">
          {{ $t("craftable-pro", "Refunds") }}
        </SidebarItem>
      </SidebarGroup>

      <SidebarGroup :title="$t('craftable-pro', 'Customers')">
        <SidebarItem :href="route('craftable-pro.customers.index')" :icon="UserGroupIcon" v-can="'craftable-pro.customers.index'">
          {{ $t("craftable-pro", "Customers") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.loyalty-cards.index')" :icon="UserGroupIcon" v-can="'craftable-pro.loyalty-cards.index'">
          {{ $t("craftable-pro", "Loyalty Cards") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.loyalty-card-types.index')" :icon="UserGroupIcon" v-can="'craftable-pro.loyalty-card-types.index'">
          {{ $t("craftable-pro", "Loyalty Card Types") }}
        </SidebarItem>
      </SidebarGroup>

      <SidebarGroup :title="$t('craftable-pro', 'Purchasing')">
        <SidebarItem :href="route('craftable-pro.purchases.index')" :icon="ShoppingBagIcon" v-can="'craftable-pro.purchases.index'">
          {{ $t("craftable-pro", "Purchases") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.purchase-items.index')" :icon="ShoppingBagIcon" v-can="'craftable-pro.purchase-items.index'">
          {{ $t("craftable-pro", "Purchase Items") }}
        </SidebarItem>
      </SidebarGroup>

      <SidebarGroup :title="$t('craftable-pro', 'Inventory & Stock')">
        <SidebarItem :href="route('craftable-pro.inventory-counts.index')" :icon="ArchiveBoxIcon" v-can="'craftable-pro.inventory-counts.index'">
          {{ $t("craftable-pro", "Inventory Counts") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.inventory-count-items.index')" :icon="ArchiveBoxIcon" v-can="'craftable-pro.inventory-count-items.index'">
          {{ $t("craftable-pro", "Inventory Count Items") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.stock-histories.index')" :icon="ArchiveBoxIcon" v-can="'craftable-pro.stock-histories.index'">
          {{ $t("craftable-pro", "Stock Histories") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.stock-returns.index')" :icon="ArchiveBoxIcon" v-can="'craftable-pro.stock-returns.index'">
          {{ $t("craftable-pro", "Stock Returns") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.stock-return-items.index')" :icon="ArchiveBoxIcon" v-can="'craftable-pro.stock-return-items.index'">
          {{ $t("craftable-pro", "Stock Return Items") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.loss-and-damages.index')" :icon="ArchiveBoxIcon" v-can="'craftable-pro.loss-and-damages.index'">
          {{ $t("craftable-pro", "Loss And Damages") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.loss-and-damage-items.index')" :icon="ArchiveBoxIcon" v-can="'craftable-pro.loss-and-damage-items.index'">
          {{ $t("craftable-pro", "Loss And Damage Items") }}
        </SidebarItem>
      </SidebarGroup>

      <SidebarGroup :title="$t('craftable-pro', 'Suppliers & Tax')">
        <SidebarItem :href="route('craftable-pro.suppliers.index')" :icon="TruckIcon" v-can="'craftable-pro.suppliers.index'">
          {{ $t("craftable-pro", "Suppliers") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.supplier-tax-types.index')" :icon="TruckIcon" v-can="'craftable-pro.supplier-tax-types.index'">
          {{ $t("craftable-pro", "Supplier Tax Types") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.supplier-item-tax-types.index')" :icon="TruckIcon" v-can="'craftable-pro.supplier-item-tax-types.index'">
          {{ $t("craftable-pro", "Supplier Item Tax Types") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.tax-types.index')" :icon="TruckIcon" v-can="'craftable-pro.tax-types.index'">
          {{ $t("craftable-pro", "Tax Types") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.item-tax-types.index')" :icon="TruckIcon" v-can="'craftable-pro.item-tax-types.index'">
          {{ $t("craftable-pro", "Item Tax Types") }}
        </SidebarItem>
      </SidebarGroup>

      <SidebarGroup :title="$t('craftable-pro', 'Promotions')">
        <SidebarItem :href="route('craftable-pro.discounts.index')" :icon="TicketIcon" v-can="'craftable-pro.discounts.index'">
          {{ $t("craftable-pro", "Discounts") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.discount-types.index')" :icon="TicketIcon" v-can="'craftable-pro.discount-types.index'">
          {{ $t("craftable-pro", "Discount Types") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.discount-schedules.index')" :icon="TicketIcon" v-can="'craftable-pro.discount-schedules.index'">
          {{ $t("craftable-pro", "Discount Schedules") }}
        </SidebarItem>
      </SidebarGroup>

      <SidebarGroup :title="$t('craftable-pro', 'Sales & Payments')">
        <SidebarItem :href="route('craftable-pro.sale-channels.index')" :icon="CreditCardIcon" v-can="'craftable-pro.sale-channels.index'">
          {{ $t("craftable-pro", "Sale Channels") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.delivery-types.index')" :icon="CreditCardIcon" v-can="'craftable-pro.delivery-types.index'">
          {{ $t("craftable-pro", "Delivery Types") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.payment-methods.index')" :icon="CreditCardIcon" v-can="'craftable-pro.payment-methods.index'">
          {{ $t("craftable-pro", "Payment Methods") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.payment-times.index')" :icon="CreditCardIcon" v-can="'craftable-pro.payment-times.index'">
          {{ $t("craftable-pro", "Payment Times") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.payment-terms.index')" :icon="CreditCardIcon" v-can="'craftable-pro.payment-terms.index'">
          {{ $t("craftable-pro", "Payment Terms") }}
        </SidebarItem>
      </SidebarGroup>

      <SidebarGroup :title="$t('craftable-pro', 'Documents')">
        <SidebarItem :href="route('craftable-pro.documents.index')" :icon="DocumentTextIcon" v-can="'craftable-pro.documents.index'">
          {{ $t("craftable-pro", "Documents") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.document-types.index')" :icon="DocumentTextIcon" v-can="'craftable-pro.document-types.index'">
          {{ $t("craftable-pro", "Document Types") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.document-categories.index')" :icon="DocumentTextIcon" v-can="'craftable-pro.document-categories.index'">
          {{ $t("craftable-pro", "Document Categories") }}
        </SidebarItem>
      </SidebarGroup>

      <SidebarGroup :title="$t('craftable-pro', 'Locations')">
        <SidebarItem :href="route('craftable-pro.stores.index')" :icon="GlobeAltIcon" v-can="'craftable-pro.stores.index'">
          {{ $t("craftable-pro", "Stores") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.countries.index')" :icon="GlobeAltIcon" v-can="'craftable-pro.countries.index'">
          {{ $t("craftable-pro", "Countries") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.regions.index')" :icon="GlobeAltIcon" v-can="'craftable-pro.regions.index'">
          {{ $t("craftable-pro", "Regions") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.cities.index')" :icon="GlobeAltIcon" v-can="'craftable-pro.cities.index'">
          {{ $t("craftable-pro", "Cities") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.time-zones.index')" :icon="GlobeAltIcon" v-can="'craftable-pro.time-zones.index'">
          {{ $t("craftable-pro", "Time Zones") }}
        </SidebarItem>
      </SidebarGroup>

      <SidebarGroup :title="$t('craftable-pro', 'Localization & Calendar')">
        <SidebarItem :href="route('craftable-pro.currencies.index')" :icon="LanguageIcon" v-can="'craftable-pro.currencies.index'">
          {{ $t("craftable-pro", "Currencies") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.languages.index')" :icon="LanguageIcon" v-can="'craftable-pro.languages.index'">
          {{ $t("craftable-pro", "Languages") }}
        </SidebarItem>
        <SidebarItem :href="route('craftable-pro.holidays.index')" :icon="CalendarDaysIcon" v-can="'craftable-pro.holidays.index'">
          {{ $t("craftable-pro", "Holidays") }}
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
  CubeIcon,
  ShoppingCartIcon,
  ShoppingBagIcon,
  ArchiveBoxIcon,
  BanknotesIcon,
  ArrowUturnLeftIcon,
  UserGroupIcon,
  TruckIcon,
  TicketIcon,
  CreditCardIcon,
  GlobeAltIcon,
  DocumentTextIcon,
  CalendarDaysIcon,
} from "@heroicons/vue/24/outline";
import { SidebarItem, SidebarGroup } from "craftable-pro/Components";
</script>
