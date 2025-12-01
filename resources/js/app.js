import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import Swal from 'sweetalert2';
window.Swal = Swal;

// This is where your custom script logic belongs
document.addEventListener('livewire:initialized', () => {
        
    Livewire.on('swal:alert', (event) => {
        let data = event[0]; // Get the first item in the event array
        
        Swal.fire({
            icon: data.type,
            title: data.title,
            text: data.text,
            timer: data.timer || 5000,
            timerProgressBar: true,
            showConfirmButton: false
        }).then((result) => {
            // This 'then' block runs after the alert closes
            // Check if a redirectUrl was passed in the event
            if (data.redirectUrl) {
                window.location.href = data.redirectUrl;
            }
        });
    });
    
});

import '../css/app.css';