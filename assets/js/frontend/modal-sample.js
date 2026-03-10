import { modal } from './modal.js';
const showModal = () => {
    const m= modal({ title: 'Sample modal', body: '<p>Here\'s your sample modal. It has many options. See <a class="text-cyan-400 font-bold" href="https://github.com/humbabba/xrq119?tab=readme-ov-file#xrq119" target="_blank">README on the GitHub repo</a> for details.</p>' });
};

const init = () => {
    const links = document.querySelectorAll('.show-modal-link');
    links.forEach((el) => {
        el.addEventListener('click', () => {
           showModal();
        });
    });
};

document.addEventListener('DOMContentLoaded', function() {
    init();
});