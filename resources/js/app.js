import './bootstrap';
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
<<<<<<< HEAD
//import router from './router';
=======
>>>>>>> 7d8f1d01f0841ff0867b607e1884af5d55f85249
createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
      },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
<<<<<<< HEAD
      //.use(router)
=======
>>>>>>> 7d8f1d01f0841ff0867b607e1884af5d55f85249
      .mount(el)
  },
})
