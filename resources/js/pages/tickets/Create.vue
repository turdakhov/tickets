<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard, support } from '@/routes';
import { store } from '@/routes/tickets';
import { type BreadcrumbItem } from '@/types';
import { Form, Head, useForm, usePage } from '@inertiajs/vue3';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
    },
    {
        title: 'Submit a Ticket',
        href: support(),
    },
];

const page = usePage();

</script>

<template>

    <Head title="Submit a Ticket" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <Form :action="store()" class="lg:w-6/12 w-full space-y-4" #default="{
                errors,
                processing,
            }">
                <div class="space-y-2">
                    <Label for="category_id">Category</Label>
                    <Select id="category_id" name="category_id">
                        <SelectTrigger class="w-[180px]">
                            <SelectValue placeholder="Select a category" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectLabel>Categories</SelectLabel>
                                <SelectItem v-for="category in page.props.categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <div class="text-sm text-red-600" v-if="errors['category_id']">{{ errors['category_id'] }}</div>
                </div>

                <div class="space-y-2">
                    <Label for="subject">Subject</Label>
                    <Textarea id="subject" name="subject" placeholder="Enter the Subject" />
                    <div class="text-sm text-red-600" v-if="errors['subject']">{{ errors['subject'] }}</div>
                </div>

                <div class="space-y-2">
                    <Label for="message">Message</Label>
                    <Textarea id="message" name="message" placeholder="Enter the Message" />
                    <div class="text-sm text-red-600" v-if="errors['message']">{{ errors['message'] }}</div>
                </div>

                <Button type="submit" :disabled="processing">Submit</Button>
            </Form>
        </div>
    </AppLayout>
</template>
