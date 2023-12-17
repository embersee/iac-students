<script lang="ts" setup>
import { useVModel } from "@vueuse/core";
import type { Calendar } from "v-calendar";
import { DatePicker } from "v-calendar";
import { ChevronLeft, ChevronRight } from "lucide-vue-next";
import { computed, nextTick, onMounted, ref } from "vue";
import { buttonVariants } from "@/Components/ui/button";
import { cn } from "@/lib/utils";

/* Extracted from v-calendar */
type DatePickerModel = DatePickerDate | DatePickerRangeObject;
type DateSource = Date | string | number;
type DatePickerDate = DateSource | Partial<SimpleDateParts> | null;

export interface DatePickerRangeObject {
    start: Exclude<DatePickerDate, null>;
    end: Exclude<DatePickerDate, null>;
}

interface SimpleDateParts {
    year: number;
    month: number;
    day: number;
    hours: number;
    minutes: number;
    seconds: number;
    milliseconds: number;
}

defineOptions({
    inheritAttrs: false,
});

const props = withDefaults(
    defineProps<{
        modelValue?: string | number | Date | DatePickerModel;
        modelModifiers?: object;
        columns?: number;
        type?: "single" | "range";
    }>(),
    {
        type: "single",
        columns: 1,
    },
);
const emits = defineEmits<{
    (e: "update:modelValue", payload: typeof props.modelValue): void;
}>();

const modelValue = useVModel(props, "modelValue", emits, {
    passive: true,
});

const datePicker = ref<InstanceType<typeof DatePicker>>();
// @ts-expect-error in this current version of v-calendar has the calendaRef instance, which is required to handle arrow nav.
const calendarRef = computed<InstanceType<typeof Calendar>>(
    () => datePicker.value?.calendarRef,
);

function handleNav(direction: "prev" | "next") {
    if (!calendarRef.value) return;

    if (direction === "prev") calendarRef.value.movePrev();
    else calendarRef.value.moveNext();
}

onMounted(async () => {
    await nextTick();
    if (modelValue.value instanceof Date && calendarRef.value)
        calendarRef.value.focusDate(modelValue.value);
});
</script>

<template>
    <div class="bg-background relative rounded-md border">
        <div class="absolute top-3 z-[1] flex w-full justify-between px-4">
            <button
                :class="
                    cn(
                        buttonVariants({ variant: 'outline' }),
                        'h-7 w-7 bg-transparent p-0 opacity-50 hover:opacity-100',
                    )
                "
                @click="handleNav('prev')"
            >
                <ChevronLeft class="h-4 w-4" />
            </button>
            <button
                :class="
                    cn(
                        buttonVariants({ variant: 'outline' }),
                        'h-7 w-7 bg-transparent p-0 opacity-50 hover:opacity-100',
                    )
                "
                @click="handleNav('next')"
            >
                <ChevronRight class="h-4 w-4" />
            </button>
        </div>

        <DatePicker
            ref="datePicker"
            v-model="modelValue"
            :columns="columns"
            :model-modifiers="modelModifiers"
            :transition="'none'"
            class="calendar"
            trim-weeks
            v-bind="$attrs"
        />
    </div>
</template>

<style lang="postcss">
.calendar {
    @apply p-3 text-center;
}

.calendar .vc-pane-layout {
    @apply grid gap-4;
}

.calendar .vc-title {
    @apply pointer-events-none text-sm font-medium;
}

.calendar .vc-pane-header-wrapper {
    @apply hidden;
}

.calendar .vc-weeks {
    @apply mt-4;
}

.calendar .vc-weekdays {
    @apply flex;
}

.calendar .vc-weekday {
    @apply text-muted-foreground w-9 rounded-md text-[0.8rem] font-normal;
}

.calendar .vc-weeks {
    @apply flex w-full flex-col space-y-2 [&>_div]:grid [&>_div]:grid-cols-7;
}

.calendar .vc-day:has(.vc-highlights) {
    @apply bg-accent overflow-hidden first:rounded-l-md last:rounded-r-md;
}

.calendar .vc-day-content {
    @apply ring-offset-background focus-visible:ring-ring hover:bg-accent hover:text-accent-foreground relative inline-flex h-9 w-9 select-none items-center justify-center p-0 text-center text-sm font-normal transition-colors focus-within:relative focus-within:z-20 focus-visible:outline-none  focus-visible:ring-2 focus-visible:ring-offset-2 aria-selected:opacity-100;
}

.calendar .vc-day-content:not(.vc-highlight-content-light) {
    @apply rounded-md;
}

