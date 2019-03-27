# WP Packages Installer

Paired with the [wp-packages-repo](https://github.com/TomodomoCo/wp-packages-repo), this Composer installer plugin allows you to easily install select premium WordPress plugins.

The Composer repository, which is hosted for free by [Tomodomo](https://tomodomo.co), defines the available WordPress plugins. This library handles the actual downloading from each plugin's website, so our servers will never see your API keys or host any plugin files.

This library was inspired by the [Composer WP Pro Plugins](https://github.com/junaidbhura/composer-wp-pro-plugins) library. It solves the same problem, in a different way:

1. Authentication and license credentials are stored in Composer's native [`auth.json`](https://getcomposer.org/doc/articles/http-basic-authentication.md) format.
2. It pulls plugin-specific information (such as the download URL structure, plugin download workflows, etc.) from the package repository, enabling easy support for new plugins without requiring package updates.

We believe these changes make this a more robust, and ultimately easier-to-implement solution than other alternatives.

## About Tomodomo

Tomodomo is a creative agency for magazine publishers. We use custom design and technology to speed up your editorial workflow, engage your readers, and build sustainable subscription revenue for your business.

Learn more at [tomodomo.co](https://tomodomo.co) or email us: [hello@tomodomo.co](mailto:hello@tomodomo.co)

## License & Conduct

This project is licensed under the terms of the MIT License, included in `LICENSE.md`.

All open source Tomodomo projects follow a strict code of conduct, included in `CODEOFCONDUCT.md`. We ask that all contributors adhere to the standards and guidelines in that document.

Thank you!
