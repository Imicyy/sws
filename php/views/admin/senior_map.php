<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Senior Map</title>
<style>body{font-family:Segoe UI,Arial,sans-serif;background:#f4f7fb;margin:0}.card{max-width:1000px;margin:32px auto;background:#fff;padding:20px;border-radius:12px;box-shadow:0 10px 24px rgba(0,0,0,.08)}</style>
</head><body><main class="card"><h1>Senior Map</h1><p>Map API endpoint: <a href="/senior-map-data">/senior-map-data</a></p><pre id="out">Loading...</pre></main><script>fetch('/senior-map-data').then(r=>r.json()).then(d=>{document.getElementById('out').textContent=JSON.stringify(d?.data?.slice?.(0,5)||d,null,2)}).catch(e=>document.getElementById('out').textContent=String(e));</script></body></html>
