export default {
    // main navigation - side menu
    items: [
        {
            label: 'Dashboard',
            permission: 'Dashboard',
            icon: 'ri-dashboard-line',
            link: route('dashboard.index')
        },
        // book
        {
            label: 'Book',
            permission: 'Book - List',
            icon: 'ri-book-line',
            link: route('book.index')
        },
        // borrow book
        {
            label: 'Borrow Book',
            permission: 'Borrow Book - List',
            icon: 'ri-book-open-line',
            link: route('borrowBook.index')
        },
        // settings
        {
            label: 'Settings',
            permission: 'Settings',
            children: [
                // author
                {
                    label: 'Author',
                    permission: 'Author - List',
                    icon: 'ri-user-line',
                    link: route('author.index')
                },
                // member
                {
                    label: 'Member',
                    permission: 'Member - List',
                    icon: 'ri-user-line',
                    link: route('member.index')
                },
                // member type
                // {
                //     label: 'Member Type',
                //     permission: 'Member Type - List',
                //     icon: 'ri-account-box-line',
                //     link: route('memberType.index')
                // }
            ]
        },
        // acl
        {
            label: 'Access Control List',
            permission: 'Acl',
            children: [
                {
                    label: 'Users',
                    permission: 'Acl: User - List',
                    icon: 'ri-user-line',
                    link: route('user.index')
                },
                {
                    label: 'Permissions',
                    permission: 'Acl: Permission - List',
                    icon: 'ri-shield-keyhole-line',
                    link: route('aclPermission.index')
                },
                {
                    label: 'Roles',
                    permission: 'Acl: Role - List',
                    icon: 'ri-account-box-line',
                    link: route('aclRole.index')
                }
            ]
        }
    ]
}
