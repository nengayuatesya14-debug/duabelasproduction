'use strict';

const toggle = document.querySelector('[data-nav-toggle]');
const nav = document.querySelector('[data-nav]');
if (toggle && nav) {
  toggle.addEventListener('click', () => nav.classList.toggle('open'));
}

document.querySelectorAll('[data-alert-close]').forEach((button) => {
  button.addEventListener('click', () => button.closest('[data-alert]')?.remove());
});

document.querySelectorAll('[data-confirm]').forEach((element) => {
  element.addEventListener('click', (event) => {
    const message = element.dataset.confirm || 'Yakin ingin melanjutkan?';
    if (!window.confirm(message)) event.preventDefault();
  });
});

const fileInputs = document.querySelectorAll('input[type="file"][data-preview]');
fileInputs.forEach((input) => {
  input.addEventListener('change', () => {
    const target = document.querySelector(input.dataset.preview);
    const file = input.files?.[0];
    if (target && file) {
      target.src = URL.createObjectURL(file);
      target.hidden = false;
    }
  });
});

const dateInputs = document.querySelectorAll('input[type="date"][data-min-today]');
const today = new Date().toISOString().split('T')[0];
dateInputs.forEach((input) => input.setAttribute('min', today));
