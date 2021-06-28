## Installation

-  docker-compose up -d

-  docker-compose exec app php artisan migrate

-  docker-compose exec app php artisan db:seed


## URLS:

- Endpoint to store the data: http://your-server-ip/api/link_journeys/

- How many hits did a link receive in a provided time interval : http://your-server-ip/api/link_journeys/counter_url/2021-05-10T00:00/2021-12-10T00:00/http://google.ro

- How many hits did each page type receive in a provided time interval: http://your-server-ip/api/link_journeys/counter_page/product/2021-05-10T00:00/2021-12-10T00:00

- Given customer identifier your service can provide the journey of that customer: http://your-server-ip/api/link_journeys/customer/13

- Provide a list of  other customers that had the same journey: http://your-server-ip/api/link_journeys/customers_same_journey/2

