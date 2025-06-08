<template>
    <Head :title="title"></Head>
    <AppSectionHeader :title="title" :bread-crumb="breadCrumb">
        <template #right>
            <AppButton
                class="btn btn-primary"
                @click="$inertia.visit(route('borrowBook.create'))"
            >
                <i class="ri-add-fill mr-1"></i>
                Create BorrowBook
            </AppButton>
        </template>
    </AppSectionHeader>

    <AppDataSearch
        v-if="borrowBooks.data.length || route().params.searchTerm"
        :url="route('borrowBook.index')"
        fields-to-search="id"
    ></AppDataSearch>

    <AppDataTable v-if="borrowBooks.data.length" :headers="headers">
        <template #TableBody>
            <tbody>
                <AppDataTableRow
                    v-for="(item, index) in borrowBooks.data"
                    :key="item.id"
                >
                    <AppDataTableData>
                        {{ item.id }}
                    </AppDataTableData>

                    <!-- <AppDataTableData>
                        {{ item.name }}
                    </AppDataTableData> -->

                    <AppDataTableData>
                        <!-- Edit borrowBook -->
                        <AppTooltip text="Edit BorrowBook" class="mr-2">
                            <AppButton
                                class="btn btn-icon btn-primary"
                                @click="
                                    $inertia.visit(
                                        route(
                                            'borrowBook.edit',
                                            item.id
                                        )
                                    )
                                "
                            >
                                <i class="ri-edit-line"></i>
                            </AppButton>
                        </AppTooltip>

                        <!-- Delete borrowBook -->
                        <AppTooltip text="Delete BorrowBook">
                            <AppButton
                                class="btn btn-icon btn-destructive"
                                @click="
                                    confirmDelete(
                                        route(
                                            'borrowBook.destroy',
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
        v-if="borrowBooks.data.length"
        :links="borrowBooks.links"
        :from="borrowBooks.from || 0"
        :to="borrowBooks.to || 0"
        :total="borrowBooks.total || 0"
        class="mt-4 justify-center"
    ></AppPaginator>

    <AppAlert v-if="!borrowBooks.data.length" class="mt-4">
        No borrowBooks found.
    </AppAlert>

    <AppConfirmDialog ref="confirmDialogRef"></AppConfirmDialog>
</template>

<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import useTitle from '@/Composables/useTitle'
import useAuthCan from '@/Composables/useAuthCan'

const { title } = useTitle('BorrowBook')
const { can } = useAuthCan()

const props = defineProps({
  borrowBooks: {
    type: Object,
    default: () => {}
  }
})

const breadCrumb = [
  { label: 'Home', href: route('dashboard.index') },
  { label: 'BorrowBooks', last: true }
]

const headers = ['ID', 'Actions']

const confirmDialogRef = ref(null)
const confirmDelete = (deleteRoute) => {
    confirmDialogRef.value.openModal(deleteRoute)
}
</script>
