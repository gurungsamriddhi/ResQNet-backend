import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

window.subscribeOrganization = (orgId) => {
    const channel = echo.private(`organization.${orgId}`);

    channel.listen('.NewAdoptionRequestEvent', (e) => {
        window.dispatchEvent(new CustomEvent('new-adoption-request', { detail: e }));
    });

    channel.listen('.PetSuspendedEvent', (e) => {
        window.dispatchEvent(new CustomEvent('pet-suspended', { detail: e }));
    });

    // Join rescue-updates globally for organization users to receive new unassigned rescue report alerts in real-time
    echo.join('rescue-updates')
        .here(() => {})
        .listen('.NewRescueReportEvent', (e) => {
            window.dispatchEvent(new CustomEvent('new-rescue-report', { detail: e }));
        });
};

window.Echo = echo;
export default echo;
