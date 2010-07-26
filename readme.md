# About Tintype
## What it is
**Tintype** is a simple HTML prototyping engine written in PHP.

## Brief History
Here&rsquo;s how this all went down. I used Django for [a little project](http://ligonier.org), and instantly fell in nerd-love with the template engine. I wanted to use it on _everything_, but that just isn&rsquo;t practical. **Tintype** allows you to use the Django template engine and all its conveniences for prototyping, but flatten the Django template file out to an HTML file when you&rsquo;re done. Neat-o!


## Awesome! How do I use it?
Put

PHP5-ish is required (obviously).

## NGINX + mod_rewrite
Don&rsquo;t you fret!! Put this in one of your server blocks:
<pre>
	location / {
		root   /path/to/site/;
		index  index.html index.htm index.php;

		if (-f $request_filename) {
			expires	 30d;
			break;
		}

		if ($request_filename ~ /) {
			rewrite ^/(.*)$ /index.php/$1 last;
		}
	}
</pre>
You can also find this _very same code_ in <code>tintype.ngx</code> plus a really generic server block in the root of the project directory.

## Other Server Software
Can&rsquo;t help you there. Use nginx. It&rsquo;s awesome, believe me.
I&rsquo;ll get some sort of <code>/index.php/</code> hack in there for you poor <code>mod_rewrite</code>-less fools.