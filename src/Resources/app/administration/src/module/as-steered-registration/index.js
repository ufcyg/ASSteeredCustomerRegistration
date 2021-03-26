import './page/as-steered-registration-overview';
import './page/as-steered-registration-invite';

import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

Shopware.Module.register('as-steered-registration', {
    type: 'plugin',
    name: 'steeredRegistration',
    title: 'as-steered-customer-registration.general.mainMenuItemGeneral',
    description: 'as-steered-customer-registration.general.descriptionTextModule',
    color: '#758CA3',
    icon: 'default-communication-envelope',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        overview: {
            component: 'as-steered-registration-overview',
            path: 'overview'
        },
        invite: {
            component: 'as-steered-registration-invite',
            path: 'invite',
            meta: {
                parentPath: 'as.steered.registration.overview'
            }
        },
    },

    navigation: [{
        label: 'as-steered-customer-registration.general.mainMenuItemGeneral',
        color: '#758CA3',
        path: 'as.steered.registration.overview',
        icon: 'default-communication-envelope',
        position: 10,
        parent: 'sw-customer'
    }],
});