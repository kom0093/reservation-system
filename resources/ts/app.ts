import App from '@/App.vue';
import router from '@/router/router';
import { createApp, watch } from 'vue';
import Toast, { POSITION } from 'vue-toastification';
import {createVfm} from 'vue-final-modal';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import "vue-toastification/dist/index.css";
import '@mdi/font/css/materialdesignicons.css';
import 'vue-final-modal/style.css';

const app = createApp(App);
const vfm = createVfm();

// adds class vfm-modal-opened when modal is opened
watch(vfm.openedModals, (opened) => {
    if (opened.length) {
        document.body.classList.add('vfm-modal-opened');
    } else {
        document.body.classList.remove('vfm-modal-opened');
    }
});

app.component('DatePicker', VueDatePicker);

app.use(router);
app.use(vfm);

app.use(Toast, {
    transition: "Vue-Toastification__bounce",
    maxToasts: 1,
    newestOnTop: true,
    position: POSITION.BOTTOM_LEFT
});

app.mount('#app');
