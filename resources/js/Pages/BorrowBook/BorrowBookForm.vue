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
                    <!-- author -->
                    <div>
                      <AppLabel for="author_id">{{ __('Author') }}</AppLabel>
                      <AppCombobox
                          id="author_id"
                          v-model="form.author"
                          :options="authors"
                          class="w-full"
                          :class="{
                              'input-error': errorsFields.includes('author_id')
                          }"
                      />
                    </div>
                    <!-- books -->
                    <div>
                      <AppLabel for="book_id">{{ __('Book') }}</AppLabel>
                      <AppCombobox
                          id="book_id"
                          v-model="form.book"
                          :options="books"
                          class="w-full"
                          :class="{
                              'input-error': errorsFields.includes('book_id')
                          }"
                      />
                    </div>

                    <!-- member -->
                    <div>
                      <AppLabel for="member_id">{{ __('Member') }}</AppLabel>
                      <AppCombobox
                          id="member_id"
                          v-model="form.member"
                          :options="membersMapped"
                          class="w-full"
                          :class="{
                              'input-error': errorsFields.includes('member_id')
                          }"
                      />
                    </div>

                    <!-- borrow date -->
                    <div>
                      <AppLabel for="borrow_date">{{ __('Borrow Date') }}</AppLabel>
                      <AppInputDate
                          id="borrow_date"
                          v-model="form.borrow_date"
                          class="w-full"
                          :class="{
                              'input-error': errorsFields.includes('borrow_date')
                          }"
                      />
                    </div>
                    <!-- due date -->
                    <div>
                      <AppLabel for="due_date">{{ __('Due Date') }}</AppLabel>
                      <AppInputDate
                          id="due_date"
                          v-model="form.due_date"
                          class="w-full"
                          :class="{
                              'input-error': errorsFields.includes('due_date')
                          }"
                      />
                    </div>
                    <!-- return date -->
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
import axios from 'axios'
import { computed, onMounted, ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import useTitle from '@/Composables/useTitle'
import useFormContext from '@/Composables/useFormContext'
import useFormErrors from '@/Composables/useFormErrors'

const { title } = useTitle('BorrowBook')

const props = defineProps({
  borrowBook: {
    type: Object,
    default: null
  },
  members: {
    type: Array,
    default: []
  }
})

const breadCrumb = [
  { label: 'Home', href: route('dashboard.index') },
  { label: 'BorrowBooks', href: route('borrowBook.index') },
  { label: 'BorrowBook', last: true }
]

const form = useForm({
  author_id: props.borrowBook ? props.borrowBook.author_id : '',
  book_id: props.borrowBook ? props.borrowBook.book_id : '',
  member_id: props.borrowBook ? props.borrowBook.member_id : '',
  return_date: props.borrowBook ? props.borrowBook.return_date : '',
  due_date: props.borrowBook ? props.borrowBook.due_date : '',
  borrow_date: props.borrowBook ? props.borrowBook.borrow_date : '',
  author: null,
  book: null,
  member: null
})

const { isCreate, isEdit } = useFormContext()

const submitForm = () => {
  form.transform(data => ({
      ...data,
      author_id: data.author.value,
      book_id: data.book.value,
      member_id: data.member?.value
  }))
  if (isCreate.value) {
    form.post(route('borrowBook.store'))
  }

  if(isEdit.value) {
    form.put(route('borrowBook.update', props.borrowBook.id))
  }
}

const authors = ref(null)
const books = ref(null)
const searchColumn = 'first_name, middle_name, last_name, suffix_name'
const getAuthors = async (searchColumn = searchColumn, searchTerm = null) => {
  await axios
    .get(route('author.getAuthors'), {
      params: {
        searchColumn,
        searchTerm,
      },
    })
    .then((response) => {
      authors.value = response.data
      books.value = null
    })
    .catch((error) => {
      console.error('Failed to fetch authors:', error)
    })
}

onMounted(async () => {
  await getAuthors(searchColumn)

  if (props.borrowBook) {
    authors.value = authors.value.find(function(author){
      return author.id === props.borrowBook.author_id
    })

    form.member = membersMapped.value.find(function(member){
      return member.value === props.borrowBook.member_id
    })
  }
})

watch(() => form.author, (val) => {
  // Set form value
  form.author = val ?? null

  // 👇 Do something when author changes
  if (val) {
    console.log('Selected Author:', val)
    // Example: Load books by selected author
    form.book = null
    form.author_id = val.value
    books.value = val.books
  } else {
    // Author cleared
    books.value = null
    console.log('Author selection cleared')
  }
})

const membersMapped = computed(() => {
  return props.members.map(member => ({
    label: member.name,
    value: member.id
  }))
})

const { errorsFields } = useFormErrors()
</script>
