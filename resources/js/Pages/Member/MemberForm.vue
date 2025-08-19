<template>
    <Head :title="title"></Head>
    <AppSectionHeader :title="title" :bread-crumb="breadCrumb">
    </AppSectionHeader>

    <AppCard class="w-full md:w-3/4 xl:w-1/2">
        <template #title> {{ title }} </template>
        <template #content>
            <AppFormErrors class="mb-4" />
            <form>
                <div>
                    <AppLabel for="name">{{ __('Name') }}</AppLabel>
                    <AppInputText
                        id="name"
                        v-model="form.name"
                        type="text"
                        :class="{
                            'input-error': errorsFields.includes('name')
                        }"
                    />
                </div>
                <div>
                    <AppLabel for="email">{{ __('Email') }}</AppLabel>
                    <AppInputText
                        id="email"
                        v-model="form.email"
                        type="email"
                        :class="{
                            'input-error': errorsFields.includes('email')
                        }"
                    />
                </div>
                <div>
                  <AppLabel for="phone">{{ __('Phone') }}</AppLabel>
                  <AppInputText
                    id="phone"
                    v-model="form.phone"
                    type="text"
                    :class="{
                      'input-error': errorsFields.includes('phone')
                    }"
                  />
                </div>
                <div>
                  <AppLabel for="member_type">{{ __('Member Type') }}</AppLabel>
                  <AppCombobox
                      id="member_type"
                      v-model="form.member_type"
                      :options="memberTypesMapped"
                      class="w-full"
                      :class="{
                          'input-error': errorsFields.includes('member_type')
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
import { computed, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import useTitle from '@/Composables/useTitle'
import useFormContext from '@/Composables/useFormContext'
import useFormErrors from '@/Composables/useFormErrors'

const { title } = useTitle('Member')

const props = defineProps({
  member: {
    type: Object,
    default: null
  }
})

const breadCrumb = [
  { label: 'Home', href: route('dashboard.index') },
  { label: 'Members', href: route('member.index') },
  { label: 'Member', last: true }
]

const form = useForm({
  name: props.member ? props.member.name : '',
  email: props.member ? props.member.email : '',
  phone: props.member ? props.member.phone : '',
  profile_image: props.member ? props.member.profile_image : '',
  member_type: props.member ? props.member.member_type : ''
})

const { isCreate, isEdit } = useFormContext()

const submitForm = () => {
  form.transform(data => ({
    ...data,
    member_type: data.member_type?.value
  }))

  if (isCreate.value) {
    form.post(route('member.store'))
  }

  if(isEdit.value) {
    form.put(route('member.update', props.member.id))
  }
}

const memberTypesMapped = computed(() => {
  return [
    {
      'label': 'Student',
      'value': 'Student'
    },
    {
      'label': 'Teacher',
      'value': 'Teacher'
    },
    {
      'label': 'Staff',
      'value': 'Staff'
    }
  ]
})

onMounted(() => {
  if(props.member) {
    form.member_type = memberTypesMapped.value.find(memberType => memberType.value === props.member.member_type)
    console.log(form.member_type)
  }
})

const { errorsFields } = useFormErrors()
</script>
