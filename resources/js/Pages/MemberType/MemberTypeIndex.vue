<template>
    <Head :title="title"></Head>
    <AppSectionHeader :title="title" :bread-crumb="breadCrumb">
        <template #right>
            <AppButton
                class="btn btn-primary"
                @click="$inertia.visit(route('memberType.create'))"
            >
                <i class="ri-add-fill mr-1"></i>
                Create MemberType
            </AppButton>
        </template>
    </AppSectionHeader>

    <AppDataSearch
        v-if="memberTypes.data.length || route().params.searchTerm"
        :url="route('memberType.index')"
        fields-to-search="id"
    ></AppDataSearch>

    <AppDataTable v-if="memberTypes.data.length" :headers="headers">
        <template #TableBody>
            <tbody>
                <AppDataTableRow
                    v-for="(item, index) in memberTypes.data"
                    :key="item.id"
                >
                    <AppDataTableData>
                        {{ item.name }}
                    </AppDataTableData>

                    <AppDataTableData>
                        <!-- Edit memberType -->
                        <AppTooltip text="Edit MemberType" class="mr-2">
                            <AppButton
                                class="btn btn-icon btn-primary"
                                @click="
                                    $inertia.visit(
                                        route(
                                            'memberType.edit',
                                            item.id
                                        )
                                    )
                                "
                            >
                                <i class="ri-edit-line"></i>
                            </AppButton>
                        </AppTooltip>

                        <!-- Delete memberType -->
                        <AppTooltip text="Delete MemberType">
                            <AppButton
                                class="btn btn-icon btn-destructive"
                                @click="
                                    confirmDelete(
                                        route(
                                            'memberType.destroy',
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
        v-if="memberTypes.data.length"
        :links="memberTypes.links"
        :from="memberTypes.from || 0"
        :to="memberTypes.to || 0"
        :total="memberTypes.total || 0"
        class="mt-4 justify-center"
    ></AppPaginator>

    <AppAlert v-if="!memberTypes.data.length" class="mt-4">
        No memberTypes found.
    </AppAlert>

    <AppConfirmDialog ref="confirmDialogRef"></AppConfirmDialog>
</template>

<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import useTitle from '@/Composables/useTitle'
import useAuthCan from '@/Composables/useAuthCan'

const { title } = useTitle('MemberType')
const { can } = useAuthCan()

const props = defineProps({
  memberTypes: {
    type: Object,
    default: () => {}
  }
})

const breadCrumb = [
  { label: 'Home', href: route('dashboard.index') },
  { label: 'MemberTypes', last: true }
]

const headers = ['Name', 'Actions']

const confirmDialogRef = ref(null)
const confirmDelete = (deleteRoute) => {
    confirmDialogRef.value.openModal(deleteRoute)
}
</script>
