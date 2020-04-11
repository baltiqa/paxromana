<div class="nav-left-sidebar sidebar-dark">
    <div class="menu-list">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="navbar-collapse" id="navbarNav">
                <ul class="navbar-nav flex-column">
                <li class="nav-divider">
                        Dashboard
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link @if(request()->segment(2) == null) active @endif" href="/panel">Home</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link @if(request()->segment(2) == 'staffchat') active @endif" href="/panel/staffchat">Staff Chat(0)</a>
                    </li>
                    @if (auth()->user()->is_admin == 1)
                    <li class="nav-item ">
                        <a class="nav-link @if(request()->segment(2) == 'admindashboard') active @endif" href="/panel/admindashboard">Admin Overview</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link @if(request()->segment(2) == 'dashboard') active @endif" href="/panel/dashboard">Moderator Overview</a>
                    </li>
                    <li class="nav-divider">
                        Moderator
                    </li>
                    @if(auth()->user()->permiss()[0]['dispute'] != null) 
                    @if(auth()->user()->permiss()[0]['dispute'] == "yes") 
                    <li class="nav-item">
                        <a class="nav-link @if(request()->segment(2) == 'disputes') active @endif" href="/panel/disputes">Disputes({{$disputes_number}})</a>
                    </li> 
                    @endif 
                    @endif

                    @if(auth()->user()->permiss()[0]['report'] != null) 
                    @if(auth()->user()->permiss()[0]['report'] == "yes") 
                    <li class="nav-item">
                        <a class="nav-link @if(request()->segment(2) == 'moderatelistings') active @endif" href="/panel/moderatelistings">Reports({{$reports_number}})</a>
                    </li>
                    @endif 
                    @endif

                    <li class="nav-item">
                        <a class="nav-link @if(request()->segment(2) == 'tickets') active @endif" href="/panel/tickets">Tickets({{$tickets_number}})</a>
                    </li>
                    <li class="nav-divider">
                        Users
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link @if(request()->segment(2) == 'users') active @endif" href="/panel/users">Users</a>
                    </li>


                    @if (auth()->user()->is_headmod == 1 || auth()->user()->is_admin == 1)
                    <li class="nav-item">
                        <a class="nav-link @if(request()->segment(2) == 'usermarkets') active @endif" href="/panel/usermarkets">Import Feedbacks</a>
                    </li>

                    <li class="nav-divider">
                        Market FAQ
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->segment(2) == 'wiki') active @endif" href="/panel/wiki">Wiki</a>
                    </li>
                    @endif

                    @if (auth()->user()->is_admin == 1)
                    <li class="nav-divider">
                        Administrator
                    </li>
                   

                    <li class="nav-item">
                        <a class="nav-link @if(request()->segment(2) == 'listings') active @endif"
                            href="/panel/listings">Listings</a>
                    </li>
                    @if(auth()->user()->username == "Augustus")
                    <li class="nav-item">
                        <a class="nav-link @if(request()->segment(2) == 'reviews') active @endif" href="/panel/reviews">Reviews</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link @if(request()->segment(2) == 'settings') active @endif" href="/panel/settings">Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->segment(2) == 'categories') active @endif" href="/panel/categories">Categories</a>
                        <div class=" @if(request()->segment(2) == 'categories')  @else collapse @endif  submenu" style="">
                            <ul class="nav flex-column">
                                <a class="nav-link" href="/panel/categories">List categories</a>

                                <li class="nav-item">
                                    <a class="nav-link" href="/panel/categories/create">Create
                                        categories</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link @if(request()->segment(2) == 'buyerlogs' || request()->segment(2) == 'regularescrow' || request()->segment(2) == 'multisigescrow') active @endif"
                            href="/panel/buyerlogs">Buy Logs</a>
                            <div class=" @if(request()->segment(2) == 'buyerlogs' || request()->segment(2) == 'regularescrow' || request()->segment(2) == 'multisigescrow')  @else collapse @endif  submenu" style="">
                            <ul class="nav flex-column">
                                <a class="nav-link" href="/panel/regularescrow">List Regular Escrow</a>

                                <li class="nav-item">
                                    <a class="nav-link" href="/panel/multisigescrow">List Multisig Escrow</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link @if(request()->segment(2) == 'orders') active @endif"
                            href="/panel/orders">Orders</a>
                    </li>

                  
                    @endif
                </ul>
            </div>
    </div>
    </nav>
</div>
</div>