<div id="{{ id }}" class="nav">
	<ul>
		{{ by_uri }}
		{{ if !is_redirect }}
		<li class="{{ is }}"><a href="{{ full_uri }}">{{ title }}</a></li>
		{{ endif }}
		{{ /by_uri }}
	</ul>
</div>