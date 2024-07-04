<style>
    .main-sidebar {
        background: linear-gradient(to bottom, #0047AA, #2270c9);
    }
</style>
<aside class="main-sidebar sidebar-primary elevation-4">
    <!-- Brand Logo -->
    <span class="text-center brand-link ">
        <span class="text-white brand-text font-weight-bold">SSE POS</span>
    </span>


    <!-- Sidebar -->
    <div class="sidebar ">


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column " data-widget="treeview" role="menu" data-accordion="false">
                @php
                    $userPermissions = auth()->user()->permission ? json_decode(auth()->user()->permission) : [];
                @endphp
                @if (in_array('Item', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="text-white nav-icon fas fa-table"></i>
                            <p class="pl-3 text-white">
                                Items
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('items') }}" class="nav-link">
                                    <i class="text-white far fa-circle nav-icon"></i>
                                    <p class="text-white">Items</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('items_register') }}" class="nav-link">
                                    <i class="text-white far fa-circle nav-icon "></i>
                                    <p class="text-white">Item Register</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (in_array('Customer', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/customer') }}" class="nav-link">
                            <i class="text-white fa-solid fa-user-plus nav-icon"></i>
                            <p class="pl-3 text-white">
                                Customer </p>
                        </a>

                    </li>
                @endif

                @if (in_array('POS', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/pos') }}" class="nav-link">
                            <i class="text-white fa-solid fa-cart-plus nav-icon"></i>
                            <p class="pl-3 text-white">
                                POS
                            </p><i class="text-white right fas fa-angle-left"></i>
                        </a>


                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('pos') }}" class="nav-link">
                                    <i class="text-white far fa-circle nav-icon"></i>
                                    <p class="text-white">POS Management</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('pos_register') }}" class="nav-link">
                                    <i class="text-white far fa-circle nav-icon"></i>
                                    <p class="text-white">Issue POS</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif

                @if (in_array('Invoice', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/invoice') }}" class="nav-link">
                            <i class="text-white nav-icon fas fa-copy"></i>
                            <p class="pl-3 text-white">
                                Invoice
                            </p><i class="text-white right fas fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('invoice') }}" class="nav-link">
                                    <i class="text-white far fa-circle nav-icon"></i>
                                    <p class="text-white">Invoice Management</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('invoice_reg') }}" class="nav-link">
                                    <i class="text-white far fa-circle nav-icon"></i>
                                    <p class="text-white">Issue Invoice</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif
                @if (in_array('Quotation', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/quotation') }}" class="nav-link">

                            <i class="text-white fa-solid fa-file-invoice-dollar nav-icon"></i>
                            <p class="pl-3 text-white">
                                Quotation
                            </p><i class="text-white right fas fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('quotation') }}" class="nav-link">
                                    <i class="text-white far fa-circle nav-icon"></i>
                                    <p class="text-white">Quotation Manage</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('quotation_register') }}" class="nav-link">
                                    <i class="text-white far fa-circle nav-icon"></i>
                                    <p class="text-white">Issue Quotation</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (in_array('Purchase Order', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="" class="nav-link">
                            <i class="text-white fa-solid fa-receipt nav-icon"></i>
                            <p class="pl-3 text-white">
                                Purchase Order
                            </p><i class="text-white right fas fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('purchase_order_manage') }}" class="nav-link">
                                    <i class="text-white far fa-circle nav-icon"></i>
                                    <p class="text-white"> Purchase Order Manage</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('purchase_order_register') }}" class="nav-link">
                                    <i class="text-white far fa-circle nav-icon"></i>
                                    <p class="text-white">Issue Purchase Order</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif




                @if (in_array('Transfer Item', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/warehouse') }}" class="nav-link">
                            <i class="fa-solid fa-arrow-right-arrow-left text-white nav-icon"></i>
                            <p class="pl-3 text-white">
                                Transfer Item
                            </p><i class="text-white right fas fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ url('transfer_item') }}" class="nav-link">
                                    <i class="text-white far fa-circle nav-icon"></i>
                                    <p class="text-white">Transfer Item</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('show_transfer_history') }}" class="nav-link">
                                    <i class="text-white far fa-circle nav-icon"></i>
                                    <p class="text-white">Transfer History</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (in_array('Expenses', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('expense') }}" class="nav-link">
                            <i class="text-white fa-solid fa-money-check-dollar nav-icon"></i>
                            <p class="pl-3 text-white">
                                Expenses </p><i class="text-white right fas fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/expense') }}" class="nav-link">
                                    <i class="text-white far fa-circle nav-icon"></i>
                                    <p class="text-white">Expense Register</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('expense_category') }}" class="nav-link">
                                    <i class="text-white far fa-circle nav-icon"></i>
                                    <p class="text-white">Expense Category</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (in_array('Location', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/warehouse') }}" class="nav-link">
                            <i class="text-white fa-solid fa-house nav-icon "></i>
                            <p class="pl-3 text-white">
                                Location </p>
                        </a>
                    </li>
                @endif
                @if (in_array('Unit', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('unit') }}" class="nav-link">
                            <i class="text-white fa-solid fa-brands fa-ubuntu nav-icon"></i>
                            <p class="pl-3 text-white">
                                Unit </p>
                        </a>
                    </li>
                @endif

                @if (in_array('Supplier', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/supplier') }}" class="nav-link">
                            <i class="text-white fa-solid fa-boxes-packing nav-icon"></i>
                            <p class="pl-3 text-white">
                                Supplier </p>
                        </a>
                    </li>
                @endif

                @if (in_array('Report', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/report') }}" class="nav-link">
                            <i class="text-white fa-solid fa-list-ul nav-icon"></i>
                            <p class="pl-3 text-white">
                                Report
                            </p>
                        </a>
                    </li>
                @endif

                @if (in_array('Net Profit', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/profit') }}" class="nav-link">
                            <i class="text-white fa-solid fa-book nav-icon"></i>
                            <p class="pl-3 text-white">
                                Net Profit
                            </p>
                        </a>
                    </li>
                @endif

                @if (in_array('User', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a class="nav-link">
                            <i class="text-white fa-solid fa-users nav-icon"></i>
                            <p class="pl-3 text-white">
                                Users</p><i class="text-white right fas fa-angle-left"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/user') }}" class="nav-link">
                                    <i class="text-white far fa-circle nav-icon"></i>
                                    <p class="text-white">User</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/user_type') }}" class="nav-link">
                                    <i class="text-white far fa-circle nav-icon"></i>
                                    <p class="text-white">User Type</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (in_array('Configuration', $userPermissions) || auth()->user()->is_admin == '1')
                    <li class="nav-item">
                        <a href="{{ url('/config_manage') }}" class="nav-link">
                            <i class="fa-solid fa-gear nav-icon text-white"></i>
                            <p class="pl-3 text-white">
                                Configuration
                            </p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>


    </div>
</aside>
