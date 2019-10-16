## CurlMulti

This a (light) abstract layer around the curl multi for handling asynchronously request. 

The purpose of this library is to make it easier to handle to handle to responses but trying to keep as close to curl as
possible. This should keep the api more stable, easier to work with (when familiar with curl) and less code around curl.

The provided request is therefor not more than an array like you wold register with `curl_setopt_array`. So urls, header 
etc. should be registered with the curl methods CURLOPT_URL, CURLOPT_WRITEHEADER, CURLOPT_HTTPHEADER and rest could be 
found [here](http://php.net/manual/en/function.curl-setopt.php).

### example

```
use PBergman\CurlMulti\MultiHandler;
use PBergman\CurlMulti\Request;

$null = fopen("/dev/null", "w");
$handler = new MultiHandler(
    new Request([
        CURLOPT_URL => "http://httpstat.us/200?sleep=5000",
        CURLOPT_FILE  => $null,
    ]),
    new Request([
        CURLOPT_URL => "http://httpstat.us/404",
        CURLOPT_FILE  => $null,
    ])
);

try {
    foreach ($handler->getResponse() as $response) {
        printf(
            "request '%s' finished with code %d in %0.2fs\n",
            $response->getInfo(CURLINFO_EFFECTIVE_URL),
            $response->getInfo(CURLINFO_RESPONSE_CODE),
            $response->getInfo(CURLINFO_TOTAL_TIME)
        );
    }
} finally {
    // register cleanup
    $handler->close();
}
```

should give a output like:

```
request 'http://httpstat.us/404' finished with code 404 in 0.45s
request 'http://httpstat.us/200?sleep=5000' finished with code 200 in 5.49s
```

### public api

##### __construct(RequestInterface ...$request): MultiHandler

constructor of MultiHandler accepts request so you could register your request on creating a new instance.

```
(new MultiHandler(
    new Request([
        CURLOPT_URL => "http://httpstat.us/200?sleep=5000",
        CURLOPT_FILE  => $null,
    ]),
    new Request([
        CURLOPT_URL => "http://httpstat.us/404",
        CURLOPT_FILE  => $null,
    ])
))->wait();
```

#####  setOptions(array $options) :bool

set options for multi handler

#####  addOption(int $key, $value) :bool

set option for multi handler

##### add(RequestInterface $request): MultiHandler

add request to defined instance.

##### defer(): void

register the close function for shutdown.

##### init(): void

will setup the curl multi hadle, this will be called when creating a new instance and can be useful when the close 
method was called. 

##### getResponse(): \Generator|ResponseInterface[]

returns a finished request and/or block until one is finished.

##### wait() : array||ResponseInterface[]

wait till all request are finished 