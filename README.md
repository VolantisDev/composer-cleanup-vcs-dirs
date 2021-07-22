# composer-cleanup-vcs-dirs

If you're stuck needing to commit your composer dependencies,
you might find you need to delete some extra .git directories
after every time you install or update something with Composer.

While not a best practice, sometimes it's simply a requirement.

## How to use the plugin

When required in composer.json, the plugin will automatically
search for any .git directories in installed or updated
dependencies and delete them immediately.

There's nothing else you need to do after requiring this plugin
in your main composer.json file.

Optionally, if you wish to develop on one of your project's dependencies and keep its .git folder, you can tell composer-cleanup-vcs-dirs to exclude that dependency's path from deletion. In your project's composer.json, set the `extras > cleanup-vcs-dirs > exclude` entry with a pattern that matches the dependency installation directories you wish to keep the .git folder for. For example, the following would keep .git folders located in a `custom` folder and inside the `symfony/debug` folder:

```json
{
    "name": "my-project",
    "description": "This is the composer.json file",
    "type": "project",
    "extra": {
        "cleanup-vcs-dirs": {
            "exclude": [
                "symfony/debug",
                "/custom\\/*/"
            ]
        }
    }
}
```

The `extras > cleanup-vcs-dirs > exclude` directory path can be a string or an array of strings. You can use patterns (delimited with `/` sign) or simple strings.

 * `"some/special/dir"`
 * `["some/directory", "another/directory"]`
 * `"/some\\/special\\/dir/"`

   (Note that any backslash characters in a regex string need to be escaped with an extra backslash when used in a JSON file like composer.json. In other words, PHP's `'/custom\/*/'` becomes `"/custom\\/*/"` in JSON.)

After adding an `extras > cleanup-vcs-dirs > exclude` entry, you will need to run `composer reinstall --prefer-source [package-needing-git]` to force composer to re-add the .git folder.

## Running the command directly

You can also run the cleanup-vcs-dirs command directly in Composer.

To do so, simply run:

    composer cleanup-vcs-dirs

Your entire project will be scanned, and all child .git
directories under the project directory will be deleted.
