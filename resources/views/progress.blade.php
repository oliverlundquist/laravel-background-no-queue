<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="/css/bulma.css">
    <meta http-equiv="refresh" content="2">
  </head>
  <body>
  <section class="section">
    <div class="container">
      <h1 class="title">
        Processing your request {{ $request->progress }}%
        <span class="loader is-inline-block" style="vertical-align: middle"></span>
      </h1>
      <p class="subtitle">
        Please hold on a few seconds while we process your request.<br />You'll be redirected back to the original page once your request is completed.
      </p>
    </div>
  </section>
  </body>
</html>
