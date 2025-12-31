<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">Core</div>
            <a class="nav-link" href="{{ url('view') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>
            <div class="sb-sidenav-menu-heading">Interface</div>
            {{-- only for Admin --}}
            @if (Auth::check() && Auth::user()->role == 'admin')
                <a class="nav-link {{ request()->is('admin/sellers*') ? '' : 'collapsed' }}" href="#"
                    data-bs-toggle="collapse" data-bs-target="#collapseSellers"
                    aria-expanded="{{ request()->is('admin/sellers*') ? 'true' : 'false' }}"
                    aria-controls="collapseSellers">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Seller Management
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                {{-- Dropdown keeps showing if the URL starts with admin/sellers --}}
                <div class="collapse {{ request()->is('admin/sellers*') ? 'show' : '' }}" id="collapseSellers"
                    aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        {{-- Link to Create Seller --}}
                        <a class="nav-link {{ request()->is('admin.sellers.create') ? 'active' : '' }}"
                            href="{{ route('admin.sellers.create') }}">Create New Seller</a>

                        {{-- Link to View Sellers --}}
                        <a class="nav-link {{ request()->is('admin/sellers') ? 'active' : '' }}"
                            href="{{ route('admin.sellers') }}">View All Sellers</a>
                        <a class="nav-link {{ request()->is('sellers-products-create') ? 'active' : '' }}"
                            href="{{ route('sellers-products-create') }}">Create New Product</a>
                        <a class="nav-link {{ request()->is('products.index') ? 'active' : '' }}"
                            href="{{ route('products.index') }}">View Product List</a>
                    </nav>
                </div>
            @endif


            @if (Auth::check() && Auth::user()->role == 'seller')
                <a class="nav-link {{ request()->is('sellers-products-create') ? 'active' : '' }}"
                    href="{{ route('sellers-products-create') }}">Create New Product</a>
                <a class="nav-link {{ request()->is('products.index') ? 'active' : '' }}"
                    href="{{ route('products.index') }}">View Product List</a>
            @endif
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Logged in as:</div>
        Start Bootstrap
    </div>
</nav>
<style>
    .sb-sidenav .nav-link.active {
        color: #ffffff !important;
        font-weight: 500;

    }

    /* On hover (active state hover) */
    .sb-sidenav .nav-link.active:hover {
        background-color: rgba(13, 110, 253, 0.25);
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
