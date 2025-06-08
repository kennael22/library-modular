<template>
    <Head :title="title"></Head>
    <AppSectionHeader :title="title" :bread-crumb="breadCrumb">
        <template #right>
            <AppButton
                class="btn btn-primary"
                @click="$inertia.visit(route('bookCopy.create'))"
            >
                <i class="ri-add-fill mr-1"></i>
                Create BookCopy
            </AppButton>
        </template>
    </AppSectionHeader>

    <AppDataSearch
        v-if="bookCopies.data.length || route().params.searchTerm"
        :url="route('bookCopy.index')"
        fields-to-search="id"
    ></AppDataSearch>

    <AppDataTable v-if="bookCopies.data.length" :headers="headers">
        <template #TableBody>
            <tbody>
                <AppDataTableRow
                    v-for="(item, index) in bookCopies.data"
                    :key="item.id"
                >
                    <AppDataTableData>
                        {{ item.id }}
                    </AppDataTableData>

                    <!-- <AppDataTableData>
                        {{ item.name }}
                    </AppDataTableData> -->

                    <AppDataTableData>
                        <!-- Edit bookCopy -->
                        <AppTooltip text="Edit BookCopy" class="mr-2">
                            <AppButton
                                class="btn btn-icon btn-primary"
                                @click="
                                    $inertia.visit(
                                        route(
                                            'bookCopy.edit',
                                            item.id
                                        )
                                    )
                                "
                            >
                                <i class="ri-edit-line"></i>
                            </AppButton>
                        </AppTooltip>

                        <!-- Delete bookCopy -->
                        <AppTooltip text="Delete BookCopy">
                            <AppButton
                                class="btn btn-icon btn-destructive"
                                @click="
                                    confirmDelete(
                                        route(
                                            'bookCopy.destroy',
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
        v-if="bookCopies.data.length"
        :links="bookCopies.links"
        :from="bookCopies.from || 0"
        :to="bookCopies.to || 0"
        :total="bookCopies.total || 0"
        class="mt-4 justify-center"
    ></AppPaginator>

    <AppAlert v-if="!bookCopies.data.length" class="mt-4">
        No bookCopies found.
    </AppAlert>

    <AppConfirmDialog ref="confirmDialogRef"></AppConfirmDialog>
</template>

<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import useTitle from '@/Composables/useTitle'
import useAuthCan from '@/Composables/useAuthCan'

const { title } = useTitle('BookCopy')
const { can } = useAuthCan()

const props = defineProps({
  bookCopies: {
    type: Object,
    default: () => {}
  }
})

const breadCrumb = [
  { label: 'Home', href: route('dashboard.index') },
  { label: 'BookCopies', last: true }
]

const headers = ['ID', 'Actions']

const confirmDialogRef = ref(null)
const confirmDelete = (deleteRoute) => {
    confirmDialogRef.value.openModal(deleteRoute)
}
</script>
