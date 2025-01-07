import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

var TeamChanel = window.Echo.private(`team.${team_id}`).error(console.log)
console.log(`team.${team_id}`)
TeamChanel.listen('.notify-group', (e) => {
    if(Notification.permission == "granted"){
        var notification = new Notification(e.title,{
            body: e.message,
            icon: '/images/icono.ico',

        })
        notification.onclick = function (){window.open(e.url)}
    }
})

var UserChanel = window.Echo.private(`user.${user_id}`).error(console.log)
console.log(`user.${user_id}`)
UserChanel.listen('.notify-user', (e) => {
    if(Notification.permission == "granted"){
        var notification = new Notification(e.title,{
            body: e.message,
            icon: '/images/icono.ico',

        })
        notification.onclick = function (){window.open(e.url)}
    }
})