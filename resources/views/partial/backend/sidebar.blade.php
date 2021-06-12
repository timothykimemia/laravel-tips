<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('frontend.index') }}">
        <div class="sidebar-brand-text mx-3">{{ config('app.name') }}</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    @admin

    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('admin.index') }}">
            <i class="fa fa-home"></i>
            <span>Main</span>
        </a>
        <hr class="sidebar-divider my-0">
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed"
           href="#" data-toggle="collapse" data-target="#collapse_posts"
           aria-expanded="true"
           aria-controls="collapse_posts">
            <i class="fa fa-paper-plane"></i>
            <span>Posts</span>
        </a>
        <div class="collapse" id="collapse_posts" aria-labelledby="heading_posts" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item active"
                   href="{{ route('admin.posts.index') }}">All posts</a>
                <a class="collapse-item"
                   href="{{ route('admin.posts.create') }}">New post</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed"
           href="#" data-toggle="collapse" data-target="#collapse_pages"
           aria-expanded="true"
           aria-controls="collapse_pages">
            <i class="fa fa-pager"></i>
            <span>Pages</span>
        </a>
        <div class="collapse" id="collapse_pages" aria-labelledby="heading_pages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item active"
                   href="{{ route('admin.pages.index') }}">All pages</a>
                <a class="collapse-item"
                   href="{{ route('admin.pages.create') }}">New page</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed"
           href="#" data-toggle="collapse" data-target="#collapse_contacts"
           aria-expanded="true"
           aria-controls="collapse_contacts">
            <i class="fa fa-pager"></i>
            <span>Contacts</span>
        </a>
        <div class="collapse" id="collapse_contacts" aria-labelledby="heading_contacts" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item active"
                   href="{{ route('admin.contacts.index') }}">contact message</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed"
           href="#" data-toggle="collapse" data-target="#collapse_users"
           aria-expanded="true"
           aria-controls="collapse_users">
            <i class="fa fa-pager"></i>
            <span>Users</span>
        </a>
        <div class="collapse" id="collapse_users" aria-labelledby="heading_users" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item active"
                   href="{{ route('admin.users.index') }}">all users</a>
            </div>
        </div>
    </li>


@endadmin

<!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
