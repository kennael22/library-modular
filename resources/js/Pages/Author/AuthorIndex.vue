<template>
    <Head :title="title"></Head>
    <AppSectionHeader :title="title" :bread-crumb="breadCrumb">
        <template #right>
            <AppButton
                class="btn btn-primary"
                @click="$inertia.visit(route('author.create'))"
            >
                <i class="ri-add-fill mr-1"></i>
                Create Author
            </AppButton>
        </template>
    </AppSectionHeader>

    <AppDataSearch
        v-if="authors.data.length || route().params.searchTerm"
        :url="route('author.index')"
        fields-to-search="id"
    ></AppDataSearch>

    <AppDataTable v-if="authors.data.length" :headers="headers">
        <template #TableBody>
            <tbody>
                <AppDataTableRow
                    v-for="(item, index) in authors.data"
                    :key="item.id"
                >
                    <AppDataTableData>
                        {{ item.id }}
                    </AppDataTableData>

                    <AppDataTableData>
                        {{ item.first_name }}
                    </AppDataTableData>

                    <AppDataTableData>
                        {{ item.middle_name }}
                    </AppDataTableData>

                    <AppDataTableData>
                        {{ item.last_name }}
                    </AppDataTableData>

                    <AppDataTableData>
                        {{ item.suffix_name }}
                    </AppDataTableData>

                    <AppDataTableData>
                        <!-- Edit author -->
                        <AppTooltip text="Edit Author" class="mr-2">
                            <AppButton
                                class="btn btn-icon btn-primary"
                                @click="
                                    $inertia.visit(
                                        route(
                                            'author.edit',
                                            item.id
                                        )
                                    )
                                "
                            >
                                <i class="ri-edit-line"></i>
                            </AppButton>
                        </AppTooltip>

                        <!-- Delete author -->
                        <AppTooltip text="Delete Author">
                            <AppButton
                                class="btn btn-icon btn-destructive"
                                @click="
                                    confirmDelete(
                                        route(
                                            'author.destroy',
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
        v-if="authors.data.length"
        :links="authors.links"
        :from="authors.from || 0"
        :to="authors.to || 0"
        :total="authors.total || 0"
        class="mt-4 justify-center"
    ></AppPaginator>

    <AppAlert v-if="!authors.data.length" class="mt-4">
        No authors found.
    </AppAlert>

    <AppConfirmDialog ref="confirmDialogRef"></AppConfirmDialog>
</template>

<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import useTitle from '@/Composables/useTitle'
import useAuthCan from '@/Composables/useAuthCan'

const { title } = useTitle('Author')
const { can } = useAuthCan()

const props = defineProps({
  authors: {
    type: Object,
    default: () => {}
  }
})

const breadCrumb = [
  { label: 'Home', href: route('dashboard.index') },
  { label: 'Authors', last: true }
]

const headers = ['ID', 'NAME', 'MIDDLE NAME', 'LAST NAME', 'SUFFIX', 'Actions']

const confirmDialogRef = ref(null)
const confirmDelete = (deleteRoute) => {
    confirmDialogRef.value.openModal(deleteRoute)
}
</script>
