<template>
    <Head :title="title"></Head>
    <AppSectionHeader :title="title" :bread-crumb="breadCrumb">
        <template #right>
            <AppButton
                class="btn btn-primary"
                @click="$inertia.visit(route('book.create'))"
            >
                <i class="ri-add-fill mr-1"></i>
                Create Book
            </AppButton>
        </template>
    </AppSectionHeader>

    <AppDataSearch
        v-if="books.data.length || route().params.searchTerm"
        :url="route('book.index')"
        fields-to-search="id"
    ></AppDataSearch>

    <AppDataTable v-if="books.data.length" :headers="headers">
        <template #TableBody>
            <tbody>
            <!-- cover_image , title, author id, edition, volumes, pages, source of fund, publisher, publication year, genre, access_book_number -->
                <AppDataTableRow
                    v-for="(item, index) in books.data"
                    :key="item.id"
                >
                    <AppDataTableData>
                        <img
                            v-if="item.cover_image"
                            :src="item.cover_image"
                            class="h-10 w-10 rounded"
                        />

                        <AppImageNotAvailable v-else />
                    </AppDataTableData>

                    <AppDataTableData>
                        {{ item.title }}
                    </AppDataTableData>

                    <AppDataTableData>
                        {{ item.author }}
                    </AppDataTableData>

                    <AppDataTableData>
                        {{ item.genre }}
                    </AppDataTableData>

                    <AppDataTableData>
                        {{ item.access_book_number }}
                    </AppDataTableData>

                    <AppDataTableData>
                        <!-- Edit book -->
                        <AppTooltip text="Edit Book" class="mr-2">
                            <AppButton
                                class="btn btn-icon btn-primary"
                                @click="
                                    $inertia.visit(
                                        route(
                                            'book.edit',
                                            item.id
                                        )
                                    )
                                "
                            >
                                <i class="ri-edit-line"></i>
                            </AppButton>
                        </AppTooltip>

                        <!-- Delete book -->
                        <AppTooltip text="Delete Book">
                            <AppButton
                                class="btn btn-icon btn-destructive"
                                @click="
                                    confirmDelete(
                                        route(
                                            'book.destroy',
                                            item.id
                                        )
                                    )
                                "
                            >
                                <i class="ri-delete-bin-line"></i>
                            </AppButton>
                        </AppTooltip>
                    </AppDataTableData>
                </AppDataTableRow>
            </tbody>
        </template>
    </AppDataTable>

    <AppPaginator
        v-if="books.data.length"
        :links="books.links"
        :from="books.from || 0"
        :to="books.to || 0"
        :total="books.total || 0"
        class="mt-4 justify-center"
    ></AppPaginator>

    <AppAlert v-if="!books.data.length" class="mt-4">
        No books found.
    </AppAlert>

    <AppConfirmDialog ref="confirmDialogRef"></AppConfirmDialog>
</template>

<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import useTitle from '@/Composables/useTitle'
import useAuthCan from '@/Composables/useAuthCan'

const { title } = useTitle('Book')
const { can } = useAuthCan()

const props = defineProps({
  books: {
    type: Object,
    default: () => {}
  }
})

const breadCrumb = [
  { label: 'Home', href: route('dashboard.index') },
  { label: 'Books', last: true }
]

const headers = ['Image', 'Title', 'Author', 'Genre', 'ISBN', 'Actions']

const confirmDialogRef = ref(null)
const confirmDelete = (deleteRoute) => {
    confirmDialogRef.value.openModal(deleteRoute)
}
</script>
