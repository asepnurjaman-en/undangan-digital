<nav class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
	<div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
		<a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
			<i class="bx bx-menu bx-sm"></i>
		</a>
	</div>

	<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
		<!-- Search -->
		<div class="navbar-nav align-items-center">
			<div class="nav-item d-flex align-items-center">
			</div>
		</div>
		<!-- /Search -->

		<ul class="navbar-nav flex-row align-items-center ms-auto">
			<!-- User -->
			<li class="nav-item navbar-dropdown dropdown-user dropdown">
				<a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
					{{ Str::title(Auth::user()->name) }}
				</a>
				<ul class="dropdown-menu dropdown-menu-end">
					<li>
						<a class="dropdown-item {{ (in_array(Route::currentRouteName(), ['setting.account'])) ? 'active' : null }}" href="{{ route('setting.account', 'profile') }}">
							<i class="bx bx-user me-2"></i>
							<span>{{ Str::title('akun saya') }}</span>
						</a>
					</li>
					<li>
						<a class="dropdown-item {{ (in_array(Route::currentRouteName(), ['setting.log_activity'])) ? 'active' : null }}" href="{{ route('setting.log_activity') }}">
							<i class="bx bx-library me-2"></i>
							<span>{{ Str::title('log aktifitas') }}</span>
						</a>
					</li>
					<li>
						<a class="dropdown-item {{ (in_array(Route::currentRouteName(), ['setting.site'])) ? 'active' : null }}" href="{{ route('setting.site', 'site') }}">
							<i class="bx bx-cog me-2"></i>
							<span>{{ Str::title('pengaturan') }}</span>
						</a>
					</li>
					<li>
						<div class="dropdown-divider"></div>
					</li>
					<li>
						<a class="dropdown-item" href="{{ route('logout') }}"
							onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
							<i class="bx bx-log-out me-2"></i>
							<span>{{ Str::title('keluar') }}</span>
						</a>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
							@csrf
						</form>
					</li>
				</ul>
			</li>
			<!--/ User -->
		</ul>
	</div>
</nav>