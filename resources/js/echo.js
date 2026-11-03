import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const reverbKey = import.meta.env.VITE_REVERB_APP_KEY;

if (reverbKey) {
    try {
        const wsHost = (typeof window !== 'undefined' && window.location.hostname)
            ? window.location.hostname
            : (import.meta.env.VITE_REVERB_HOST ?? '127.0.0.1');

        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: reverbKey,
            wsHost: wsHost,
            wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
            wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
            forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
            enabledTransports: ['ws', 'wss'],
        });

        // Notify any page scripts that Echo instance is initialized and available
        if (typeof window !== 'undefined') {
            window.dispatchEvent(new CustomEvent('echo:ready', { detail: { echo: window.Echo } }));
        }
    } catch (error) {
        console.warn('Laravel Echo / Reverb initialization error:', error);
        window.Echo = null;
    }
} else {
    window.Echo = null;
}

