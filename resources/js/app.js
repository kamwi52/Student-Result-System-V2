import './bootstrap';
import 'flowbite';

import Alpine from 'alpinejs';

// === NEW: Import the ApexCharts library ===
import ApexCharts from 'apexcharts';

window.Alpine = Alpine;

// === NEW: Make ApexCharts globally available for Blade scripts ===
window.ApexCharts = ApexCharts;

Alpine.start();