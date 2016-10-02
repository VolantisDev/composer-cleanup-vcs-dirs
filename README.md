# composer-cleanup-vcs-dirs

If you're stuck needing to commit your composer dependencies,
you might find you need to delete some extra .git directories
after every time you install or update something with Composer.

While not a best practice, sometimes it's simply a requirement.
This plugin, when included in composer.json, will automatically
search for any .git directories in installed or updated 
dependencies and delete them.

There's nothing else you need to do after requiring this plugin
in your main composer.json file.
