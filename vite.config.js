import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: 
            [   'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/login.css', 
                'resources/js/login.js',
                'resources/css/mesas.css',
                'resources/js/mesas.js',
                'resources/css/orden.css',
                'resources/js/orden.js',
            
            ],
            refresh: true,
            
        }),
    ],
    // Allow Vite to be reachable on the local network and configure HMR to use
    // the machine IP so clients (phones) can connect to the dev server.
    //server: {
        // true => listen on all addresses (0.0.0.0)
      //  host: true,
       // port: 5173,
       // hmr: {
            // Replace with your machine IP on the LAN
         //   host: '192.168.1.71',
          //  protocol: 'ws',
          //  port: 5173,
       // },
   // },
});
