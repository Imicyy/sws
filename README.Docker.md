### Building and running your application

When you're ready, start your application by running:
`docker compose up --build`.

Your PHP application will be available at http://localhost:8080.

Notes:
- Compose now uses `Dockerfile.php` for the web app container (`php:8.2-apache`).
- Apache `mod_rewrite` is enabled, and `.htaccess` routing is supported.
- The canonical web root is `php/`, so the app entrypoint is `php/index.php` in the container.
- MySQL is exposed on `localhost:3307` and initialized with `model/ebmag2.5.sql`.
- phpMyAdmin is available at `http://localhost:8081` and connects to the `db` service.

### Deploying your application to the cloud

First, build your image, e.g.: `docker build -t myapp .`.
If your cloud uses a different CPU architecture than your development
machine (e.g., you are on a Mac M1 and your cloud provider is amd64),
you'll want to build the image for that platform, e.g.:
`docker build --platform=linux/amd64 -t myapp .`.

Then, push it to your registry, e.g. `docker push myregistry.com/myapp`.

Consult Docker's [getting started](https://docs.docker.com/go/get-started-sharing/)
docs for more detail on building and pushing.

### References
* [Docker's Node.js guide](https://docs.docker.com/language/nodejs/)