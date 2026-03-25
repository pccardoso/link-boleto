import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import type { DefineComponent } from 'vue'
import { createApp, h } from 'vue'

import AuthenticatedLayout from './layouts/AuthenticatedLayout.vue'
import GuestLayout from './layouts/GuestLayout.vue'

import '../css/app.css'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

createInertiaApp({

    title: (title) => (title ? `${title} - ${appName}` : appName),

    resolve: async (name) => {

        const page = await resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        )

        const guestPages = ['Login', 'Home']

        if (page.default.layout === undefined) {
    page.default.layout = guestPages.includes(name)
        ? GuestLayout
        : AuthenticatedLayout
}

        return page

    },

    setup({ el, App, props, plugin }) {

        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)

    },

    progress: {
        color: '#4B5563'
    }

})