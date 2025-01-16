<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/admin" class="brand-link">
        <img src="#" alt="" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">MySys</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        @if (Auth::check() && Auth::user()->role_id == 1)
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <li class="nav-item menu-open">

                        @foreach ($menus as $menu)
                    <li class="nav-item">
                        <a href="{{ $menu->url }}" class="nav-link">
                            <i class="{{ $menu->icon }}"></i>
                            <p>
                                {{ $menu->name }}
                                <i class="{{ $menu->left }}"></i>
                            </p>

                        </a>
                        @if ($menu->children->count())
                            <ul class="nav nav-treeview">
                                @foreach ($menu->children as $child)
                                    <li class="nav-item">
                                        <a href="{{ $child->url }}" class="nav-link">
                                            <i class="{{ $child->icon }}"></i> {{ $child->name }}
                                        </a>

                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                    </li>
        @endforeach
        <li class="nav-item">
            <form action="/logout" method="post">
                @csrf
                <button type="submit" class="nav-link btn-danger text-white">
                    Keluar
                </button>
            </form>
        </li>
    @elseif(Auth::user()->role_id == 2)
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Dashboard
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="../../index.html" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Dashboard v1</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../../index2.html" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Dashboard v2</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../../index3.html" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Dashboard v3</p>
                    </a>
                </li>
            </ul>
        </li>
        @endif
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
