# About Tintype
## What it is
**Tintype** is a simple HTML prototyping engine written in PHP.

## Brief History
Here&rsquo;s how this all went down. I used Django for [a little project](http://ligonier.org), and instantly fell in nerd-love with the template engine. I wanted to use it on _everything_, but that just isn&rsquo;t practical. **Tintype** allows you to use the Django template engine and all its conveniences for prototyping, but once you&rsquo;re done, you can flatten the Django template file out to a normal HTML file. Neat-o!

## Awesome! How do I use it?
* Dump the contents of this repository in a web-accessible directory.
* Copy the contents of <code>/sites/example/</code> into a new directory in <code>/sites/</code>.
* Add templates in the templates directory, and media the media directory. Media is accessible via the *MEDIA_URL* variable (example: <code>{{ MEDIA_URL }}style.css</code>).
* There is no step four!

PHP5-ish is required (obviously).

## Pro Tips
* How I use **Tintype**: I use [VirtualHostX](http://clickontyler.com/virtualhostx/) for local development (and you should too!) and I use a virtual host called <code>prototypes</code> for all my HTML prototyping stuff.
* If you don&rsquo;t want a file to show up in the project view, prefix the filename with an <code>_underscore</code>, and it won&rsquo;t show. This works well with template inheritance.
* Click *Recompile Templates* to, well, recompile all your Django templates to flat HTML. The compiled templates will be in <code>/sites/SITE_DIR/templates-compiled/</code>. That way, you can zip and share the templates easily.
* Set variables in <code>data.yml</code>. You can create an array an iterate through it using a Django *for* loop (example: <code>{% for var in array_name %}</code>).

## NGINX + mod_rewrite
Don&rsquo;t you fret!! Put this in one of your server blocks:
<pre>
	location / {
		root   /path/to/site/;
		index  index.html index.php;

		if ($request_filename ~ /) {
			rewrite ^/(.*)$ /index.php/$1 last;
		}
	}
</pre>

## Other Server Software
Can&rsquo;t help you there. Google that bidness.

## But my server doesn&rsquo;t support <code>mod_rewrite</code>!!
I&rsquo;ll get some sort of <code>/index.php/</code> hack in there for you poor <code>mod_rewrite</code>-less fools.