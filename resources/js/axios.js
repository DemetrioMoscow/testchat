import {axios} from '@bundled-es-modules/axios';

export default axios.create({
    timeout: 30000,
    headers: {
        'X-Requested-With': 'XMLHttpRequest'
    }
});
