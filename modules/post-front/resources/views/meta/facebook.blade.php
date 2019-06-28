<meta property="og:url" content="{{ request()->url() }}" />
<meta property="og:type" content="article" />
<meta property="og:locale" content="{{ app()->getLocale() }}" />
<meta property="og:title" content="@yield('title', 'Welcome') - {{ PlOption::get('blog_title', app()->getLocale()) }}" />
<meta property="og:description" content="@yield('description', PlOption::get('blog_description'))" />
<meta property="og:image" content="@yield('og_image', PlOption::get('blog_logo'))" />

