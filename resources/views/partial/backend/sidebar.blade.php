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
            <a class="nav-link collapsed"
               href="#" data-toggle="collapse" data-target="#collapse_posts"
               aria-expanded="true"
               aria-controls="collapse_posts">
                <i class="fa fa-home"></i>
                <span>Posts</span>
            </a>
            <div class="collapse" id="collapse_posts" aria-labelledby="heading_posts" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item active"
                       href="{{ route('admin.posts.index') }}">All post</a>
                    <a class="collapse-item"
                       href="{{ route('admin.posts.create') }}">New post</a>
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
