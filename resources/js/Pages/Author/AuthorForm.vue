<template>
    <Head :title="title"></Head>
    <AppSectionHeader :title="title" :bread-crumb="breadCrumb">
    </AppSectionHeader>

    <AppCard class="w-full md:w-3/4 xl:w-1/2">
        <template #title> {{ title }} </template>
        <template #content>
            <AppFormErrors class="mb-4" />
            <!-- first_name, middle_name, last_name, suffix_name -->
            <form>
                <div>
                    <AppLabel for="first_name">{{ __('First Name') }}</AppLabel>
                    <AppInputText
                        id="first_name"
                        v-model="form.first_name"
                        type="text"
                        :class="{
                            'input-error': errorsFields.includes('first_name')
                        }"
                    />
                </div>
                <div>
                    <AppLabel for="middle_name">{{
                        __('Middle Name')
                    }}</AppLabel>
                    <AppInputText
                        id="middle_name"
                        v-model="form.middle_name"
                        type="text"
                        :class="{
                            'input-error': errorsFields.includes('middle_name')
                        }"
                    />
                </div>
                <div>
                    <AppLabel for="last_name">{{ __('Last Name') }}</AppLabel>
                    <AppInputText
                        id="last_name"
                        v-model="form.last_name"
                        type="text"
                        :class="{
                            'input-error': errorsFields.includes('last_name')
                        }"
                    />
                </div>
                <div>
                    <AppLabel for="suffix_name">{{
                        __('Suffix Name')
                    }}</AppLabel>
                    <AppInputText
                        id="suffix_name"
                        v-model="form.suffix_name"
                        type="text"
                        :class="{
                            'input-error': errorsFields.includes('suffix_name')
                        }"
                    />
                </div>
            </form>
        </template>
        <template #footer>
            <AppButton class="btn btn-primary" @click="submitForm">
                {{ __('Save') }}
            </AppButton>
        </template>
    </AppCard>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import useTitle from '@/Composables/useTitle'
import useFormContext from '@/Composables/useFormContext'
import useFormErrors from '@/Composables/useFormErrors'

const { title } = useTitle('Author')

const props = defineProps({
  author: {
    type: Object,
    default: null
  }
})

const breadCrumb = [
  { label: 'Home', href: route('dashboard.index') },
  { label: 'Authors', href: route('author.index') },
  { label: 'Author', last: true }
]


// first_name, middle_name, last_name, suffix_name
const form = useForm({
  first_name: props.author ? props.author.first_name : '',
  middle_name: props.author ? props.author.middle_name : '',
  last_name: props.author ? props.author.last_name : '',
  suffix_name: props.author ? props.author.suffix_name : '',
})

const { isCreate, isEdit } = useFormContext()

const submitForm = () => {
  if (isCreate.value) {
    form.post(route('author.store'))
  }

  if(isEdit.value) {
    form.put(route('author.update', props.author.id))
  }
}

const { errorsFields } = useFormErrors()
</script>
