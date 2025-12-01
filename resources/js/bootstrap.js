// Lodash
import _ from 'lodash';
window._ = _;

// jQuery & Bootstrap JS
try {
    import('popper.js').then(module => {
        window.Popper = module.default;
    });

    import('jquery').then(($) => {
        window.$ = window.jQuery = $;

        // Bootstrap benötigt jQuery, also erst importen, wenn jQuery verfügbar ist
        import('bootstrap');
    });

} catch (e) {
    console.error('Bootstrap/jQuery konnte nicht geladen werden', e);
}

// Axios
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Optional: Laravel Echo / Pusher
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';
// window.Pusher = Pusher;
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
