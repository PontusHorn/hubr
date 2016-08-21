# hubr

Demo app built with Slim Framework. Gets the username and user ID for a given Github URL.

## Prerequisites

Requires Apache with mod_rewrite enabled and PHP 5.6+.
[Composer](https://getcomposer.org/) and [Node.js](https://nodejs.org/en/) (npm) are also required.

## Setting it up

Clone the repository and enter the directory: `git clone https://github.com/PontusHorn/hubr.git && cd hubr`
Install dependencies: `composer install && npm install`
Make sure the `cache` and `logs` files are writable by Apache.
Create an Apache VirtualHost with the `public` folder as its `DocumentRoot`. For example (Apache 2.4+):
```
Listen 3000
<VirtualHost *:3000>
  DocumentRoot "/path/to/hubr/public"
  <Directory "/path/to/hubr/">
      AllowOverride FileInfo
      Require all granted
  </Directory>
</VirtualHost>
```
Open the configured URL, e.g. [http://localhost:3000](http://localhost:3000), in your browser.
