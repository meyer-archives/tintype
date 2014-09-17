# Hey! Listen!
### This project is *super old* and is on Github mainly for entertainment value.

Don’t use Tintype, use [Cactus](http://cactusformac.com). I stopped maintaining Tintype the moment I found out about Cactus. It’s *exactly* what I wanted Tintype to be—Django templates and handy shortcuts that compile to flat HTML.

I built Tintype sometime in early 2010 to scratch an itch (hey there jQuery 1.4.2). I used for a couple of years until I cooked up a nasty little Python script that used Jinja to basically do the exact same thing, but without semicolons.

The (mostly) original README:

---

# About Tintype
**Tintype** is a simple HTML prototyping engine written in PHP. It uses a PHP implementation of the Django template engine called [Twig](http://github.com/fabpot/twig).

## Brief History
Here’s how this all went down. I used Django for [a little project](http://ligonier.org), and instantly fell in nerd-love with the template engine. I wanted to use it on _everything_, but that just isn’t practical. **Tintype** allows you to use the Django template engine and all its conveniences for prototyping, but once you’re done, you can flatten the Django template file out to a normal HTML file. Neat-o!

## Awesome! How do I use it?
PHP5-ish is required (obviously).

1. Dump the contents of this repository in a web-accessible directory.
2. Copy the contents of `/sites/example/` into a new directory in `/sites/`.
3. Put templates (HTML, XML, JSON) in the templates directory, and media (images, CSS, JS) in the media directory. Media is accessible via the *MEDIA_URL* variable (example: `{{ MEDIA_URL }}style.css`).
4. Visit `http://prototype-url/site-folder/`, where *site-folder* refers to the new folder you created in step 2. This page is the project index, and if you have templates in the templates directory, they’ll be displayed there.
5. There is no step five!

## Pro Tips
* How I use **Tintype**: I use [VirtualHostX](http://clickontyler.com/virtualhostx/) for local development (and you should too!) and I use a virtual host called `prototypes` for all my HTML prototyping stuff.
* If you don’t want a file to show up in the project view, prefix the filename with an `_underscore`, and it won’t show. This works nicely if you have several base templates you don’t want visible in the project index, or old versions of templates that you don’t want to delete.
* Click *Recompile Templates* to, well, recompile all your Django templates to flat HTML. The compiled templates will be in `templates-compiled` in the site directory. That way, you can zip and share the templates easily.
* Set variables in `data.yml`. You can create an array and iterate through it using a Django `for` loop in any template.

## NGINX + mod_rewrite
Don’t you fret!! Put this in your `server` block:
```nginx
location / {
	root   /path/to/site/;
	index  index.html index.php;

	if ($request_filename ~ /) {
		rewrite ^/(.*)$ /index.php/$1 last;
	}
}
```

## Other Server Software
Can’t help you there. Google that bidness.

## But my server doesn’t support `mod_rewrite`!!
I’ll get some sort of `/index.php/` hack in there for you poor `mod_rewrite`-less fools.
