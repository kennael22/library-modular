<?php

return [
    App\Providers\AppServiceProvider::class,

    Modules\Support\SupportServiceProvider::class,
    Modules\AdminAuth\AdminAuthServiceProvider::class,
    Modules\User\UserServiceProvider::class,
    Modules\Dashboard\DashboardServiceProvider::class,
    Modules\Acl\AclServiceProvider::class,
    Modules\Book\BookServiceProvider::class,
    Modules\Member\MemberServiceProvider::class,
    Modules\BorrowBook\BorrowBookServiceProvider::class,
    Modules\BookCopy\BookCopyServiceProvider::class,
    Modules\Author\AuthorServiceProvider::class,
    Modules\MemberType\MemberTypeServiceProvider::class,
];
