<div id="{{ id }}" class="nav">
	<ul>
		{{ by_uri }}
		{{ if !is:redirect }}
		<li class="{{ is:class }}"><a href="{{ full_uri }}">{{ title }}</a></li>
		{{ endif }}
		{{ /by_uri }}
	</ul>
</div>