.calendar
    .is-not-in-month:not(:has(.vc-highlight-content-solid)):not(
        :has(.vc-highlight-content-light)
    ):not(:has(.vc-highlight-content-outline)),
.calendar .vc-disabled {
    @apply text-muted-foreground opacity-50;
}

.calendar .vc-highlight-content-solid,
.calendar .vc-highlight-content-outline {
    @apply bg-primary text-primary-foreground hover:bg-primary hover:text-primary-foreground focus:bg-primary focus:text-primary-foreground;
}

.calendar .vc-highlight-content-light {
    @apply bg-accent text-accent-foreground;
}

.calendar .vc-pane-container.in-transition {
    @apply overflow-hidden;
}

.calendar .vc-pane-container {
    @apply relative w-full;
}

:root {
    --vc-slide-translate: 22px;
    --vc-slide-duration: 0.15s;
    --vc-slide-timing: ease;
}

.calendar .vc-fade-enter-active,
.calendar .vc-fade-leave-active,
.calendar .vc-slide-left-enter-active,
.calendar .vc-slide-left-leave-active,
.calendar .vc-slide-right-enter-active,
.calendar .vc-slide-right-leave-active,
.calendar .vc-slide-up-enter-active,
.calendar .vc-slide-up-leave-active,
.calendar .vc-slide-down-enter-active,
.calendar .vc-slide-down-leave-active,
.calendar .vc-slide-fade-enter-active,
.calendar .vc-slide-fade-leave-active {
    transition:
        opacity var(--vc-slide-duration) var(--vc-slide-timing),
        -webkit-transform var(--vc-slide-duration) var(--vc-slide-timing);
    transition:
        transform var(--vc-slide-duration) var(--vc-slide-timing),
        opacity var(--vc-slide-duration) var(--vc-slide-timing);
    transition:
        transform var(--vc-slide-duration) var(--vc-slide-timing),
        opacity var(--vc-slide-duration) var(--vc-slide-timing),
        -webkit-transform var(--vc-slide-duration) var(--vc-slide-timing);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    pointer-events: none;
}

.calendar .vc-none-leave-active,
.calendar .vc-fade-leave-active,
.calendar .vc-slide-left-leave-active,
.calendar .vc-slide-right-leave-active,
.calendar .vc-slide-up-leave-active,
.calendar .vc-slide-down-leave-active {
    position: absolute !important;
    width: 100%;
}

.calendar .vc-none-enter-from,
.calendar .vc-none-leave-to,
.calendar .vc-fade-enter-from,
.calendar .vc-fade-leave-to,
.calendar .vc-slide-left-enter-from,
.calendar .vc-slide-left-leave-to,
.calendar .vc-slide-right-enter-from,
.calendar .vc-slide-right-leave-to,
.calendar .vc-slide-up-enter-from,
.calendar .vc-slide-up-leave-to,
.calendar .vc-slide-down-enter-from,
.calendar .vc-slide-down-leave-to,
.calendar .vc-slide-fade-enter-from,
.calendar .vc-slide-fade-leave-to {
    opacity: 0;
}

.calendar .vc-slide-left-enter-from,
.calendar .vc-slide-right-leave-to,
.calendar .vc-slide-fade-enter-from.direction-left,
.calendar .vc-slide-fade-leave-to.direction-left {
    -webkit-transform: translateX(var(--vc-slide-translate));
    transform: translateX(var(--vc-slide-translate));
}

.calendar .vc-slide-right-enter-from,
.calendar .vc-slide-left-leave-to,
.calendar .vc-slide-fade-enter-from.direction-right,
.calendar .vc-slide-fade-leave-to.direction-right {
    -webkit-transform: translateX(calc(-1 * var(--vc-slide-translate)));
    transform: translateX(calc(-1 * var(--vc-slide-translate)));
}

.calendar .vc-slide-up-enter-from,
.calendar .vc-slide-down-leave-to,
.calendar .vc-slide-fade-enter-from.direction-top,
.calendar .vc-slide-fade-leave-to.direction-top {
    -webkit-transform: translateY(var(--vc-slide-translate));
    transform: translateY(var(--vc-slide-translate));
}

.calendar .vc-slide-down-enter-from,
.calendar .vc-slide-up-leave-to,
.calendar .vc-slide-fade-enter-from.direction-bottom,
.calendar .vc-slide-fade-leave-to.direction-bottom {
    -webkit-transform: translateY(calc(-1 * var(--vc-slide-translate)));
    transform: translateY(calc(-1 * var(--vc-slide-translate)));
}
</style>
