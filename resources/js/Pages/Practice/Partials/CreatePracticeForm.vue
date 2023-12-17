<script lang="ts" setup>
import * as z from "zod";
import { Button } from "@/Components/ui/button";
import {
    Form,
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/Components/ui/form";
import { Input } from "@/Components/ui/input";

import { Calendar } from "@/Components/ui/calendar";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/Components/ui/popover";

import { vAutoAnimate } from "@formkit/auto-animate/vue";

import { toTypedSchema } from "@vee-validate/zod";
import { useForm } from "vee-validate";
import { phoneRegex } from "@/lib/utils";
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from "@/Components/ui/command";
import { ref } from "vue";
import { addDays } from "date-fns";
import { formatDate } from "@vueuse/shared";

const schema = z
    .object({
        first_name: z.string().min(1).max(255),
        last_name: z.string().min(1).max(255),
        patronymic: z.string().min(1).max(255),
        phone_number: z
            .string()
            .min(10)
            .max(11)
            .regex(phoneRegex, "Invalid Phone number!"),
        start_date: z.coerce.date().refine((data) => data > new Date(), {
            message: "Start date must be in the future",
        }),
        end_date: z.date(),
        school_name: z.string(),
        specialty: z.string(),
        practice: z.string(),
    })
    .refine((data) => data.end_date > data.start_date, {
        message: "End date cannot be earlier than start date.",
        path: ["end_date"],
    });

const formSchema = toTypedSchema(schema);

const schools = [
    { label: "petro", value: "petro" },
    { label: "itmo", value: "itmo" },
    { label: "college", value: "college" },
];
const practices = [
    { label: "pract1", value: "pract1" },
    { label: "pract2", value: "pract2" },
];

const { handleSubmit, values, setFieldValue, setValues } = useForm<
    z.infer<typeof schema>
>({
    validationSchema: formSchema,
});

const onSubmit = handleSubmit((values) => {
    // toast({
    //     title: 'You submitted the following values:',
    //     description: h('pre', { class: 'mt-2 w-[340px] rounded-md bg-slate-950 p-4' }, h('code', { class: 'text-white' }, JSON.stringify(values, null, 2))),
    // })
    console.log(values);
});

const date = ref({
    start: new Date(),
    end: addDays(new Date(), 20),
});

const updateDate = (): void => {
    setValues({
        start_date: new Date(date.value.start),
        end_date: new Date(date.value.end),
    });
};
</script>

<template>
    <form
        :validation-schema="formSchema"
        className="space-y-4 mx-auto w-[20rem] sm:w-[30rem]"
        @submit="onSubmit"
    >
        <FormField v-slot="{ componentField }" name="first_name">
            <FormItem v-auto-animate>
                <FormLabel>First name</FormLabel>
                <FormControl>
                    <Input placeholder="First name" v-bind="componentField" />
                </FormControl>
                <FormDescription></FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>

        <FormField v-slot="{ componentField }" name="last_name">
            <FormItem v-auto-animate>
                <FormLabel>Last name</FormLabel>
                <FormControl>
                    <Input placeholder="Last name" v-bind="componentField" />
                </FormControl>
                <FormDescription></FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>

        <FormField v-slot="{ componentField }" name="patronymic">
            <FormItem v-auto-animate>
                <FormLabel>Patronymic</FormLabel>
                <FormControl>
                    <Input placeholder="Patronymic" v-bind="componentField" />
                </FormControl>
                <FormDescription></FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>

        <FormField v-slot="{ componentField }" )} name="phone_number">
            <FormItem v-auto-animate>
                <FormLabel>Phone number</FormLabel>
                <FormControl>
                    <Input placeholder="89052252432" v-bind="componentField" />
                </FormControl>
                <FormDescription>
                    Your emergency contact information.
                </FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>

        <FormField name="date">
            <FormItem v-auto-animate>
                <FormLabel>Dates</FormLabel>
                <Popover>
                    <PopoverTrigger asChild>
                        <FormControl>
                            <Button class="w-[250px]" variant="outline">
                                <span v-if="values.start_date">{{
                                    formatDate(date.start, "DD.MM.YYYY", {
                                        locales: "ru-RU",
                                    }) +
                                    " – " +
                                    formatDate(date.end, "DD.MM.YYYY", {
                                        locales: "ru-RU",
                                    })
                                }}</span>
                                <span v-else> Pick a date range </span>
                            </Button>
                        </FormControl>
                    </PopoverTrigger>
                    <PopoverContent
                        :avoid-collisions="true"
                        align="start"
                        className="w-auto p-0"
                    >
                        <Calendar
                            v-model.range="date"
                            :columns="2"
                            type="range"
                            @update:modelValue="() => updateDate()"
                        />
                    </PopoverContent>
                </Popover>
                <FormDescription>
                    The days your practice starts and ends.
                </FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>

        <FormField name="schools">
            <FormItem v-auto-animate>
                <FormLabel>Schools</FormLabel>
                <Popover>
                    <PopoverTrigger asChild>
                        <FormControl>
                            <Button
                                class="w-full"
                                role="combobox"
                                variant="outline"
                            >
                                <span v-if="!values.school_name"
                                    >Select schools type...</span
                                >
                                <span v-else>{{ values.school_name }}</span>
                            </Button>
                        </FormControl>
                    </PopoverTrigger>
                    <PopoverContent className="w-[300px] border rounded-md">
                        <Command>
                            <CommandInput placeholder="Search schools..." />
                            <CommandEmpty>No schools found.</CommandEmpty>
                            <CommandList>
                                <CommandGroup>
                                    <CommandItem
                                        v-for="school in schools"
                                        :key="school.label"
                                        :value="school.value"
                                        @select="
                                            setFieldValue(
                                                'school_name',
                                                school.value,
                                            )
                                        "
                                    >
                                        {{ school.label }}
                                    </CommandItem>
                                </CommandGroup>
                            </CommandList>
                        </Command>
                    </PopoverContent>
                </Popover>
                <FormDescription>
                    This is where you're studying at.
                </FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>

        <FormField v-slot="{ componentField }" name="specialty">
            <FormItem v-auto-animate>
                <FormLabel>Specialty</FormLabel>
                <FormControl>
                    <Input
                        placeholder="Your specialty"
                        v-bind="componentField"
                    />
                </FormControl>
                <FormDescription>
                    This is the specialty you study.
                </FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>

        <FormField name="practice">
            <FormItem v-auto-animate>
                <FormLabel>Practice</FormLabel>
                <Popover>
                    <PopoverTrigger asChild>
                        <FormControl>
                            <Button
                                class="w-full"
                                role="combobox"
                                variant="outline"
                            >
                                <span v-if="!values.practice"
                                    >Select practice type...</span
                                >
                                <span v-else>{{ values.practice }}</span>
                            </Button>
                        </FormControl>
                    </PopoverTrigger>
                    <PopoverContent className="w-[300px] border rounded-md">
                        <Command>
                            <CommandInput placeholder="Search practices..." />
                            <CommandEmpty>No practices found.</CommandEmpty>
                            <CommandList>
                                <CommandGroup>
                                    <CommandItem
                                        v-for="practice in practices"
                                        :key="practice.label"
                                        :value="practice.value"
                                        @select="
                                            setFieldValue(
                                                'practice',
                                                practice.value,
                                            )
                                        "
                                    >
                                        {{ practice.label }}
                                    </CommandItem>
                                </CommandGroup>
                            </CommandList>
                        </Command>
                    </PopoverContent>
                </Popover>
                <FormDescription>
                    Choose the type of practice you signed up for.
                </FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>

        <div class="flex flex-col">
            <Button type="submit">Submit</Button>
        </div>
        {{ JSON.stringify(values, null, 4) }}
    </form>
</template>
