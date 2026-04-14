## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --pull --no-cache` to build fresh images
3. Run `docker compose up --wait` to set up and start a fresh Symfony project
4. Populate the tables `docker compose exec php bin/console app:geoapi:fetch`
5. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
6. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Additional notes

The exercise asked to only fetch the first 50 towns of each department.
Seeing how many town there is in France, it's a sensible choice to not have a gigantic query.
I ran into this problem and found a solution before noticing the exercise's requirements, so I've let the solution in.

No CSS or JS needed, lightning fast website !!

A map implementation was possible, but that would require installing some JS bundles.
I've already lost too much time reinstalling my dev environment on a new machine and I won't spend the extra time on that. 

## License

Symfony Docker is available under the MIT License.

## Credits

Created by [Kévin Dunglas](https://dunglas.dev), co-maintained by [Maxime Helias](https://twitter.com/maxhelias) and sponsored by [Les-Tilleuls.coop](https://les-tilleuls.coop).
