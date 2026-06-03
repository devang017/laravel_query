// bootstrap-icons
import "bootstrap-icons/font/bootstrap-icons.css";

// bootstrap
// import 'bootstrap/dist/js/bootstrap.bundle.js';
// import 'bootstrap/dist/css/bootstrap.css';

// fonts
import "@fontsource/source-sans-3/index.css";

// select 2
import select2 from 'select2/dist/js/select2.full.min.js'
import 'select2/dist/css/select2.min.css'
import 'select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css'

select2();
$.fn.select2.defaults.set('theme', 'bootstrap-5');
// $.fn.select2.defaults.set('width', '100%');

// sweetalert
import Swal from 'sweetalert2';
window.Swal = Swal;

// tagify
import Tagify from '@yaireo/tagify'
import '@yaireo/tagify/dist/tagify.css'

window.Tagify = Tagify

// overlayscrollbars
import { OverlayScrollbars } from "overlayscrollbars";
import "overlayscrollbars/styles/overlayscrollbars.css";

import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';

window.flatpickr = flatpickr;

window.addEventListener("load", () => {
    const elements = document.querySelectorAll(".os-scrollbar");

    if (elements.length > 0) {
        OverlayScrollbars(elements, {
            scrollbars: {
                autoHide: "scroll",
                theme: "os-theme-dark",
            },
        });
    }
});

// Create reusable Toast instance
window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
});

// import.meta.glob([
//     '../assets/img/**/*.{png,jpg,jpeg,svg,gif}'
// ], { eager: true });




