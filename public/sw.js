const appKey = "my-forecast-app"

self.addEventListener("install", (e) => {
    caches.open(appKey).then((cache) => {
        cache.addAll(["/pwa_forecast", "https://cdn.jsdelivr.net/npm/vue@2.6.11"])
    })
})

self.addEventListener("fetch", (e) => {
    e.respondWith(getFetchResponse(e.request))
})

async function getFetchResponse(request) {
    const url = new URL(request.url)

    if (url.host === location.host) {
        const cachedResponse = await caches.match(request)

        if (cachedResponse) {
            return cachedResponse
        }

        return fetch(request)
    } else {
        const cache = await caches.open(appKey)

        try {
            const response = await fetch(request)
            await cache.put(request, response.clone())
            return response
        } catch {
            return await cache.match(request)
        }
    }
}
