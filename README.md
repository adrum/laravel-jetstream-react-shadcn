# Laravel Jetstream React (shadcn/ui) Starter Kit

## Introduction

A React starter kit based on Laravel Jetstream which provides a robust, modern starting point for building Laravel applications with a React frontend using [Inertia](https://inertiajs.com).

Inertia allows you to build modern, single-page React applications using classic server-side routing and controllers. This lets you enjoy the frontend power of React combined with the incredible backend productivity of Laravel and lightning-fast Vite compilation.

This React starter kit utilizes React 19, TypeScript, Tailwind, and [shadcn/ui](https://ui.shadcn.com).

## Getting Started

```bash
laravel new --using=adrum/laravel-jetstream-react-shadcn
```

## Documentation

Documentation for Official Laravel Jetstream can be found on the [Laravel website](https://jetstream.laravel.com/). This project is not an official Laravel Jestream starter kit, but most of the documentation for Jetstream should apply to this project as well.

Note: The installer has already been run for you, so you can skip the `jetstream:install` command. Instead, you will be asked which authentication and Jetstream features you want to keep, and the code, routes, migrations, and tests for anything you leave out are removed from your new project.

You can re-run that prompt at any time before the first `composer update` with:

```bash
php artisan install:features
```

## Other Starter Kits

Check out my other Laravel starter kits:

- [Laravel React (Mantine) Starter Kit](https://github.com/adrum/laravel-react-mantine-starter-kit): The official Laravel React starter kit, with [Mantine](https://mantine.dev) in place of shadcn/ui.
- [Laravel Jetstream + React (TypeScript) Starter Kit](https://github.com/adrum/laravel-jetstream-react-typescript): Jetstream and Inertia with [HeadlessUI](https://headlessui.com), the closest match to what Jetstream generates.
- [Laravel Jetstream + React (Mantine) Starter Kit](https://github.com/adrum/laravel-jetstream-react-mantine): Jetstream and Inertia with [Mantine](https://mantine.dev).

## License

The Laravel Jetstream React (shadcn/ui) Starter Kit starter kit is open-sourced software licensed under the MIT license.
