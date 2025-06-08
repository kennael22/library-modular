<template>
    <Head :title="title"></Head>
    <AppSectionHeader :title="title" :bread-crumb="breadCrumb">
    </AppSectionHeader>

    <AppCard class="w-full md:w-3/4 xl:w-1/2">
        <template #title> {{ title }} </template>
        <template #content>
            <AppFormErrors class="mb-4" />
            <!-- title, author id, edition, volumes, pages, source of fund, publisher, publication year, genre, access_book_number, cover_image -->
            <form>
                <div>
                    <AppLabel for="title">{{ __('Title') }}</AppLabel>
                    <AppInputText
                        id="title"
                        v-model="form.title"
                        type="text"
                        :class="{
                            'input-error': errorsFields.includes('title')
                        }"
                    />
                </div>
                <div>
                    <AppLabel for="author_id">{{ __('Author') }}</AppLabel>
                    <AppCombobox
                        id="author_id"
                        v-model="form.author_id"
                        :options="authorsMapped"
                        class="w-full"
                        :class="{
                            'input-error': errorsFields.includes('author_id')
                        }"
                    />
                </div>
                <div>
                    <AppLabel for="edition">{{ __('Edition') }}</AppLabel>
                    <AppInputText
                        id="edition"
                        v-model="form.edition"
                        type="text"
                        :class="{
                            'input-error': errorsFields.includes('edition')
                        }"
                    />
                </div>
                <div>
                    <AppLabel for="volumes">{{ __('Volumes') }}</AppLabel>
                    <AppInputText
                        id="volumes"
                        v-model="form.volumes"
                        type="text"
                        :class="{
                            'input-error': errorsFields.includes('volumes')
                        }"
                    />
                </div>
                <div>
                    <AppLabel for="pages">{{ __('Pages') }}</AppLabel>
                    <AppInputText
                        id="pages"
                        v-model="form.pages"
                        type="text"
                        :class="{
                            'input-error': errorsFields.includes('pages')
                        }"
                    />
                </div>
                <div>
                    <AppLabel for="source_of_fund">{{
                        __('Source of Fund')
                    }}</AppLabel>
                    <AppInputText
                        id="source_of_fund"
                        v-model="form.source_of_fund"
                        type="text"
                        :class="{
                            'input-error': errorsFields.includes('source_of_fund')
                        }"
                    />
                </div>
                <div>
                    <AppLabel for="publisher">{{ __('Publisher') }}</AppLabel>
                    <AppInputText
                        id="publisher"
                        v-model="form.publisher"
                        type="text"
                        :class="{
                            'input-error': errorsFields.includes('publisher')
                        }"
                    />
                </div>
                <div>
                    <AppLabel for="publication_year">{{
                        __('Publication Year')
                    }}</AppLabel>
                    <AppInputText
                        id="publication_year"
                        v-model="form.publication_year"
                        type="text"
                        :class="{
                            'input-error': errorsFields.includes('publication_year')
                        }"
                    />
                </div>
                <div>
                    <AppLabel for="genre">{{ __('Genre') }}</AppLabel>
                    <AppInputText
                        id="genre"
                        v-model="form.genre"
                        type="text"
                        :class="{
                            'input-error': errorsFields.includes('genre')
                        }"
                    />
                </div>
                <div>
                    <AppLabel for="access_book_number">{{
                        __('Access Book Number')
                    }}</AppLabel>
                    <AppInputText
                        id="access_book_number"
                        v-model="form.access_book_number"
                        type="text"
                        :class="{
                            'input-error': errorsFields.includes('access_book_number')
                        }"
                    />
                </div>
                <div>
                    <AppLabel for="cover_image">{{
                        __('Cover Image')
                    }}</AppLabel>
                    <AppInputFile
                        v-model="form.cover_image"
                        :image-preview-url="getImagePreviewURL()"
                        @remove-file="removeFile()"
                    ></AppInputFile>
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
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import useTitle from '@/Composables/useTitle'
import useFormContext from '@/Composables/useFormContext'
import useFormErrors from '@/Composables/useFormErrors'
import AppCombobox from '@/Components/Form/AppCombobox.vue'

const { title } = useTitle('Book')

const props = defineProps({
  book: {
    type: Object,
    default: null
  },
  authors: {
    type: Array,
    default: []
  }
})

const breadCrumb = [
  { label: 'Home', href: route('dashboard.index') },
  { label: 'Books', href: route('book.index') },
  { label: 'Book', last: true }
]

// title, author id, edition, volumes, pages, source of fund, publisher, publication year, genre, access_book_number, cover_image
const form = useForm({
  title: props.book ? props.book.title : '',
  author_id: props.book ? props.book.author_id : '',
  edition: props.book ? props.book.edition : '',
  volumes: props.book ? props.book.volumes : '',
  pages: props.book ? props.book.pages : '',
  source_of_fund: props.book ? props.book.source_of_fund : '',
  publisher: props.book ? props.book.publisher : '',
  publication_year: props.book ? props.book.publication_year : '',
  genre: props.book ? props.book.genre : '',
  access_book_number: props.book ? props.book.access_book_number : '',
  cover_image: props.book ? props.book.cover_image : null,
  remove_previous_image: false
})

const getImagePreviewURL = () => {
    if (!isCreate.value && form.cover_image) {
        return form.cover_image
    }

    return null
}

const { isCreate, isEdit } = useFormContext()

const submitForm = () => {
  if (isCreate.value) {
    form.transform((data) => {
      return {
        ...data,
        author_id: form.author_id.value
      }
    }).post(route('book.store'))
  }

  if(isEdit.value) {
    console.log(form)
    form.transform((data) => {
      return {
        ...data,
        author_id: form.author_id.value
      }
    }).post(route('book.update', props.book.id))
  }
}

const { errorsFields } = useFormErrors()

const authorsMapped = computed(() => {
  return props.authors.map((author) => {
    return {
      label: `${author.first_name} ${author.middle_name} ${author.last_name} ${author.suffix_name}`,
      value: author.id
    }
  })
})

const removeFile = () => {
    form.cover_image = null
    form.remove_previous_image = true
}
</script>
