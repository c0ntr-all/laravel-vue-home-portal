import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
  broadcaster: 'reverb',
  key: 'c3stozeuvxljelzhhakf',
  wsHost: window.location.hostname,
  wsPort: 8080,
  wssPort: 8080,
  forceTLS: false,
  enabledTransports: ['ws'],
  authEndpoint: `http://home-portal.${process.env.mode}/api/broadcasting/auth`,
  auth: {
    headers: {
      Authorization: `Bearer ${localStorage.token}`
    }
  }
});
