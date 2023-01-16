<aside class="navbar navbar-vertical navbar-expand-lg navbar-{{config('tabler.sidebar-color')}}">
	<div class="container-fluid">
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
			<span class="navbar-toggler-icon"></span>
		</button>
		<h1 class="navbar-brand navbar-brand-autodark">Admin</h1>
		<div class="collapse navbar-collapse" id="navbar-menu">
			<ul class="navbar-nav pt-lg-3">
				<x-tabler::sidebar-link
						title="Home"
						route="admin.home"
						icon='<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="5 12 3 12 12 3 21 12 19 12" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>'/>
				@php
					$menuBladesPath = glob(app_path('Modules/*/views/admin/menu*.blade.php'));
					$modulesOrder = [];
					usort($menuBladesPath, function($a, $b) use ($modulesOrder){
						// sort using the numeric index of the second array
						$sa= explode('/',$a);
						$sb = explode('/',$b);
						$valA = array_search($sa[count($sb)-3], $modulesOrder);
						$valB = array_search($sb[count($sb)-3], $modulesOrder);
						// move items that don't match to end
						if ($valA === false)
							return -1;
						if ($valB === false)
							return 0;

						if ($valA > $valB)
							return 1;
						if ($valA < $valB)
							return -1;
						return 0;
					});
				@endphp
				@foreach($menuBladesPath as $menu)
					@include(Str::after(str_replace('.blade.php','',$menu),'Modules/'))
				@endforeach
			</ul>
		</div>
	</div>
</aside>
