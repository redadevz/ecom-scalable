<template>
    <div class="chart-root">
        <!-- empty -->
        <div v-if="!data.length" class="flex h-48 items-center justify-center text-sm text-gray-400">
            No data to chart
        </div>

        <!-- AREA -->
        <div v-else-if="type === 'area'" class="relative">
            <svg :viewBox="`0 0 ${W} ${H}`" class="w-full" :style="{ height: height + 'px' }" preserveAspectRatio="none"
                @mousemove="onMove" @mouseleave="active = -1">
                <defs>
                    <linearGradient :id="gid" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="rgb(var(--p))" stop-opacity="0.35" />
                        <stop offset="100%" stop-color="rgb(var(--p))" stop-opacity="0" />
                    </linearGradient>
                </defs>
                <line v-for="g in 4" :key="g" :x1="0" :x2="W" :y1="(H - pad) * g / 4" :y2="(H - pad) * g / 4"
                    stroke="currentColor" class="text-gray-100 dark:text-[#2c2f3d]" stroke-width="1" />
                <path :d="areaPath" :fill="`url(#${gid})`" :style="{ opacity: anim }" />
                <path ref="lineEl" :d="linePath" fill="none" stroke="rgb(var(--p))" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round"
                    :style="{ strokeDasharray: lineLen, strokeDashoffset: lineLen * (1 - anim) }" />
                <g v-if="active >= 0" :style="{ opacity: anim }">
                    <line :x1="pts[active].x" :x2="pts[active].x" :y1="0" :y2="H - pad" stroke="rgb(var(--p))" stroke-width="1" stroke-dasharray="3 3" opacity="0.4" />
                    <circle :cx="pts[active].x" :cy="pts[active].y" r="5" fill="rgb(var(--p))" stroke="white" stroke-width="2" class="dark:stroke-[#212330]" />
                </g>
            </svg>
            <div v-if="active >= 0" class="pointer-events-none absolute z-10 -translate-x-1/2 -translate-y-full rounded-lg bg-gray-900 px-2.5 py-1.5 text-xs text-white shadow-lg dark:bg-black"
                :style="{ left: `${(pts[active].x / W) * 100}%`, top: `${(pts[active].y / H) * height}px` }">
                <div class="font-semibold">{{ data[active].label }}</div>
                <div class="text-primary-300">{{ fmt(data[active].value) }}</div>
            </div>
            <div class="mt-2 flex justify-between px-0.5">
                <span v-for="(d, i) in data" :key="i" class="text-[0.6rem] text-gray-400" :class="{ 'opacity-0': !showLabel(i) }">{{ d.label }}</span>
            </div>
        </div>

        <!-- BARS -->
        <div v-else-if="type === 'bars'">
            <div class="relative flex items-end gap-2" :style="{ height: height + 'px' }" @mouseleave="active = -1">
                <div v-for="(d, i) in data" :key="i" class="group flex flex-1 flex-col items-center justify-end"
                    @mouseenter="active = i">
                    <div class="flex w-full flex-1 items-end">
                        <div class="w-full rounded-t-md transition-all duration-300"
                            :style="{ height: barH(d.value), background: d.color || 'rgb(var(--p))', opacity: active === i ? 1 : 0.85 }"></div>
                    </div>
                </div>
                <div v-if="active >= 0" class="pointer-events-none absolute -top-2 z-10 -translate-y-full rounded-lg bg-gray-900 px-2.5 py-1.5 text-xs text-white shadow-lg dark:bg-black"
                    :style="{ left: `${((active + 0.5) / data.length) * 100}%`, transform: 'translate(-50%, -100%)' }">
                    <div class="font-semibold">{{ data[active].label }}</div>
                    <div class="text-primary-300">{{ fmt(data[active].value) }}</div>
                </div>
            </div>
            <div class="mt-2 flex gap-2">
                <span v-for="(d, i) in data" :key="i" class="flex-1 truncate text-center text-[0.6rem] text-gray-400" :title="d.label">{{ d.label }}</span>
            </div>
        </div>

        <!-- DONUT -->
        <div v-else class="flex flex-col items-center">
            <div class="relative h-40 w-40">
                <svg viewBox="0 0 42 42" class="h-full w-full -rotate-90">
                    <circle cx="21" cy="21" r="15.915" fill="none" stroke="currentColor" class="text-gray-100 dark:text-[#2c2f3d]" stroke-width="4" />
                    <circle v-for="(seg, i) in donut" :key="i" cx="21" cy="21" r="15.915" fill="none"
                        :stroke="seg.color" stroke-width="4" stroke-linecap="round"
                        :stroke-dasharray="`${seg.len * anim} ${100 - seg.len * anim}`"
                        :stroke-dashoffset="seg.offset" class="transition-[stroke-dasharray] duration-300" />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-xl font-bold text-gray-900 dark:text-white">{{ centerLabel }}</span>
                    <span v-if="centerSub" class="text-[0.6rem] uppercase tracking-wide text-gray-400">{{ centerSub }}</span>
                </div>
            </div>
            <div class="mt-5 w-full space-y-2">
                <div v-for="(d, i) in data" :key="i" class="flex items-center gap-2.5 text-sm">
                    <span class="h-2.5 w-2.5 flex-shrink-0 rounded-full" :style="{ background: color(i) }"></span>
                    <span class="min-w-0 flex-1 truncate text-gray-700 dark:text-gray-300">{{ d.label }}</span>
                    <span class="font-semibold tabular-nums text-gray-900 dark:text-white">{{ fmt(d.value) }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from "vue";

interface Datum { label: string; value: number; color?: string }
const props = withDefaults(defineProps<{
    type: "area" | "bars" | "donut";
    data: Datum[];
    format?: "money" | "number";
    currency?: string;
    height?: number;
    centerLabel?: string;
    centerSub?: string;
}>(), { format: "number", currency: "DH", height: 224 });

const palette = ["#f97316", "#6366f1", "#10b981", "#f43f5e", "#eab308", "#0ea5e9", "#a855f7", "#14b8a6"];
const color = (i: number) => props.data[i]?.color || palette[i % palette.length];

const fmt = (v: number) =>
    props.format === "money"
        ? Number(v ?? 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + " " + props.currency
        : Number(v ?? 0).toLocaleString("en-US");

/* single rAF drives every animation */
const anim = ref(0);
onMounted(() => {
    if (window.matchMedia?.("(prefers-reduced-motion: reduce)").matches) { anim.value = 1; return; }
    const D = 1000, t0 = performance.now();
    const tick = (t: number) => {
        const p = Math.min(1, (t - t0) / D);
        anim.value = 1 - Math.pow(1 - p, 3);
        if (p < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
});

/* ── AREA ── */
const W = 600, H = 240, pad = 24;
const values = computed(() => props.data.map((d) => d.value));
const maxV = computed(() => Math.max(1, ...values.value));
const pts = computed(() =>
    values.value.map((v, i) => ({
        x: (i / Math.max(1, values.value.length - 1)) * W,
        y: (H - pad) - (v / maxV.value) * (H - pad - 10) + 5,
    }))
);
const linePath = computed(() => {
    const p = pts.value;
    if (!p.length) return "";
    let d = `M ${p[0].x} ${p[0].y}`;
    for (let i = 0; i < p.length - 1; i++) {
        const c = (p[i].x + p[i + 1].x) / 2;
        d += ` C ${c} ${p[i].y}, ${c} ${p[i + 1].y}, ${p[i + 1].x} ${p[i + 1].y}`;
    }
    return d;
});
const areaPath = computed(() => (pts.value.length ? `${linePath.value} L ${W} ${H - pad} L 0 ${H - pad} Z` : ""));
const gid = "rc" + Math.round(props.height) + props.type;
const showLabel = (i: number) => {
    const step = Math.max(1, Math.ceil(props.data.length / 7));
    return i % step === 0 || i === props.data.length - 1;
};

const lineEl = ref<SVGPathElement | null>(null);
const lineLen = ref(1);
onMounted(() => { if (lineEl.value) lineLen.value = lineEl.value.getTotalLength(); });

const active = ref(-1);
function onMove(e: MouseEvent) {
    const r = (e.currentTarget as SVGElement).getBoundingClientRect();
    const rel = (e.clientX - r.left) / r.width;
    active.value = Math.max(0, Math.min(pts.value.length - 1, Math.round(rel * (pts.value.length - 1))));
}

/* ── BARS ── */
const barH = (v: number) => `${Math.max(2, (v / maxV.value) * 100 * anim.value)}%`;

/* ── DONUT ── */
const totalDonut = computed(() => props.data.reduce((a, d) => a + d.value, 0));
const donut = computed(() => {
    let offset = 25;
    return props.data.map((d, i) => {
        const len = totalDonut.value ? (d.value / totalDonut.value) * 100 : 0;
        const seg = { len, offset, color: color(i) };
        offset = (offset - len + 100) % 100;
        return seg;
    });
});
</script>

<style scoped>
.chart-root { --p: 249 115 22; } /* Larkon orange */
</style>
