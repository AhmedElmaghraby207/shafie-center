<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            <li class="nav-item">
                <a href="{{url('/')}}">
                    <i class="la la-home"></i>
                    <span class="menu-title">@lang('dashboard.title')</span>
                </a>
            </li>
            @if(session()->get('user_admin')->can('admin-list'))
                <li class="nav-item">
                    <a href="{{url('/dashboard/admin/index')}}">
                        <i class="la la-user-secret"></i>
                        <span class="menu-title">@lang('admin.title_list')</span>
                    </a>
                </li>
            @endif
            @if(session()->get('user_admin')->can('announcement-create'))
                <li class="nav-item">
                    <a href="{{url('/dashboard/announcement/create')}}">
                        <i class="la la-bullhorn"></i>
                        <span class="menu-title">@lang('announcement.attribute_name')</span>
                    </a>
                </li>
            @endif
            @if(session()->get('user_admin')->can('role-list'))
                <li class="nav-item">
                    <a href="{{url('/dashboard/role/index')}}">
                        <i class="la la-unlock"></i>
                        <span class="menu-title">@lang('role.title_list')</span>
                    </a>
                </li>
            @endif
            @if(session()->get('user_admin')->can('setting-list'))
                <li class="nav-item">
                    <a href="{{url('/dashboard/setting/index')}}">
                        <i class="la la-cog"></i>
                        <span class="menu-title">@lang('setting.title_list')</span>
                    </a>
                </li>
            @endif

            {{--            <li class=" navigation-header">--}}
            {{--                <span data-i18n="nav.category.layouts">Layouts</span>--}}
            {{--                <i class="la la-ellipsis-h ft-minus" data-toggle="tooltip" data-placement="right"--}}
            {{--                   data-original-title="Layouts">--}}
            {{--                </i>--}}
            {{--            </li>--}}
            {{--            <li class=" nav-item">--}}
            {{--                <a href="#"><i class="la la-columns"></i>--}}
            {{--                    <span class="menu-title" data-i18n="nav.page_layouts.main">Page layouts</span>--}}
            {{--                </a>--}}
            {{--                <ul class="menu-content">--}}
            {{--                    <li>--}}
            {{--                        <a class="menu-item" href="layout-1-column.html" data-i18n="nav.page_layouts.1_column">--}}
            {{--                            1 column--}}
            {{--                        </a>--}}
            {{--                    </li>--}}
            {{--                    <li>--}}
            {{--                        <a class="menu-item" href="layout-2-columns.html" data-i18n="nav.page_layouts.2_columns">--}}
            {{--                            2 columns--}}
            {{--                        </a>--}}
            {{--                    </li>--}}
            {{--                </ul>--}}
            {{--            </li>--}}

        </ul>
    </div>
</div>
