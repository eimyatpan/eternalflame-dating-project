import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';

// Import Vuetify
import 'vuetify/styles'; // Vuetify CSS
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

// Import MDI icons (Material Design Icons)
import '@mdi/font/css/materialdesignicons.css';

// Bootstrap CSS (optional)
import 'bootstrap/dist/css/bootstrap.css';
import 'animate.css'; // Animation library if needed

// Create Vuetify instance
const vuetify = createVuetify({
  components,
  directives,
});

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
    return pages[`./Pages/${name}.vue`];
  },
  setup({ el, App, props, plugin }) {
    const vueApp = createApp({ render: () => h(App, props) });

    vueApp
      .use(plugin)     // Inertia plugin
      .use(vuetify)    // Vuetify plugin
      .mount(el);      // Mount app
  },
});
